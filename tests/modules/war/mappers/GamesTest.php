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
use Modules\War\Mappers\Games as GamesMapper;
use Modules\War\Models\Games as EntriesModel;

/**
 * @package ilch_phpunit
 */
class GamesTest extends DatabaseTestCase
{
    protected $phpunitDataset;
    private $mapper;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database.yml');

        $this->mapper = new GamesMapper();
    }

    /**
     * All test routines
     */
    public function testgetWarGamesAllRows()
    {
        $entrys = $this->mapper->getGamesByWhere();

        self::assertCount(5, $entrys);
    }

    public function testgetWarGames()
    {
        $entrys = $this->mapper->getGamesByWhere();

        self::assertCount(5, $entrys);

        $i = 0;
        self::assertEquals(4, $entrys[$i]->getId());
        self::assertEquals(4, $entrys[$i]->getWarId());
        self::assertEquals(2, $entrys[$i]->getMap());
        self::assertEquals(2, $entrys[$i]->getGroupPoints());
        self::assertEquals(2, $entrys[$i]->getEnemyPoints());
        
        $i++;
        self::assertEquals(5, $entrys[$i]->getId());
        self::assertEquals(4, $entrys[$i]->getWarId());
        self::assertEquals(1, $entrys[$i]->getMap());
        self::assertEquals(1, $entrys[$i]->getGroupPoints());
        self::assertEquals(2, $entrys[$i]->getEnemyPoints());
        
        $i++;
        self::assertEquals(3, $entrys[$i]->getId());
        self::assertEquals(3, $entrys[$i]->getWarId());
        self::assertEquals(1, $entrys[$i]->getMap());
        self::assertEquals(2, $entrys[$i]->getGroupPoints());
        self::assertEquals(2, $entrys[$i]->getEnemyPoints());
        
        $i++;
        self::assertEquals(2, $entrys[$i]->getId());
        self::assertEquals(2, $entrys[$i]->getWarId());
        self::assertEquals(2, $entrys[$i]->getMap());
        self::assertEquals(2, $entrys[$i]->getGroupPoints());
        self::assertEquals(2, $entrys[$i]->getEnemyPoints());
        
        $i++;
        self::assertEquals(1, $entrys[$i]->getId());
        self::assertEquals(1, $entrys[$i]->getWarId());
        self::assertEquals(1, $entrys[$i]->getMap());
        self::assertEquals(2, $entrys[$i]->getGroupPoints());
        self::assertEquals(2, $entrys[$i]->getEnemyPoints());
    }

    public function testsaveNewWarGames()
    {
        $model = new EntriesModel();
        $model->setWarId(2);
        $model->setMap(1);
        $model->setGroupPoints(3);
        $model->setEnemyPoints(3);
        $id = $this->mapper->save($model);

        $entry = $this->mapper->getEntriesBy(['id' => $id]);
        $entry = reset($entry);

        self::assertNotNull($entry);
        self::assertEquals($id, $entry->getId());
        self::assertEquals(2, $entry->getWarId());
        self::assertEquals(1, $entry->getMap());
        self::assertEquals(3, $entry->getGroupPoints());
        self::assertEquals(3, $entry->getEnemyPoints());
    }

    public function testsaveUpdateExistingWarGames()
    {
        $model = new EntriesModel();
        $model->setId(1);
        $model->setWarId(2);
        $model->setMap(1);
        $model->setGroupPoints(3);
        $model->setEnemyPoints(3);
        $id = $this->mapper->save($model);

        $entry = $this->mapper->getEntriesBy(['id' => $id]);
        $entry = reset($entry);

        self::assertNotNull($entry);
        self::assertEquals(1, $id);
        self::assertEquals(1, $entry->getId());
        self::assertEquals(2, $entry->getWarId());
        self::assertEquals(1, $entry->getMap());
        self::assertEquals(3, $entry->getGroupPoints());
        self::assertEquals(3, $entry->getEnemyPoints());
    }

    public function testdeleteWarGames()
    {
        self::assertSame(true, $this->mapper->delete(1));

        $entry = $this->mapper->getEntriesBy(['id' => 1]);
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
