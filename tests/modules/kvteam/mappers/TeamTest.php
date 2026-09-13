<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Kvteam\Mappers;

use PHPUnit\Ilch\DatabaseTestCase;
use PHPUnit\Ilch\PhpunitDataset;
use Modules\Kvteam\Config\Config as ModuleConfig;
use Modules\Kvteam\Mappers\Team as TeamMapper;
use Modules\Kvteam\Models\Team as TeamModel;

class TeamTest extends DatabaseTestCase
{
    /**
     * @var TeamMapper
     */
    protected Team $out;
    protected PhpunitDataset $phpunitDataset;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database.yml');
        $this->out = new TeamMapper();
    }

    /**
     * Tests that getTeams() returns all teams from the seed data.
     */
    public function testGetTeams()
    {
        $teams = $this->out->getTeams();

        self::assertIsArray($teams);
        self::assertCount(3, $teams);
        self::assertInstanceOf(TeamModel::class, $teams[0]);
    }

    /**
     * Tests that getTeams() returns teams ordered by position ASC.
     */
    public function testGetTeamsOrderPositionAsc()
    {
        $teams = $this->out->getTeams();

        self::assertEquals(1, $teams[0]->getPosition());
        self::assertEquals(2, $teams[1]->getPosition());
        self::assertEquals(3, $teams[2]->getPosition());
    }

    /**
     * Tests that getTeams() returns correct fields for the first team.
     */
    public function testGetTeamsFields()
    {
        $teams = $this->out->getTeams();

        self::assertEquals(1, $teams[0]->getId());
        self::assertEquals('Board Members', $teams[0]->getTitle());
        self::assertEquals('1,2,3', $teams[0]->getUserIds());
        self::assertEquals(1, $teams[0]->getPosition());
    }

    /**
     * Tests that getTeams() returns correct fields for the second team.
     */
    public function testGetTeamsSecond()
    {
        $teams = $this->out->getTeams();

        self::assertEquals(2, $teams[1]->getId());
        self::assertEquals('Coaches', $teams[1]->getTitle());
        self::assertEquals('4,5', $teams[1]->getUserIds());
        self::assertEquals(2, $teams[1]->getPosition());
    }

    /**
     * Tests that getTeams() returns correct fields for the third team.
     */
    public function testGetTeamsThird()
    {
        $teams = $this->out->getTeams();

        self::assertEquals(3, $teams[2]->getId());
        self::assertEquals('Support Team', $teams[2]->getTitle());
        self::assertEquals('6,7,8,9', $teams[2]->getUserIds());
        self::assertEquals(3, $teams[2]->getPosition());
    }

    /**
     * Tests that getTeams() returns an empty array when no teams exist.
     */
    public function testGetTeamsEmpty()
    {
        $this->out->delete(1);
        $this->out->delete(2);
        $this->out->delete(3);

        $teams = $this->out->getTeams();

        self::assertIsArray($teams);
        self::assertCount(0, $teams);
    }

    /**
     * Tests that getTeams() filters by a where clause.
     */
    public function testGetTeamsWithWhere()
    {
        $teams = $this->out->getTeams(['id' => 2]);

        self::assertCount(1, $teams);
        self::assertEquals(2, $teams[0]->getId());
        self::assertEquals('Coaches', $teams[0]->getTitle());
    }

    /**
     * Tests that getTeams() filters by position.
     */
    public function testGetTeamsWherePosition()
    {
        $teams = $this->out->getTeams(['position' => 1]);

        self::assertCount(1, $teams);
        self::assertEquals(1, $teams[0]->getId());
        self::assertEquals('Board Members', $teams[0]->getTitle());
    }

    /**
     * Tests that getTeams() returns an empty array when no rows match the where clause.
     */
    public function testGetTeamsWhereNoMatch()
    {
        $teams = $this->out->getTeams(['id' => 9999]);

        self::assertIsArray($teams);
        self::assertCount(0, $teams);
    }

    /**
     * Tests that getTeamById() returns the correct team.
     */
    public function testGetTeamById()
    {
        $team = $this->out->getTeamById(1);

        self::assertNotNull($team);
        self::assertInstanceOf(TeamModel::class, $team);
        self::assertEquals(1, $team->getId());
        self::assertEquals('Board Members', $team->getTitle());
        self::assertEquals('1,2,3', $team->getUserIds());
        self::assertEquals(1, $team->getPosition());
    }

    /**
     * Tests that getTeamById() returns a different team.
     */
    public function testGetTeamByIdSecond()
    {
        $team = $this->out->getTeamById(3);

        self::assertNotNull($team);
        self::assertEquals(3, $team->getId());
        self::assertEquals('Support Team', $team->getTitle());
        self::assertEquals('6,7,8,9', $team->getUserIds());
    }

    /**
     * Tests that getTeamById() returns null for a non-existent id.
     */
    public function testGetTeamByIdNotFound()
    {
        $team = $this->out->getTeamById(9999);

        self::assertNull($team);
    }

    /**
     * Tests that save() inserts a new team when id is 0.
     */
    public function testSaveInsert()
    {
        $model = new TeamModel();
        $model->setId(0)
            ->setTitle('Sponsors')
            ->setUserIds('10,11,12')
            ->setPosition(4);

        $result = $this->out->save($model);

        self::assertTrue($result);

        $teams = $this->out->getTeams();
        self::assertCount(4, $teams);

        // New team should appear last (position 4)
        $new = $teams[3];
        self::assertGreaterThan(3, $new->getId());
        self::assertEquals('Sponsors', $new->getTitle());
        self::assertEquals('10,11,12', $new->getUserIds());
        self::assertEquals(4, $new->getPosition());
    }

    /**
     * Tests that save() inserts with default position 0.
     */
    public function testSaveInsertDefaultPosition()
    {
        $model = new TeamModel();
        $model->setId(0)
            ->setTitle('New Group')
            ->setUserIds('20')
            ->setPosition(0);

        $result = $this->out->save($model);

        self::assertTrue($result);

        $teams = $this->out->getTeams();
        self::assertCount(4, $teams);

        // Position 0 should place it first
        $first = $teams[0];
        self::assertEquals('New Group', $first->getTitle());
        self::assertEquals(0, $first->getPosition());
    }

    /**
     * Tests that save() updates an existing team when id is set.
     */
    public function testSaveUpdate()
    {
        $model = new TeamModel();
        $model->setId(1)
            ->setTitle('Updated Board')
            ->setUserIds('1,2,4')
            ->setPosition(2);

        $result = $this->out->save($model);

        self::assertTrue($result);

        $team = $this->out->getTeamById(1);
        self::assertNotNull($team);
        self::assertEquals(1, $team->getId());
        self::assertEquals('Updated Board', $team->getTitle());
        self::assertEquals('1,2,4', $team->getUserIds());
        self::assertEquals(2, $team->getPosition());
    }

    /**
     * Tests that save() update does not affect other teams.
     */
    public function testSaveUpdateDoesNotAffectOthers()
    {
        $model = new TeamModel();
        $model->setId(1)
            ->setTitle('Changed')
            ->setUserIds('99')
            ->setPosition(5);

        $this->out->save($model);

        $other = $this->out->getTeamById(2);
        self::assertNotNull($other);
        self::assertEquals('Coaches', $other->getTitle());
        self::assertEquals('4,5', $other->getUserIds());
        self::assertEquals(2, $other->getPosition());
    }

    /**
     * Tests that updatePositionById() changes the position.
     */
    public function testUpdatePositionById()
    {
        $result = $this->out->updatePositionById(1, 10);

        self::assertTrue($result);

        $team = $this->out->getTeamById(1);
        self::assertNotNull($team);
        self::assertEquals(10, $team->getPosition());

        // Other fields unchanged
        self::assertEquals('Board Members', $team->getTitle());
        self::assertEquals('1,2,3', $team->getUserIds());
    }

    /**
     * Tests that updatePositionById() reorders getTeams() output.
     */
    public function testUpdatePositionByIdReorders()
    {
        // Move team 1 to position 5 (last)
        $this->out->updatePositionById(1, 5);

        $teams = $this->out->getTeams();

        self::assertEquals(2, $teams[0]->getId());
        self::assertEquals(3, $teams[1]->getId());
        self::assertEquals(1, $teams[2]->getId());
    }

    /**
     * Tests that sort() sets the position of a team.
     */
    public function testSort()
    {
        $result = $this->out->sort(2, 7);

        self::assertTrue($result);

        $team = $this->out->getTeamById(2);
        self::assertNotNull($team);
        self::assertEquals(7, $team->getPosition());

        // Other fields unchanged
        self::assertEquals('Coaches', $team->getTitle());
        self::assertEquals('4,5', $team->getUserIds());
    }

    /**
     * Tests that sort() reorders getTeams() output.
     */
    public function testSortReorders()
    {
        // Move team 3 to position 0 (first)
        $this->out->sort(3, 0);

        $teams = $this->out->getTeams();

        self::assertEquals(3, $teams[0]->getId());
        self::assertEquals(1, $teams[1]->getId());
        self::assertEquals(2, $teams[2]->getId());
    }

    /**
     * Tests that sort() and updatePositionById() produce the same effect.
     */
    public function testSortSameAsUpdatePosition()
    {
        $this->out->sort(1, 8);
        $this->out->updatePositionById(2, 8);

        // Both now have position 8; order between them depends on DB
        $team1 = $this->out->getTeamById(1);
        $team2 = $this->out->getTeamById(2);

        self::assertEquals(8, $team1->getPosition());
        self::assertEquals(8, $team2->getPosition());
    }

    /**
     * Tests that delete() removes a team.
     */
    public function testDelete()
    {
        $result = $this->out->delete(1);

        self::assertTrue($result);
        self::assertNull($this->out->getTeamById(1));

        $teams = $this->out->getTeams();
        self::assertCount(2, $teams);
        self::assertEquals(2, $teams[0]->getId());
        self::assertEquals(3, $teams[1]->getId());
    }

    /**
     * Tests that delete() on a non-existent id does not remove other teams.
     */
    public function testDeleteNotFound()
    {
        $this->out->delete(9999);

        $teams = $this->out->getTeams();
        self::assertCount(3, $teams);
    }

    /**
     * Tests that multiple deletes remove all teams.
     */
    public function testDeleteAll()
    {
        $this->out->delete(1);
        $this->out->delete(2);
        $this->out->delete(3);

        $teams = $this->out->getTeams();
        self::assertCount(0, $teams);
    }

    /**
     * Tests that save() after delete re-inserts correctly.
     */
    public function testSaveAfterDelete()
    {
        $this->out->delete(1);
        self::assertCount(2, $this->out->getTeams());

        $model = new TeamModel();
        $model->setId(0)
            ->setTitle('Replaced Team')
            ->setUserIds('50,51')
            ->setPosition(1);

        $this->out->save($model);

        $teams = $this->out->getTeams();
        self::assertCount(3, $teams);

        // Original team 1 is gone; new team exists
        self::assertNull($this->out->getTeamById(1));
    }

    /**
     * Tests that getTeams() with multiple where conditions works.
     */
    public function testGetTeamsMultipleWhere()
    {
        $teams = $this->out->getTeams(['position' => 2, 'id' => 2]);

        self::assertCount(1, $teams);
        self::assertEquals(2, $teams[0]->getId());
        self::assertEquals('Coaches', $teams[0]->getTitle());
    }

    /**
     * Tests that getTeams() with no matching where returns empty array (not null).
     */
    public function testGetTeamsWhereNoMatchReturnsEmptyArray()
    {
        $teams = $this->out->getTeams(['position' => 999]);

        self::assertIsArray($teams);
        self::assertCount(0, $teams);
    }

    /**
     * Returns database schema SQL statements to initialize database.
     *
     * @return string
     */
    protected static function getSchemaSQLQueries(): string
    {
        $config = new ModuleConfig();

        return $config->getInstallSql();
    }
}
