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
     * @param int $userId id of the user who wants to add a friend.
     * @param int $friendUserId id of the friend.
     * @param int $approved
     * @since 2.1.20
     */
    public function addFriend(int $userId, int $friendUserId, int $approved = 2): void
    {
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
     * Check if user already has a specific friend.
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
     * Approve the friend request.
     *
     * @param int $userId id of the user who wants to approve a friend request.
     * @param int $friendUserId id of the friend.
     * @since 2.1.20
     */
    public function approveFriendRequest(int $userId, int $friendUserId): void
    {
        $this->db()->update('user_friends')
            ->values(['approved' => 1])
            ->where(['friend_user_id' => $userId, 'user_id' => $friendUserId])
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
