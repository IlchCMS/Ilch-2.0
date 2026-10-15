<?php

/**
 * Tests for \Ilch\Scheduler\Scheduler.
 *
 * @copyright Ilch 2
 * @package ilch
 */

namespace Ilch\Scheduler;

use PHPUnit\Ilch\DatabaseTestCase;
use PHPUnit\Ilch\PhpunitDataset;
use Ilch\Registry;

class SchedulerTest extends DatabaseTestCase
{
    /** @var Scheduler The scheduler under test. */
    protected Scheduler $out;

    /** @var string[] Fixture files (YAML datasets) loaded before each test. */
    protected static array $fixtureFiles = [__DIR__ . '/../_files/scheduler_tasks.yml'];

    /** @var PhpunitDataset Helper that loads the fixture dataset into the database. */
    protected PhpunitDataset $phpunitDataset;

    /**
     * Resets the static handler registry, loads the fixtures into the database
     * and prepares a fresh Scheduler with a fresh request budget for each test.
     */
    public function setUp(): void
    {
        // DatabaseTestCase already binds $this->db to the registry as 'db'
        // (that is how the no-argument mapper constructors in the other tests
        // find their connection), so do not set it again.
        parent::setUp();

        // The scheduler formats every datetime with date(), which uses the PHP
        // default timezone; production always runs with UTC (set in index.php).
        date_default_timezone_set('UTC');

        // Scheduler keeps its handlers in a private static array that would
        // leak between tests; start every test with an empty handler registry.
        $handlers = new \ReflectionProperty(Scheduler::class, 'handlers');
        $handlers->setValue(null, []);

        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFiles(static::$fixtureFiles);

        $this->out = new Scheduler($this->db);

        // Site default timezone for daily tasks without an explicit one.
        // Only provide it if the test base did not already.
        if (!Registry::has('config')) {
            Registry::set('config', new \Ilch\Config\Database($this->db));
        }

        // A fresh budget for every test.
        Registry::remove('startTime');
    }

    /**
     * Removes the request start time and restores the base test state.
     */
    public function tearDown(): void
    {
        // Do not leak a request start time into other tests.
        Registry::remove('startTime');

        parent::tearDown();
    }

