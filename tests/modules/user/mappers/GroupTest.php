<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\User\Mappers;

use Modules\Admin\Config\Config as AdminConfig;
use Modules\User\Config\Config as ModuleConfig;
use Modules\User\Mappers\Group as GroupMapper;
use Modules\User\Models\Group as GroupModel;
use Modules\User\Models\User;
use PHPUnit\Ilch\DatabaseTestCase;
use PHPUnit\Ilch\PhpunitDataset;

/**
 * Tests the user group mapper class.
 *
 * @package ilch_phpunit
 */
class GroupTest extends DatabaseTestCase
{
    /**
     * @var GroupMapper
     */
    protected GroupMapper $groupMapper;
    protected PhpunitDataset $phpunitDataset;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/database_groups.yml');
        $this->groupMapper = new GroupMapper();
    }

    /**
     * Tests if the group mapper returns true when the group tables exist.
     */
    public function testCheckDB()
    {
        self::assertTrue($this->groupMapper->checkDB());
    }

    /**
     * Tests if the group mapper returns the right group model using a data array.
     */
    public function testLoadFromArray()
    {
        $groupRow =
            [
                'id'   => 1,
                'name' => 'Administrator',
            ];

        $group = $this->groupMapper->loadFromArray($groupRow);

        self::assertNotFalse($group);
        self::assertInstanceOf(GroupModel::class, $group);
        self::assertEquals(1, $group->getId());
        self::assertEquals('Administrator', $group->getName());
    }

    /**
     * Tests that getGroupList() returns all groups ordered by id ascending.
     */
    public function testGetGroupList()
    {
        $groups = $this->groupMapper->getGroupList();

        self::assertNotNull($groups);
        self::assertCount(3, $groups);
        self::assertInstanceOf(GroupModel::class, $groups[0]);

        self::assertEquals(1, $groups[0]->getId());
        self::assertEquals('Administrator', $groups[0]->getName());

        self::assertEquals(2, $groups[1]->getId());
        self::assertEquals('User', $groups[1]->getName());

        self::assertEquals(3, $groups[2]->getId());
        self::assertEquals('Guest', $groups[2]->getName());
    }

    /**
     * Tests that getEntriesBy() filters by the given where clause.
     */
    public function testGetEntriesByWhere()
    {
        $groups = $this->groupMapper->getEntriesBy(['name' => 'Guest']);

        self::assertNotNull($groups);
        self::assertCount(1, $groups);
        self::assertEquals(3, $groups[0]->getId());
        self::assertEquals('Guest', $groups[0]->getName());
    }

    /**
     * Tests that getEntriesBy() sorts by the given order.
     */
    public function testGetEntriesByOrderBy()
    {
        $groups = $this->groupMapper->getEntriesBy([], ['a.name' => 'DESC']);

        self::assertNotNull($groups);
        self::assertCount(3, $groups);
        self::assertEquals('User', $groups[0]->getName());
        self::assertEquals('Guest', $groups[1]->getName());
        self::assertEquals('Administrator', $groups[2]->getName());
    }

    /**
     * Tests that getEntriesBy() returns null when no group matches the where clause.
     */
    public function testGetEntriesByNoResult()
    {
        $groups = $this->groupMapper->getEntriesBy(['name' => 'NotExisting']);

        self::assertNull($groups);
    }

    /**
     * Tests that getGroupById() returns the right group.
     */
    public function testGetGroupById()
    {
        $group = $this->groupMapper->getGroupById(2);

        self::assertNotNull($group);
        self::assertEquals(2, $group->getId());
        self::assertEquals('User', $group->getName());
    }

    /**
     * Tests that getGroupById() returns null for a non-existent id.
     */
    public function testGetGroupByIdNotFound()
    {
        self::assertNull($this->groupMapper->getGroupById(9999));
    }

    /**
     * Tests that getGroupByName() returns the right group.
     */
    public function testGetGroupByName()
    {
        $group = $this->groupMapper->getGroupByName('Guest');

        self::assertNotNull($group);
        self::assertEquals(3, $group->getId());
        self::assertEquals('Guest', $group->getName());
    }

    /**
     * Tests that getGroupByName() returns null for a non-existent name.
     */
    public function testGetGroupByNameNotFound()
    {
        self::assertNull($this->groupMapper->getGroupByName('NotExisting'));
    }

    /**
     * Tests that getUsersForGroup() returns the user ids assigned to a group.
     */
    public function testGetUsersForGroup()
    {
        self::assertEquals(['1'], $this->groupMapper->getUsersForGroup(1));
        self::assertEquals(['1', '3'], $this->groupMapper->getUsersForGroup(2));
        self::assertEquals(['2'], $this->groupMapper->getUsersForGroup(3));
    }

    /**
     * Tests that getUsersForGroup() returns an empty array for a group without users.
     */
    public function testGetUsersForGroupEmpty()
    {
        self::assertSame([], $this->groupMapper->getUsersForGroup(9999));
    }

    /**
     * Tests inserting a new group via save().
     */
    public function testSaveInsert()
    {
        $group = $this->groupMapper->loadFromArray(['id' => 0, 'name' => 'Test Group']);

        $lastInsertId = $this->groupMapper->save($group);

        self::assertGreaterThan(3, $lastInsertId);

        $saved = $this->groupMapper->getGroupById($lastInsertId);
        self::assertNotNull($saved);
        self::assertEquals('Test Group', $saved->getName());

        self::assertCount(4, $this->groupMapper->getGroupList());
    }

    /**
     * Tests updating an existing group via save().
     */
    public function testSaveUpdate()
    {
        $group = $this->groupMapper->loadFromArray(['id' => 1, 'name' => 'Super Admin']);

        self::assertSame(1, $this->groupMapper->save($group));

        $updated = $this->groupMapper->getGroupById(1);
        self::assertNotNull($updated);
        self::assertEquals(1, $updated->getId());
        self::assertEquals('Super Admin', $updated->getName());
    }

    /**
     * Tests that updating a group does not affect other groups.
     */
    public function testSaveUpdateDoesNotAffectOthers()
    {
        $group = $this->groupMapper->loadFromArray(['id' => 1, 'name' => 'Changed']);

        $this->groupMapper->save($group);

        $other = $this->groupMapper->getGroupById(2);
        self::assertNotNull($other);
        self::assertEquals('User', $other->getName());
    }

    /**
     * Tests that groupWithIdExists() returns true for an existing group.
     */
    public function testGroupWithIdExists()
    {
        self::assertTrue($this->groupMapper->groupWithIdExists(2));
    }

    /**
     * Tests that groupWithIdExists() returns false for a non-existent group.
     */
    public function testGroupWithIdExistsNotFound()
    {
        self::assertFalse($this->groupMapper->groupWithIdExists(9999));
    }

    /**
     * Tests that delete() removes a group by its id.
     */
    public function testDelete()
    {
        self::assertTrue($this->groupMapper->delete(1));

        self::assertNull($this->groupMapper->getGroupById(1));
        self::assertCount(2, $this->groupMapper->getGroupList());

        // The users_groups rows of the deleted group are removed by the FK cascade.
        self::assertSame([], $this->groupMapper->getUsersForGroup(1));
    }

    /**
     * Tests that delete() also accepts a group model.
     */
    public function testDeleteByModel()
    {
        $group = $this->groupMapper->getGroupById(2);

        self::assertTrue($this->groupMapper->delete($group));

        self::assertNull($this->groupMapper->getGroupById(2));
        self::assertCount(2, $this->groupMapper->getGroupList());
    }

    /**
     * Tests that delete() on a non-existent id returns false.
     */
    public function testDeleteNotFound()
    {
        self::assertFalse($this->groupMapper->delete(9999));

        self::assertCount(3, $this->groupMapper->getGroupList());
    }

    /**
     * Tests that getGroupAccessList() returns all access entries of a group.
     */
    public function testGetGroupAccessList()
    {
        $accessList = $this->groupMapper->getGroupAccessList(1);

        $expected = [
            'group_name' => 'Administrator',
            'entries' => [
                'page'    => [5 => 2],
                'module'  => ['forum' => 1],
                'article' => [9 => 0],
                'box'     => [7 => 3],
            ],
        ];

        self::assertEquals($expected, $accessList);
    }

    /**
     * Tests that getGroupAccessList() returns partial entries for a group
     * with only a few access rows.
     */
    public function testGetGroupAccessListPartial()
    {
        $accessList = $this->groupMapper->getGroupAccessList(2);

        $expected = [
            'group_name' => 'User',
            'entries' => [
                'page'    => [5 => 1],
                'module'  => [],
                'article' => [],
                'box'     => [],
            ],
        ];

        self::assertEquals($expected, $accessList);
    }

    /**
     * Tests that getGroupAccessList() returns empty entries for a group
     * without access rows.
     */
    public function testGetGroupAccessListNoEntries()
    {
        $accessList = $this->groupMapper->getGroupAccessList(9999);

        self::assertArrayNotHasKey('group_name', $accessList);
        self::assertEquals(['page' => [], 'module' => [], 'article' => [], 'box' => []], $accessList['entries']);
    }

    /**
     * Tests that getAccessAccessList() returns null for an invalid type.
     */
    public function testGetAccessAccessListInvalidType()
    {
        self::assertNull($this->groupMapper->getAccessAccessList('invalid', 1));
    }

    /**
     * Tests that getAccessAccessList() returns the access levels of the groups for a page.
     */
    public function testGetAccessAccessListPage()
    {
        $accessList = $this->groupMapper->getAccessAccessList('page', 5);

        self::assertNotNull($accessList);
        self::assertEquals('page', $accessList['Type']);
        self::assertEquals(5, $accessList['Id']);
        self::assertEquals([1 => 2, 2 => 1], $accessList['entries']);
    }

    /**
     * Tests that getAccessAccessList() returns the access levels of the groups for a module.
     */
    public function testGetAccessAccessListModule()
    {
        $accessList = $this->groupMapper->getAccessAccessList('module', 'forum');

        self::assertNotNull($accessList);
        self::assertEquals('module', $accessList['Type']);
        self::assertEquals('forum', $accessList['Id']);
        self::assertEquals([1 => 1, 3 => 3], $accessList['entries']);
    }

    /**
     * Tests that getAccessAccessList() returns the access levels of the groups for a box.
     */
    public function testGetAccessAccessListBox()
    {
        $accessList = $this->groupMapper->getAccessAccessList('box', 7);

        self::assertNotNull($accessList);
        self::assertEquals([1 => 3], $accessList['entries']);
    }

    /**
     * Tests that getAccessAccessList() returns empty entries when no group
     * has access to the given page.
     */
    public function testGetAccessAccessListNoEntries()
    {
        $accessList = $this->groupMapper->getAccessAccessList('page', 9999);

        self::assertNotNull($accessList);
        self::assertSame([], $accessList['entries']);
    }

    /**
     * Tests that saveAccessData() inserts a new module access entry.
     */
    public function testSaveAccessDataInsertModule()
    {
        self::assertTrue($this->groupMapper->saveAccessData(2, 'someModule', 2, 'module'));

        $accessList = $this->groupMapper->getGroupAccessList(2);
        self::assertEquals(['someModule' => 2], $accessList['entries']['module']);
    }

    /**
     * Tests that saveAccessData() inserts a new page access entry.
     */
    public function testSaveAccessDataInsertPage()
    {
        self::assertTrue($this->groupMapper->saveAccessData(3, 10, 1, 'page'));

        $accessList = $this->groupMapper->getAccessAccessList('page', 10);
        self::assertEquals([3 => 1], $accessList['entries']);
    }

    /**
     * Tests that saveAccessData() updates an existing access entry.
     */
    public function testSaveAccessDataUpdate()
    {
        self::assertTrue($this->groupMapper->saveAccessData(1, 5, 3, 'page'));

        $accessList = $this->groupMapper->getAccessAccessList('page', 5);
        self::assertEquals([1 => 3, 2 => 1], $accessList['entries']);
    }

    /**
     * Tests that getAccessLevel() always allows admins (level 2).
     */
    public function testGetAccessLevelAdmin()
    {
        $user = new User();
        $user->setId(1);
        $user->addGroup($this->groupMapper->getGroupById(1));

        self::assertSame(2, $this->groupMapper->getAccessLevel('module_forum', $user));
    }

    /**
     * Tests that getAccessLevel() returns the access level of the user's group.
     */
    public function testGetAccessLevelForGroup()
    {
        $user = new User();
        $user->setId(2);
        $user->addGroup($this->groupMapper->getGroupById(3));

        self::assertSame(3, $this->groupMapper->getAccessLevel('module_forum', $user));
    }

    /**
     * Tests that getAccessLevel() returns the default value when the user's
     * group has no access entry for the module.
     */
    public function testGetAccessLevelNoAccessReturnsDefault()
    {
        $user = new User();
        $user->setId(3);
        $user->addGroup($this->groupMapper->getGroupById(2));

        self::assertSame(1, $this->groupMapper->getAccessLevel('module_forum', $user));
    }

    /**
     * Tests that getAccessLevel() honors a custom default value.
     */
    public function testGetAccessLevelNoAccessReturnsCustomDefault()
    {
        $user = new User();
        $user->setId(3);
        $user->addGroup($this->groupMapper->getGroupById(2));

        self::assertSame(7, $this->groupMapper->getAccessLevel('module_forum', $user, 7));
    }

    /**
     * Returns database schema SQL statements to initialize database.
     *
     * @return string
     */
    protected static function getSchemaSQLQueries(): string
    {
        $config = new ModuleConfig();
        $configAdmin = new AdminConfig();

        return $configAdmin->getInstallSql() . $config->getInstallSql();
    }
}
