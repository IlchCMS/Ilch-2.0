<?php
/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Calendar\Mappers;

use Ilch\Pagination;
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
    protected $phpunitDataset;
    private $mapper;

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
        $entrys = $this->mapper->getEntries();

        self::assertCount(3, $entrys);
    }

    public function testgetCalendar()
    {
        $entrys = $this->mapper->getEntries();

        self::assertCount(3, $entrys);

        $i = 0;
        self::assertEquals(1, $entrys[$i]->getId());
        self::assertSame('Training 1', $entrys[$i]->getTitle());
        self::assertSame('', $entrys[$i]->getPlace());
        self::assertSame('2021-05-09 08:10:38', $entrys[$i]->getStart());
        self::assertSame('2021-05-09 08:10:38', $entrys[$i]->getEnd());
        self::assertSame('', $entrys[$i]->getText());
        self::assertSame('#32333B', $entrys[$i]->getColor());
        self::assertSame(0, $entrys[$i]->getPeriodDay());
        self::assertSame('1,3', $entrys[$i]->getReadAccess());
        
        $i++;
        self::assertEquals(2, $entrys[$i]->getId());
        self::assertSame('Training 2', $entrys[$i]->getTitle());
        self::assertSame('', $entrys[$i]->getPlace());
        self::assertSame('2021-05-09 09:10:38', $entrys[$i]->getStart());
        self::assertSame('2021-05-09 09:10:38', $entrys[$i]->getEnd());
        self::assertSame('', $entrys[$i]->getText());
        self::assertSame('#32333B', $entrys[$i]->getColor());
        self::assertSame(0, $entrys[$i]->getPeriodDay());
        self::assertSame('1', $entrys[$i]->getReadAccess());
        
        $i++;
        self::assertEquals(3, $entrys[$i]->getId());
        self::assertSame('Training 3', $entrys[$i]->getTitle());
        self::assertSame('', $entrys[$i]->getPlace());
        self::assertSame('2021-05-10 09:10:38', $entrys[$i]->getStart());
        self::assertSame('2021-05-10 09:10:38', $entrys[$i]->getEnd());
        self::assertSame('', $entrys[$i]->getText());
        self::assertSame('#32333B', $entrys[$i]->getColor());
        self::assertSame(0, $entrys[$i]->getPeriodDay());
        self::assertSame('all', $entrys[$i]->getReadAccess());

    }

    public function testgetCalendarByAccess()
    {
        $entrys = $this->mapper->getEntries(['ra.group_id' => [1,2,3]]);

        self::assertCount(3, $entrys);
    }

    public function testgetCalendarByAccessGuest()
    {
        $entrys = $this->mapper->getEntries(['ra.group_id' => [3]]);

        self::assertCount(2, $entrys);
    }

    public function testgetCalendarByDate()
    {
        $entrys = $this->mapper->getEntriesForJson('2021-05-03', '2021-05-09', '1,2,3');

        self::assertCount(2, $entrys);
    }

    public function testsaveNewCalendar()
    {
        $model = new EntriesModel();
        $model->setTitle('Training 4');
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
        self::assertSame('Training 4', $entry->getTitle());
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
        $model->setTitle('Training 4');
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
        self::assertSame('Training 4', $entry->getTitle());
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
    protected static function getSchemaSQLQueries()
    {
        $config = new ModuleConfig();
        $configUser = new UserConfig();
        $configAdmin = new AdminConfig();

        return $configAdmin->getInstallSql() . $configUser->getInstallSql() . $config->getInstallSql();
    }
}
