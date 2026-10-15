<?php

namespace Ilch\Scheduler;

use Ilch\Database\Mysql;
use Ilch\Registry;
use Modules\Admin\Mappers\Logs as LogsMapper;

/**
 * Visitor-triggered scheduler
 *
 * Runs at most one due task per visitor request (front end, admin, AJAX and CMS
 * pages). It is registered once per request in Page::loadCms() and stored in the
 * Registry under the key 'scheduler'. No cron job, no queue, no extra endpoint is
 * involved: ordinary traffic is the trigger.
 *
 * Tasks live in the single table [prefix]_scheduler_tasks (created by the admin
 * module's install/update SQL). Handlers are PHP callables registered under a
 * unique name (convention: <moduleKey>.<taskKey>). A module provides one
 * self-registering handler file per task in application/modules/<moduleKey>/scheduler/;
 * the folder is lazy-included only when one of that module's tasks is due.
 *
 * ---------------------------------------------------------------------------
 * Usage
 * ---------------------------------------------------------------------------
 * Module install (Modules\<Module>\Config\Config::install()):
 *
 *     public function install()
 *     {
 *         $this->queryMulti($this->getInstallSql());
 *
 *         (new \Ilch\Scheduler\Scheduler($this->db()))->createTask([
 *             'name'             => 'comments.cleanup',
 *             'handler'          => 'comments.cleanup',
 *             'type'             => \Ilch\Scheduler\Scheduler::TYPE_DAILY,
 *             'daily_time'       => '03:00:00',      // H:i:s
 *             'timezone'         => 'Europe/Berlin', // IANA, optional (site default otherwise)
 *             'payload'          => ['max_age_days' => 30],
 *         ]);
 *     }
 *
 * Module handler file (application/modules/comments/scheduler/Cleanup.php):
 *
 *     <?php
 *     namespace Modules\Comments\scheduler;
 *
 *     use Ilch\Scheduler\Scheduler;
 *
 *     final class Cleanup
 *     {
 *         public static function handle(?array $payload, Scheduler $scheduler): void
 *         {
 *             $maxAgeDays = (int)($payload['max_age_days'] ?? 30);
 *             // ... work in small steps ...
 *             if ($scheduler->timeLeftMs() < 100) {
 *                 return; // leave the rest for the next due run
 *             }
 *         }
 *     }
 *
 *     Scheduler::registerHandler('comments.cleanup', [Cleanup::class, 'handle']);
 *
 * Module uninstall:
 *
 *     public function uninstall()
 *     {
 *         (new \Ilch\Scheduler\Scheduler($this->db()))->deleteTask('comments.cleanup');
 *     }
 *
 * ---------------------------------------------------------------------------
 * Runtime semantics
 * ---------------------------------------------------------------------------
 * Budget:      A request that has already spent BUDGET_MS of its own time does not
 *              start a new task (checked at the entry of runDue()). Handlers that
 *              process many rows should consult timeLeftMs() and stop early.
 * Lease:       A due task is claimed atomically with a unique lease token that is
 *              valid for LEASE_SECONDS, so concurrent visitors can never run the
 *              same task twice. The outcome update only applies while the token
 *              still matches. If a request dies mid-run, the task becomes claimable
 *              again after LEASE_SECONDS.
 * Retry:       Failed runs are retried with exponential backoff:
 *              RETRY_BASE_SECONDS * 2^(failures - 1), capped at RETRY_MAX_SECONDS.
 *              Successful runs reset the failure counter.
 * Catch-up:    Interval tasks anchor their next run to the time they started, so a
 *              long-delayed task re-runs once per request until it is caught up
 *              (bounded by the one-task-per-request rule).
 * Idempotency: Handlers MUST be idempotent — catch-up re-runs, and lease expiry
 *              after a mid-run crash, can cause a task to run twice for the same
 *              schedule slot.
 * Services:    At handler time the Registry holds 'startTime', 'db', 'config' and
 *              'scheduler'. Note: 'translator' is registered later in loadPage()
 *              and must NOT be relied on inside handlers.
 * Safety:      runDue() absorbs every \Throwable (including a missing table during
 *              fresh install, the upgrade request itself, or an opcache transition)
 *              and never breaks the page. Failures are written to the admin logs
 *              table (admin center > Infos > Logs, user id 1 as system identity,
 *              deduplicated per minute by the Logs mapper), with \error_log() as
 *              fallback if that write fails. The error is also stored per task in
 *              last_error (truncated to 255 chars).
 *
 * @since 2.2.21
 */
