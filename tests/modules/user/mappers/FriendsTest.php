<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\User\Mappers;

use Modules\Admin\Config\Config as AdminConfig;
use PHPUnit\Ilch\DatabaseTestCase;
use PHPUnit\Ilch\PhpunitDataset;
use Modules\User\Config\Config as ModuleConfig;
use Modules\User\Mappers\Friends as FriendsMapper;
use Modules\User\Models\Friend as FriendModel;

class FriendsTest extends DatabaseTestCase
{
    /**
     * @var FriendsMapper
     */
    protected Friends $out;
    protected PhpunitDataset $phpunitDataset;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database_user_friends.yml');
        $this->out = new FriendsMapper();
    }

    /**
     * Tests that getFriendsByUserId() returns the approved friends with the joined user data.
     */
    public function testGetFriendsByUserId()
    {
        $friends = $this->out->getFriendsByUserId(1);

        self::assertIsArray($friends);
        self::assertCount(1, $friends);
        self::assertInstanceOf(FriendModel::class, $friends[0]);
        self::assertEquals(1, $friends[0]->getId());
        self::assertEquals(1, $friends[0]->getUserId());
        self::assertEquals(2, $friends[0]->getFriendUserId());
        self::assertEquals('Testuser2', $friends[0]->getName());
        self::assertEquals('avatar2.png', $friends[0]->getAvatar());
    }

    /**
     * Tests that getFriendsByUserId() works for multiple users.
     */
    public function testGetFriendsByUserIdMultiple()
    {
        $friends = $this->out->getFriendsByUserId(3);
        self::assertCount(1, $friends);
        self::assertEquals(4, $friends[0]->getFriendUserId());
        self::assertEquals('Testuser4', $friends[0]->getName());

        $friends = $this->out->getFriendsByUserId(2);
        self::assertCount(1, $friends);
        self::assertEquals(1, $friends[0]->getFriendUserId());
        self::assertEquals('Testuser1', $friends[0]->getName());
    }

    /**
     * Tests that getFriendsByUserId() returns an empty array when the user has no friends.
     */
    public function testGetFriendsByUserIdNone()
    {
        $friends = $this->out->getFriendsByUserId(4);

        self::assertIsArray($friends);
        self::assertCount(0, $friends);
    }

    /**
     * Tests that getFriendsByUserId() returns an empty array for an unknown user.
     */
    public function testGetFriendsByUserIdUnknownUser()
    {
        $friends = $this->out->getFriendsByUserId(9999);

        self::assertIsArray($friends);
        self::assertCount(0, $friends);
    }

    /**
     * Tests that pending requests (approved = 2) are not counted as friends.
     */
    public function testGetFriendsExcludesPendingRequests()
    {
        // User 1 has a pending request to user 3, which must not appear here.
        $friends = $this->out->getFriendsByUserId(1);

        self::assertCount(1, $friends);
        self::assertEquals(2, $friends[0]->getFriendUserId());
    }

    /**
     * Tests that getOpenFriendRequests() returns the open requests with the joined requesters.
     */
    public function testGetOpenFriendRequests()
    {
        $requests = $this->out->getOpenFriendRequests(3);

        self::assertIsArray($requests);
        self::assertCount(1, $requests);
        self::assertInstanceOf(FriendModel::class, $requests[0]);
        self::assertEquals(3, $requests[0]->getId());
        self::assertEquals(1, $requests[0]->getUserId());
        self::assertEquals(3, $requests[0]->getFriendUserId());
        self::assertEquals('Testuser1', $requests[0]->getName());
    }

    /**
     * Tests that hasOpenFriendRequest() only detects open requests (approved = 2).
     */
    public function testHasOpenFriendRequest()
    {
        // User 1 sent an open request to user 3 (row: user_id = 1, friend_user_id = 3).
        self::assertTrue($this->out->hasOpenFriendRequest(1, 3));

        // The approved relation 1 -> 2 is not an open request.
        self::assertFalse($this->out->hasOpenFriendRequest(1, 2));

        // No request from user 2 to user 3.
        self::assertFalse($this->out->hasOpenFriendRequest(2, 3));

        // No self-requests.
        self::assertFalse($this->out->hasOpenFriendRequest(1, 1));
    }

    /**
     * Tests that getOpenFriendRequests() returns an empty array when there are no open requests.
     */
    public function testGetOpenFriendRequestsNone()
    {
        self::assertCount(0, $this->out->getOpenFriendRequests(1));
        self::assertCount(0, $this->out->getOpenFriendRequests(4));
        self::assertCount(0, $this->out->getOpenFriendRequests(9999));
    }

    /**
     * Tests that hasFriend() detects existing relations (approved and pending).
     */
    public function testHasFriend()
    {
        self::assertTrue($this->out->hasFriend(1, 2));
        self::assertTrue($this->out->hasFriend(2, 1));
        // A pending request already counts as an existing relation.
        self::assertTrue($this->out->hasFriend(1, 3));
        self::assertFalse($this->out->hasFriend(1, 4));
        self::assertFalse($this->out->hasFriend(4, 1));
    }

    /**
     * Tests that addFriend() inserts a new approved friendship.
     */
    public function testAddFriend()
    {
        $this->out->addFriend(4, 1, 1);

        self::assertTrue($this->out->hasFriend(4, 1));

        $friends = $this->out->getFriendsByUserId(4);
        self::assertCount(1, $friends);
        self::assertEquals(1, $friends[0]->getFriendUserId());
        self::assertEquals('Testuser1', $friends[0]->getName());
    }

    /**
     * Tests that addFriend() with the default $approved = 2 creates an open request only.
     */
    public function testAddFriendDefaultApprovedIsPending()
    {
        $this->out->addFriend(2, 3);

        self::assertTrue($this->out->hasFriend(2, 3));
        // Not yet an approved friendship.
        self::assertCount(1, $this->out->getFriendsByUserId(2));

        // User 3 already has the open request of user 1 from the dataset,
        // so the new request of user 2 is the second one.
        $requests = $this->out->getOpenFriendRequests(3);
        self::assertCount(2, $requests);

        $requesterIds = array_map(
            static fn (FriendModel $request): int => (int)$request->getUserId(),
            $requests
        );
        self::assertContains(1, $requesterIds);
        self::assertContains(2, $requesterIds);
    }

    /**
     * Tests that addFriend() does not create duplicates or modify existing relations.
     */
    public function testAddFriendDoesNotCreateDuplicates()
    {
        $this->out->addFriend(1, 2, 1); // Relation 1 -> 2 already exists.

        $friends = $this->out->getFriendsByUserId(1);
        self::assertCount(1, $friends);
        self::assertEquals(2, $friends[0]->getFriendUserId());
    }

    /**
     * Tests that addFriend() does not create a self-relation.
     */
    public function testAddFriendDoesNotAddSelf()
    {
        $this->out->addFriend(1, 1);

        self::assertFalse($this->out->hasFriend(1, 1));
        self::assertCount(1, $this->out->getFriendsByUserId(1));
    }

    /**
     * Tests that approveFriendRequest() approves only the requested direction.
     */
    public function testApproveFriendRequest()
    {
        // User 3 approves the pending request of user 1 (row: user_id = 1, friend_user_id = 3).
        $this->out->approveFriendRequest(3, 1);

        // The pending row belongs to user 1, so user 1 now has two friends (user 2 and user 3).
        $friends = $this->out->getFriendsByUserId(1);
        self::assertCount(2, $friends);

        // The other direction (3 -> 1) is not created by this call,
        // so user 3 still only has user 4 as friend.
        self::assertCount(1, $this->out->getFriendsByUserId(3));

        self::assertCount(0, $this->out->getOpenFriendRequests(3));
    }

    /**
     * Tests that approveFriendRequest() with swapped arguments updates nothing.
     */
    public function testApproveFriendRequestSwappedArguments()
    {
        // Would match user_id = 3, friend_user_id = 1, which does not exist.
        $this->out->approveFriendRequest(1, 3);

        self::assertCount(1, $this->out->getFriendsByUserId(1));
        self::assertCount(1, $this->out->getOpenFriendRequests(3));
    }

    /**
     * Tests that deleteFriendById() removes exactly one relation.
     */
    public function testDeleteFriendById()
    {
        $this->out->deleteFriendById(1);

        self::assertFalse($this->out->hasFriend(1, 2));
        self::assertTrue($this->out->hasFriend(2, 1));
    }

    /**
     * Tests that deleteFriendOfUser() removes only the given relation.
     */
    public function testDeleteFriendOfUser()
    {
        $this->out->deleteFriendOfUser(1, 2);

        self::assertFalse($this->out->hasFriend(1, 2));
        self::assertTrue($this->out->hasFriend(2, 1));

        // Other relations of the user remain.
        self::assertTrue($this->out->hasFriend(1, 3));
    }

    /**
     * Tests that deleteFriendByFriendUserId() removes all rows where the given user is the friend.
     */
    public function testDeleteFriendByFriendUserId()
    {
        $this->out->deleteFriendByFriendUserId(1);

        // Only the row 2 -> 1 has friend_user_id = 1.
        self::assertFalse($this->out->hasFriend(2, 1));
        self::assertTrue($this->out->hasFriend(1, 2));
    }

    /**
     * Tests that deleteFriendsByUserId() removes all relations of the user (approved and pending).
     */
    public function testDeleteFriendsByUserId()
    {
        $this->out->deleteFriendsByUserId(1);

        self::assertFalse($this->out->hasFriend(1, 2));
        self::assertFalse($this->out->hasFriend(1, 3));

        // The reverse direction (2 -> 1) remains, as the method only deletes user_id rows.
        self::assertTrue($this->out->hasFriend(2, 1));
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
