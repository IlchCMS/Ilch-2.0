<?php
/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Teams\Mappers;

use Ilch\Pagination;
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
    protected $phpunitDataset;
    private $mapper;

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
        $entrys = $this->mapper->getTeams();

        self::assertCount(3, $entrys);
    }

    public function testgetTeams()
    {
        $entrys = $this->mapper->getTeams();

        self::assertCount(3, $entrys);

        $i = 0;
        self::assertEquals(1, $entrys[$i]->getId());
        self::assertEquals('Team 1', $entrys[$i]->getName());
        self::assertEquals(1, $entrys[$i]->getPosition());
        self::assertEquals('', $entrys[$i]->getImg());
        self::assertEquals('1,3', $entrys[$i]->getLeader());
        self::assertEquals('4', $entrys[$i]->getCoLeader());
        self::assertEquals(1, $entrys[$i]->getGroupId());
        self::assertEquals(1, $entrys[$i]->getOptShow());
        self::assertEquals(0, $entrys[$i]->getOptIn());
        self::assertEquals(0, $entrys[$i]->getNotifyLeader());


        $i++;
        self::assertEquals(3, $entrys[$i]->getId());
        self::assertEquals('Team 3', $entrys[$i]->getName());
        self::assertEquals(2, $entrys[$i]->getPosition());
        self::assertEquals('', $entrys[$i]->getImg());
        self::assertEquals('1', $entrys[$i]->getLeader());
        self::assertEquals('', $entrys[$i]->getCoLeader());
        self::assertEquals(3, $entrys[$i]->getGroupId());
        self::assertEquals(1, $entrys[$i]->getOptShow());
        self::assertEquals(1, $entrys[$i]->getOptIn());
        self::assertEquals(1, $entrys[$i]->getNotifyLeader());
        
        $i++;
        self::assertEquals(2, $entrys[$i]->getId());
        self::assertEquals('Team 2', $entrys[$i]->getName());
        self::assertEquals(3, $entrys[$i]->getPosition());
        self::assertEquals('', $entrys[$i]->getImg());
        self::assertEquals('3', $entrys[$i]->getLeader());
        self::assertEquals('4', $entrys[$i]->getCoLeader());
        self::assertEquals(3, $entrys[$i]->getGroupId());
        self::assertEquals(1, $entrys[$i]->getOptShow());
        self::assertEquals(1, $entrys[$i]->getOptIn());
        self::assertEquals(1, $entrys[$i]->getNotifyLeader());
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
    protected static function getSchemaSQLQueries()
    {
        $config = new ModuleConfig();
        $configUser = new UserConfig();
        $configAdmin = new AdminConfig();

        return $configAdmin->getInstallSql() . $configUser->getInstallSql() . $config->getInstallSql();
    }
}
