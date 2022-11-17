<?php
/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\War\Mappers;

use Ilch\Pagination;
use PHPUnit\Ilch\DatabaseTestCase;
use PHPUnit\Ilch\PhpunitDataset;
use Modules\War\Config\Config as ModuleConfig;
use Modules\User\Config\Config as UserConfig;
use Modules\Admin\Config\Config as AdminConfig;
use Modules\War\Mappers\Maps as MapsMapper;
use Modules\War\Models\Maps as EntriesModel;

/**
 * @package ilch_phpunit
 */
class MapsTest extends DatabaseTestCase
{
    protected $phpunitDataset;
    private $mapper;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database.yml');

        $this->mapper = new MapsMapper();
    }

    /**
     * All test routines
     */
    public function testgetWarMapsAllRows()
    {
        $entrys = $this->mapper->getEntries();

        self::assertCount(2, $entrys);
    }

    public function testgetWarMaps()
    {
        $entrys = $this->mapper->getEntries();

        self::assertCount(2, $entrys);

        $i = 0;
        self::assertEquals(1, $entrys[$i]->getId());
        self::assertSame('Map1', $entrys[$i]->getName());
        
        $i++;
        self::assertEquals(2, $entrys[$i]->getId());
        self::assertSame('Map2', $entrys[$i]->getName());
    }

    public function testsaveNewWarMaps()
    {
        $model = new EntriesModel();
        $model->setName('Map3');
        $id = $this->mapper->save($model);

        $entry = $this->mapper->getEntryById($id);

        self::assertNotNull($entry);
        self::assertEquals($id, $entry->getId());
        self::assertSame('Map3', $entry->getName());
    }

    public function testsaveUpdateExistingWarMaps()
    {
        $model = new EntriesModel();
        $model->setId(1);
        $model->setName('Map3');
        $id = $this->mapper->save($model);

        $entry = $this->mapper->getEntryById($id);

        self::assertNotNull($entry);
        self::assertEquals(1, $id);
        self::assertEquals(1, $entry->getId());
        self::assertSame('Map3', $entry->getName());
    }

    public function testdeleteWarMaps()
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