final class Scheduler
{
    public const TYPE_INTERVAL = 'interval';
    public const TYPE_DAILY    = 'daily';

    /** Max. milliseconds a request may already have spent when a new task is started. */
    public const BUDGET_MS          = 5000;
    /** How long a claimed task is held before another request may take it over. */
    public const LEASE_SECONDS      = 60;
    /** Delay before the first retry. */
    public const RETRY_BASE_SECONDS = 60;
    /** Cap for the retry delay. */
    public const RETRY_MAX_SECONDS  = 3600;

    /** Raw-SQL table name; the database layer replaces the [prefix] token. */
    private const TABLE = '[prefix]_scheduler_tasks';

    /**
     * The logs table has no system user; the scheduler writes with the admin
     * user id (1) as the system identity.
     */
    private const LOG_USER_ID = 1;

    /** @var array<string, callable> Registered handlers (handler name => callable). */
    private static array $handlers = [];

    /** @var Mysql Database connection used for all raw SQL access. */
    private Mysql $db;

    /**
     * @param Mysql $db Database connection (raw-SQL layer with [prefix] handling).
     */
    public function __construct(Mysql $db)
    {
        $this->db = $db;
    }

    /**
     * Register a handler under a unique name (convention: <moduleKey>.<taskKey>).
     *
     * Modules normally do this in a self-registering file inside
     * application/modules/<moduleKey>/scheduler/ (lazy-included only when due);
     * plugins or core bootstrap code may register handlers directly (core tasks
     * use the 'core.' prefix).
     *
     * @param string $name Unique handler name; registering it again overwrites the previous handler.
     * @param callable $handler
     * @return void
     */
    public static function registerHandler(string $name, callable $handler): void
    {
        self::$handlers[$name] = $handler;
    }

    /**
     * Whether a handler is currently registered under $name.
     *
     * @param string $name
     * @return bool
     */
    public static function hasHandler(string $name): bool
    {
        return isset(self::$handlers[$name]);
    }

