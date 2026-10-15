<?php

/**
 * Tests for \Ilch\Scheduler\Task (row mapping, defaults and fluent setters).
 *
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Ilch\Scheduler;

use PHPUnit\Framework\TestCase;
use Ilch\Scheduler\Task as TaskModel;

class TaskTest extends TestCase
{
    // -----------------------------------------------------------------
    // fromRow()
    // -----------------------------------------------------------------

    /**
     * Tests that fromRow() maps a fully populated database row.
     */
    public function testFromRow()
    {
        $task = TaskModel::fromRow($this->buildFullRow());

        self::assertSame(7, $task->id());
        self::assertSame('Cleanup Logs', $task->name());
        self::assertSame('App\Handlers\CleanupLogsHandler', $task->handler());
        self::assertSame(Scheduler::TYPE_INTERVAL, $task->type());
        self::assertSame(3600, $task->intervalSeconds());
        self::assertSame('08:30:00', $task->dailyTime());
        self::assertSame('UTC', $task->timezone());
        self::assertSame(['threshold' => 10, 'dryRun' => false], $task->payload());
        self::assertTrue($task->enabled());
        self::assertSame(2, $task->failures());
        self::assertSame('error', $task->lastStatus());
        self::assertSame('Connection refused', $task->lastError());
        self::assertSame('worker-42', $task->leaseOwner());

        $this->assertUtcDateTime('2025-03-10 08:30:00', $task->nextRun());
        $this->assertUtcDateTime('2025-03-10 07:00:00', $task->lastStarted());
        $this->assertUtcDateTime('2025-03-10 07:00:05', $task->lastFinished());
        $this->assertUtcDateTime('2025-03-10 07:10:00', $task->leaseExpires());
        $this->assertUtcDateTime('2025-01-01 12:00:00', $task->created());
    }

    /**
     * Tests that a row with only a few columns still yields a valid task.
     */
    public function testFromRowMinimalRow()
    {
        $task = TaskModel::fromRow(['id' => 1, 'name' => 'Ping']);

        self::assertSame(1, $task->id());
        self::assertSame('Ping', $task->name());
        self::assertSame('', $task->handler());
        self::assertSame(Scheduler::TYPE_INTERVAL, $task->type());
        self::assertNull($task->intervalSeconds());
        self::assertNull($task->payload());
        self::assertTrue($task->enabled());
        self::assertSame(0, $task->failures());
        self::assertNull($task->nextRun());
        self::assertNull($task->created());
    }

    /**
     * Tests that missing keys fall back to the documented defaults.
     */
    public function testFromRowMissingKeys()
    {
        $task = TaskModel::fromRow([]);

        self::assertNull($task->id());
        self::assertSame('', $task->name());
        self::assertSame('', $task->handler());
        self::assertSame(Scheduler::TYPE_INTERVAL, $task->type());
        self::assertNull($task->intervalSeconds());
        self::assertNull($task->dailyTime());
        self::assertNull($task->timezone());
        self::assertNull($task->payload());
        self::assertTrue($task->enabled());
        self::assertSame(0, $task->failures());
        self::assertNull($task->lastStatus());
        self::assertNull($task->lastError());
        self::assertNull($task->leaseOwner());
        self::assertNull($task->nextRun());
        self::assertNull($task->lastStarted());
        self::assertNull($task->lastFinished());
        self::assertNull($task->leaseExpires());
        self::assertNull($task->created());
    }

    /**
     * Tests that null/empty database values map to null (empty string for name/handler).
     */
    public function testFromRowNullAndEmptyValues()
    {
        $task = TaskModel::fromRow([
            'id'               => null,
            'name'             => null,
            'handler'          => null,
            'interval_seconds' => null,
            'daily_time'       => null,
            'timezone'         => null,
            'payload'          => null,
            'enabled'          => null,
            'failures'         => null,
            'next_run'         => '',
            'last_started'     => null,
            'last_finished'    => '',
            'last_status'      => null,
            'last_error'       => null,
            'lease_owner'      => null,
            'lease_expires'    => '',
            'created'          => null,
        ]);

        self::assertNull($task->id());
        self::assertSame('', $task->name());
        self::assertSame('', $task->handler());
        self::assertNull($task->intervalSeconds());
        self::assertNull($task->dailyTime());
        self::assertNull($task->timezone());
        self::assertNull($task->payload());
        self::assertTrue($task->enabled());
        self::assertSame(0, $task->failures());
        self::assertNull($task->lastStatus());
        self::assertNull($task->lastError());
        self::assertNull($task->leaseOwner());
        self::assertNull($task->nextRun());
        self::assertNull($task->lastStarted());
        self::assertNull($task->lastFinished());
        self::assertNull($task->leaseExpires());
        self::assertNull($task->created());
    }

    /**
     * Tests that numeric database strings are cast to their native types.
     */
    public function testFromRowCastsNumericStrings()
    {
        $task = TaskModel::fromRow([
            'id'               => '17',
            'interval_seconds' => '86400',
            'failures'         => '3',
        ]);

        self::assertSame(17, $task->id());
        self::assertIsInt($task->id());
        self::assertSame(86400, $task->intervalSeconds());
        self::assertSame(3, $task->failures());
        self::assertIsInt($task->failures());
    }

    /**
     * Tests that enabled is interpreted as a boolean, defaulting to true.
     */
    public function testFromRowEnabled()
    {
        self::assertTrue(TaskModel::fromRow(['enabled' => 1])->enabled());
        self::assertFalse(TaskModel::fromRow(['enabled' => 0])->enabled());
        self::assertFalse(TaskModel::fromRow(['enabled' => '0'])->enabled());
        self::assertTrue(TaskModel::fromRow(['enabled' => null])->enabled());
        self::assertTrue(TaskModel::fromRow([])->enabled());
    }

    /**
     * Tests that a missing or null type falls back to the interval default.
     */
    public function testFromRowTypeDefault()
    {
        self::assertSame(Scheduler::TYPE_INTERVAL, TaskModel::fromRow([])->type());
        self::assertSame(Scheduler::TYPE_INTERVAL, TaskModel::fromRow(['type' => null])->type());
    }

    /**
     * Tests that dailyTime stays a raw H:i:s string (schedule parameter, not a point in time).
     */
    public function testFromRowDailyTimeStaysRawString()
    {
        $task = TaskModel::fromRow(['daily_time' => '08:30:00']);

        self::assertSame('08:30:00', $task->dailyTime());
        self::assertIsString($task->dailyTime());
    }

    /**
     * Tests that datetime columns are parsed as \DateTimeImmutable in UTC.
     */
    public function testFromRowDatetimeColumnsAreUtc()
    {
        $task = TaskModel::fromRow([
            'next_run'      => '2025-03-10 08:30:00',
            'last_started'  => '2025-03-10 07:00:00',
            'last_finished' => '2025-03-10 07:00:05',
            'lease_expires' => '2025-03-10 07:10:00',
            'created'       => '2025-01-01 12:00:00',
        ]);

        $this->assertUtcDateTime('2025-03-10 08:30:00', $task->nextRun());
        $this->assertUtcDateTime('2025-03-10 07:00:00', $task->lastStarted());
        $this->assertUtcDateTime('2025-03-10 07:00:05', $task->lastFinished());
        $this->assertUtcDateTime('2025-03-10 07:10:00', $task->leaseExpires());
        $this->assertUtcDateTime('2025-01-01 12:00:00', $task->created());
    }

    /**
     * Tests that a JSON object string is decoded into an array.
     */
    public function testFromRowDecodesJsonPayloadObject()
    {
        $task = TaskModel::fromRow(['payload' => '{"foo":"bar","count":5}']);

        self::assertSame(['foo' => 'bar', 'count' => 5], $task->payload());
    }

    /**
     * Tests that a JSON array string is decoded into an array.
     */
    public function testFromRowDecodesJsonPayloadArray()
    {
        $task = TaskModel::fromRow(['payload' => '["a","b"]']);

        self::assertSame(['a', 'b'], $task->payload());
    }

    /**
     * Tests that invalid, empty, scalar or non-string payloads result in null.
     */
    public function testFromRowPayloadNotAnArray()
    {
        $cases = [
            null,
            '',
            'not-json',
            '42',
            '"a string"',
            'true',
            ['already' => 'decoded'],
        ];

        foreach ($cases as $payload) {
            $task = TaskModel::fromRow(['payload' => $payload]);
            self::assertNull($task->payload(), 'Expected null payload for: ' . var_export($payload, true));
        }

        self::assertNull(TaskModel::fromRow([])->payload());
    }

    // -----------------------------------------------------------------
    // Defaults
    // -----------------------------------------------------------------

    /**
     * Tests that a fresh Task holds the documented defaults.
     */
    public function testDefaultValues()
    {
        $task = new TaskModel();

        self::assertNull($task->id());
        self::assertSame('', $task->name());
        self::assertSame('', $task->handler());
        self::assertSame(Scheduler::TYPE_INTERVAL, $task->type());
        self::assertNull($task->intervalSeconds());
        self::assertNull($task->dailyTime());
        self::assertNull($task->timezone());
        self::assertNull($task->payload());
        self::assertTrue($task->enabled());
        self::assertNull($task->nextRun());
        self::assertNull($task->lastStarted());
        self::assertNull($task->lastFinished());
        self::assertNull($task->lastStatus());
        self::assertNull($task->lastError());
        self::assertSame(0, $task->failures());
        self::assertNull($task->leaseOwner());
        self::assertNull($task->leaseExpires());
        self::assertNull($task->created());
    }

    // -----------------------------------------------------------------
    // Fluent setters
    // -----------------------------------------------------------------

    /**
     * Tests that setId() sets and returns the id, including 0 and null.
     */
    public function testSetId()
    {
        $task = new TaskModel();
        $task->setId(5);

        self::assertSame(5, $task->id());

        $task->setId(0);
        self::assertSame(0, $task->id());

        $task->setId(null);
        self::assertNull($task->id());
    }

    /**
     * Tests that setName() sets and returns the name.
     */
    public function testSetName()
    {
        $task = new TaskModel();
        $task->setName('Cleanup Logs');

        self::assertSame('Cleanup Logs', $task->name());
    }

    /**
     * Tests that setHandler() sets and returns the handler.
     */
    public function testSetHandler()
    {
        $task = new TaskModel();
        $task->setHandler('App\Handlers\CleanupLogsHandler');

        self::assertSame('App\Handlers\CleanupLogsHandler', $task->handler());
    }

    /**
     * Tests that setType() sets and returns the type.
     */
    public function testSetType()
    {
        $task = new TaskModel();
        $task->setType('daily');

        self::assertSame('daily', $task->type());
    }

    /**
     * Tests that setIntervalSeconds() sets and returns the interval, including null.
     */
    public function testSetIntervalSeconds()
    {
        $task = new TaskModel();
        $task->setIntervalSeconds(3600);

        self::assertSame(3600, $task->intervalSeconds());

        $task->setIntervalSeconds(null);
        self::assertNull($task->intervalSeconds());
    }

    /**
     * Tests that setDailyTime() sets and returns the raw time string, including null.
     */
    public function testSetDailyTime()
    {
        $task = new TaskModel();
        $task->setDailyTime('08:30:00');

        self::assertSame('08:30:00', $task->dailyTime());

        $task->setDailyTime(null);
        self::assertNull($task->dailyTime());
    }

    /**
     * Tests that setTimezone() sets and returns the timezone, including null.
     */
    public function testSetTimezone()
    {
        $task = new TaskModel();
        $task->setTimezone('Europe/Berlin');

        self::assertSame('Europe/Berlin', $task->timezone());

        $task->setTimezone(null);
        self::assertNull($task->timezone());
    }

    /**
     * Tests that setPayload() sets and returns the payload, including null.
     */
    public function testSetPayload()
    {
        $task = new TaskModel();
        $task->setPayload(['foo' => 'bar']);

        self::assertSame(['foo' => 'bar'], $task->payload());

        $task->setPayload(null);
        self::assertNull($task->payload());
    }

    /**
     * Tests that setEnabled() sets and returns the flag.
     */
    public function testSetEnabled()
    {
        $task = new TaskModel();
        self::assertTrue($task->enabled());

        $task->setEnabled(false);
        self::assertFalse($task->enabled());

        $task->setEnabled(true);
        self::assertTrue($task->enabled());
    }

    /**
     * Tests that setFailures() sets and returns the counter, including 0.
     */
    public function testSetFailures()
    {
        $task = new TaskModel();
        $task->setFailures(3);

        self::assertSame(3, $task->failures());

        $task->setFailures(0);
        self::assertSame(0, $task->failures());
    }

    /**
     * Tests that setLastStatus() sets and returns the status, including null.
     */
    public function testSetLastStatus()
    {
        $task = new TaskModel();
        $task->setLastStatus('ok');

        self::assertSame('ok', $task->lastStatus());

        $task->setLastStatus(null);
        self::assertNull($task->lastStatus());
    }

    /**
     * Tests that setLastError() sets and returns the error message, including null.
     */
    public function testSetLastError()
    {
        $task = new TaskModel();
        $task->setLastError('Connection refused');

        self::assertSame('Connection refused', $task->lastError());

        $task->setLastError(null);
        self::assertNull($task->lastError());
    }

    /**
     * Tests that setLeaseOwner() sets and returns the lease owner, including null.
     */
    public function testSetLeaseOwner()
    {
        $task = new TaskModel();
        $task->setLeaseOwner('worker-42');

        self::assertSame('worker-42', $task->leaseOwner());

        $task->setLeaseOwner(null);
        self::assertNull($task->leaseOwner());
    }

    /**
     * Tests that setNextRun() sets and returns the next run, including null.
     */
    public function testSetNextRun()
    {
        $task = new TaskModel();
        $nextRun = (new \DateTimeImmutable('2025-03-10 08:30:00'))->setTimezone(new \DateTimeZone('UTC'));

        $task->setNextRun($nextRun);
        self::assertSame($nextRun, $task->nextRun());

        $task->setNextRun(null);
        self::assertNull($task->nextRun());
    }

    /**
     * Tests that the remaining datetime setters store and clear values.
     */
    public function testSetDatetimeFields()
    {
        $task = new TaskModel();
        $started      = (new \DateTimeImmutable('2025-03-10 07:00:00'))->setTimezone(new \DateTimeZone('UTC'));
        $finished     = (new \DateTimeImmutable('2025-03-10 07:00:05'))->setTimezone(new \DateTimeZone('UTC'));
        $leaseExpires = (new \DateTimeImmutable('2025-03-10 07:10:00'))->setTimezone(new \DateTimeZone('UTC'));
        $created      = (new \DateTimeImmutable('2025-01-01 12:00:00'))->setTimezone(new \DateTimeZone('UTC'));

        $task->setLastStarted($started)
            ->setLastFinished($finished)
            ->setLeaseExpires($leaseExpires)
            ->setCreated($created);

        self::assertSame($started, $task->lastStarted());
        self::assertSame($finished, $task->lastFinished());
        self::assertSame($leaseExpires, $task->leaseExpires());
        self::assertSame($created, $task->created());

        $task->setLastStarted(null)
            ->setLastFinished(null)
            ->setLeaseExpires(null)
            ->setCreated(null);

        self::assertNull($task->lastStarted());
        self::assertNull($task->lastFinished());
        self::assertNull($task->leaseExpires());
        self::assertNull($task->created());
    }

    /**
     * Tests that all setters are chainable (return $this).
     */
    public function testSettersReturnSelf()
    {
        $task = new TaskModel();

        self::assertSame($task, $task->setId(1));
        self::assertSame($task, $task->setName('Task'));
        self::assertSame($task, $task->setHandler('App\Handler'));
        self::assertSame($task, $task->setType('interval'));
        self::assertSame($task, $task->setIntervalSeconds(60));
        self::assertSame($task, $task->setDailyTime('00:00:00'));
        self::assertSame($task, $task->setTimezone('UTC'));
        self::assertSame($task, $task->setPayload([]));
        self::assertSame($task, $task->setEnabled(false));
        self::assertSame($task, $task->setNextRun(new \DateTimeImmutable('2025-01-01 00:00:00')));
        self::assertSame($task, $task->setLastStarted(new \DateTimeImmutable('2025-01-01 00:00:00')));
        self::assertSame($task, $task->setLastFinished(new \DateTimeImmutable('2025-01-01 00:00:00')));
        self::assertSame($task, $task->setLastStatus('ok'));
        self::assertSame($task, $task->setLastError('err'));
        self::assertSame($task, $task->setFailures(1));
        self::assertSame($task, $task->setLeaseOwner('worker-1'));
        self::assertSame($task, $task->setLeaseExpires(new \DateTimeImmutable('2025-01-01 00:00:00')));
        self::assertSame($task, $task->setCreated(new \DateTimeImmutable('2025-01-01 00:00:00')));
    }

    /**
     * Tests that chaining setters builds a complete task.
     */
    public function testChainedSetters()
    {
        $task = (new TaskModel())
            ->setId(3)
            ->setName('Backup')
            ->setHandler('App\Handlers\BackupHandler')
            ->setType(Scheduler::TYPE_INTERVAL)
            ->setIntervalSeconds(86400)
            ->setTimezone('UTC')
            ->setPayload(['path' => '/var/backups'])
            ->setEnabled(true);

        self::assertSame(3, $task->id());
        self::assertSame('Backup', $task->name());
        self::assertSame('App\Handlers\BackupHandler', $task->handler());
        self::assertSame(Scheduler::TYPE_INTERVAL, $task->type());
        self::assertSame(86400, $task->intervalSeconds());
        self::assertSame('UTC', $task->timezone());
        self::assertSame(['path' => '/var/backups'], $task->payload());
        self::assertTrue($task->enabled());
    }

    /**
     * Tests that overwriting a previously set value works.
     */
    public function testOverwriteValues()
    {
        $task = new TaskModel();
        $task->setId(1)
            ->setName('Old Name')
            ->setHandler('Old\Handler')
            ->setEnabled(true);

        $task->setId(2)
            ->setName('New Name')
            ->setHandler('New\Handler')
            ->setEnabled(false);

        self::assertSame(2, $task->id());
        self::assertSame('New Name', $task->name());
        self::assertSame('New\Handler', $task->handler());
        self::assertFalse($task->enabled());
    }

    // -----------------------------------------------------------------
    // Helpers
    // -----------------------------------------------------------------

    /**
     * Returns a fully populated raw database row (snake_case columns, UTC datetimes).
     *
     * @return array<string, mixed>
     */
    private function buildFullRow(): array
    {
        return [
            'id'               => 7,
            'name'             => 'Cleanup Logs',
            'handler'          => 'App\Handlers\CleanupLogsHandler',
            'type'             => Scheduler::TYPE_INTERVAL,
            'interval_seconds' => 3600,
            'daily_time'       => '08:30:00',
            'timezone'         => 'UTC',
            'payload'          => '{"threshold":10,"dryRun":false}',
            'enabled'          => 1,
            'failures'         => 2,
            'next_run'         => '2025-03-10 08:30:00',
            'last_started'     => '2025-03-10 07:00:00',
            'last_finished'    => '2025-03-10 07:00:05',
            'last_status'      => 'error',
            'last_error'       => 'Connection refused',
            'lease_owner'      => 'worker-42',
            'lease_expires'    => '2025-03-10 07:10:00',
            'created'          => '2025-01-01 12:00:00',
        ];
    }

    /**
     * Asserts that the given datetime is a \DateTimeImmutable holding the expected UTC value.
     *
     * @param string $expected Expected UTC 'Y-m-d H:i:s'.
     * @param \DateTimeImmutable|null $actual
     * @return void
     */
    private function assertUtcDateTime(string $expected, ?\DateTimeImmutable $actual): void
    {
        self::assertInstanceOf(\DateTimeImmutable::class, $actual);
        self::assertSame('UTC', $actual->getTimezone()->getName());
        self::assertSame($expected, $actual->format('Y-m-d H:i:s'));
    }
}
