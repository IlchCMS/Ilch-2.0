<?php

namespace Ilch\Scheduler;

/**
 * Data transfer object for one row of `[prefix]_scheduler_tasks`.
 *
 * Mirrors the style of the Ilch models (e.g. Modules\Comment\Model\Comment):
 * typed protected properties, nullable where the schema allows NULL, fluent
 * getters/setters. It performs no database access of its own — all CRUD belongs
 * to \Ilch\Scheduler\Scheduler.
 *
 * Datetime columns are UTC: parsed as \DateTimeImmutable in UTC on read, to be
 * written back as UTC 'Y-m-d H:i:s' strings. `dailyTime` is intentionally a raw
 * H:i:s string: it is a schedule parameter, not a point in time.
 *
 * @since 2.2.21
 */
final class Task
{
    /** @var int|null Primary key; NULL before the row exists in the database. */
    protected ?int $id = null;
    /** @var string Unique task name (1-191 chars of [A-Za-z0-9._-]). */
    protected string $name = '';
    /** @var string Registered handler name (convention: <moduleKey>.<taskKey>). */
    protected string $handler = '';
    /** @var string Scheduler::TYPE_INTERVAL or Scheduler::TYPE_DAILY. */
    protected string $type = Scheduler::TYPE_INTERVAL;
    /** @var int|null Interval in seconds (interval tasks only). */
    protected ?int $intervalSeconds = null;
    /** @var string|null Daily schedule time as raw 'H:i:s' (daily tasks only). */
    protected ?string $dailyTime = null;
    /** @var string|null IANA time zone (daily tasks only); NULL means the site default. */
    protected ?string $timezone = null;
    /** @var array<string, mixed>|null Decoded JSON payload; NULL when unset. */
    protected ?array $payload = null;
    /** @var bool Whether the task may be picked up by Scheduler::runDue(). */
    protected bool $enabled = true;
    /** @var \DateTimeImmutable|null Next scheduled run (UTC). */
    protected ?\DateTimeImmutable $nextRun = null;
    /** @var \DateTimeImmutable|null Start time of the last run (UTC). */
    protected ?\DateTimeImmutable $lastStarted = null;
    /** @var \DateTimeImmutable|null Finish time of the last run (UTC). */
    protected ?\DateTimeImmutable $lastFinished = null;
    /** @var string|null 'success' or 'failure'; NULL before the first run. */
    protected ?string $lastStatus = null;
    /** @var string|null Error of the last failed run (truncated to 255 chars). */
    protected ?string $lastError = null;
    /** @var int Consecutive failures, drives the retry backoff; reset on success. */
    protected int $failures = 0;
    /** @var string|null Lease token of the request currently running the task. */
    protected ?string $leaseOwner = null;
    /** @var \DateTimeImmutable|null Expiry of the current lease (UTC). */
    protected ?\DateTimeImmutable $leaseExpires = null;
    /** @var \DateTimeImmutable|null Row creation time (UTC). */
    protected ?\DateTimeImmutable $created = null;

    /**
     * Build a Task from a raw database row (snake_case columns, UTC datetime strings).
     *
     * @param array<string, mixed> $row
     * @return self
     */
    public static function fromRow(array $row): self
    {
        $task = new self();

        $task->id              = ($row['id'] ?? null) !== null ? (int)$row['id'] : null;
        $task->name            = (string)($row['name'] ?? '');
        $task->handler         = (string)($row['handler'] ?? '');
        $task->type            = (string)($row['type'] ?? Scheduler::TYPE_INTERVAL);
        $task->intervalSeconds = ($row['interval_seconds'] ?? null) !== null ? (int)$row['interval_seconds'] : null;
        $task->dailyTime       = ($row['daily_time'] ?? null) !== null ? (string)$row['daily_time'] : null;
        $task->timezone        = ($row['timezone'] ?? null) !== null ? (string)$row['timezone'] : null;
        $task->enabled         = (bool)(int)($row['enabled'] ?? 1);
        $task->failures        = (int)($row['failures'] ?? 0);
        $task->lastStatus      = ($row['last_status'] ?? null) !== null ? (string)$row['last_status'] : null;
        $task->lastError       = ($row['last_error'] ?? null) !== null ? (string)$row['last_error'] : null;
        $task->leaseOwner      = ($row['lease_owner'] ?? null) !== null ? (string)$row['lease_owner'] : null;

        $task->nextRun      = self::fromUtcString($row['next_run'] ?? null);
        $task->lastStarted  = self::fromUtcString($row['last_started'] ?? null);
        $task->lastFinished = self::fromUtcString($row['last_finished'] ?? null);
        $task->leaseExpires = self::fromUtcString($row['lease_expires'] ?? null);
        $task->created      = self::fromUtcString($row['created'] ?? null);

        $payload = $row['payload'] ?? null;
        $decoded = is_string($payload) && $payload !== '' ? json_decode($payload, true) : null;
        $task->payload = is_array($decoded) ? $decoded : null;

        return $task;
    }

    // -----------------------------------------------------------------
    // Getters
    // -----------------------------------------------------------------

    /** @return int|null */
    public function id(): ?int
    {
        return $this->id;
    }

    /** @return string */
    public function name(): string
    {
        return $this->name;
    }

    /** @return string */
    public function handler(): string
    {
        return $this->handler;
    }

