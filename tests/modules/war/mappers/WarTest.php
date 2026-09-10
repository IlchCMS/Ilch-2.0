<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\War\Mappers;

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
    protected PhpunitDataset $phpunitDataset;
    private War $mapper;

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
        $entries = $this->mapper->getWars();

        self::assertCount(4, $entries);
    }

    public function testgetWar()
    {
        $entries = $this->mapper->getWars();

        self::assertCount(4, $entries);

        $i = 0;
        self::assertEquals(2, $entries[$i]->getId());
        self::assertEquals(1, $entries[$i]->getWarEnemy());
        self::assertEquals(2, $entries[$i]->getWarGroup());
        self::assertSame('2021-05-09 08:10:38', $entries[$i]->getWarTime());
        self::assertSame('', $entries[$i]->getWarMaps());
        self::assertSame('localhost', $entries[$i]->getWarServer());
        self::assertSame('', $entries[$i]->getWarPassword());
        self::assertSame('1on1', $entries[$i]->getWarXonx());
        self::assertSame('CSS', $entries[$i]->getWarGame());
        self::assertSame('Train', $entries[$i]->getWarMatchtype());
        self::assertSame('', $entries[$i]->getWarReport());
        self::assertEquals(0, $entries[$i]->getWarStatus());
        self::assertEquals(1, $entries[$i]->getShow());
        self::assertSame('1,3', $entries[$i]->getReadAccess());
        self::assertEquals(0, $entries[$i]->getLastAcceptTime());
        self::assertSame('ILCH2-TG2', $entries[$i]->getWarGroupTag());
        self::assertSame('TG1', $entries[$i]->getWarEnemyTag());

        $i++;
        self::assertEquals(3, $entries[$i]->getId());
        self::assertEquals(2, $entries[$i]->getWarEnemy());
        self::assertEquals(1, $entries[$i]->getWarGroup());
        self::assertSame('2021-05-09 08:10:38', $entries[$i]->getWarTime());
        self::assertSame('', $entries[$i]->getWarMaps());
        self::assertSame('localhost', $entries[$i]->getWarServer());
        self::assertSame('', $entries[$i]->getWarPassword());
        self::assertSame('1on1', $entries[$i]->getWarXonx());
        self::assertSame('CSS', $entries[$i]->getWarGame());
        self::assertSame('Train', $entries[$i]->getWarMatchtype());
        self::assertSame('', $entries[$i]->getWarReport());
        self::assertEquals(0, $entries[$i]->getWarStatus());
        self::assertEquals(1, $entries[$i]->getShow());
        self::assertSame('1,2', $entries[$i]->getReadAccess());
        self::assertEquals(0, $entries[$i]->getLastAcceptTime());
        self::assertSame('ILCH2-TG1', $entries[$i]->getWarGroupTag());
        self::assertSame('TG2', $entries[$i]->getWarEnemyTag());

        $i++;
        self::assertEquals(4, $entries[$i]->getId());
        self::assertEquals(2, $entries[$i]->getWarEnemy());
        self::assertEquals(2, $entries[$i]->getWarGroup());
        self::assertSame('2021-05-09 08:10:38', $entries[$i]->getWarTime());
        self::assertSame('', $entries[$i]->getWarMaps());
        self::assertSame('localhost', $entries[$i]->getWarServer());
        self::assertSame('', $entries[$i]->getWarPassword());
        self::assertSame('1on1', $entries[$i]->getWarXonx());
        self::assertSame('CSS', $entries[$i]->getWarGame());
        self::assertSame('Train', $entries[$i]->getWarMatchtype());
        self::assertSame('', $entries[$i]->getWarReport());
        self::assertEquals(0, $entries[$i]->getWarStatus());
        self::assertEquals(1, $entries[$i]->getShow());
        self::assertSame('1', $entries[$i]->getReadAccess());
        self::assertEquals(0, $entries[$i]->getLastAcceptTime());
        self::assertSame('ILCH2-TG2', $entries[$i]->getWarGroupTag());
        self::assertSame('TG2', $entries[$i]->getWarEnemyTag());

        $i++;
        self::assertEquals(1, $entries[$i]->getId());
        self::assertEquals(1, $entries[$i]->getWarEnemy());
        self::assertEquals(1, $entries[$i]->getWarGroup());
        self::assertSame('2021-05-10 08:10:38', $entries[$i]->getWarTime());
        self::assertSame('', $entries[$i]->getWarMaps());
        self::assertSame('localhost', $entries[$i]->getWarServer());
        self::assertSame('', $entries[$i]->getWarPassword());
        self::assertSame('1on1', $entries[$i]->getWarXonx());
        self::assertSame('CSS', $entries[$i]->getWarGame());
        self::assertSame('Train', $entries[$i]->getWarMatchtype());
        self::assertSame('', $entries[$i]->getWarReport());
        self::assertEquals(0, $entries[$i]->getWarStatus());
        self::assertEquals(1, $entries[$i]->getShow());
        self::assertSame('1,2,3', $entries[$i]->getReadAccess());
        self::assertEquals(0, $entries[$i]->getLastAcceptTime());
        self::assertSame('ILCH2-TG1', $entries[$i]->getWarGroupTag());
        self::assertSame('TG1', $entries[$i]->getWarEnemyTag());
    }

    public function testgetWarByAccess()
    {
        $entries = $this->mapper->getWars(['ra.group_id' => [1,2,3]]);

        self::assertCount(4, $entries);
    }

    public function testgetWarByAccessGuest()
    {
        $entries = $this->mapper->getWars(['ra.group_id' => [3]]);

        self::assertCount(2, $entries);
    }

    public function testgetWarByDate()
    {
        $entries = $this->mapper->getWarsForJson('2021-05-03', '2021-05-09', '1,2,3');
        self::assertCount(3, $entries);
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
    protected static function getSchemaSQLQueries(): string
    {
        $config = new ModuleConfig();
        $configUser = new UserConfig();
        $configAdmin = new AdminConfig();
        $configComment = new CommentConfig();

        return $configAdmin->getInstallSql() . $configUser->getInstallSql() . $configComment->getInstallSql() . $config->getInstallSql();
    }
}
