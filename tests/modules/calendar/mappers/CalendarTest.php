<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Calendar\Mappers;

use PHPUnit\Ilch\DatabaseTestCase;
use PHPUnit\Ilch\PhpunitDataset;
use Modules\Calendar\Config\Config as ModuleConfig;
use Modules\User\Config\Config as UserConfig;
use Modules\Admin\Config\Config as AdminConfig;
use Modules\Calendar\Mappers\Calendar as CalendarMapper;
use Modules\Calendar\Models\Calendar as EntriesModel;

/**
 * Tests the Calendar mapper class.
 *
 * @package ilch_phpunit
 */
class CalendarTest extends DatabaseTestCase
{
    protected PhpunitDataset $phpunitDataset;
    private Calendar $mapper;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database.yml');

        $this->mapper = new CalendarMapper();
    }

    /**
     * Tests if getEntries() returns all articles from the database.
     */
    public function testgetCalendarAllRows()
    {
        $entries = $this->mapper->getEntries();

        self::assertCount(4, $entries);
    }

    public function testgetCalendar()
    {
        $entries = $this->mapper->getEntries();

        self::assertCount(4, $entries);

        $i = 0;
        self::assertEquals(1, $entries[$i]->getId());
        self::assertSame('39efcc37-43dd-43b7-923a-06c4a0d98fc1', $entries[$i]->getUid());
        self::assertSame('Training 1', $entries[$i]->getTitle());
        self::assertSame('', $entries[$i]->getPlace());
        self::assertSame('2021-05-09 08:10:38', $entries[$i]->getStart());
        self::assertSame('2021-05-09 08:10:38', $entries[$i]->getEnd());
        self::assertSame('', $entries[$i]->getText());
        self::assertSame('#32333B', $entries[$i]->getColor());
        self::assertSame(0, $entries[$i]->getPeriodDay());
        self::assertSame('1,3', $entries[$i]->getReadAccess());

        $i++;
        self::assertEquals(2, $entries[$i]->getId());
        self::assertSame('39efcc37-43dd-43b7-923a-06c4a0d98fc2', $entries[$i]->getUid());
        self::assertSame('Training 2', $entries[$i]->getTitle());
        self::assertSame('', $entries[$i]->getPlace());
        self::assertSame('2021-05-09 09:10:38', $entries[$i]->getStart());
        self::assertSame('2021-05-09 09:10:38', $entries[$i]->getEnd());
        self::assertSame('', $entries[$i]->getText());
        self::assertSame('#32333B', $entries[$i]->getColor());
        self::assertSame(0, $entries[$i]->getPeriodDay());
        self::assertSame('1', $entries[$i]->getReadAccess());

        $i++;
        self::assertEquals(3, $entries[$i]->getId());
        self::assertSame('39efcc37-43dd-43b7-923a-06c4a0d98fc3', $entries[$i]->getUid());
        self::assertSame('Training 3', $entries[$i]->getTitle());
        self::assertSame('', $entries[$i]->getPlace());
        self::assertSame('2021-05-10 09:10:38', $entries[$i]->getStart());
        self::assertSame('2021-05-10 09:10:38', $entries[$i]->getEnd());
        self::assertSame('', $entries[$i]->getText());
        self::assertSame('#32333B', $entries[$i]->getColor());
        self::assertSame(0, $entries[$i]->getPeriodDay());
        self::assertSame('all', $entries[$i]->getReadAccess());

        $i++;
        self::assertEquals(4, $entries[$i]->getId());
        self::assertSame('39efcc37-43dd-43b7-923a-06c4a0d98fc4', $entries[$i]->getUid());
        self::assertSame('Training 4', $entries[$i]->getTitle());
        self::assertSame('', $entries[$i]->getPlace());
        self::assertSame('2021-05-10 18:00:00', $entries[$i]->getStart());
        self::assertSame('2021-05-10 21:00:00', $entries[$i]->getEnd());
        self::assertSame('', $entries[$i]->getText());
        self::assertSame('#32333B', $entries[$i]->getColor());
        self::assertSame('weekly', $entries[$i]->getPeriodType());
        self::assertSame(1, $entries[$i]->getPeriodDay());
        self::assertSame('2021-12-31 21:00:00', $entries[$i]->getRepeatUntil());
        self::assertSame('all', $entries[$i]->getReadAccess());
    }

    public function testgetCalendarByAccess()
    {
        $entries = $this->mapper->getEntries(['ra.group_id' => [1,2,3]]);

        self::assertCount(4, $entries);
    }

    public function testgetCalendarByAccessGuest()
    {
        $entries = $this->mapper->getEntries(['ra.group_id' => [3]]);

        self::assertCount(3, $entries);
    }

    public function testgetCalendarByDate()
    {
        $entries = $this->mapper->getEntriesForJson('2021-05-03', '2021-05-09', '1,2,3');

        self::assertCount(2, $entries);
    }

    public function testsaveNewCalendar()
    {
        $model = new EntriesModel();
        $model->setUid('39efcc37-43dd-43b7-923a-06c4a0d98fc5');
        $model->setTitle('Training 5');
        $model->setPlace('');
        $model->setStart('2021-05-09 09:10:38');
        $model->setEnd('2021-05-09 09:10:38');
        $model->setColor('#32333B');
        $model->setPeriodDay(0);
        $model->setReadAccess('all');
        $id = $this->mapper->save($model);

        $entry = $this->mapper->getCalendarById($id);

        self::assertNotNull($entry);
        self::assertEquals($id, $entry->getId());
        self::assertSame('39efcc37-43dd-43b7-923a-06c4a0d98fc5', $entry->getUid());
        self::assertSame('Training 5', $entry->getTitle());
        self::assertSame('', $entry->getPlace());
        self::assertSame('2021-05-09 09:10:38', $entry->getStart());
        self::assertSame('2021-05-09 09:10:38', $entry->getEnd());
        self::assertSame('', $entry->getText());
        self::assertSame('#32333B', $entry->getColor());
        self::assertSame(0, $entry->getPeriodDay());
        self::assertSame('all', $entry->getReadAccess());
    }

    public function testsaveUpdateExistingCalendar()
    {
        $model = new EntriesModel();
        $model->setId(1);
        $model->setUid('39efcc37-43dd-43b7-923a-06c4a0d98fc5');
        $model->setTitle('Training 5');
        $model->setPlace('');
        $model->setStart('2021-05-10 09:10:38');
        $model->setEnd('2021-05-10 09:10:38');
        $model->setColor('#32333B');
        $model->setPeriodDay(0);
        $model->setReadAccess('all');
        $id = $this->mapper->save($model);

        $entry = $this->mapper->getCalendarById($id);

        self::assertNotNull($entry);
        self::assertEquals(1, $id);
        self::assertEquals($id, $entry->getId());
        self::assertSame('39efcc37-43dd-43b7-923a-06c4a0d98fc5', $entry->getUid());
        self::assertSame('Training 5', $entry->getTitle());
        self::assertSame('', $entry->getPlace());
        self::assertSame('2021-05-10 09:10:38', $entry->getStart());
        self::assertSame('2021-05-10 09:10:38', $entry->getEnd());
        self::assertSame('', $entry->getText());
        self::assertSame('#32333B', $entry->getColor());
        self::assertSame(0, $entry->getPeriodDay());
        self::assertSame('all', $entry->getReadAccess());
    }

    public function testdeleteCalendar()
    {
        self::assertSame(true, $this->mapper->delete(1));

        $entry = $this->mapper->getCalendarById(1);
        self::assertNull($entry);
    }

    /**
     * Returns database schema sql statements to initialize database
     *
     * @return string
     */
    protected static function getSchemaSQLQueries(): string
    {
        $config = new ModuleConfig();
        $configUser = new UserConfig();
        $configAdmin = new AdminConfig();

        return $configAdmin->getInstallSql() . $configUser->getInstallSql() . $config->getInstallSql();
    }
}