    /**
     * Minimal schema for the scheduler, config and logs tables used by the tests.
     *
     * @return string
     */
    protected static function getSchemaSQLQueries(): string
    {
        return 'CREATE TABLE IF NOT EXISTS `[prefix]_scheduler_tasks` (
                `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                `name` VARCHAR(191) NOT NULL,
                `handler` VARCHAR(191) NOT NULL,
                `type` VARCHAR(32) NOT NULL DEFAULT \'interval\',
                `interval_seconds` INT(11) UNSIGNED NULL DEFAULT NULL,
                `daily_time` TIME NULL DEFAULT NULL,
                `timezone` VARCHAR(64) NULL DEFAULT NULL,
                `payload` TEXT NULL DEFAULT NULL,
                `enabled` TINYINT(1) NOT NULL DEFAULT 1,
                `next_run` DATETIME NOT NULL,
                `last_started` DATETIME NULL DEFAULT NULL,
                `last_finished` DATETIME NULL DEFAULT NULL,
                `last_status` VARCHAR(16) NULL DEFAULT NULL,
                `last_error` VARCHAR(255) NULL DEFAULT NULL,
                `failures` INT(11) UNSIGNED NOT NULL DEFAULT 0,
                `lease_owner` VARCHAR(64) NULL DEFAULT NULL,
                `lease_expires` DATETIME NULL DEFAULT NULL,
                `created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                UNIQUE KEY `name` (`name`),
                KEY `idx_due` (`enabled`, `next_run`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

            CREATE TABLE IF NOT EXISTS `[prefix]_config` (
                `key` VARCHAR(191) NOT NULL,
                `value` TEXT NOT NULL,
                `autoload` TINYINT(1) NOT NULL,
                UNIQUE KEY `key` (`key`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

            CREATE TABLE IF NOT EXISTS `[prefix]_logs` (
                `user_id` VARCHAR(255) NOT NULL,
                `date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `info` VARCHAR(255) NOT NULL,
                INDEX `logs_user_info_date` (`user_id`(50), `info`(50), `date`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;';
    }

    // -----------------------------------------------------------------
    // Handler registry
    // -----------------------------------------------------------------

    /**
     * Tests that a handler registered at runtime becomes available via hasHandler().
     */
    public function testRegisterHandlerMakesHandlerAvailable(): void
    {
        self::assertFalse(Scheduler::hasHandler('test.reg'));

        Scheduler::registerHandler('test.reg', function (?array $payload, Scheduler $scheduler): void {
        });

        self::assertTrue(Scheduler::hasHandler('test.reg'));
    }

    /**
     * Tests that hasHandler() is false for a name that was never registered.
     */
    public function testHasHandlerReturnsFalseForUnknownHandler(): void
    {
        self::assertFalse(Scheduler::hasHandler('nope.nope'));
    }

    /**
     * Tests that registering the same name again replaces the previous handler.
     */
    public function testRegisterHandlerOverwritesPreviousHandler(): void
    {
        $called = 0;
        Scheduler::registerHandler('test.overwrite', function () use (&$called): void {
            $called += 1;
        });
        Scheduler::registerHandler('test.overwrite', function () use (&$called): void {
            $called += 10;
        });

        $this->db->query('DELETE FROM [prefix]_scheduler_tasks');
        $this->insertTask(['name' => 'test.overwrite', 'handler' => 'test.overwrite']);

        $this->out->runDue();

        // The second registration must be the one that runs.
        self::assertSame(10, $called);
    }

    /**
     * Tests that the public constants keep their documented values.
     */
    public function testPublicConstants(): void
    {
        self::assertSame('interval', Scheduler::TYPE_INTERVAL);
        self::assertSame('daily', Scheduler::TYPE_DAILY);
        self::assertSame(5000, Scheduler::BUDGET_MS);
        self::assertSame(60, Scheduler::LEASE_SECONDS);
        self::assertSame(60, Scheduler::RETRY_BASE_SECONDS);
        self::assertSame(3600, Scheduler::RETRY_MAX_SECONDS);
    }

    // -----------------------------------------------------------------
    // Reading and deleting tasks
    // -----------------------------------------------------------------

    /**
     * Tests that getTask() maps a fixture row to a Task model.
     */
    public function testGetTask(): void
    {
        $task = $this->out->getTask('fixture.interval.due');

        self::assertInstanceOf(Task::class, $task);
        self::assertSame(1, $task->id());
        self::assertSame('fixture.interval.due', $task->name());
        self::assertSame('fixture.interval.due', $task->handler());
        self::assertSame(Scheduler::TYPE_INTERVAL, $task->type());
        self::assertSame(3600, $task->intervalSeconds());
        self::assertNull($task->dailyTime());
        self::assertNull($task->timezone());
        self::assertSame(['step' => 2], $task->payload());
        self::assertTrue($task->enabled());
        self::assertSame('2020-01-01 00:00:00', $task->nextRun()->format('Y-m-d H:i:s'));
        self::assertNull($task->lastStarted());
        self::assertNull($task->lastFinished());
        self::assertNull($task->lastStatus());
        self::assertNull($task->lastError());
        self::assertSame(0, $task->failures());
        self::assertNull($task->leaseOwner());
        self::assertNull($task->leaseExpires());
        self::assertSame('2020-01-01 00:00:00', $task->created()->format('Y-m-d H:i:s'));
    }

    /**
     * Tests that getTask() returns null for an unknown name.
     */
    public function testGetTaskReturnsNullForUnknownTask(): void
    {
        self::assertNull($this->out->getTask('does.not.exist'));
    }

    /**
     * Tests that getAllTasks() returns all fixture tasks ordered by name.
     */
    public function testGetAllTasks(): void
    {
        $tasks = $this->out->getAllTasks();

        self::assertCount(4, $tasks);
        self::assertSame(
            ['fixture.daily', 'fixture.disabled', 'fixture.interval.due', 'fixture.interval.notdue'],
            array_map(static fn (Task $task): string => $task->name(), $tasks)
        );
    }

    /**
     * Tests that getAllTasks() returns an empty array when no tasks exist.
     */
    public function testGetAllTasksEmpty(): void
    {
        $this->db->query('DELETE FROM [prefix]_scheduler_tasks');

        self::assertSame([], $this->out->getAllTasks());
    }

    /**
     * Tests that deleteTask() removes the task.
     */
    public function testDeleteTask(): void
    {
        $this->out->deleteTask('fixture.interval.due');

        self::assertNull($this->out->getTask('fixture.interval.due'));
        self::assertCount(3, $this->out->getAllTasks());
    }

    /**
     * Tests that deleteTask() on an unknown task does not throw.
     */
    public function testDeleteTaskIsIdempotentForUnknownTasks(): void
    {
        $this->out->deleteTask('does.not.exist'); // must not throw

        self::assertCount(4, $this->out->getAllTasks());
    }

    // -----------------------------------------------------------------
    // createTask()
    // -----------------------------------------------------------------

    /**
     * Tests that createTask() inserts an interval task and returns its new id.
     */
    public function testCreateTaskInterval(): void
    {
        $id = $this->out->createTask([
            'name'             => 'created.interval',
            'handler'          => 'created.interval',
            'interval_seconds' => 900,
            'payload'          => ['a' => 1],
        ]);

        self::assertIsInt($id);
        self::assertGreaterThan(0, $id);

        $task = $this->out->getTask('created.interval');
        self::assertInstanceOf(Task::class, $task);
        self::assertSame($id, $task->id());
        self::assertSame('created.interval', $task->name());
        self::assertSame('created.interval', $task->handler());
        self::assertSame(Scheduler::TYPE_INTERVAL, $task->type());
        self::assertSame(900, $task->intervalSeconds());
        self::assertNull($task->dailyTime());
        self::assertNull($task->timezone());
        self::assertSame(['a' => 1], $task->payload());
        self::assertTrue($task->enabled());
        self::assertSame(0, $task->failures());
        self::assertNull($task->lastStatus());
        self::assertNull($task->lastError());
        self::assertNull($task->leaseOwner());
        self::assertNotNull($task->created());
        $this->assertUtcWithinSeconds(time() + 900, $task->nextRun());
    }

    /**
     * Tests that createTask() stores a daily task with an explicit timezone.
     */
    public function testCreateTaskDaily(): void
    {
        $id = $this->out->createTask([
            'name'       => 'created.daily',
            'handler'    => 'created.daily',
            'type'       => Scheduler::TYPE_DAILY,
            'daily_time' => '23:45:00',
            'timezone'   => 'Europe/Berlin',
        ]);

        self::assertIsInt($id);
        self::assertGreaterThan(0, $id);

        $task = $this->out->getTask('created.daily');
        self::assertSame(Scheduler::TYPE_DAILY, $task->type());
        self::assertSame('23:45:00', $task->dailyTime());
        self::assertSame('Europe/Berlin', $task->timezone());
        self::assertNull($task->intervalSeconds());
        $this->assertTimeInTimezone('23:45:00', $task->nextRun(), 'Europe/Berlin');
        $this->assertNextDailyOccurrence($task->nextRun());
    }

    /**
     * Tests that a daily task without a timezone materializes the site default.
     */
    public function testCreateTaskDailyWithoutTimezoneUsesSiteDefault(): void
    {
        $this->out->createTask([
            'name'       => 'created.daily.defaulttz',
            'handler'    => 'created.daily.defaulttz',
            'type'       => Scheduler::TYPE_DAILY,
            'daily_time' => '06:00:00',
        ]);

        $task = $this->out->getTask('created.daily.defaulttz');
        // createTask() materializes the site default timezone (Europe/Berlin,
        // see fixture) into the row instead of leaving it NULL.
        self::assertSame('Europe/Berlin', $task->timezone());
        $this->assertTimeInTimezone('06:00:00', $task->nextRun(), 'Europe/Berlin');
        $this->assertNextDailyOccurrence($task->nextRun());
    }

    /**
     * Tests that createTask() respects enabled = false.
     */
    public function testCreateTaskDisabled(): void
    {
        $this->out->createTask([
            'name'             => 'created.disabled',
            'handler'          => 'created.disabled',
            'interval_seconds' => 60,
            'enabled'          => false,
        ]);

        $task = $this->out->getTask('created.disabled');
        self::assertInstanceOf(Task::class, $task);
        self::assertFalse($task->enabled());
    }

    /**
     * Tests that a JSON string payload is accepted and decoded on read.
     */
    public function testCreateTaskPayloadJsonString(): void
    {
        $this->out->createTask([
            'name'             => 'created.payloadjson',
            'handler'          => 'created.payloadjson',
            'interval_seconds' => 60,
            'payload'          => '{"k":"v"}',
        ]);

        self::assertSame(['k' => 'v'], $this->out->getTask('created.payloadjson')->payload());
    }

    /**
     * Tests that createTask() rejects a missing name.
     */
    public function testCreateTaskWithoutName(): void
    {
        $this->assertCreateTaskRejected([
            'handler'          => 'x.y',
            'interval_seconds' => 60,
        ]);
    }

    /**
     * Tests that createTask() rejects names with invalid characters.
     */
    public function testCreateTaskWithInvalidName(): void
    {
        $this->assertCreateTaskRejected([
            'name'             => 'bad/name',
            'handler'          => 'x.y',
            'interval_seconds' => 60,
        ]);
    }

    /**
     * Tests that createTask() rejects names longer than 191 chars.
     */
    public function testCreateTaskWithTooLongName(): void
    {
        $this->assertCreateTaskRejected([
            'name'             => str_repeat('a', 192),
            'handler'          => 'x.y',
            'interval_seconds' => 60,
        ]);
    }

    /**
     * Tests that createTask() accepts a 191-char (maximum length) name.
     */
    public function testCreateTaskWithMaxLengthName(): void
    {
        $name = str_repeat('a', 191);
        $this->out->createTask([
            'name'             => $name,
            'handler'          => 'x.y',
            'interval_seconds' => 60,
        ]);

        self::assertNotNull($this->out->getTask($name));
    }

    /**
     * Tests that createTask() rejects handlers with invalid characters.
     */
    public function testCreateTaskWithInvalidHandler(): void
    {
        $this->assertCreateTaskRejected([
            'name'             => 'x.y',
            'handler'          => 'bad handler',
            'interval_seconds' => 60,
        ]);
    }

    /**
     * Tests that createTask() rejects unknown task types.
     */
    public function testCreateTaskWithUnknownType(): void
    {
        $this->assertCreateTaskRejected([
            'name'             => 'x.y',
            'handler'          => 'x.y',
            'type'             => 'weekly',
            'interval_seconds' => 60,
        ]);
    }

    /**
     * Tests that createTask() rejects interval tasks without interval_seconds.
     */
    public function testCreateTaskIntervalWithoutIntervalSeconds(): void
    {
        $this->assertCreateTaskRejected([
            'name'    => 'x.y',
            'handler' => 'x.y',
        ]);
    }

    /**
     * Tests that createTask() rejects a zero interval.
     */
    public function testCreateTaskIntervalWithZeroIntervalSeconds(): void
    {
        $this->assertCreateTaskRejected([
            'name'             => 'x.y',
            'handler'          => 'x.y',
            'interval_seconds' => 0,
        ]);
    }

    /**
     * Tests that createTask() rejects a negative interval.
     */
    public function testCreateTaskIntervalWithNegativeIntervalSeconds(): void
    {
        $this->assertCreateTaskRejected([
            'name'             => 'x.y',
            'handler'          => 'x.y',
            'interval_seconds' => -5,
        ]);
    }

    /**
     * Tests that createTask() rejects daily tasks without daily_time.
     */
    public function testCreateTaskDailyWithoutDailyTime(): void
    {
        $this->assertCreateTaskRejected([
            'name'    => 'x.y',
            'handler' => 'x.y',
            'type'    => Scheduler::TYPE_DAILY,
        ]);
    }

    /**
     * Tests that createTask() rejects a daily_time outside 00:00:00-23:59:59.
     */
    public function testCreateTaskDailyWithInvalidDailyTime(): void
    {
        $this->assertCreateTaskRejected([
            'name'       => 'x.y',
            'handler'    => 'x.y',
            'type'       => Scheduler::TYPE_DAILY,
            'daily_time' => '24:00:00',
        ]);
    }

    /**
     * Tests that createTask() rejects a daily_time that lacks the seconds part.
     */
    public function testCreateTaskDailyWithUnformattedDailyTime(): void
    {
        $this->assertCreateTaskRejected([
            'name'       => 'x.y',
            'handler'    => 'x.y',
            'type'       => Scheduler::TYPE_DAILY,
            'daily_time' => '03:00',
        ]);
    }

    /**
     * Tests that createTask() rejects an unknown IANA timezone.
     */
    public function testCreateTaskWithInvalidTimezone(): void
    {
        $this->assertCreateTaskRejected([
            'name'       => 'x.y',
            'handler'    => 'x.y',
            'type'       => Scheduler::TYPE_DAILY,
            'daily_time' => '03:00:00',
            'timezone'   => 'Not/AZone',
        ]);
    }

    /**
     * Tests that createTask() rejects payloads that are neither array nor JSON.
     */
    public function testCreateTaskWithNonArrayPayload(): void
    {
        $this->assertCreateTaskRejected([
            'name'             => 'x.y',
            'handler'          => 'x.y',
            'interval_seconds' => 60,
            'payload'          => 123,
        ]);
    }

    /**
     * Tests that createTask() rejects invalid JSON payload strings.
     */
    public function testCreateTaskWithInvalidJsonPayload(): void
    {
        $this->assertCreateTaskRejected([
            'name'             => 'x.y',
            'handler'          => 'x.y',
            'interval_seconds' => 60,
            'payload'          => 'not-json',
        ]);
    }

    /**
     * Tests that the unique name key rejects a second insert with the same name.
     */
    public function testCreateTaskWithDuplicateName(): void
    {
        $data = [
            'name'             => 'dup.name',
            'handler'          => 'dup.name',
            'interval_seconds' => 60,
        ];
        $this->out->createTask($data);

        // The unique name key must reject the second insert.
        $this->expectException(\Exception::class);
        $this->out->createTask($data);
    }

    // -----------------------------------------------------------------
    // ensureTask()
    // -----------------------------------------------------------------

    /**
     * Tests that ensureTask() creates the task when no task with the name exists yet.
     */
    public function testEnsureTaskCreatesTaskWhenItDoesNotExist(): void
    {
        $id = $this->out->ensureTask([
            'name'             => 'ensured.interval',
            'handler'          => 'ensured.interval',
            'interval_seconds' => 900,
            'payload'          => ['b' => 2],
        ]);

        self::assertIsInt($id);
        self::assertGreaterThan(0, $id);

        $task = $this->out->getTask('ensured.interval');
        self::assertInstanceOf(Task::class, $task);
        self::assertSame($id, $task->id());
        self::assertSame('ensured.interval', $task->name());
        self::assertSame('ensured.interval', $task->handler());
        self::assertSame(Scheduler::TYPE_INTERVAL, $task->type());
        self::assertSame(900, $task->intervalSeconds());
        self::assertSame(['b' => 2], $task->payload());
        self::assertTrue($task->enabled());
        self::assertSame(0, $task->failures());
        $this->assertUtcWithinSeconds(time() + 900, $task->nextRun());
    }

    /**
     * Tests that ensureTask() returns the id of an existing task and leaves the
     * row untouched, even when the data describes a different schedule.
     */
    public function testEnsureTaskReturnsExistingTaskIdWithoutChanges(): void
    {
        $existing = $this->out->getTask('fixture.interval.due');
        self::assertNotNull($existing);

        // The data below conflicts with the existing row on every schedule
        // field; none of it may be applied.
        $id = $this->out->ensureTask([
            'name'             => 'fixture.interval.due',
            'handler'          => 'fixture.other.handler',
            'interval_seconds' => 999,
            'payload'          => ['other' => true],
        ]);

        self::assertSame($existing->id(), $id);

        $task = $this->out->getTask('fixture.interval.due');
        self::assertSame('fixture.interval.due', $task->handler());
        self::assertSame(3600, $task->intervalSeconds());
        self::assertSame(['step' => 2], $task->payload());
        self::assertSame('2020-01-01 00:00:00', $task->nextRun()->format('Y-m-d H:i:s'));

        // No second row was created.
        self::assertCount(4, $this->out->getAllTasks());
    }

    /**
     * Tests that calling ensureTask() repeatedly creates exactly one task and
     * always returns the same id.
     */
    public function testEnsureTaskIsIdempotentOnRepeatedCalls(): void
    {
        $this->db->query('DELETE FROM [prefix]_scheduler_tasks');

        $data = [
            'name'             => 'ensured.once',
            'handler'          => 'ensured.once',
            'interval_seconds' => 120,
        ];

        $first  = $this->out->ensureTask($data);
        $second = $this->out->ensureTask($data);

        self::assertIsInt($first);
        self::assertSame($first, $second);

        $task = $this->out->getTask('ensured.once');
        self::assertSame($first, $task->id());
        self::assertCount(1, $this->out->getAllTasks());
    }

    /**
     * Tests that ensureTask() throws for a missing name (no task to look up,
     * so the createTask() validation runs).
     */
    public function testEnsureTaskWithoutName(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->out->ensureTask(['handler' => 'x.y', 'interval_seconds' => 60]);
    }

    /**
     * Tests that ensureTask() rejects invalid data for a not-yet-existing task
     * and creates nothing.
     */
    public function testEnsureTaskWithInvalidDataCreatesNothing(): void
    {
        try {
            $this->out->ensureTask([
                'name'             => 'ensured.invalid',
                'handler'          => 'ensured.invalid',
                'interval_seconds' => 0,
            ]);
            self::fail('Expected \InvalidArgumentException was not thrown.');
        } catch (\InvalidArgumentException $e) {
            self::assertNotEmpty($e->getMessage());
        }

        self::assertNull($this->out->getTask('ensured.invalid'));
    }

    // -----------------------------------------------------------------
    // updateTask()
    // -----------------------------------------------------------------

    /**
     * Tests that updateTask() throws for an unknown task.
     */
    public function testUpdateTaskWithUnknownTask(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->out->updateTask('does.not.exist', ['enabled' => false]);
    }

    /**
     * Tests that changing the handler does not touch next_run.
     */
    public function testUpdateTaskHandler(): void
    {
        $this->out->updateTask('fixture.interval.due', ['handler' => 'fixture.new.handler']);

        $task = $this->out->getTask('fixture.interval.due');
        self::assertSame('fixture.new.handler', $task->handler());
        // Changing the handler is not schedule-relevant: next_run stays untouched.
        self::assertSame('2020-01-01 00:00:00', $task->nextRun()->format('Y-m-d H:i:s'));
        self::assertTrue($task->enabled());
    }

    /**
     * Tests that updateTask() can disable a task.
     */
    public function testUpdateTaskEnabled(): void
    {
        $this->out->updateTask('fixture.interval.due', ['enabled' => false]);

        $task = $this->out->getTask('fixture.interval.due');
        self::assertFalse($task->enabled());
        self::assertSame('2020-01-01 00:00:00', $task->nextRun()->format('Y-m-d H:i:s'));
    }

    /**
     * Tests that updateTask() replaces the payload.
     */
    public function testUpdateTaskPayload(): void
    {
        $this->out->updateTask('fixture.interval.due', ['payload' => ['max' => 5]]);

        self::assertSame(['max' => 5], $this->out->getTask('fixture.interval.due')->payload());
    }

    /**
     * Tests that updateTask() can reset the payload to NULL.
     */
    public function testUpdateTaskPayloadToNull(): void
    {
        // The fixture task carries a payload; resetting it must write an explicit NULL.
        $this->out->updateTask('fixture.interval.due', ['payload' => null]);

        self::assertNull($this->out->getTask('fixture.interval.due')->payload());
    }

    /**
     * Tests that changing the interval recomputes next_run from now.
     */
    public function testUpdateTaskIntervalSeconds(): void
    {
        $this->out->updateTask('fixture.interval.due', ['interval_seconds' => 7200]);

        $task = $this->out->getTask('fixture.interval.due');
        self::assertSame(7200, $task->intervalSeconds());
        $this->assertUtcWithinSeconds(time() + 7200, $task->nextRun());
    }

    /**
     * Tests that changing daily_time recomputes next_run in the task's zone.
     */
    public function testUpdateTaskDailyTime(): void
    {
        $this->out->updateTask('fixture.daily', ['daily_time' => '04:30:00']);

        $task = $this->out->getTask('fixture.daily');
        self::assertSame('04:30:00', $task->dailyTime());
        $this->assertTimeInTimezone('04:30:00', $task->nextRun(), 'Europe/Berlin');
        $this->assertNextDailyOccurrence($task->nextRun());
    }

    /**
     * Tests that changing the timezone recomputes next_run in the new zone.
     */
    public function testUpdateTaskTimezone(): void
    {
        $this->out->updateTask('fixture.daily', ['timezone' => 'America/New_York']);

        $task = $this->out->getTask('fixture.daily');
        self::assertSame('America/New_York', $task->timezone());
        $this->assertTimeInTimezone('03:00:00', $task->nextRun(), 'America/New_York');
        $this->assertNextDailyOccurrence($task->nextRun());
    }

    /**
     * Tests that resetting the timezone to NULL falls back to the site default.
     */
    public function testUpdateTaskTimezoneToNull(): void
    {
        // The explicit-NULL write must fall back to the task's previous timezone.
        $this->out->updateTask('fixture.daily', ['timezone' => null]);

        $task = $this->out->getTask('fixture.daily');
        self::assertNull($task->timezone());
        $this->assertTimeInTimezone('03:00:00', $task->nextRun(), 'Europe/Berlin');
        $this->assertNextDailyOccurrence($task->nextRun());
    }

    /**
     * Tests that switching to a daily task stores and schedules the new schedule.
     */
    public function testUpdateTaskTypeToDaily(): void
    {
        $this->out->updateTask('fixture.interval.due', [
            'type'       => Scheduler::TYPE_DAILY,
            'daily_time' => '05:00:00',
            'timezone'   => 'UTC',
        ]);

        $task = $this->out->getTask('fixture.interval.due');
        self::assertSame(Scheduler::TYPE_DAILY, $task->type());
        self::assertSame('05:00:00', $task->dailyTime());
        $this->assertTimeInTimezone('05:00:00', $task->nextRun(), 'UTC');
        $this->assertNextDailyOccurrence($task->nextRun());
    }

    /**
     * Tests that switching to an interval task stores and schedules the new schedule.
     */
    public function testUpdateTaskTypeToInterval(): void
    {
        $this->out->updateTask('fixture.daily', [
            'type'             => Scheduler::TYPE_INTERVAL,
            'interval_seconds' => 1200,
        ]);

        $task = $this->out->getTask('fixture.daily');
        self::assertSame(Scheduler::TYPE_INTERVAL, $task->type());
        self::assertSame(1200, $task->intervalSeconds());
        $this->assertUtcWithinSeconds(time() + 1200, $task->nextRun());
    }

    /**
     * Tests that updateTask() rejects a zero interval and keeps the task unchanged.
     */
    public function testUpdateTaskWithZeroIntervalSeconds(): void
    {
        $this->assertUpdateTaskRejected('fixture.interval.due', ['interval_seconds' => 0], function (): void {
            $task = $this->out->getTask('fixture.interval.due');
            self::assertSame(3600, $task->intervalSeconds());
            self::assertSame('2020-01-01 00:00:00', $task->nextRun()->format('Y-m-d H:i:s'));
        });
    }

    /**
     * Tests that switching to an interval task without interval_seconds is rejected.
     */
    public function testUpdateTaskIntervalWithoutIntervalSeconds(): void
    {
        // The daily fixture task has no interval_seconds, so switching it to an
        // interval task without providing one must fail and leave it unchanged.
        $this->assertUpdateTaskRejected('fixture.daily', ['type' => Scheduler::TYPE_INTERVAL], function (): void {
            $task = $this->out->getTask('fixture.daily');
            self::assertSame(Scheduler::TYPE_DAILY, $task->type());
            self::assertSame('03:00:00', $task->dailyTime());
        });
    }

    /**
     * Tests that switching to a daily task without daily_time is rejected.
     */
    public function testUpdateTaskDailyWithoutDailyTime(): void
    {
        $this->assertUpdateTaskRejected('fixture.interval.due', ['type' => Scheduler::TYPE_DAILY], function (): void {
            $task = $this->out->getTask('fixture.interval.due');
            self::assertSame(Scheduler::TYPE_INTERVAL, $task->type());
            self::assertSame(3600, $task->intervalSeconds());
        });
    }

    /**
     * Tests that updateTask() rejects an unknown timezone and keeps the task unchanged.
     */
    public function testUpdateTaskWithInvalidTimezone(): void
    {
        $this->assertUpdateTaskRejected('fixture.daily', ['timezone' => 'Not/AZone'], function (): void {
            $task = $this->out->getTask('fixture.daily');
            self::assertSame('Europe/Berlin', $task->timezone());
        });
    }

    /**
     * Tests that updateTask() rejects an invalid daily_time and keeps the task unchanged.
     */
    public function testUpdateTaskWithInvalidDailyTime(): void
    {
        $this->assertUpdateTaskRejected('fixture.daily', ['daily_time' => '25:00:00'], function (): void {
            $task = $this->out->getTask('fixture.daily');
            self::assertSame('03:00:00', $task->dailyTime());
        });
    }

    /**
     * Tests that updateTask() with empty data changes nothing.
     */
    public function testUpdateTaskWithoutDataDoesNothing(): void
    {
        $this->out->updateTask('fixture.interval.due', []); // must not throw

        $task = $this->out->getTask('fixture.interval.due');
        self::assertSame(3600, $task->intervalSeconds());
        self::assertSame('2020-01-01 00:00:00', $task->nextRun()->format('Y-m-d H:i:s'));
        self::assertSame(['step' => 2], $task->payload());
    }

    // -----------------------------------------------------------------
    // timeLeftMs()
    // -----------------------------------------------------------------

    /**
     * Tests that the full budget is reported when no request start time is set.
     */
    public function testTimeLeftMsWithoutRequestStartTime(): void
    {
        self::assertSame((float)Scheduler::BUDGET_MS, $this->out->timeLeftMs());
    }

    /**
     * Tests that an exhausted budget reports zero.
     */
    public function testTimeLeftMsWithExhaustedBudget(): void
    {
        Registry::set('startTime', microtime(true) - 10);

        self::assertSame(0.0, $this->out->timeLeftMs());
    }

    /**
     * Tests that a partially used budget reports the remaining time.
     */
    public function testTimeLeftMsWithPartialBudget(): void
    {
        Registry::set('startTime', microtime(true));

        $left = $this->out->timeLeftMs();
        self::assertGreaterThan(0.0, $left);
        self::assertLessThanOrEqual((float)Scheduler::BUDGET_MS, $left);
    }

    // -----------------------------------------------------------------
    // runDue()
    // -----------------------------------------------------------------

    /**
     * Tests that a due interval task runs, records the outcome and reschedules.
     */
    public function testRunDueRunsDueIntervalTask(): void
    {
        $calls = [];
        $schedulers = [];
        Scheduler::registerHandler('test.success', function (?array $payload, Scheduler $scheduler) use (&$calls, &$schedulers): void {
            $calls[] = $payload;
            $schedulers[] = $scheduler;
        });

        $this->db->query('DELETE FROM [prefix]_scheduler_tasks');
        $this->insertTask([
            'name'    => 'test.success',
            'handler' => 'test.success',
            'payload' => '{"a":1}',
        ]);

        $this->out->runDue();

        // The decoded payload and the scheduler service are passed to the handler.
        self::assertSame([['a' => 1]], $calls);
        self::assertSame($this->out, $schedulers[0]);

        $task = $this->out->getTask('test.success');
        self::assertSame('success', $task->lastStatus());
        self::assertNull($task->lastError());
        self::assertSame(0, $task->failures());
        self::assertNull($task->leaseOwner());
        self::assertNull($task->leaseExpires());
        $this->assertUtcWithinSeconds(time(), $task->lastStarted());
        $this->assertUtcWithinSeconds(time(), $task->lastFinished());
        // Interval tasks anchor the next run to the (claim) time: now + interval.
        $this->assertUtcWithinSeconds(time() + 3600, $task->nextRun());

        // A clean run must not write any scheduler log line.
        self::assertSame([], $this->schedulerLogLines('Scheduler:%'));
    }

    /**
     * Tests that a not-yet-due task is not touched.
     */
    public function testRunDueSkipsNotDueTask(): void
    {
        $called = false;
        Scheduler::registerHandler('test.notdue', function (?array $payload, Scheduler $scheduler) use (&$called): void {
            $called = true;
        });

        $nextRun = date('Y-m-d H:i:s', time() + 3600);
        $this->db->query('DELETE FROM [prefix]_scheduler_tasks');
        $this->insertTask(['name' => 'test.notdue', 'handler' => 'test.notdue', 'next_run' => $nextRun]);

        $this->out->runDue();

        self::assertFalse($called);

        $task = $this->out->getTask('test.notdue');
        self::assertSame($nextRun, $task->nextRun()->format('Y-m-d H:i:s'));
        self::assertNull($task->leaseOwner());
        self::assertNull($task->lastStatus());
    }

    /**
     * Tests that a disabled task is not run.
     */
    public function testRunDueSkipsDisabledTask(): void
    {
        $called = false;
        Scheduler::registerHandler('test.disabled', function (?array $payload, Scheduler $scheduler) use (&$called): void {
            $called = true;
        });

        $this->db->query('DELETE FROM [prefix]_scheduler_tasks');
        $this->insertTask(['name' => 'test.disabled', 'handler' => 'test.disabled', 'enabled' => 0]);

        $this->out->runDue();

        self::assertFalse($called);

        $task = $this->out->getTask('test.disabled');
        self::assertFalse($task->enabled());
        self::assertNull($task->leaseOwner());
        self::assertNull($task->lastStatus());
    }

    /**
     * Tests that a task with an active lease is not taken over.
     */
    public function testRunDueSkipsTaskWithActiveLease(): void
    {
        $called = false;
        Scheduler::registerHandler('test.leased', function (?array $payload, Scheduler $scheduler) use (&$called): void {
            $called = true;
        });

        $this->db->query('DELETE FROM [prefix]_scheduler_tasks');
        $this->insertTask([
            'name'          => 'test.leased',
            'handler'       => 'test.leased',
            'lease_owner'   => 'other-request',
            'lease_expires' => date('Y-m-d H:i:s', time() + 3600),
        ]);

        $this->out->runDue();

        self::assertFalse($called);

        $task = $this->out->getTask('test.leased');
        self::assertSame('other-request', $task->leaseOwner());
        self::assertNull($task->lastStatus());
    }

    /**
     * Tests that a task whose lease expired can be claimed and run again.
     */
    public function testRunDueTakesOverExpiredLease(): void
    {
        $calls = 0;
        Scheduler::registerHandler('test.expiredlease', function (?array $payload, Scheduler $scheduler) use (&$calls): void {
            $calls += 1;
        });

        $this->db->query('DELETE FROM [prefix]_scheduler_tasks');
        $this->insertTask([
            'name'          => 'test.expiredlease',
            'handler'       => 'test.expiredlease',
            'lease_owner'   => 'dead-request',
            'lease_expires' => date('Y-m-d H:i:s', time() - 3600),
        ]);

        $this->out->runDue();

        self::assertSame(1, $calls);

        $task = $this->out->getTask('test.expiredlease');
        self::assertSame('success', $task->lastStatus());
        self::assertNull($task->leaseOwner());
        self::assertNull($task->leaseExpires());
    }

    /**
     * Tests that only the single oldest due task runs per request.
     */
    public function testRunDueOnlyRunsOneTaskPerRequest(): void
    {
        $calls = [];
        foreach (['test.older', 'test.newer'] as $name) {
            Scheduler::registerHandler($name, function (?array $payload, Scheduler $scheduler) use (&$calls, $name): void {
                $calls[] = $name;
            });
        }

        $newerNextRun = date('Y-m-d H:i:s', time() - 3600);
        $this->db->query('DELETE FROM [prefix]_scheduler_tasks');
        $this->insertTask(['name' => 'test.older', 'handler' => 'test.older', 'next_run' => date('Y-m-d H:i:s', time() - 7200)]);
        $this->insertTask(['name' => 'test.newer', 'handler' => 'test.newer', 'next_run' => $newerNextRun]);

        $this->out->runDue();

        // Only the single oldest due task may run per request.
        self::assertSame(['test.older'], $calls);

        $newer = $this->out->getTask('test.newer');
        self::assertSame($newerNextRun, $newer->nextRun()->format('Y-m-d H:i:s'));
        self::assertNull($newer->leaseOwner());
        self::assertNull($newer->lastStatus());
    }

    /**
     * Tests that runDue() is a no-op when the request budget is exhausted.
     */
    public function testRunDueDoesNotStartWhenBudgetIsExhausted(): void
    {
        $called = false;
        Scheduler::registerHandler('test.budget', function (?array $payload, Scheduler $scheduler) use (&$called): void {
            $called = true;
        });

        $this->db->query('DELETE FROM [prefix]_scheduler_tasks');
        $this->insertTask(['name' => 'test.budget', 'handler' => 'test.budget']);

        // Pretend this request has already spent more than the whole budget.
        Registry::set('startTime', microtime(true) - 10);

        $this->out->runDue();

        self::assertFalse($called);

        $task = $this->out->getTask('test.budget');
        self::assertNull($task->leaseOwner());
        self::assertNull($task->lastStatus());
    }

    /**
     * Tests that an unknown handler is recorded as a failure, backed off and logged.
     */
    public function testRunDueWithUnknownHandler(): void
    {
        // No handler is registered and the handler name has no module segment,
        // so the "Unknown handler" failure path must be taken.
        $this->db->query('DELETE FROM [prefix]_scheduler_tasks');
        $this->insertTask(['name' => 'no-such-handler', 'handler' => 'no-such-handler']);

        $this->out->runDue(); // must not throw

        $task = $this->out->getTask('no-such-handler');
        self::assertSame('failure', $task->lastStatus());
        self::assertSame('Unknown handler: no-such-handler', $task->lastError());
        self::assertSame(1, $task->failures());
        self::assertNull($task->leaseOwner());
        self::assertNull($task->leaseExpires());
        // The first retry is RETRY_BASE_SECONDS away.
        $this->assertUtcWithinSeconds(time() + Scheduler::RETRY_BASE_SECONDS, $task->nextRun());

        $logs = $this->schedulerLogLines('Scheduler: Task "no-such-handler" has unknown handler "no-such-handler".');
        self::assertCount(1, $logs);
    }

    /**
     * Tests that consecutive failures back off exponentially (60s, 120s, 240s).
     */
    public function testRunDueWithFailingHandlerRetriesWithBackoff(): void
    {
        Scheduler::registerHandler('test.failing', function (?array $payload, Scheduler $scheduler): void {
            throw new \RuntimeException('boom');
        });

        $this->db->query('DELETE FROM [prefix]_scheduler_tasks');
        $this->insertTask(['name' => 'test.failing', 'handler' => 'test.failing']);

        $this->out->runDue();
        $task = $this->out->getTask('test.failing');
        self::assertSame(1, $task->failures());
        self::assertSame('failure', $task->lastStatus());
        self::assertSame('boom', $task->lastError());
        $this->assertUtcWithinSeconds(time() + 60, $task->nextRun());

        $this->makeDue('test.failing');
        $this->out->runDue();
        $task = $this->out->getTask('test.failing');
        self::assertSame(2, $task->failures());
        $this->assertUtcWithinSeconds(time() + 120, $task->nextRun());

        $this->makeDue('test.failing');
        $this->out->runDue();
        $task = $this->out->getTask('test.failing');
        self::assertSame(3, $task->failures());
        $this->assertUtcWithinSeconds(time() + 240, $task->nextRun());
    }

    /**
     * Tests that the retry backoff is capped at RETRY_MAX_SECONDS.
     */
    public function testRunDueBackoffIsCapped(): void
    {
        Scheduler::registerHandler('test.capped', function (?array $payload, Scheduler $scheduler): void {
            throw new \RuntimeException('boom');
        });

        $this->db->query('DELETE FROM [prefix]_scheduler_tasks');
        $this->insertTask(['name' => 'test.capped', 'handler' => 'test.capped', 'failures' => 16]);

        $this->out->runDue();

        $task = $this->out->getTask('test.capped');
        self::assertSame(17, $task->failures());
        // 60 * 2^16 is far above the cap: the delay must be exactly RETRY_MAX_SECONDS.
        $this->assertUtcWithinSeconds(time() + Scheduler::RETRY_MAX_SECONDS, $task->nextRun());
    }

    /**
     * Tests that a successful run resets the failure counter and clears last_error.
     */
    public function testRunDueWithSuccessfulHandlerResetsFailures(): void
    {
        $calls = 0;
        Scheduler::registerHandler('test.reset', function (?array $payload, Scheduler $scheduler) use (&$calls): void {
            $calls += 1;
        });

        $this->db->query('DELETE FROM [prefix]_scheduler_tasks');
        $this->insertTask([
            'name'        => 'test.reset',
            'handler'     => 'test.reset',
            'failures'    => 5,
            'last_status' => 'failure',
            'last_error'  => 'old error',
        ]);

        $this->out->runDue();

        self::assertSame(1, $calls);

        $task = $this->out->getTask('test.reset');
        self::assertSame(0, $task->failures());
        self::assertSame('success', $task->lastStatus());
        self::assertNull($task->lastError());
        $this->assertUtcWithinSeconds(time() + 3600, $task->nextRun());
    }

    /**
     * Tests that a successful daily run schedules the next occurrence of daily_time.
     */
    public function testRunDueWithDailyTaskSchedulesNextOccurrence(): void
    {
        $calls = 0;
        Scheduler::registerHandler('test.daily', function (?array $payload, Scheduler $scheduler) use (&$calls): void {
            $calls += 1;
        });

        $this->db->query('DELETE FROM [prefix]_scheduler_tasks');
        $this->insertTask([
            'name'       => 'test.daily',
            'handler'    => 'test.daily',
            'type'       => Scheduler::TYPE_DAILY,
            'daily_time' => '03:00:00',
            'timezone'   => 'Europe/Berlin',
            'next_run'   => date('Y-m-d H:i:s', time() - 3600),
        ]);

        $this->out->runDue();

        self::assertSame(1, $calls);

        $task = $this->out->getTask('test.daily');
        self::assertSame('success', $task->lastStatus());
        self::assertSame(0, $task->failures());
        $this->assertTimeInTimezone('03:00:00', $task->nextRun(), 'Europe/Berlin');
        $this->assertNextDailyOccurrence($task->nextRun());
    }

    /**
     * Tests that a daily task without a timezone uses the site default zone.
     */
    public function testRunDueWithDailyTaskWithoutTimezoneUsesSiteDefault(): void
    {
        $calls = 0;
        Scheduler::registerHandler('test.daily.defaulttz', function (?array $payload, Scheduler $scheduler) use (&$calls): void {
            $calls += 1;
        });

        $this->db->query('DELETE FROM [prefix]_scheduler_tasks');
        $this->insertTask([
            'name'       => 'test.daily.defaulttz',
            'handler'    => 'test.daily.defaulttz',
            'type'       => Scheduler::TYPE_DAILY,
            'daily_time' => '04:15:00',
        ]);

        $this->out->runDue();

        self::assertSame(1, $calls);

        $task = $this->out->getTask('test.daily.defaulttz');
        self::assertNull($task->timezone());
        // The site default timezone (Europe/Berlin, see fixture) must be used.
        $this->assertTimeInTimezone('04:15:00', $task->nextRun(), 'Europe/Berlin');
        $this->assertNextDailyOccurrence($task->nextRun());
    }

    /**
     * Tests that runDue() absorbs a missing table and logs the failure.
     */
    public function testRunDueSurvivesMissingTable(): void
    {
        $this->db->query('DROP TABLE [prefix]_scheduler_tasks');

        $this->out->runDue(); // must absorb the database error, never throw

        self::assertNotSame([], $this->schedulerLogLines('Scheduler: runDue failed:%'));

        // Restore the table so later tests in this process are unaffected.
        $this->db->queryMulti(static::getSchemaSQLQueries());
    }

    // -----------------------------------------------------------------
    // Helpers
    // -----------------------------------------------------------------

    /**
     * Inserts one raw task row. $overrides may replace any of the defaults.
     *
     * @param array<string, mixed> $overrides
     * @return void
     */
    private function insertTask(array $overrides = []): void
    {
        $row = array_merge([
            'name'             => 'test.default',
            'handler'          => 'test.default',
            'type'             => Scheduler::TYPE_INTERVAL,
            'interval_seconds' => 3600,
            'daily_time'       => null,
            'timezone'         => null,
            'payload'          => null,
            'enabled'          => 1,
            'next_run'         => date('Y-m-d H:i:s', time() - 3600), // due
            'last_started'     => null,
            'last_finished'    => null,
            'last_status'      => null,
            'last_error'       => null,
            'failures'         => 0,
            'lease_owner'      => null,
            'lease_expires'    => null,
        ], $overrides);

        $values = [];
        foreach ($row as $value) {
            $values[] = $value === null ? 'NULL' : $this->db->escape((string) $value, true);
        }

        $this->db->query('INSERT INTO [prefix]_scheduler_tasks ('
            . implode(', ', array_keys($row)) . ') VALUES (' . implode(', ', $values) . ')');
    }

    /**
     * Forces a task to be due again (used between consecutive runDue() calls).
     *
     * @param string $name
     * @return void
     */
    private function makeDue(string $name): void
    {
        $this->db->query('UPDATE [prefix]_scheduler_tasks SET next_run = '
            . $this->db->escape(date('Y-m-d H:i:s', time() - 10), true)
            . ' WHERE name = ' . $this->db->escape($name, true));
    }

    /**
     * Asserts that createTask() rejects $data and did not create anything.
     *
     * @param array<string, mixed> $data
     * @return void
     */
    private function assertCreateTaskRejected(array $data): void
    {
        try {
            $this->out->createTask($data);
            self::fail('Expected \InvalidArgumentException was not thrown.');
        } catch (\InvalidArgumentException $e) {
            self::assertNotEmpty($e->getMessage());
        }

        self::assertNull($this->out->getTask($data['name'] ?? 'missing.name'));
    }

    /**
     * Asserts that updateTask() rejects the data and leaves the task unchanged.
     *
     * @param string $name
     * @param array<string, mixed> $data
     * @param \Closure(): void $verifyUnchanged
     * @return void
     */
    private function assertUpdateTaskRejected(string $name, array $data, \Closure $verifyUnchanged): void
    {
        try {
            $this->out->updateTask($name, $data);
            self::fail('Expected \InvalidArgumentException was not thrown.');
        } catch (\InvalidArgumentException $e) {
            self::assertNotEmpty($e->getMessage());
        }

        $verifyUnchanged();
    }

    /**
     * Asserts that the UTC instant lies within ±$tolerance seconds of $expectedUnix.
     *
     * @param int $expectedUnix
     * @param \DateTimeImmutable|null $actual
     * @param int $tolerance
     * @return void
     */
    private function assertUtcWithinSeconds(int $expectedUnix, ?\DateTimeImmutable $actual, int $tolerance = 5): void
    {
        self::assertNotNull($actual);
        $actualUnix = $actual->getTimestamp();
        self::assertGreaterThan($expectedUnix - $tolerance, $actualUnix);
        self::assertLessThan($expectedUnix + $tolerance, $actualUnix);
    }

    /**
     * Asserts that the UTC instant has the given wall-clock time in $timezone.
     *
     * @param string $expectedTime Expected time as 'H:i:s'.
     * @param \DateTimeImmutable $utc
     * @param string $timezone
     * @return void
     */
    private function assertTimeInTimezone(string $expectedTime, \DateTimeImmutable $utc, string $timezone): void
    {
        self::assertSame($expectedTime, $utc->setTimezone(new \DateTimeZone($timezone))->format('H:i:s'));
    }

    /**
     * Asserts that $nextRun is strictly in the future and at most 24 hours away.
     *
     * @param \DateTimeImmutable $nextRun
     * @return void
     */
    private function assertNextDailyOccurrence(\DateTimeImmutable $nextRun): void
    {
        $now = new \DateTimeImmutable('now', new \DateTimeZone('UTC'));
        self::assertGreaterThan($now, $nextRun);
        self::assertLessThanOrEqual($now->add(new \DateInterval('P1D')), $nextRun);
    }

    /**
     * Log lines written by the scheduler whose info matches the SQL LIKE pattern.
     *
     * @param string $like SQL LIKE pattern for the info column.
     * @return array<int, array<string, mixed>>
     */
    private function schedulerLogLines(string $like): array
    {
        return $this->db->queryArray('SELECT * FROM [prefix]_logs WHERE user_id = 1 AND info LIKE '
            . $this->db->escape($like, true));
    }
}
