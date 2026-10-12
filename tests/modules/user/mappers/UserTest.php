<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\User\Mappers;

require APPLICATION_PATH . '/modules/user/config/config.php';

use PHPUnit\Ilch\DatabaseTestCase;
use PHPUnit\Ilch\PhpunitDataset;
use Modules\Admin\Config\Config as AdminConfig;
use Modules\User\Config\Config as ModuleConfig;
use Modules\User\Mappers\User as UserMapper;
use Modules\User\Mappers\Group as GroupMapper;
use Modules\User\Models\User as UserModel;
use Ilch\Date as IlchDate;

/**
 * Tests the user mapper class.
 *
 * @package ilch_phpunit
 */
class UserTest extends DatabaseTestCase
{
    /**
     * @var UserMapper
     */
    protected User $out;

    protected PhpunitDataset $phpunitDataset;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database.yml');
        $this->out = new UserMapper();
    }

    /**
     * Tests that getUserById() returns the correct user model.
     */
    public function testGetUserById()
    {
        $user = $this->out->getUserById(1);

        self::assertNotNull($user);
        self::assertInstanceOf(UserModel::class, $user);
        self::assertEquals(1, $user->getId());
        self::assertEquals('Testuser1', $user->getName());
        self::assertEquals('hash', $user->getPassword());
        self::assertEquals('testuser1@host.de', $user->getEmail());
        self::assertEquals(1, $user->getConfirmed());
        self::assertEquals('123', $user->getConfirmedCode());
        self::assertEquals('static/img/noavatar.jpg', $user->getAvatar());
        self::assertEquals(
            (new IlchDate('2014-01-01 12:12:12'))->getTimestamp(),
            $user->getDateCreated()->getTimestamp()
        );
        self::assertEquals(
            (new IlchDate('2014-01-01 12:12:12'))->getTimestamp(),
            $user->getDateConfirmed()->getTimestamp()
        );
    }

    /**
     * Tests that getUserById() returns the groups of the user.
     */
    public function testGetUserByIdWithGroups()
    {
        $user = $this->out->getUserById(1);

        self::assertNotNull($user);

        $groups = $user->getGroups();
        self::assertCount(2, $groups);
        self::assertArrayHasKey(1, $groups);
        self::assertArrayHasKey(3, $groups);
        self::assertEquals('Admin', $groups[1]->getName());
        self::assertEquals('Moderator', $groups[3]->getName());
    }

    /**
     * Tests that getUserById() returns null for a non-existent id.
     */
    public function testGetUserByIdNotFound()
    {
        self::assertNull($this->out->getUserById(9999));
    }

    /**
     * Tests that getUserByName() returns the correct user model.
     */
    public function testGetUserByName()
    {
        $user = $this->out->getUserByName('Testuser2');

        self::assertNotNull($user);
        self::assertEquals(2, $user->getId());
        self::assertEquals('Testuser2', $user->getName());
    }

    /**
     * Tests that getUserByName() returns null for an unknown name.
     */
    public function testGetUserByNameNotFound()
    {
        self::assertNull($this->out->getUserByName('UnknownUser'));
    }

    /**
     * Tests that getUserByEmail() returns the correct user model.
     */
    public function testGetUserByEmail()
    {
        $user = $this->out->getUserByEmail('admin@admin.admin');

        self::assertNotNull($user);
        self::assertEquals(3, $user->getId());
        self::assertEquals('admin', $user->getName());
    }

    /**
     * Tests that getUserByEmail() returns null for an unknown email.
     */
    public function testGetUserByEmailNotFound()
    {
        self::assertNull($this->out->getUserByEmail('unknown@host.de'));
    }

    /**
     * Tests that getUserByIds() returns the users keyed by their id.
     */
    public function testGetUserByIds()
    {
        $users = $this->out->getUserByIds([1, 3]);

        self::assertCount(2, $users);
        self::assertArrayHasKey(1, $users);
        self::assertArrayHasKey(3, $users);
        self::assertEquals('Testuser1', $users[1]->getName());
        self::assertEquals('admin', $users[3]->getName());
    }

    /**
     * Tests that getUserByIds() filters duplicates, invalid and unknown ids.
     */
    public function testGetUserByIdsFiltersInvalidIds()
    {
        $users = $this->out->getUserByIds([1, 1, 0, -5, 9999]);

        self::assertCount(1, $users);
        self::assertArrayHasKey(1, $users);
    }

    /**
     * Tests that getUserByIds() returns an empty array when no valid id is given.
     */
    public function testGetUserByIdsEmpty()
    {
        self::assertSame([], $this->out->getUserByIds([]));
        self::assertSame([], $this->out->getUserByIds([0]));
    }

    /**
     * Tests that getUserBySelector() returns the correct user model.
     */
    public function testGetUserBySelector()
    {
        $user = $this->out->getUserBySelector('selector1');

        self::assertNotNull($user);
        self::assertEquals(1, $user->getId());
        self::assertEquals('selector1', $user->getSelector());
    }

    /**
     * Tests that getUserBySelector() returns null for an unknown selector.
     */
    public function testGetUserBySelectorNotFound()
    {
        self::assertNull($this->out->getUserBySelector('does-not-exist'));
    }

    /**
     * Tests that getUserListByGroupId() returns the users of the group.
     */
    public function testGetUserListByGroupId()
    {
        /** @var UserModel[] $users */
        $users = $this->out->getUserListByGroupId(1, 1);

        self::assertNotEmpty($users);
        self::assertEquals(1, $users[0]->getId());
        self::assertEquals('Testuser1', $users[0]->getName());
        self::assertEquals(1, $users[0]->getConfirmed());
    }

    /**
     * Tests that getUserListByGroupIds() returns the users of at least one of the groups.
     */
    public function testGetUserListByGroupIds()
    {
        /** @var UserModel[] $users */
        $users = $this->out->getUserListByGroupIds([1, 3], 1);

        self::assertNotEmpty($users);
        self::assertCount(2, $users);

        self::assertEquals(1, $users[0]->getId());
        self::assertEquals('Testuser1', $users[0]->getName());
        self::assertEquals(1, $users[0]->getConfirmed());

        self::assertEquals(2, $users[1]->getId());
        self::assertEquals('Testuser2', $users[1]->getName());
        self::assertEquals(1, $users[1]->getConfirmed());
    }

    /**
     * Tests that getUserListByGroupIds() returns no users for unconfirmed users.
     */
    public function testGetUserListByGroupIdsUnconfirmed()
    {
        $users = $this->out->getUserListByGroupIds([1, 2, 3]);

        self::assertIsArray($users);
        self::assertCount(0, $users);
    }

    /**
     * Tests that getUserList() returns all users.
     */
    public function testGetUserList()
    {
        /** @var UserModel[] $users */
        $users = $this->out->getUserList();

        self::assertCount(4, $users);
        self::assertInstanceOf(UserModel::class, $users[0]);
        self::assertEquals(1, $users[0]->getId());
        self::assertEquals('Testuser1', $users[0]->getName());
        self::assertEquals(4, $users[3]->getId());
        self::assertEquals('user', $users[3]->getName());
    }

    /**
     * Tests that getUserList() filters by the where clause.
     */
    public function testGetUserListWithWhere()
    {
        /** @var UserModel[] $users */
        $users = $this->out->getUserList(['name' => 'admin']);

        self::assertCount(1, $users);
        self::assertEquals(3, $users[0]->getId());
        self::assertEquals('admin', $users[0]->getName());
    }

    /**
     * Tests that getUserList() returns an empty array when nothing matches.
     */
    public function testGetUserListEmpty()
    {
        $users = $this->out->getUserList(['name' => 'UnknownUser']);

        self::assertIsArray($users);
        self::assertCount(0, $users);
    }

    /**
     * Tests that loadFromArray() maps an array to a user model.
     */
    public function testLoadFromArray()
    {
        $user = $this->out->loadFromArray([
            'id' => 7,
            'name' => 'ArrayUser',
            'password' => 'hash',
            'email' => 'array@host.de',
            'first_name' => 'First',
            'last_name' => 'Last',
            'gender' => 1,
            'city' => 'Berlin',
            'birthday' => '1990-01-01',
            'avatar' => '',
            'signature' => 'Signature',
            'locale' => 'en_EN',
            'opt_mail' => 1,
            'opt_comments' => 1,
            'admin_comments' => 1,
            'opt_gallery' => 1,
            'confirmed' => 0,
            'confirmed_code' => 'code',
            'selector' => 'selector7',
            'locked' => 0,
        ]);

        self::assertEquals(7, $user->getId());
        self::assertEquals('ArrayUser', $user->getName());
        self::assertEquals('hash', $user->getPassword());
        self::assertEquals('array@host.de', $user->getEmail());
        self::assertEquals('First', $user->getFirstName());
        self::assertEquals('Last', $user->getLastName());
        self::assertEquals(1, $user->getGender());
        self::assertEquals('Berlin', $user->getCity());
        self::assertEquals('1990-01-01', $user->getBirthday());
        self::assertEquals('static/img/noavatar.jpg', $user->getAvatar());
        self::assertEquals('Signature', $user->getSignature());
        self::assertEquals('en_EN', $user->getLocale());
        self::assertEquals(0, $user->getConfirmed());
        self::assertEquals('code', $user->getConfirmedCode());
        self::assertEquals('selector7', $user->getSelector());
    }

    /**
     * Tests that loadFromArray() only sets the fields that are present in the array.
     */
    public function testLoadFromArrayWithMissingFields()
    {
        $user = $this->out->loadFromArray(['id' => 5, 'name' => 'MinimalUser']);

        self::assertEquals(5, $user->getId());
        self::assertEquals('MinimalUser', $user->getName());
    }

    /**
     * Tests that save() inserts a new user and returns its id.
     */
    public function testSaveInsert()
    {
        $user = new UserModel();
        $user->setName('NewUser')
            ->setPassword('hash')
            ->setEmail('newuser@host.de')
            ->setFirstName('New')
            ->setLastName('User')
            ->setGender(0)
            ->setCity('Hamburg')
            ->setBirthday('1990-01-01')
            ->setSignature('New signature')
            ->setLocale('de_DE')
            ->setOptMail(1)
            ->setOptComments(1)
            ->setAdminComments(1)
            ->setOptGallery(1)
            ->setDateCreated(new IlchDate('2020-01-01 10:00:00'))
            ->setDateConfirmed(new IlchDate('2020-01-01 10:00:00'))
            ->setDateLastActivity(new IlchDate('2020-01-01 10:00:00'))
            ->setConfirmed(1);

        $userId = $this->out->save($user);

        self::assertIsInt($userId);
        self::assertGreaterThan(0, $userId);
        self::assertEquals(5, $this->out->getUserCount());

        $newUser = $this->out->getUserById($userId);
        self::assertNotNull($newUser);
        self::assertEquals('NewUser', $newUser->getName());
        self::assertEquals('hash', $newUser->getPassword());
        self::assertEquals('newuser@host.de', $newUser->getEmail());
        self::assertEquals('New', $newUser->getFirstName());
        self::assertEquals('User', $newUser->getLastName());
        self::assertEquals(0, $newUser->getGender());
        self::assertEquals('Hamburg', $newUser->getCity());
        self::assertEquals('New signature', $newUser->getSignature());
        self::assertEquals('de_DE', $newUser->getLocale());
        self::assertEquals(1, $newUser->getConfirmed());
        self::assertCount(0, $newUser->getGroups());
    }

    /**
     * Tests that save() inserts a user together with its groups.
     */
    public function testSaveInsertWithGroups()
    {
        $groupMapper = new GroupMapper();

        $user = new UserModel();
        $user->setName('GroupUser')
            ->setPassword('hash')
            ->setEmail('groupuser@host.de')
            ->setDateCreated(new IlchDate('2020-01-01 10:00:00'))
            ->setDateConfirmed(new IlchDate('2020-01-01 10:00:00'))
            ->addGroup($groupMapper->getGroupById(2))
            ->addGroup($groupMapper->getGroupById(3));

        $userId = $this->out->save($user);

        self::assertGreaterThan(0, $userId);

        $newUser = $this->out->getUserById($userId);
        self::assertNotNull($newUser);

        $groups = $newUser->getGroups();
        self::assertCount(2, $groups);
        self::assertArrayHasKey(2, $groups);
        self::assertArrayHasKey(3, $groups);
    }

    /**
     * Tests that save() updates an existing user when the id is set.
     */
    public function testSaveUpdate()
    {
        $user = new UserModel();
        $user->setId(1)
            ->setName('UpdatedTestuser1')
            ->setPassword('hash')
            ->setEmail('updated@host.de')
            ->setCity('Munich')
            ->setDateCreated(new IlchDate('2020-01-01 10:00:00'))
            ->setDateConfirmed(new IlchDate('2020-01-01 10:00:00'));

        $userId = $this->out->save($user);

        self::assertEquals(1, $userId);

        $updated = $this->out->getUserById(1);
        self::assertNotNull($updated);
        self::assertEquals('UpdatedTestuser1', $updated->getName());
        self::assertEquals('updated@host.de', $updated->getEmail());
        self::assertEquals('Munich', $updated->getCity());

        // The other user should be unaffected.
        $other = $this->out->getUserById(2);
        self::assertNotNull($other);
        self::assertEquals('Testuser2', $other->getName());
        self::assertEquals('testuser1@host.de', $other->getEmail());
    }

    /**
     * Tests that save() without groups does not change the existing groups of the user.
     */
    public function testSaveUpdateKeepsGroupsWhenNotSet()
    {
        $user = new UserModel();
        $user->setId(1)
            ->setName('UpdatedTestuser1')
            ->setPassword('hash')
            ->setEmail('updated@host.de')
            ->setDateCreated(new IlchDate('2020-01-01 10:00:00'))
            ->setDateConfirmed(new IlchDate('2020-01-01 10:00:00'));

        $this->out->save($user);

        $updated = $this->out->getUserById(1);
        self::assertNotNull($updated);

        $groups = $updated->getGroups();
        self::assertCount(2, $groups);
        self::assertArrayHasKey(1, $groups);
        self::assertArrayHasKey(3, $groups);
    }

    /**
     * Tests that save() replaces the existing groups of the user when new groups are set.
     */
    public function testSaveUpdateWithGroups()
    {
        $groupMapper = new GroupMapper();

        $user = new UserModel();
        $user->setId(1)
            ->setName('UpdatedTestuser1')
            ->setPassword('hash')
            ->setEmail('updated@host.de')
            ->setDateCreated(new IlchDate('2020-01-01 10:00:00'))
            ->setDateConfirmed(new IlchDate('2020-01-01 10:00:00'))
            ->addGroup($groupMapper->getGroupById(2));

        $this->out->save($user);

        $updated = $this->out->getUserById(1);
        self::assertNotNull($updated);

        $groups = $updated->getGroups();
        self::assertCount(1, $groups);
        self::assertArrayHasKey(2, $groups);
    }

    /**
     * Tests that getHomepage() prepends http:// to plain domains.
     */
    public function testGetHomepageAddsProtocol()
    {
        self::assertEquals('http://example.com', $this->out->getHomepage('example.com'));
    }

    /**
     * Tests that getHomepage() keeps an existing protocol.
     */
    public function testGetHomepageKeepsProtocol()
    {
        self::assertEquals('http://example.com', $this->out->getHomepage('http://example.com'));
        self::assertEquals('https://example.com', $this->out->getHomepage('https://example.com'));
    }

    /**
     * Tests that getHomepage() trims whitespace before adding the protocol.
     */
    public function testGetHomepageTrimsWhitespace()
    {
        self::assertEquals('http://example.com', $this->out->getHomepage("  \t example.com \n "));
    }

    /**
     * Tests that getAdministratorCount() returns the number of users in the administrator group.
     */
    public function testGetAdministratorCount()
    {
        self::assertEquals(1, $this->out->getAdministratorCount());
    }

    /**
     * Tests that getUserCount() returns the number of users, optionally filtered.
     */
    public function testGetUserCount()
    {
        self::assertEquals(4, $this->out->getUserCount());
        self::assertEquals(1, $this->out->getUserCount(['id' => 1]));
        self::assertEquals(0, $this->out->getUserCount(['id' => 9999]));
    }

    /**
     * Tests that userWithIdExists() returns whether a user exists.
     */
    public function testUserWithIdExists()
    {
        self::assertTrue($this->out->userWithIdExists(1));
        self::assertFalse($this->out->userWithIdExists(9999));
    }

    /**
     * Tests that addUserToGroup() adds the user to the given group.
     */
    public function testAddUserToGroup()
    {
        $this->out->addUserToGroup(2, 1);

        $user = $this->out->getUserById(2);
        self::assertNotNull($user);

        $groups = $user->getGroups();
        self::assertCount(2, $groups);
        self::assertArrayHasKey(1, $groups);
        self::assertArrayHasKey(3, $groups);
    }

    /**
     * Tests that addUserToGroup() is idempotent and does not fail for unknown users or groups.
     */
    public function testAddUserToGroupIdempotentAndInvalid()
    {
        $this->out->addUserToGroup(1, 2);
        $this->out->addUserToGroup(1, 2); // must not create a duplicate
        $this->out->addUserToGroup(9999, 1); // unknown user
        $this->out->addUserToGroup(1, 9999); // unknown group

        $user = $this->out->getUserById(1);
        self::assertNotNull($user);

        $groups = $user->getGroups();
        self::assertCount(3, $groups);
        self::assertArrayHasKey(1, $groups);
        self::assertArrayHasKey(2, $groups);
        self::assertArrayHasKey(3, $groups);
    }

    /**
     * Tests that deleteUserToGroup() removes the user from the given group.
     */
    public function testDeleteUserToGroup()
    {
        $this->out->deleteUserToGroup(1, 3);

        $user = $this->out->getUserById(1);
        self::assertNotNull($user);

        $groups = $user->getGroups();
        self::assertCount(1, $groups);
        self::assertArrayHasKey(1, $groups);
    }

    /**
     * Tests that deleteUserToGroup() does not fail when the user is not in the group.
     */
    public function testDeleteUserToGroupNotInGroup()
    {
        $this->out->deleteUserToGroup(2, 1);

        $user = $this->out->getUserById(2);
        self::assertNotNull($user);

        $groups = $user->getGroups();
        self::assertCount(1, $groups);
        self::assertArrayHasKey(3, $groups);
    }

    /**
     * Tests that selectsdelete() marks a user by id for deletion.
     * The value is read directly from the database because loadFromArray()
     * passes the raw string to setSelectsDelete(), which expects an Ilch\Date.
     */
    public function testSelectsdelete()
    {
        $affected = $this->out->selectsdelete(1, '2020-05-05 00:00:00');

        self::assertEquals(1, $affected);

        $selectsDelete = $this->db->select('selectsdelete')
            ->from('users')
            ->where(['id' => 1])
            ->execute()
            ->fetchCell();

        self::assertEquals('2020-05-05 00:00:00', $selectsDelete);
    }

    /**
     * Tests that selectsdelete() accepts a user model.
     */
    public function testSelectsdeleteWithModel()
    {
        $user = $this->out->getUserById(2);
        self::assertNotNull($user);

        $affected = $this->out->selectsdelete($user, '2021-01-01 00:00:00');

        self::assertEquals(1, $affected);

        $selectsDelete = $this->db->select('selectsdelete')
            ->from('users')
            ->where(['id' => 2])
            ->execute()
            ->fetchCell();

        self::assertEquals('2021-01-01 00:00:00', $selectsDelete);
    }

    /**
     * Tests that selectsdelete() returns 0 for a non-existent id.
     */
    public function testSelectsdeleteNotFound()
    {
        self::assertEquals(0, $this->out->selectsdelete(9999));
    }

    /**
     * Tests that deleteselectsdelete() does not delete any user when nothing is due.
     * The branch that actually deletes a due user calls the statistic mapper and
     * requires the statistic module's tables, so it is not covered here.
     */
    public function testDeleteselectsdeleteDoesNothingWhenNothingIsDue()
    {
        self::assertTrue($this->out->deleteselectsdelete());

        self::assertEquals(4, $this->out->getUserCount());
    }

    /**
     * Tests that deleteselectsdelete() keeps users whose date is in the future.
     */
    public function testDeleteselectsdeleteKeepsUserWithFutureDate()
    {
        $this->out->selectsdelete(4, '2030-01-01 00:00:00');

        self::assertTrue($this->out->deleteselectsdelete());
        self::assertTrue($this->out->userWithIdExists(4));
    }

    /**
     * Tests that delete() removes a user by id.
     */
    public function testDelete()
    {
        $this->out->delete(1);

        self::assertNull($this->out->getUserById(1));
        self::assertFalse($this->out->userWithIdExists(1));
        self::assertEquals(3, $this->out->getUserCount());
        self::assertEquals('Testuser2', $this->out->getUserById(2)->getName());
    }

    /**
     * Tests that delete() accepts a user model.
     */
    public function testDeleteWithModel()
    {
        $user = $this->out->getUserById(3);
        self::assertNotNull($user);

        $this->out->delete($user);

        self::assertNull($this->out->getUserById(3));
        self::assertEquals(3, $this->out->getUserCount());
    }

    /**
     * Tests that delete() does not fail for a non-existent id.
     */
    public function testDeleteNotFound()
    {
        $this->out->delete(9999);

        self::assertEquals(4, $this->out->getUserCount());
    }

    /**
     * Tests that getDummyUser() returns a model with the guest group.
     */
    public function testGetDummyUser()
    {
        $dummy = $this->out->getDummyUser();

        self::assertInstanceOf(UserModel::class, $dummy);
        self::assertEquals('No longer exist', $dummy->getName());
        self::assertEquals('static/img/noavatar.jpg', $dummy->getAvatar());

        $groups = $dummy->getGroups();
        self::assertCount(1, $groups);
        self::assertEquals('Moderator', reset($groups)->getName());
    }

    /**
     * Returns database schema sql statements to initialize database.
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