    /**
     * Create a task.
     *
     * @param array{
     *     name: string,                // unique, 1-191 chars of [A-Za-z0-9._-]
     *     handler: string,             // registered handler name
     *     type?: string,               // 'interval' (default) or 'daily'
     *     interval_seconds?: int,      // required for 'interval', >= 1
     *     daily_time?: string,         // required for 'daily', H:i:s
     *     timezone?: string,           // IANA, for 'daily' (site default otherwise)
     *     payload?: array|string|null, // stored as JSON, passed decoded to the handler
     *     enabled?: bool               // default true
     * } $data
     * @return int The new task id.
     * @throws \InvalidArgumentException on invalid data
     */
    public function createTask(array $data): int
    {
        $name    = $this->validateIdentifier($data['name'] ?? '', 'name');
        $handler = $this->validateIdentifier($data['handler'] ?? '', 'handler');

        $type = (string)($data['type'] ?? self::TYPE_INTERVAL);
        if (!in_array($type, [self::TYPE_INTERVAL, self::TYPE_DAILY], true)) {
            throw new \InvalidArgumentException('Task type must be "' . self::TYPE_INTERVAL . '" or "' . self::TYPE_DAILY . '".');
        }

        $intervalSeconds = null;
        $dailyTime       = null;
        $timezone        = null;

        if ($type === self::TYPE_INTERVAL) {
            $intervalSeconds = (int)($data['interval_seconds'] ?? 0);
            if ($intervalSeconds < 1) {
                throw new \InvalidArgumentException('interval_seconds must be a positive integer (>= 1) for interval tasks.');
            }
        } else {
            $dailyTime = (string)($data['daily_time'] ?? '');
            if (!preg_match('/^([01]\d|2[0-3]):[0-5]\d:[0-5]\d$/', $dailyTime)) {
                throw new \InvalidArgumentException('daily_time must be formatted as H:i:s for daily tasks.');
            }
            $timezone = $this->validateTimezone($data['timezone'] ?? null);
        }

        $payload = $this->normalizePayload($data['payload'] ?? null);
        $enabled = (int)((bool)($data['enabled'] ?? true));

        $nextRun = ($type === self::TYPE_DAILY)
            ? $this->nextDailyOccurrence($dailyTime, $timezone)
            : $this->utcNowSql($intervalSeconds);

        $sql = 'INSERT INTO ' . self::TABLE
            . ' (name, handler, type, interval_seconds, daily_time, timezone, payload, enabled, next_run) VALUES ('
            . $this->escape($name) . ', '
            . $this->escape($handler) . ', '
            . $this->escape($type) . ', '
            . ($intervalSeconds !== null ? (string)$intervalSeconds : 'NULL') . ', '
            . ($dailyTime !== null ? $this->escape($dailyTime) : 'NULL') . ', '
            . ($timezone !== null ? $this->escape($timezone) : 'NULL') . ', '
            . ($payload !== null ? $this->escape($payload) : 'NULL') . ', '
            . $enabled . ', '
            . $this->escape($nextRun) . ')';
        $this->db->query($sql);

        // Read the new id back by the unique name (keeps the code independent of
        // wrapper API details).
        $row = $this->db->queryArray('SELECT id FROM ' . self::TABLE . ' WHERE name = ' . $this->escape($name) . ' LIMIT 1');

        return (int)($row[0]['id'] ?? 0);
    }

