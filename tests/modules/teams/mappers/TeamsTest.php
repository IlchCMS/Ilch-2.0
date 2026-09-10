<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Teams\Mappers;

use PHPUnit\Ilch\DatabaseTestCase;
use PHPUnit\Ilch\PhpunitDataset;
use Modules\Teams\Config\Config as ModuleConfig;
use Modules\User\Config\Config as UserConfig;
use Modules\Admin\Config\Config as AdminConfig;
use Modules\Teams\Mappers\Teams as TeamsMapper;
use Modules\Teams\Models\Teams as EntriesModel;

/**
 * @package ilch_phpunit
 */
class TeamsTest extends DatabaseTestCase
{
    protected PhpunitDataset $phpunitDataset;
    private Teams $mapper;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database.yml');

        $this->mapper = new TeamsMapper();
    }

    /**
     * All test routines
     */
    public function testgetTeamsAllRows()
    {
        $entries = $this->mapper->getTeams();

        self::assertCount(3, $entries);
    }

    public function testgetTeams()
    {
        $entries = $this->mapper->getTeams();

        self::assertCount(3, $entries);

        $i = 0;
        self::assertEquals(1, $entries[$i]->getId());
        self::assertEquals('Team 1', $entries[$i]->getName());
        self::assertEquals(1, $entries[$i]->getPosition());
        self::assertEquals('', $entries[$i]->getImg());
        self::assertEquals('1,3', $entries[$i]->getLeader());
        self::assertEquals('4', $entries[$i]->getCoLeader());
        self::assertEquals(1, $entries[$i]->getGroupId());
        self::assertEquals(1, $entries[$i]->getOptShow());
        self::assertEquals(0, $entries[$i]->getOptIn());
        self::assertEquals(0, $entries[$i]->getNotifyLeader());


        $i++;
        self::assertEquals(3, $entries[$i]->getId());
        self::assertEquals('Team 3', $entries[$i]->getName());
        self::assertEquals(2, $entries[$i]->getPosition());
        self::assertEquals('', $entries[$i]->getImg());
        self::assertEquals('1', $entries[$i]->getLeader());
        self::assertEquals('', $entries[$i]->getCoLeader());
        self::assertEquals(3, $entries[$i]->getGroupId());
        self::assertEquals(1, $entries[$i]->getOptShow());
        self::assertEquals(1, $entries[$i]->getOptIn());
        self::assertEquals(1, $entries[$i]->getNotifyLeader());

        $i++;
        self::assertEquals(2, $entries[$i]->getId());
        self::assertEquals('Team 2', $entries[$i]->getName());
        self::assertEquals(3, $entries[$i]->getPosition());
        self::assertEquals('', $entries[$i]->getImg());
        self::assertEquals('3', $entries[$i]->getLeader());
        self::assertEquals('4', $entries[$i]->getCoLeader());
        self::assertEquals(3, $entries[$i]->getGroupId());
        self::assertEquals(1, $entries[$i]->getOptShow());
        self::assertEquals(1, $entries[$i]->getOptIn());
        self::assertEquals(1, $entries[$i]->getNotifyLeader());
    }

    public function testsaveNewTeams()
    {
        $model = new EntriesModel();
        $model->setId(0);
        $model->setPosition(4);
        $model->setName('Team 4');
        $model->setImg('');
        $model->setLeader('3');
        $model->setCoLeader('1');
        $model->setGroupId(4);
        $model->setOptShow(1);
        $model->setOptIn(1);
        $model->setNotifyLeader(0);
        $id = $this->mapper->save($model);

        $entry = $this->mapper->getEntryById($id);

        self::assertNotNull($entry);
        self::assertEquals($id, $entry->getId());
        self::assertEquals($model->getName(), $entry->getName());
        self::assertEquals($model->getPosition(), $entry->getPosition());
        self::assertEquals($model->getImg(), $entry->getImg());
        self::assertEquals($model->getLeader(), $entry->getLeader());
        self::assertEquals($model->getCoLeader(), $entry->getCoLeader());
        self::assertEquals($model->getGroupId(), $entry->getGroupId());
        self::assertEquals($model->getOptShow(), $entry->getOptShow());
        self::assertEquals($model->getOptIn(), $entry->getOptIn());
        self::assertEquals($model->getNotifyLeader(), $entry->getNotifyLeader());
    }

    public function testsaveUpdateExistingTeams()
    {
        $model = new EntriesModel();
        $model->setId(1);
        $model->setPosition(4);
        $model->setName('Team 4');
        $model->setImg('');
        $model->setLeader('3');
        $model->setCoLeader('1');
        $model->setGroupId(4);
        $model->setOptShow(1);
        $model->setOptIn(1);
        $model->setNotifyLeader(0);
        $id = $this->mapper->save($model);

        $entry = $this->mapper->getEntryById($id);

        self::assertNotNull($entry);
        self::assertEquals(1, $id);
        self::assertEquals($id, $entry->getId());
        self::assertEquals($model->getName(), $entry->getName());
        self::assertEquals($model->getPosition(), $entry->getPosition());
        self::assertEquals($model->getImg(), $entry->getImg());
        self::assertEquals($model->getLeader(), $entry->getLeader());
        self::assertEquals($model->getCoLeader(), $entry->getCoLeader());
        self::assertEquals($model->getGroupId(), $entry->getGroupId());
        self::assertEquals($model->getOptShow(), $entry->getOptShow());
        self::assertEquals($model->getOptIn(), $entry->getOptIn());
        self::assertEquals($model->getNotifyLeader(), $entry->getNotifyLeader());
    }

    public function testdeleteTeams()
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
    protected static function getSchemaSQLQueries(): string
    {
        $config = new ModuleConfig();
        $configUser = new UserConfig();
        $configAdmin = new AdminConfig();

        return $configAdmin->getInstallSql() . $configUser->getInstallSql() . $config->getInstallSql();
    }
}
