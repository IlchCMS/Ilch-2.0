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
    protected static array $fixtureFiles = [__DIR__ . '/../_files/mysql_database.yml'];

    protected PhpunitDataset $phpunitDataset;
    private Teams $mapper;

    /**
     * Loads the fixture data and creates the mapper before each test.
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFiles(static::$fixtureFiles);

        $this->mapper = new TeamsMapper();
    }

    /**
     * Tests that the teams table exists.
     */
    public function testCheckDB()
    {
        self::assertTrue($this->mapper->checkDB());
    }

    /**
     * Tests that getTeams() returns all teams.
     */
    public function testgetTeamsAllRows()
    {
        $entries = $this->mapper->getTeams();

        self::assertCount(3, $entries);
    }

    /**
     * Tests that getTeams() returns the expected fields in ascending position order.
     */
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

    /**
     * Tests that getTeams() filters by the given where clause.
     */
    public function testGetTeamsByWhere()
    {
        $entries = $this->mapper->getTeams(['groupId' => 3]);

        self::assertCount(2, $entries);
        self::assertEquals(3, $entries[0]->getId());
        self::assertEquals(2, $entries[1]->getId());
    }

    /**
     * Tests that getEntriesBy() supports a custom order.
     */
    public function testGetEntriesByOrderDesc()
    {
        $entries = $this->mapper->getEntriesBy([], ['position' => 'DESC']);

        self::assertCount(3, $entries);
        self::assertEquals(2, $entries[0]->getId());
        self::assertEquals(3, $entries[1]->getId());
        self::assertEquals(1, $entries[2]->getId());
    }

    /**
     * Tests that getEntryById() returns the matching team.
     */
    public function testGetEntryById()
    {
        $entry = $this->mapper->getEntryById(1);

        self::assertNotNull($entry);
        self::assertInstanceOf(EntriesModel::class, $entry);
        self::assertEquals(1, $entry->getId());
        self::assertEquals('Team 1', $entry->getName());
    }

    /**
     * Tests that getEntryById() accepts a model instance.
     */
    public function testGetEntryByIdWithModel()
    {
        $model = new EntriesModel();
        $model->setId(2);

        $entry = $this->mapper->getEntryById($model);

        self::assertNotNull($entry);
        self::assertEquals(2, $entry->getId());
        self::assertEquals('Team 2', $entry->getName());
    }

    /**
     * Tests that getEntryById() returns null for a non-existent id.
     */
    public function testGetEntryByIdNotFound()
    {
        self::assertNull($this->mapper->getEntryById(9999));
    }

    /**
     * Tests that getTeamByGroupId() returns the first team (position ASC) of the group.
     */
    public function testGetTeamByGroupId()
    {
        // Team 2 and Team 3 both belong to group 3, Team 3 has the lower position.
        $entry = $this->mapper->getTeamByGroupId(3);

        self::assertNotNull($entry);
        self::assertEquals(3, $entry->getId());
        self::assertEquals(3, $entry->getGroupId());
    }

    /**
     * Tests that getTeamByGroupId() accepts a model instance.
     */
    public function testGetTeamByGroupIdWithModel()
    {
        $model = new EntriesModel();
        $model->setGroupId(1);

        $entry = $this->mapper->getTeamByGroupId($model);

        self::assertNotNull($entry);
        self::assertEquals(1, $entry->getId());
    }

    /**
     * Tests that getTeamByGroupId() returns null when no team uses the group.
     */
    public function testGetTeamByGroupIdNotFound()
    {
        self::assertNull($this->mapper->getTeamByGroupId(9999));
    }

    /**
     * Tests that sort() updates the position of a team.
     */
    public function testSort()
    {
        self::assertSame(true, $this->mapper->sort(1, 10));

        $entry = $this->mapper->getTeamById(1);

        self::assertNotNull($entry);
        self::assertEquals(10, $entry->getPosition());
    }

    /**
     * Tests that updateShow() sets the given value explicitly.
     */
    public function testUpdateShowExplicit()
    {
        self::assertSame(true, $this->mapper->updateShow(1, 0));
        self::assertFalse($this->mapper->getTeamById(1)->getOptShow());

        self::assertSame(true, $this->mapper->updateShow(1, 1));
        self::assertTrue($this->mapper->getTeamById(1)->getOptShow());
    }

    /**
     * Tests that updateShow() toggles the current value when no value is given.
     */
    public function testUpdateShowToggle()
    {
        // Fixture: Team 1 starts with optShow = 1.
        self::assertTrue($this->mapper->getTeamById(1)->getOptShow());

        self::assertSame(true, $this->mapper->updateShow(1));
        self::assertFalse($this->mapper->getTeamById(1)->getOptShow());

        self::assertSame(true, $this->mapper->updateShow(1));
        self::assertTrue($this->mapper->getTeamById(1)->getOptShow());
    }

    /**
     * Tests that updateShow() toggles the optShow value of a model instance (1 -> 0).
     */
    public function testUpdateShowWithModel()
    {
        $model = new EntriesModel();
        $model->setId(2);
        $model->setOptShow(true);

        self::assertSame(true, $this->mapper->updateShow($model));

        // Toggled from 1 to 0.
        self::assertFalse($this->mapper->getTeamById(2)->getOptShow());
    }

    /**
     * Tests that updateShow() toggles a hidden team to shown (0 -> 1) via a model.
     * The model must reflect the current database state, otherwise the
     * UPDATE is a no-op (no rows changed) and the mapper returns false.
     */
    public function testUpdateShowWithModelFromHidden()
    {
        // Hide the team first so the model below matches the database state.
        self::assertSame(true, $this->mapper->updateShow(2, 0));
        self::assertFalse($this->mapper->getTeamById(2)->getOptShow());

        $model = new EntriesModel();
        $model->setId(2);
        $model->setOptShow(false);

        self::assertSame(true, $this->mapper->updateShow($model));

        self::assertTrue($this->mapper->getTeamById(2)->getOptShow());
    }

    /**
     * Tests that delImageById() removes the image file and resets the img column.
     */
    public function testDelImageById()
    {
        $file = sys_get_temp_dir() . '/teams_test_image_' . uniqid() . '.png';
        @unlink($file);
        file_put_contents($file, 'test');

        $model = new EntriesModel();
        $model->setId(0);
        $model->setPosition(4);
        $model->setName('Team 4');
        $model->setImg($file);
        $model->setLeader('3');
        $model->setCoLeader('1');
        $model->setGroupId(4);
        $model->setOptShow(1);
        $model->setOptIn(1);
        $model->setNotifyLeader(0);
        $id = $this->mapper->save($model);

        self::assertTrue(file_exists($file));

        self::assertSame(true, $this->mapper->delImageById($id));

        self::assertFalse(file_exists($file));
        self::assertEquals('', $this->mapper->getTeamById($id)->getImg());
    }

    /**
     * Tests that delImageById() returns false for a non-existent team.
     */
    public function testDelImageByIdNotFound()
    {
        self::assertFalse($this->mapper->delImageById(9999));
    }

    /**
     * Tests inserting a new team via save().
     */
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

    /**
     * Tests updating an existing team via save().
     */
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

    /**
     * Tests that delete() removes a team (including its image file).
     */
    public function testdeleteTeams()
    {
        self::assertSame(true, $this->mapper->delete(1));

        $entry = $this->mapper->getTeamById(1);
        self::assertNull($entry);

        self::assertCount(2, $this->mapper->getTeams());
    }

    /**
     * Tests that delete() returns false for a non-existent team.
     */
    public function testDeleteTeamsNotFound()
    {
        self::assertFalse($this->mapper->delete(9999));

        self::assertCount(3, $this->mapper->getTeams());
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