    /**
     * Partially update a task by name.
     *
     * Accepted keys: handler, type, interval_seconds, daily_time, timezone,
     * payload, enabled. Whenever a schedule-relevant key (type, interval_seconds,
     * daily_time, timezone) changes, next_run is recomputed from now. Raw SQL is
     * used on purpose: the builder update() skips NULL values, but e.g. resetting
     * timezone must write an explicit NULL.
     *
     * @param string $name
     * @param array<string, mixed> $data
     * @return void
     * @throws \InvalidArgumentException if the task does not exist or the resulting
     *                                  schedule would be invalid
     */
    public function updateTask(string $name, array $data): void
    {
        $task = $this->getTask($name);
        if ($task === null) {
            throw new \InvalidArgumentException('Task not found: ' . $name);
        }

        $sets            = [];
        $scheduleChanged = false;

        if (isset($data['handler'])) {
            $sets['handler'] = $this->escape($this->validateIdentifier($data['handler'], 'handler'));
        }
        if (isset($data['type'])) {
            $type = (string)$data['type'];
            if (!in_array($type, [self::TYPE_INTERVAL, self::TYPE_DAILY], true)) {
                throw new \InvalidArgumentException('Task type must be "' . self::TYPE_INTERVAL . '" or "' . self::TYPE_DAILY . '".');
            }
            $sets['type']          = $this->escape($type);
            $scheduleChanged       = true;
        }
        if (array_key_exists('interval_seconds', $data)) {
            $value = (int)$data['interval_seconds'];
            if ($value < 1) {
                throw new \InvalidArgumentException('interval_seconds must be a positive integer (>= 1).');
            }
            $sets['interval_seconds'] = $value;
            $scheduleChanged          = true;
        }
        if (array_key_exists('daily_time', $data)) {
            $value = (string)$data['daily_time'];
            if (!preg_match('/^([01]\d|2[0-3]):[0-5]\d:[0-5]\d$/', $value)) {
                throw new \InvalidArgumentException('daily_time must be formatted as H:i:s.');
            }
            $sets['daily_time']  = $this->escape($value);
            $scheduleChanged     = true;
        }
        if (array_key_exists('timezone', $data)) {
            $value = $data['timezone'];
            if ($value === null || $value === '') {
                $sets['timezone'] = 'NULL'; // explicit NULL via raw SQL (builder update() would skip it)
            } else {
                $sets['timezone'] = $this->escape($this->validateTimezone((string)$value));
            }
            $scheduleChanged = true;
        }
        if (array_key_exists('payload', $data)) {
            $payload = $this->normalizePayload($data['payload']);
            $sets['payload'] = ($payload !== null) ? $this->escape($payload) : 'NULL';
        }
        if (isset($data['enabled'])) {
            $sets['enabled'] = (int)(bool)$data['enabled'];
        }

        if (empty($sets)) {
            return;
        }

        // Validate the effective (existing + overridden) schedule and recompute
        // next_run whenever the schedule itself changed.
        $type = isset($data['type']) ? (string)$data['type'] : $task->type();
        if ($type === self::TYPE_INTERVAL) {
            $interval = isset($data['interval_seconds']) ? (int)$data['interval_seconds'] : $task->intervalSeconds();
            if ($interval === null || $interval < 1) {
                throw new \InvalidArgumentException('Task of type "interval" requires interval_seconds.');
            }
            if ($scheduleChanged) {
                $sets['next_run'] = $this->escape($this->utcNowSql($interval));
            }
        } else {
            $dailyTime = isset($data['daily_time']) ? (string)$data['daily_time'] : $task->dailyTime();
            if ($dailyTime === null) {
                throw new \InvalidArgumentException('Task of type "daily" requires daily_time.');
            }
            $timezone = (isset($data['timezone']) && $data['timezone'] !== null && $data['timezone'] !== '')
                ? $this->validateTimezone((string)$data['timezone'])
                : $task->timezone();
            if ($scheduleChanged) {
                $sets['next_run'] = $this->escape($this->nextDailyOccurrence($dailyTime, $timezone));
            }
        }

        $sqlParts = [];
        foreach ($sets as $column => $value) {
            $sqlParts[] = $column . ' = ' . $value;
        }
        $sql = 'UPDATE ' . self::TABLE . ' SET ' . implode(', ', $sqlParts) . ' WHERE name = ' . $this->escape($name);
        $this->db->query($sql);
    }

    /**
     * Idempotent counterpart of createTask(): returns the id of the existing
     * task when a task with this name already exists, otherwise creates it.
     *
     * @param array<string, mixed> $data Same keys as createTask().
     * @return int The task id (existing or newly created).
     * @throws \InvalidArgumentException on invalid data
     */
    public function ensureTask(array $data): int
    {
        $existing = $this->getTask((string)($data['name'] ?? ''));
        if ($existing !== null) {
            return $existing->id();
        }
        return $this->createTask($data);
    }

    /**
     * Delete a task by name.
     *
     * Idempotent on purpose: a missing task (e.g. a failed install, or uninstall
     * after the task was never created) must never break module uninstall().
     *
     * @param string $name
     * @return void
     */
    public function deleteTask(string $name): void
    {
        $this->db->query('DELETE FROM ' . self::TABLE . ' WHERE name = ' . $this->escape((string)$name));
    }

    /**
     * Fetch one task by its unique name.
     *
     * @param string $name
     * @return Task|null
     */
    public function getTask(string $name): ?Task
    {
        $rows = $this->db->queryArray('SELECT * FROM ' . self::TABLE . ' WHERE name = ' . $this->escape((string)$name) . ' LIMIT 1');
        if (empty($rows)) {
            return null;
        }
        return Task::fromRow($rows[0]);
    }

    /**
     * @return Task[] All tasks, ordered by name.
     */
    public function getAllTasks(): array
    {
        $rows  = $this->db->queryArray('SELECT * FROM ' . self::TABLE . ' ORDER BY name ASC');
        $tasks = [];
        foreach ($rows as $row) {
            $tasks[] = Task::fromRow($row);
        }
        return $tasks;
    }

