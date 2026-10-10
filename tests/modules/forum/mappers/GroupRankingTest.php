<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Forum\Mappers;

use Modules\Admin\Config\Config as AdminConfig;
use Modules\User\Config\Config as UserConfig;
use PHPUnit\Ilch\DatabaseTestCase;
use PHPUnit\Ilch\PhpunitDataset;
use Modules\Forum\Config\Config as ModuleConfig;
use Modules\Forum\Mappers\GroupRanking as GroupRankingMapper;
use Modules\Forum\Models\GroupRank as GroupRankModel;
use Modules\User\Models\Group as GroupModel;

class GroupRankingTest extends DatabaseTestCase
{
    /**
     * @var GroupRankingMapper
     */
    protected GroupRanking $out;
    protected PhpunitDataset $phpunitDataset;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database.yml');
        $this->out = new GroupRankingMapper();
    }

    /**
     * Tests that getGroupRanking() returns all entries ordered by rank.
     */
    public function testGetGroupRanking()
    {
        $rankings = $this->out->getGroupRanking();

        self::assertIsArray($rankings);
        self::assertCount(3, $rankings);
        self::assertInstanceOf(GroupRankModel::class, $rankings[0]);

        self::assertEquals(1, $rankings[0]->getGroupId());
        self::assertEquals(0, $rankings[0]->getRank());
        self::assertEquals(2, $rankings[1]->getGroupId());
        self::assertEquals(2, $rankings[1]->getRank());
        self::assertEquals(3, $rankings[2]->getGroupId());
        self::assertEquals(4, $rankings[2]->getRank());
    }

    /**
     * Tests that getGroupRanking() supports the where parameter.
     */
    public function testGetGroupRankingWithWhere()
    {
        $rankings = $this->out->getGroupRanking(['group_id' => 2]);

        self::assertCount(1, $rankings);
        self::assertEquals(2, $rankings[0]->getGroupId());
        self::assertEquals(2, $rankings[0]->getRank());
    }

    /**
     * Tests that getGroupRanking() returns an empty array when nothing matches.
     */
    public function testGetGroupRankingWithWhereEmpty()
    {
        $rankings = $this->out->getGroupRanking(['group_id' => 9999]);

        self::assertIsArray($rankings);
        self::assertCount(0, $rankings);
    }

    /**
     * Tests that getGroupRankingByGroupId() returns the correct entry.
     */
    public function testGetGroupRankingByGroupId()
    {
        $ranking = $this->out->getGroupRankingByGroupId(2);

        self::assertInstanceOf(GroupRankModel::class, $ranking);
        self::assertEquals(2, $ranking->getGroupId());
        self::assertEquals(2, $ranking->getRank());
    }

    /**
     * Tests that getGroupRankingByGroupId() returns false for an unranked group.
     */
    public function testGetGroupRankingByGroupIdNotFound()
    {
        self::assertFalse($this->out->getGroupRankingByGroupId(9999));
    }

    /**
     * Tests that getHighestRankOfGroups() returns the entry with the lowest rank value.
     */
    public function testGetHighestRankOfGroups()
    {
        // Group 3 is listed first, but group 2 has the better (lower) rank.
        $ranking = $this->out->getHighestRankOfGroups([3, 2]);

        self::assertInstanceOf(GroupRankModel::class, $ranking);
        self::assertEquals(2, $ranking->getGroupId());
        self::assertEquals(2, $ranking->getRank());
    }

    /**
     * Tests that getHighestRankOfGroups() works with a single group id.
     */
    public function testGetHighestRankOfGroupsSingle()
    {
        $ranking = $this->out->getHighestRankOfGroups([1]);

        self::assertEquals(1, $ranking->getGroupId());
        self::assertEquals(0, $ranking->getRank());
    }

    /**
     * Tests that getHighestRankOfGroups() returns null when nothing matches.
     */
    public function testGetHighestRankOfGroupsNotFound()
    {
        self::assertNull($this->out->getHighestRankOfGroups([9999]));
    }

    /**
     * Tests that getUserGroupsSortedByRank() returns all groups,
     * ranked ones in ascending rank order.
     */
    public function testGetUserGroupsSortedByRank()
    {
        $groups = $this->out->getUserGroupsSortedByRank();

        self::assertIsArray($groups);
        self::assertCount(4, $groups);
        self::assertInstanceOf(GroupModel::class, $groups[0]);

        // All groups are present, including the unranked one.
        $groupIds = [];
        foreach ($groups as $group) {
            $groupIds[] = $group->getId();
        }
        self::assertEqualsCanonicalizing([1, 2, 3, 4], $groupIds);

        // Names are mapped correctly from the groups table.
        $namesById = [];
        foreach ($groups as $group) {
            $namesById[$group->getId()] = $group->getName();
        }
        self::assertSame('Administrator', $namesById[1]);
        self::assertSame('Moderator', $namesById[2]);
        self::assertSame('Member', $namesById[3]);
        self::assertSame('Subscriber', $namesById[4]);

        // The ranked groups follow the rank order (0 < 2 < 4).
        $positions = [];
        foreach ($groups as $position => $group) {
            $positions[$group->getId()] = $position;
        }
        self::assertLessThan($positions[2], $positions[1]);
        self::assertLessThan($positions[3], $positions[2]);
    }

    /**
     * Tests that saveGroupRanking() updates an existing entry.
     */
    public function testSaveGroupRankingUpdatesExisting()
    {
        $this->out->saveGroupRanking([
            5 => 1,
        ]);

        $ranking = $this->out->getGroupRankingByGroupId(1);
        self::assertNotNull($ranking);
        self::assertEquals(1, $ranking->getGroupId());
        self::assertEquals(5, $ranking->getRank());

        // No new entry was created
        $rankings = $this->out->getGroupRanking();
        self::assertCount(3, $rankings);
    }

    /**
     * Tests that saveGroupRanking() inserts a new entry for an unranked group.
     */
    public function testSaveGroupRankingInsertsNew()
    {
        // Group 4 exists but has no ranking yet.
        self::assertFalse($this->out->getGroupRankingByGroupId(4));

        $this->out->saveGroupRanking([
            1 => 4,
        ]);

        $ranking = $this->out->getGroupRankingByGroupId(4);
        self::assertNotNull($ranking);
        self::assertEquals(4, $ranking->getGroupId());
        self::assertEquals(1, $ranking->getRank());

        // Other rankings are untouched
        self::assertEquals(0, $this->out->getGroupRankingByGroupId(1)->getRank());
        self::assertEquals(2, $this->out->getGroupRankingByGroupId(2)->getRank());
        self::assertEquals(4, $this->out->getGroupRankingByGroupId(3)->getRank());

        $rankings = $this->out->getGroupRanking();
        self::assertCount(4, $rankings);
    }

    /**
     * Tests that saveGroupRanking() can update and insert in one call.
     */
    public function testSaveGroupRankingMixedUpdateAndInsert()
    {
        $this->out->saveGroupRanking([
            3 => 2, // update group 2 from rank 2 to rank 3
            5 => 4, // insert a new entry for group 4
        ]);

        self::assertEquals(3, $this->out->getGroupRankingByGroupId(2)->getRank());
        self::assertEquals(5, $this->out->getGroupRankingByGroupId(4)->getRank());
        self::assertEquals(0, $this->out->getGroupRankingByGroupId(1)->getRank());

        self::assertCount(4, $this->out->getGroupRanking());
    }

    /**
     * Tests that getGroupRanking() sorts a newly inserted entry into its
     * rank position instead of appending it.
     */
    public function testGetGroupRankingOrderingAfterInsert()
    {
        // Insert rank 1 for group 4, which must sort between 0 and 2.
        $this->out->saveGroupRanking([
            1 => 4,
        ]);

        $rankings = $this->out->getGroupRanking();
        self::assertCount(4, $rankings);

        self::assertEquals(1, $rankings[0]->getGroupId());
        self::assertEquals(4, $rankings[1]->getGroupId());
        self::assertEquals(2, $rankings[2]->getGroupId());
        self::assertEquals(3, $rankings[3]->getGroupId());
    }

    /**
     * Returns database schema SQL statements to initialize database.
     *
     * @return string
     */
    protected static function getSchemaSQLQueries(): string
    {
        $config = new ModuleConfig();
        $userConfig = new UserConfig();
        $adminConfig = new AdminConfig();

        return $adminConfig->getInstallSql() . $userConfig->getInstallSql() . $config->getInstallSql();
    }
}
