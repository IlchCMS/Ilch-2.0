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
use Modules\Calendar\Mappers\Events as EventsMapper;
use Modules\Calendar\Models\Events as EntriesModel;

/**
 * Tests the Events mapper class.
 *
 * @package ilch_phpunit
 */
class EventsTest extends DatabaseTestCase
{
    protected $phpunitDataset;
    private $mapper;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database.yml');

        $this->mapper = new EventsMapper();
    }

    /**
     * Tests if getEntries() returns all articles from the database.
     */
    public function testgetEventsAllRows()
    {
        $entrys = $this->mapper->getEntries();

        self::assertCount(6, $entrys);
    }

    public function testgetEvents()
    {
        $entrys = $this->mapper->getEntries();

        self::assertCount(6, $entrys);

        $i = 0;
        self::assertEquals(1, $entrys[$i]->getId());
        self::assertSame('calendar/events/index/', $entrys[$i]->getUrl());
        
        $i++;
        self::assertEquals(2, $entrys[$i]->getId());
        self::assertSame('war/wars/index/', $entrys[$i]->getUrl());
        
        $i++;
        self::assertEquals(3, $entrys[$i]->getId());
        self::assertSame('training/trainings/index/', $entrys[$i]->getUrl());
        
        $i++;
        self::assertEquals(4, $entrys[$i]->getId());
        self::assertSame('birthday/birthdays/index/', $entrys[$i]->getUrl());
        
        $i++;
        self::assertEquals(5, $entrys[$i]->getId());
        self::assertSame('events/events/index/', $entrys[$i]->getUrl());
        
        $i++;
        self::assertEquals(6, $entrys[$i]->getId());
        self::assertSame('away/aways/index/', $entrys[$i]->getUrl());
    }

    public function testsaveNewEvents()
    {
        $model = new EntriesModel();
        $model->setUrl('testurl');
        $id = $this->mapper->save($model);

        $entry = $this->mapper->getEntryById($id);

        self::assertNotNull($entry);
        self::assertEquals($id, $entry->getId());
        self::assertSame('testurl', $entry->getUrl());
    }

    public function testsaveUpdateExistingEvents()
    {
        $model = new EntriesModel();
        $model->setId(1);
        $model->setUrl('testurl');
        $id = $this->mapper->save($model);

        $entry = $this->mapper->getEntryById($id);

        self::assertNotNull($entry);
        self::assertEquals(1, $id);
        self::assertEquals(1, $entry->getId());
        self::assertSame('testurl', $entry->getUrl());
    }

    public function testdeleteEvents()
    {
        self::assertSame(true, $this->mapper->delete(1));

        $entry = $this->mapper->getEntryById(1);
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
