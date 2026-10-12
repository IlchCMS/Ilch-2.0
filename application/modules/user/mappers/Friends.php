<?php

/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\User\Mappers;

use Modules\User\Models\Friend as FriendModel;

class Friends extends \Ilch\Mapper
{
    /**
     * Get friends.
     *
     * @param int $userId
     * @return array|FriendModel[]
     * @since 2.1.20
     */
    public function getFriendsByUserId(int $userId): array
    {
        $friendsRows = $this->db()->select()
            ->fields(['f.id', 'f.user_id', 'f.friend_user_id', 'f.approved'])
            ->from(['f' => 'user_friends'])
            ->join(['u' => 'users'], 'u.id = f.friend_user_id', 'LEFT', ['name' => 'u.name', 'avatar' => 'u.avatar'])
            ->where(['f.user_id' => $userId, 'f.approved' => 1])
            ->execute()
            ->fetchRows();

        if (empty($friendsRows)) {
            return [];
        }

        $friends = [];
        foreach ($friendsRows as $row) {
            $friendModel = new FriendModel();
            $friendModel->setId($row['id']);
            $friendModel->setUserId($row['user_id']);
            $friendModel->setFriendUserId($row['friend_user_id']);
            $friendModel->setName($row['name']);
            $friendModel->setAvatar($row['avatar']);
            $friends[] = $friendModel;
        }

        return $friends;
    }

    /**
     * Get open friend requests.
     *
     * @param int $userId
     * @return array|FriendModel[]
     * @since 2.1.20
     */
    public function getOpenFriendRequests(int $userId): array
    {
        $friendsRows = $this->db()->select()
            ->fields(['f.id', 'f.user_id', 'f.friend_user_id', 'f.approved'])
            ->from(['f' => 'user_friends'])
            ->join(['u' => 'users'], 'u.id = f.user_id', 'LEFT', ['name' => 'u.name'])
            ->where(['f.friend_user_id' => $userId, 'f.approved' => 2])
            ->execute()
            ->fetchRows();

        if (empty($friendsRows)) {
            return [];
        }

        $friends = [];
        foreach ($friendsRows as $row) {
            $friendModel = new FriendModel();
            $friendModel->setId($row['id']);
            $friendModel->setUserId($row['user_id']);
            $friendModel->setFriendUserId($row['friend_user_id']);
            $friendModel->setName($row['name']);
            $friends[] = $friendModel;
        }

        return $friends;
    }

    /**
     * Add friend.
     *
     * Inserts a new relation where $userId is the sender and $friendUserId is
     * the recipient of the request.
     *
     * If the user tries to add themselves, or the relation already exists
     * (approved or pending), nothing happens. This is intentional: a pending
     * request must not be turned into an approved friendship by the sender
     * alone. Approve such requests with approveFriendRequest() instead.
     *
     * Note that passing $approved = 1 bypasses the request flow and directly
     * creates an approved relation. Only call it that way in trusted contexts.
     *
     * @param int $userId id of the user who wants to add a friend.
     * @param int $friendUserId id of the friend.
     * @param int $approved 1 = approved, 2 = open request (default).
     * @since 2.1.20
     */
    public function addFriend(int $userId, int $friendUserId, int $approved = 2): void
    {
        if ($userId === $friendUserId) {
            return;
        }

        if ($this->hasFriend($userId, $friendUserId)) {
            return;
        }

        $fields = [
            'user_id' => $userId,
            'friend_user_id' => $friendUserId,
            'approved' => $approved
        ];

        $this->db()->insert('user_friends')
            ->values($fields)
            ->execute();
    }

    /**
     * Check if user already has a specific friend (approved or pending relation).
     *
     * @param int $userId
     * @param int $friendUserId
     * @return bool
     * @since 2.1.20
     */
    public function hasFriend(int $userId, int $friendUserId): bool
    {
        return (bool) $this->db()->select('COUNT(*)')
            ->from('user_friends')
            ->where(['user_id' => $userId, 'friend_user_id' => $friendUserId])
            ->execute()
            ->fetchCell();
    }

    /**
     * Check if the given user has an open friend request to another user.
     *
     * @param int $requesterId id of the user who sent the request.
     * @param int $approverId id of the user who received the request.
     * @return bool
     * @since 2.2.21
     */
    public function hasOpenFriendRequest(int $requesterId, int $approverId): bool
    {
        return (bool) $this->db()->select('COUNT(*)')
            ->from('user_friends')
            ->where(['user_id' => $requesterId, 'friend_user_id' => $approverId, 'approved' => 2])
            ->execute()
            ->fetchCell();
    }

    /**
     * Approve the friend request.
     *
     * $approverId is the id of the user who approves the request, i.e. the
     * recipient of the request (friend_user_id in the user_friends row).
     * $requesterId is the id of the user who sent the request (user_id in the
     * user_friends row).
     *
     * Example: approveFriendRequest(3, 1) approves the request user 1 sent to
     * user 3 (updates the row user_id = 1, friend_user_id = 3).
     * Swapping the two arguments matches no row and silently does nothing.
     *
     * @param int $approverId id of the user who approves the request.
     * @param int $requesterId id of the user who sent the request.
     * @since 2.1.20
     */
    public function approveFriendRequest(int $approverId, int $requesterId): void
    {
        $this->db()->update('user_friends')
            ->values(['approved' => 1])
            ->where(['friend_user_id' => $approverId, 'user_id' => $requesterId])
            ->execute();
    }

    /**
     * Delete friend by id.
     *
     * @param int $id
     * @since 2.1.20
     */
    public function deleteFriendById(int $id): void
    {
        $this->deleteFriend(['id' => $id]);
    }

    /**
     * Delete friend of user.
     *
     * @param int $userId id of user with the friend
     * @param int $friendUserId id of friend
     * @since 2.1.20
     */
    public function deleteFriendOfUser(int $userId, int $friendUserId): void
    {
        $this->deleteFriend(['user_id' => $userId, 'friend_user_id' => $friendUserId]);
    }

    /**
     * Delete friend by friend user id.
     *
     * Deletes all rows where the given user is the friend (recipient).
     * Use with care: this affects the friend lists of all users, not just one.
     *
     * @param int $friendUserId
     * @since 2.1.20
     */
    public function deleteFriendByFriendUserId(int $friendUserId): void
    {
        $this->deleteFriend(['friend_user_id' => $friendUserId]);
    }

    /**
     * Delete friend by user id.
     *
     * Deletes only the rows where the given user is the sender (user_id).
     * Rows where the user is only the recipient (friend_user_id) are kept.
     * This is intentional: unfriending is one-sided, and when the user
     * account is deleted, the foreign keys (ON DELETE CASCADE) remove the
     * remaining rows automatically.
     *
     * @param int $userId
     * @since 2.1.20
     */
    public function deleteFriendsByUserId(int $userId): void
    {
        $this->deleteFriend(['user_id' => $userId]);
    }

    /**
     * Delete friend.
     *
     * @param array $where
     * @since 2.1.20
     */
    private function deleteFriend(array $where = []): void
    {
        $this->db()->delete('user_friends')
            ->where($where)
            ->execute();
    }
}
