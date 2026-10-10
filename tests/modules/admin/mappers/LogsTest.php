<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Admin\Mappers;

use PHPUnit\Ilch\DatabaseTestCase;
use PHPUnit\Ilch\PhpunitDataset;
use Modules\Admin\Config\Config as ModuleConfig;
use Modules\Admin\Models\Logs as LogsModel;

class LogsTest extends DatabaseTestCase
{
    /**
     * @var Logs
     */
    protected Logs $out;
    protected PhpunitDataset $phpunitDataset;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database.yml');
        $this->out = new Logs();
    }

    /**
     * Tests that getLogs() returns all logs of the given day.
     */
    public function testGetLogs()
    {
        $logs = $this->out->getLogs('2024-01-01');

        self::assertNotNull($logs);
        self::assertCount(2, $logs);
        self::assertInstanceOf(LogsModel::class, $logs[0]);
    }

    /**
     * Tests that getLogs() returns the correct fields in descending date order.
     */
    public function testGetLogsFieldsAndOrder()
    {
        $logs = $this->out->getLogs('2024-01-01');

        self::assertEquals(2, $logs[0]->getUserId());
        self::assertEquals('2024-01-01 11:30:00', $logs[0]->getDate());
        self::assertEquals('Password changed', $logs[0]->getInfo());

        self::assertEquals(1, $logs[1]->getUserId());
        self::assertEquals('2024-01-01 10:00:00', $logs[1]->getDate());
        self::assertEquals('Login', $logs[1]->getInfo());
    }

    /**
     * Tests that getLogs() only returns logs matching the date prefix.
     */
    public function testGetLogsFiltersByDate()
    {
        $logs = $this->out->getLogs('2024-01-02');

        self::assertCount(1, $logs);
        self::assertEquals(1, $logs[0]->getUserId());
        self::assertEquals('Login', $logs[0]->getInfo());
    }

    /**
     * Tests that getLogs() returns null when no logs match the date.
     */
    public function testGetLogsNoMatch()
    {
        self::assertNull($this->out->getLogs('1999-01-01'));
    }

    /**
     * Tests that getLogsDate() returns the distinct dates in descending order.
     */
    public function testGetLogsDate()
    {
        $dates = $this->out->getLogsDate();

        self::assertNotNull($dates);
        self::assertCount(2, $dates);
        self::assertEquals('2024-01-02', $dates[0]->getDate());
        self::assertEquals('2024-01-01', $dates[1]->getDate());
    }

    /**
     * Tests that getLogsDate() returns null when no logs exist.
     */
    public function testGetLogsDateEmpty()
    {
        $this->out->clearLog();

        self::assertNull($this->out->getLogsDate());
    }

    /**
     * Tests that getLogsBy() returns all logs without a where clause.
     */
    public function testGetLogsByWithoutWhere()
    {
        self::assertCount(3, $this->out->getLogsBy());
    }

    /**
     * Tests that getLogsBy() filters by the given where clause.
     */
    public function testGetLogsByWhere()
    {
        $logs = $this->out->getLogsBy(['user_id' => 1]);

        self::assertCount(2, $logs);
    }

    /**
     * Tests that getLogsBy() returns an empty array (not null) when nothing matches.
     */
    public function testGetLogsByEmpty()
    {
        $logs = $this->out->getLogsBy(['user_id' => 999]);

        self::assertIsArray($logs);
        self::assertCount(0, $logs);
    }

    /**
     * Tests that saveLog() inserts a new log entry.
     */
    public function testSaveLogInserts()
    {
        $this->out->saveLog(1, 'Test action');

        $logs = $this->out->getLogsBy(['user_id' => 1, 'info' => 'Test action']);

        self::assertCount(1, $logs);
        self::assertEquals('Test action', $logs[0]->getInfo());
    }

    /**
     * Tests that saveLog() does not insert a duplicate entry within the last minute.
     */
    public function testSaveLogNoDuplicate()
    {
        $this->out->saveLog(3, 'Duplicate test');
        $this->out->saveLog(3, 'Duplicate test');

        $logs = $this->out->getLogsBy(['user_id' => 3, 'info' => 'Duplicate test']);

        self::assertCount(1, $logs);
    }

    /**
     * Tests that saveLog() inserts entries with different info texts.
     */
    public function testSaveLogDifferentInfo()
    {
        $this->out->saveLog(3, 'First action');
        $this->out->saveLog(3, 'Second action');

        $logs = $this->out->getLogsBy(['user_id' => 3]);

        self::assertCount(2, $logs);
    }

    /**
     * Tests that clearLog() removes all log entries.
     */
    public function testClearLog()
    {
        $this->out->clearLog();

        self::assertNull($this->out->getLogs('2024-01-01'));
        self::assertNull($this->out->getLogsDate());
        self::assertCount(0, $this->out->getLogsBy());
    }

    /**
     * Returns database schema SQL statements to initialize database.
     *
     * @return string
     */
    protected static function getSchemaSQLQueries(): string
    {
        $config = new ModuleConfig();

        return $config->getInstallSql();
    }
}