    /**
     * Run at most one due task for this request.
     *
     * Safe to call once per request from Page::loadCms(). Every failure — including
     * a missing table (fresh install, the upgrade request itself, opcache
     * transition) — is absorbed and logged; the page is never affected.
     *
     * @return void
     */
    public function runDue(): void
    {
        try {
            // 1) Budget guard: do not start a new task if this request already
            //    spent the whole budget on its own work.
            if ($this->timeLeftMs() <= 0.0) {
                return;
            }

            $now    = $this->utcNowSql();
            $nowSql = $this->escape($now);

            // 2) One indexed due check (idx_due): the single oldest due task.
            $rows = $this->db->queryArray(
                'SELECT * FROM ' . self::TABLE . '
                 WHERE enabled = 1
                   AND next_run <= ' . $nowSql . '
                   AND (lease_owner IS NULL OR lease_expires < ' . $nowSql . ')
                 ORDER BY next_run ASC
                 LIMIT 1'
            );
            if (empty($rows)) {
                return;
            }
            $task = Task::fromRow($rows[0]);

            // 3) Atomic claim: unique lease token, expiry, last_started.
            //    The WHERE re-checks the due/lease state, so two concurrent requests
            //    can never claim the same row — exactly one of them affects a row.
            $leaseToken  = bin2hex(random_bytes(8));
            $leaseExpiry = $this->utcNowSql(self::LEASE_SECONDS);
            $this->db->query(
                'UPDATE ' . self::TABLE . '
                 SET lease_owner = ' . $this->escape($leaseToken) . ',
                     lease_expires = ' . $this->escape($leaseExpiry) . ',
                     last_started = ' . $nowSql . '
                 WHERE id = ' . $task->id() . '
                   AND next_run <= ' . $nowSql . '
                   AND (lease_owner IS NULL OR lease_expires < ' . $nowSql . ')'
            );
            if ($this->db->getAffectedRows() !== 1) {
                return; // lost the race: another request claimed the task first
            }

            // 4) Execute outside any transaction.
            $this->ensureHandlerLoaded($task->handler());

            $error = null;
            if (!self::hasHandler($task->handler())) {
                $error = 'Unknown handler: ' . $task->handler();
                $this->logMessage(sprintf('Task "%s" has unknown handler "%s".', $task->name(), $task->handler()));
            } else {
                $startedAt = hrtime(true);
                try {
                    call_user_func(self::$handlers[$task->handler()], $task->payload(), $this);
                } catch (\Throwable $e) {
                    $error = $e->getMessage();
                }
                $elapsedMs = (hrtime(true) - $startedAt) / 1000000;
                if ($elapsedMs > (float)self::BUDGET_MS) {
                    // The handler runs to completion; the overrun is only noted.
                    $this->logMessage(sprintf('Task "%s" took %d ms (budget %d ms).', $task->name(), (int)$elapsedMs, self::BUDGET_MS));
                }
            }

            // 5) One lease-token-guarded outcome update.
            $finishedAt = $this->utcNowSql();
            $where      = 'WHERE id = ' . $task->id() . ' AND lease_owner = ' . $this->escape($leaseToken);

            if ($error === null) {
                $nextRun = $this->computeNextRun($task);
                $this->db->query(
                    'UPDATE ' . self::TABLE . '
                     SET lease_owner = NULL,
                         lease_expires = NULL,
                         last_finished = ' . $this->escape($finishedAt) . ',
                         last_status = ' . $this->escape('success') . ',
                         last_error = NULL,
                         failures = 0,
                         next_run = ' . $this->escape($nextRun) . '
                     ' . $where
                );
            } else {
                $failures = $task->failures() + 1;
                $backoff  = min(self::RETRY_BASE_SECONDS * (1 << min($failures - 1, 20)), self::RETRY_MAX_SECONDS);
                $this->logMessage(sprintf('Task "%s" failed: %s', $task->name(), $error));
                $this->db->query(
                    'UPDATE ' . self::TABLE . '
                     SET lease_owner = NULL,
                         lease_expires = NULL,
                         last_finished = ' . $this->escape($finishedAt) . ',
                         last_status = ' . $this->escape('failure') . ',
                         last_error = ' . $this->escape($this->sanitizeError($error)) . ',
                         failures = failures + 1,
                         next_run = ' . $this->escape($this->utcNowSql($backoff)) . '
                     ' . $where
                );
            }
        } catch (\Throwable $e) {
            // Absorb everything: a scheduler problem must never break the page.
            $this->logMessage('runDue failed: ' . $e->getMessage());
        }
    }

