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
use Modules\Comment\Config\Config as CommentConfig;
use Modules\War\Mappers\War as WarMapper;
use Modules\War\Models\War as EntriesModel;

/**
 * @package ilch_phpunit
 */
class WarTest extends DatabaseTestCase
{
    protected $phpunitDataset;
    private $mapper;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database.yml');

        $this->mapper = new WarMapper();
    }

    /**
     * All test routines
     */
    public function testgetWarAllRows()
    {
        $entrys = $this->mapper->getWars();

        self::assertCount(4, $entrys);
    }

    public function testgetWar()
    {
        $entrys = $this->mapper->getWars();

        self::assertCount(4, $entrys);

        $i = 0;
        self::assertEquals(2, $entrys[$i]->getId());
        self::assertEquals(1, $entrys[$i]->getWarEnemy());
        self::assertEquals(2, $entrys[$i]->getWarGroup());
        self::assertSame('2021-05-09 08:10:38', $entrys[$i]->getWarTime());
        self::assertSame('', $entrys[$i]->getWarMaps());
        self::assertSame('localhost', $entrys[$i]->getWarServer());
        self::assertSame('', $entrys[$i]->getWarPassword());
        self::assertSame('1on1', $entrys[$i]->getWarXonx());
        self::assertSame('CSS', $entrys[$i]->getWarGame());
        self::assertSame('Train', $entrys[$i]->getWarMatchtype());
        self::assertSame('', $entrys[$i]->getWarReport());
        self::assertEquals(0, $entrys[$i]->getWarStatus());
        self::assertEquals(1, $entrys[$i]->getShow());
        self::assertSame('1,3', $entrys[$i]->getReadAccess());
        self::assertEquals(0, $entrys[$i]->getLastAcceptTime());
        self::assertSame('ILCH2-TG2', $entrys[$i]->getWarGroupTag());
        self::assertSame('TG1', $entrys[$i]->getWarEnemyTag());

        $i++;
        self::assertEquals(3, $entrys[$i]->getId());
        self::assertEquals(2, $entrys[$i]->getWarEnemy());
        self::assertEquals(1, $entrys[$i]->getWarGroup());
        self::assertSame('2021-05-09 08:10:38', $entrys[$i]->getWarTime());
        self::assertSame('', $entrys[$i]->getWarMaps());
        self::assertSame('localhost', $entrys[$i]->getWarServer());
        self::assertSame('', $entrys[$i]->getWarPassword());
        self::assertSame('1on1', $entrys[$i]->getWarXonx());
        self::assertSame('CSS', $entrys[$i]->getWarGame());
        self::assertSame('Train', $entrys[$i]->getWarMatchtype());
        self::assertSame('', $entrys[$i]->getWarReport());
        self::assertEquals(0, $entrys[$i]->getWarStatus());
        self::assertEquals(1, $entrys[$i]->getShow());
        self::assertSame('1,2', $entrys[$i]->getReadAccess());
        self::assertEquals(0, $entrys[$i]->getLastAcceptTime());
        self::assertSame('ILCH2-TG1', $entrys[$i]->getWarGroupTag());
        self::assertSame('TG2', $entrys[$i]->getWarEnemyTag());
        
        $i++;
        self::assertEquals(4, $entrys[$i]->getId());
        self::assertEquals(2, $entrys[$i]->getWarEnemy());
        self::assertEquals(2, $entrys[$i]->getWarGroup());
        self::assertSame('2021-05-09 08:10:38', $entrys[$i]->getWarTime());
        self::assertSame('', $entrys[$i]->getWarMaps());
        self::assertSame('localhost', $entrys[$i]->getWarServer());
        self::assertSame('', $entrys[$i]->getWarPassword());
        self::assertSame('1on1', $entrys[$i]->getWarXonx());
        self::assertSame('CSS', $entrys[$i]->getWarGame());
        self::assertSame('Train', $entrys[$i]->getWarMatchtype());
        self::assertSame('', $entrys[$i]->getWarReport());
        self::assertEquals(0, $entrys[$i]->getWarStatus());
        self::assertEquals(1, $entrys[$i]->getShow());
        self::assertSame('1', $entrys[$i]->getReadAccess());
        self::assertEquals(0, $entrys[$i]->getLastAcceptTime());
        self::assertSame('ILCH2-TG2', $entrys[$i]->getWarGroupTag());
        self::assertSame('TG2', $entrys[$i]->getWarEnemyTag());
        
        $i++;
        self::assertEquals(1, $entrys[$i]->getId());
        self::assertEquals(1, $entrys[$i]->getWarEnemy());
        self::assertEquals(1, $entrys[$i]->getWarGroup());
        self::assertSame('2021-05-10 08:10:38', $entrys[$i]->getWarTime());
        self::assertSame('', $entrys[$i]->getWarMaps());
        self::assertSame('localhost', $entrys[$i]->getWarServer());
        self::assertSame('', $entrys[$i]->getWarPassword());
        self::assertSame('1on1', $entrys[$i]->getWarXonx());
        self::assertSame('CSS', $entrys[$i]->getWarGame());
        self::assertSame('Train', $entrys[$i]->getWarMatchtype());
        self::assertSame('', $entrys[$i]->getWarReport());
        self::assertEquals(0, $entrys[$i]->getWarStatus());
        self::assertEquals(1, $entrys[$i]->getShow());
        self::assertSame('1,2,3', $entrys[$i]->getReadAccess());
        self::assertEquals(0, $entrys[$i]->getLastAcceptTime());
        self::assertSame('ILCH2-TG1', $entrys[$i]->getWarGroupTag());
        self::assertSame('TG1', $entrys[$i]->getWarEnemyTag());
    }

    public function testgetWarByAccess()
    {
        $entrys = $this->mapper->getWars(['ra.group_id' => [1,2,3]]);

        self::assertCount(4, $entrys);
    }

    public function testgetWarByAccessGuest()
    {
        $entrys = $this->mapper->getWars(['ra.group_id' => [3]]);

        self::assertCount(2, $entrys);
    }

    public function testgetWarByDate()
    {
        $entrys = $this->mapper->getWarsForJson('2021-05-03', '2021-05-09', '1,2,3');
        self::assertCount(3, $entrys);
    }

    public function testsaveNewWar()
    {
        $model = new EntriesModel();
        $model->setWarEnemy(1);
        $model->setWarGroup(2);
        $model->setWarTime('2021-05-12 08:10:38');
        $model->setWarMaps('');
        $model->setWarServer('localhost');
        $model->setWarPassword('');
        $model->setWarXonx('1on1');
        $model->setWarGame('CSS');
        $model->setWarMatchtype('Train');
        $model->setWarReport('');
        $model->setWarStatus(0);
        $model->setShow(1);
        $model->setReadAccess('all');
        $model->setLastAcceptTime(0);
        $id = $this->mapper->save($model);

        $entry = $this->mapper->getWarById($id);

        self::assertNotNull($entry);
        self::assertEquals($id, $entry->getId());
        self::assertEquals(1, $entry->getWarEnemy());
        self::assertEquals(2, $entry->getWarGroup());
        self::assertSame('2021-05-12 08:10:38', $entry->getWarTime());
        self::assertSame('', $entry->getWarMaps());
        self::assertSame('localhost', $entry->getWarServer());
        self::assertSame('', $entry->getWarPassword());
        self::assertSame('1on1', $entry->getWarXonx());
        self::assertSame('CSS', $entry->getWarGame());
        self::assertSame('Train', $entry->getWarMatchtype());
        self::assertSame('', $entry->getWarReport());
        self::assertEquals(0, $entry->getWarStatus());
        self::assertEquals(1, $entry->getShow());
        self::assertSame('all', $entry->getReadAccess());
        self::assertEquals(0, $entry->getLastAcceptTime());
        self::assertSame('ILCH2-TG2', $entry->getWarGroupTag());
        self::assertSame('TG1', $entry->getWarEnemyTag());
    }

    public function testsaveUpdateExistingWar()
    {
        $model = new EntriesModel();
        $model->setId(1);
        $model->setWarEnemy(1);
        $model->setWarGroup(2);
        $model->setWarTime('2021-05-12 08:10:38');
        $model->setWarMaps('');
        $model->setWarServer('localhost');
        $model->setWarPassword('');
        $model->setWarXonx('1on1');
        $model->setWarGame('CSS');
        $model->setWarMatchtype('Train');
        $model->setWarReport('');
        $model->setWarStatus(0);
        $model->setShow(1);
        $model->setReadAccess('all');
        $model->setLastAcceptTime(0);
        $id = $this->mapper->save($model);

        $entry = $this->mapper->getWarById($id);

        self::assertNotNull($entry);
        self::assertEquals(1, $id);
        self::assertEquals($id, $entry->getId());
        self::assertEquals(1, $entry->getWarEnemy());
        self::assertEquals(2, $entry->getWarGroup());
        self::assertSame('2021-05-12 08:10:38', $entry->getWarTime());
        self::assertSame('', $entry->getWarMaps());
        self::assertSame('localhost', $entry->getWarServer());
        self::assertSame('', $entry->getWarPassword());
        self::assertSame('1on1', $entry->getWarXonx());
        self::assertSame('CSS', $entry->getWarGame());
        self::assertSame('Train', $entry->getWarMatchtype());
        self::assertSame('', $entry->getWarReport());
        self::assertEquals(0, $entry->getWarStatus());
        self::assertEquals(1, $entry->getShow());
        self::assertSame('all', $entry->getReadAccess());
        self::assertEquals(0, $entry->getLastAcceptTime());
        self::assertSame('ILCH2-TG2', $entry->getWarGroupTag());
        self::assertSame('TG1', $entry->getWarEnemyTag());
    }

    public function testdeleteWar()
    {
        self::assertSame(true, $this->mapper->delete(1));

        $entry = $this->mapper->getWarById(1);
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
        $configComment = new CommentConfig();

        return $configAdmin->getInstallSql() . $configUser->getInstallSql() . $configComment->getInstallSql() . $config->getInstallSql();
    }
}