    /** @return string */
    public function type(): string
    {
        return $this->type;
    }

    /** @return int|null */
    public function intervalSeconds(): ?int
    {
        return $this->intervalSeconds;
    }

    /** @return string|null Raw 'H:i:s' schedule parameter, not a point in time. */
    public function dailyTime(): ?string
    {
        return $this->dailyTime;
    }

    /** @return string|null */
    public function timezone(): ?string
    {
        return $this->timezone;
    }

    /** @return array<string, mixed>|null Decoded payload (null when unset). */
    public function payload(): ?array
    {
        return $this->payload;
    }

    /** @return bool */
    public function enabled(): bool
    {
        return $this->enabled;
    }

    /** @return \DateTimeImmutable|null */
    public function nextRun(): ?\DateTimeImmutable
    {
        return $this->nextRun;
    }

    /** @return \DateTimeImmutable|null */
    public function lastStarted(): ?\DateTimeImmutable
    {
        return $this->lastStarted;
    }

    /** @return \DateTimeImmutable|null */
    public function lastFinished(): ?\DateTimeImmutable
    {
        return $this->lastFinished;
    }

    /** @return string|null */
    public function lastStatus(): ?string
    {
        return $this->lastStatus;
    }

    /** @return string|null */
    public function lastError(): ?string
    {
        return $this->lastError;
    }

    /** @return int */
    public function failures(): int
    {
        return $this->failures;
    }

    /** @return string|null */
    public function leaseOwner(): ?string
    {
        return $this->leaseOwner;
    }

    /** @return \DateTimeImmutable|null */
    public function leaseExpires(): ?\DateTimeImmutable
    {
        return $this->leaseExpires;
    }

    /** @return \DateTimeImmutable|null */
    public function created(): ?\DateTimeImmutable
    {
        return $this->created;
    }

    // -----------------------------------------------------------------
    // Fluent setters
    // -----------------------------------------------------------------

    /**
     * @param int|null $id
     * @return self
     */
    public function setId(?int $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param string $name
     * @return self
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param string $handler
     * @return self
     */
    public function setHandler(string $handler): self
    {
        $this->handler = $handler;
        return $this;
    }

    /**
     * @param string $type
     * @return self
     */
    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @param int|null $intervalSeconds
     * @return self
     */
    public function setIntervalSeconds(?int $intervalSeconds): self
    {
        $this->intervalSeconds = $intervalSeconds;
        return $this;
    }

    /**
     * @param string|null $dailyTime
     * @return self
     */
    public function setDailyTime(?string $dailyTime): self
    {
        $this->dailyTime = $dailyTime;
        return $this;
    }

    /**
     * @param string|null $timezone
     * @return self
     */
    public function setTimezone(?string $timezone): self
    {
        $this->timezone = $timezone;
        return $this;
    }

    /**
     * @param array<string, mixed>|null $payload
     * @return self
     */
    public function setPayload(?array $payload): self
    {
        $this->payload = $payload;
        return $this;
    }

    /**
     * @param bool $enabled
     * @return self
     */
    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;
        return $this;
    }

    /**
     * @param \DateTimeImmutable|null $nextRun
     * @return self
     */
    public function setNextRun(?\DateTimeImmutable $nextRun): self
    {
        $this->nextRun = $nextRun;
        return $this;
    }

    /**
     * @param \DateTimeImmutable|null $lastStarted
     * @return self
     */
    public function setLastStarted(?\DateTimeImmutable $lastStarted): self
    {
        $this->lastStarted = $lastStarted;
        return $this;
    }

    /**
     * @param \DateTimeImmutable|null $lastFinished
     * @return self
     */
    public function setLastFinished(?\DateTimeImmutable $lastFinished): self
    {
        $this->lastFinished = $lastFinished;
        return $this;
    }

    /**
     * @param string|null $lastStatus
     * @return self
     */
    public function setLastStatus(?string $lastStatus): self
    {
        $this->lastStatus = $lastStatus;
        return $this;
    }

    /**
     * @param string|null $lastError
     * @return self
     */
    public function setLastError(?string $lastError): self
    {
        $this->lastError = $lastError;
        return $this;
    }

    /**
     * @param int $failures
     * @return self
     */
    public function setFailures(int $failures): self
    {
        $this->failures = $failures;
        return $this;
    }

    /**
     * @param string|null $leaseOwner
     * @return self
     */
    public function setLeaseOwner(?string $leaseOwner): self
    {
        $this->leaseOwner = $leaseOwner;
        return $this;
    }

    /**
     * @param \DateTimeImmutable|null $leaseExpires
     * @return self
     */
    public function setLeaseExpires(?\DateTimeImmutable $leaseExpires): self
    {
        $this->leaseExpires = $leaseExpires;
        return $this;
    }

    /**
     * @param \DateTimeImmutable|null $created
     * @return self
     */
    public function setCreated(?\DateTimeImmutable $created): self
    {
        $this->created = $created;
        return $this;
    }

    /**
     * Parse a UTC 'Y-m-d H:i:s' string from the database.
     *
     * @param string|null $value
     * @return \DateTimeImmutable|null
     */
    private static function fromUtcString(?string $value): ?\DateTimeImmutable
    {
        if ($value === null || $value === '') {
            return null;
        }
        return (new \DateTimeImmutable($value))->setTimezone(new \DateTimeZone('UTC'));
    }
}