    /**
     * Milliseconds of the request budget still available (0.0 when exhausted).
     * Handlers that process many rows should stop early when this gets low.
     *
     * @return float
     */
    public function timeLeftMs(): float
    {
        $startTime = Registry::get('startTime');
        if (!is_float($startTime) && !is_int($startTime)) {
            return (float)self::BUDGET_MS;
        }
        return max(0.0, (float)self::BUDGET_MS - ((microtime(true) - (float)$startTime) * 1000.0));
    }

    // -----------------------------------------------------------------
    // Internals
    // -----------------------------------------------------------------

    /**
     * Lazy-include the owning module's handler files (in its scheduler/
     * folder) if the handler is not registered yet. Only called for due
     * tasks, so modules without a due task cost nothing. The module key must
     * be a plain lowercase identifier; the include path is therefore fully
     * validated (no traversal possible). Each file in the folder is expected
     * to register its own handler(s) when included.
     *
     * @param string $handler
     * @return void
     */
    private function ensureHandlerLoaded(string $handler): void
    {
        if (self::hasHandler($handler)) {
            return;
        }
        $dot = strpos($handler, '.');
        if ($dot === false) {
            return; // no module segment: the handler must already be registered (core bootstrap/plugin)
        }
        $moduleKey = substr($handler, 0, $dot);
        if (!preg_match('/^[a-z0-9_]+$/', $moduleKey)) {
            return;
        }
        $dir = APPLICATION_PATH . '/modules/' . $moduleKey . '/scheduler';
        if (is_dir($dir)) {
            foreach (glob($dir . '/*.php') ?: [] as $file) {
                require_once $file;
            }
        }
    }

    /**
     * next_run after a successful run.
     *
     * Interval: anchored to the claim time (last_started ≈ now); a delayed task
     * therefore re-runs once per request until caught up.
     * Daily:    the next occurrence of daily_time in the task's timezone (UTC-stored).
     *
     * @param Task $task
     * @return string UTC 'Y-m-d H:i:s'
     */
    private function computeNextRun(Task $task): string
    {
        if ($task->type() === self::TYPE_DAILY) {
            return $this->nextDailyOccurrence((string)$task->dailyTime(), $task->timezone());
        }
        return $this->utcNowSql((int)$task->intervalSeconds());
    }

    /**
     * Next occurrence of $dailyTime (H:i:s) in $timezone, strictly after now,
     * returned as a UTC 'Y-m-d H:i:s' string.
     *
     * @param string|null $dailyTime
     * @param string|null $timezone
     * @return string
     */
    private function nextDailyOccurrence(?string $dailyTime, ?string $timezone): string
    {
        $timezone = ($timezone !== null && $timezone !== '') ? $timezone : $this->defaultTimezone();
        $tz       = new \DateTimeZone($timezone);

        [$hour, $minute, $second] = array_map('intval', explode(':', (string)$dailyTime));

        $now       = new \DateTimeImmutable('now', $tz);
        $candidate = (new \DateTimeImmutable('today', $tz))->setTime($hour, $minute, $second);
        if ($candidate <= $now) {
            $candidate = $candidate->add(new \DateInterval('P1D'));
        }

        return $candidate->setTimezone(new \DateTimeZone('UTC'))->format('Y-m-d H:i:s');
    }

    /**
     * Daily tasks without an explicit timezone follow the site-wide default.
     *
     * @return string IANA time zone name.
     */
    private function defaultTimezone(): string
    {
        $config = Registry::get('config');
        if ($config instanceof \Ilch\Config\Database) {
            $timezone = $config->get('timezone');
            if (is_string($timezone) && $timezone !== '') {
                return $timezone;
            }
        }
        return defined('SERVER_TIMEZONE') ? SERVER_TIMEZONE : 'UTC';
    }

    /**
     * 'Y-m-d H:i:s' in UTC ($db layer's default timezone, set in index.php),
     * optionally offset by whole seconds.
     *
     * @param int $offsetSeconds
     * @return string
     */
    private function utcNowSql(int $offsetSeconds = 0): string
    {
        return date('Y-m-d H:i:s', time() + $offsetSeconds);
    }

    /**
     * Validate an identifier (name or handler): 1-191 chars of [A-Za-z0-9._-].
     *
     * @param mixed $value
     * @param string $what Human-readable field name used in the error message.
     * @return string
     * @throws \InvalidArgumentException
     */
    private function validateIdentifier($value, string $what): string
    {
        $value = trim((string)$value, " \f\n\r\t\v\x00");
        if ($value === '' || !preg_match('/^[A-Za-z0-9._\-]{1,191}$/', $value)) {
            throw new \InvalidArgumentException($what . ' must be 1-191 characters and may only contain letters, digits, ".", "_" and "-".');
        }
        return $value;
    }

    /**
     * Validate an IANA time zone; an empty value falls back to the site default.
     *
     * @param string|null $timezone
     * @return string
     * @throws \InvalidArgumentException if the zone is not a valid IANA zone.
     */
    private function validateTimezone(?string $timezone): string
    {
        $timezone = ($timezone !== null && $timezone !== '') ? $timezone : $this->defaultTimezone();
        try {
            new \DateTimeZone($timezone);
        } catch (\Exception $e) {
            throw new \InvalidArgumentException('timezone "' . $timezone . '" is not a valid IANA time zone.');
        }
        return $timezone;
    }

    /**
     * Accepts null, an array or a JSON string; always returns the canonical
     * JSON string to store, or null.
     *
     * @param array|string|null $payload
     * @return string|null
     * @throws \InvalidArgumentException if $payload is not null, an array or valid JSON.
     */
    private function normalizePayload($payload): ?string
    {
        if ($payload === null) {
            return null;
        }
        if (is_array($payload)) {
            return json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
        }
        if (is_string($payload)) {
            json_decode($payload);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \InvalidArgumentException('payload must be a valid JSON string.');
            }
            return $payload;
        }
        throw new \InvalidArgumentException('payload must be null, an array or a JSON string.');
    }

    /**
     * Escape a value for embedding in raw SQL; the database layer's
     * escape($value, true) returns a ready-to-embed, quoted string literal.
     *
     * @param string $value
     * @return string
     */
    private function escape(string $value): string
    {
        return $this->db->escape($value, true);
    }

    /**
     * last_error must fit VARCHAR(255) and stay one line.
     *
     * @param string $error
     * @return string
     */
    private function sanitizeError(string $error): string
    {
        $error = preg_replace('/[\x00-\x1F\x7F]/', ' ', $error);
        if (function_exists('mb_substr')) {
            return mb_substr($error, 0, 255);
        }
        return substr($error, 0, 255);
    }

    /**
     * Write a message to the admin logs table (admin center > Infos > Logs).
     *
     * Core library depending on an admin-module mapper: accepted because the admin
     * module is a system module that is always installed, and the logs table is
     * created by its install SQL (same timing as the scheduler table). If the
     * write fails (e.g. the database is down), the message still goes to
     * \error_log().
     *
     * @param string $message
     * @return void
     */
    private function logMessage(string $message): void
    {
        // The info column is VARCHAR(255) and saveLog() does not truncate.
        $message = $this->sanitizeError('Scheduler: ' . $message);

        try {
            (new LogsMapper())->saveLog(self::LOG_USER_ID, $message);
        } catch (\Throwable $e) {
            \error_log('Ilch Scheduler: ' . $message);
        }
    }
}
