<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\User\Mappers;

use Modules\Statistic\Mappers\Statistic as StatisticMapper;
use Modules\User\Models\User as UserModel;
use Modules\User\Mappers\Group as GroupMapper;
use Ilch\Date as IlchDate;

class User extends \Ilch\Mapper
{
    /**
     * Returns user model found by the id.
     *
     * @param int $id
     * @return null|UserModel
     * @throws \Ilch\Database\Exception
     */
    public function getUserById(int $id): ?UserModel
    {
        $where = [
            'id' => $id,
        ];

        $users = $this->getBy($where);

        if (!empty($users)) {
            return reset($users);
        }

        return null;
    }

    /**
     * Returns user model found by the username.
     *
     * @param string $name
     * @return null|UserModel
     * @throws \Ilch\Database\Exception
     */
    public function getUserByName(string $name): ?UserModel
    {
        $where = [
            'name' => $name,
        ];

        $users = $this->getBy($where);

        if (!empty($users)) {
            return reset($users);
        }

        return null;
    }

    /**
     * Returns user model found by the email.
     *
     * @param string $email
     * @return null|UserModel
     * @throws \Ilch\Database\Exception
     */
    public function getUserByEmail(string $email): ?UserModel
    {
        $where = [
            'email' => $email,
        ];

        $users = $this->getBy($where);

        if (!empty($users)) {
            return reset($users);
        }

        return null;
    }

    /**
     * Returns user model found by the selector.
     *
     * @param string $selector
     * @return null|UserModel
     * @throws \Ilch\Database\Exception
     */
    public function getUserBySelector(string $selector): ?UserModel
    {
        $where = [
            'selector' => $selector,
        ];

        $users = $this->getBy($where);

        if (!empty($users)) {
            return reset($users);
        }

        return null;
    }

    /**
     * Get users (not all fields) by group id.
     *
     * @param int $groupId
     * @param int $confirmed
     * @param null $pagination
     * @return array
     * @since 2.1.20
     */
    public function getUserListByGroupId(int $groupId, int $confirmed = 0, $pagination = null): array
    {
        return $this->getUserListByGroupIds([$groupId], $confirmed, $pagination);
    }

    /**
     * Get users (not all fields) by group ids. The users have to be in at least one of the groups.
     *
     * @param int[] $groupIds array of group ids.
     * @param int $confirmed
     * @param $pagination
     * @return array
     * @since 2.2.7
     */
    public function getUserListByGroupIds(array $groupIds, int $confirmed = 0, $pagination = null): array
    {
        $select = $this->db()->select()
            ->fields(['u.id', 'u.name', 'u.opt_mail', 'u.date_created', 'u.date_last_activity', 'u.confirmed'])
            ->from(['u' => 'users'])
            ->join(['g' => 'users_groups'], 'u.id = g.user_id', 'LEFT', ['group_id' => 'g.group_id'])
            ->where(['group_id' => $groupIds], 'or')
            ->andWhere(['confirmed' => $confirmed])
            ->group(['u.id']);
        if ($pagination !== null) {
            $select->limit($pagination->getLimit())
                ->useFoundRows();
            $result = $select->execute();
            $pagination->setRows($result->getFoundRows());
        } else {
            $result = $select->execute();
        }

        if (!empty($select)) {
            $entryArray = $result->fetchRows();
            $users = [];

            foreach ($entryArray as $userRow) {
                $user = $this->loadFromArray($userRow);
                $users[] = $user;
            }

            return $users;
        }

        return [];
    }

    /**
     * Returns an array with user models found by the where clause of false if
     * none found.
     *
     * @param array $where
     * @param null $pagination
     * @return null|UserModel[]
     * @throws \Ilch\Database\Exception
     */
    protected function getBy(array $where = [], $pagination = null): ?array
    {
        $select = $this->db()->select('*')
            ->from('users')
            ->where($where);
        if ($pagination !== null) {
            $select->limit($pagination->getLimit())
                ->useFoundRows();
            $result = $select->execute();
            $pagination->setRows($result->getFoundRows());
        } else {
            $result = $select->execute();
        }

        if (!empty($select)) {
            $entryArray = $result->fetchRows();
            $users = [];

            foreach ($entryArray as $userRow) {
                $groups = [];
                $sql = 'SELECT g.*
                        FROM `[prefix]_groups` AS g
                        INNER JOIN `[prefix]_users_groups` AS ug ON g.id = ug.group_id
                        WHERE ug.user_id = ' . $userRow['id'];
                $groupRows = $this->db()->queryArray($sql);
                $groupMapper = new Group();

                foreach ($groupRows as $groupRow) {
                    $groups[$groupRow['id']] = $groupMapper->loadFromArray($groupRow);
                }

                $user = $this->loadFromArray($userRow);
                $user->setGroups($groups);
                $users[] = $user;
            }

            return $users;
        }

        return null;
    }

    /**
     * Returns a user created using an array with user data.
     *
     * @param array $userRow
     * @return UserModel
     */
    public function loadFromArray(array $userRow = []): UserModel
    {
        $user = new UserModel();

        if (!empty($userRow['id'])) {
            $user->setId($userRow['id']);
        }

        if (isset($userRow['name'])) {
            $user->setName($userRow['name']);
        }

        if (isset($userRow['email'])) {
            $user->setEmail($userRow['email']);
        }

        if (isset($userRow['first_name'])) {
            $user->setFirstName($userRow['first_name']);
        }

        if (isset($userRow['last_name'])) {
            $user->setLastName($userRow['last_name']);
        }

        if (isset($userRow['gender'])) {
            $user->setGender($userRow['gender']);
        }

        if (isset($userRow['city'])) {
            $user->setCity($userRow['city']);
        }

        if (isset($userRow['birthday'])) {
            $user->setBirthday($userRow['birthday']);
        }

        if (isset($userRow['avatar'])) {
            if (file_exists($userRow['avatar'])) {
                $user->setAvatar($userRow['avatar']);
            } else {
                $user->setAvatar('static/img/noavatar.jpg');
            }
        }

        if (isset($userRow['signature'])) {
            $user->setSignature($userRow['signature']);
        }

        if (isset($userRow['password'])) {
            $user->setPassword($userRow['password']);
        }

        if (isset($userRow['locale'])) {
            $user->setLocale($userRow['locale']);
        }

        if (isset($userRow['opt_mail'])) {
            $user->setOptMail($userRow['opt_mail']);
        }

        if (isset($userRow['opt_comments'])) {
            $user->setOptComments($userRow['opt_comments']);
        }

        if (isset($userRow['admin_comments'])) {
            $user->setAdminComments($userRow['admin_comments']);
        }

        if (isset($userRow['opt_gallery'])) {
            $user->setOptGallery($userRow['opt_gallery']);
        }

        if (isset($userRow['date_created'])) {
            $dateCreated = new IlchDate($userRow['date_created']);
            $user->setDateCreated($dateCreated);
        }

        if (isset($userRow['date_confirmed'])) {
            $dateConfirmed = new IlchDate($userRow['date_confirmed']);
            $user->setDateConfirmed($dateConfirmed);
        }

        if (isset($userRow['date_last_activity'])) {
            $dateLastActivity = new IlchDate($userRow['date_last_activity']);
            $user->setDateLastActivity($dateLastActivity);
        }

        if (isset($userRow['confirmed'])) {
            $user->setConfirmed($userRow['confirmed']);
        }

        if (isset($userRow['confirmed_code'])) {
            $user->setConfirmedCode($userRow['confirmed_code']);
        }

        if (isset($userRow['selector'])) {
            $user->setSelector($userRow['selector']);
        }

        if (isset($userRow['expires'])) {
            $user->setExpires($userRow['expires']);
        }

        if (isset($userRow['locked'])) {
            $user->setLocked($userRow['locked']);
        }
        if (isset($userRow['selectsdelete'])) {
            $user->setSelectsDelete($userRow['selectsdelete']);
        }

        return $user;
    }

    /**
     * Inserts or updates a user model in the database.
     *
     * @param UserModel $user
     *
     * @return int The userId of the updated or inserted user.
     */
    public function save(UserModel $user): int
    {
        $fields = [];

        if (!empty($user->getName())) {
            $fields['name'] = $user->getName();
        }

        if (!empty($user->getPassword())) {
            $fields['password'] = $user->getPassword();
        }

        if (!empty($user->getEmail())) {
            $fields['email'] = $user->getEmail();
        }

        if (!empty($user->getDateCreated())) {
            $fields['date_created'] = $user->getDateCreated();
        }

        if (!empty($user->getDateConfirmed())) {
            $fields['date_confirmed'] = $user->getDateConfirmed();
        }

        if (!empty($user->getDateLastActivity())) {
            $fields['date_last_activity'] = $user->getDateLastActivity();
        }

        if ($user->getConfirmed() !== null) {
            $fields['confirmed'] = $user->getConfirmed();
        }

        if ($user->getConfirmedCode() !== null) {
            $fields['confirmed_code'] = $user->getConfirmedCode();
        }

        if ($user->getSelector() !== null) {
            $fields['selector'] = $user->getSelector();
        }

        if ($user->getExpires() !== null) {
            $fields['expires'] = $user->getExpires();
        }

        if ($user->getLocked() !== null) {
            $fields['locked'] = $user->getLocked();
        }
        if ($user->getSelectsDelete() !== null) {
            $fields['selectsdelete'] = $user->getSelectsDelete();
        }

        $fields['first_name'] = $user->getFirstName();
        $fields['last_name'] = $user->getLastName();
        $fields['gender'] = $user->getGender();
        $fields['city'] = $user->getCity();
        $fields['birthday'] = $user->getBirthday();
        $fields['avatar'] = $user->getAvatar();
        $fields['signature'] = $user->getSignature();
        $fields['locale'] = $user->getLocale();
        $fields['opt_mail'] = $user->getOptMail();
        $fields['opt_comments'] = $user->getOptComments();
        $fields['admin_comments'] = $user->getAdminComments();
        $fields['opt_gallery'] = $user->getOptGallery();

        $userId = (int)$this->db()->select('id')
            ->from('users')
            ->where(['id' => $user->getId()])
            ->execute()
            ->fetchCell();

        if ($userId) {
            // User does exist already, update.
            $this->db()->update('users')
                ->values($fields)
                ->where(['id' => $userId])
                ->execute();
        } else {
            // User does not exist yet, insert.
            $userId = $this->db()->insert('users')
                ->values($fields)
                ->execute();
        }

        if ($user->getGroups()) {
            $this->db()->delete('users_groups')
                ->where(['user_id' => $userId])
                ->execute();

            foreach ($user->getGroups() as $group) {
                $this->db()->insert('users_groups')
                    ->values(['user_id' => $userId, 'group_id' => $group->getId()])
                    ->execute();
            }
        }

        return $userId;
    }

    /**
     * @param string $homepage
     * @return string
     */
    public function getHomepage(string $homepage): string
    {
        $homepage = trim($homepage);
        if (preg_match('~^https?://~', $homepage) === 0) {
            $homepage = 'http://' . $homepage;
        }

        return $homepage;
    }

    /**
     * Gets the counter of all users with group "administrator".
     *
     * @return int
     */
    public function getAdministratorCount(): int
    {
        return $this->db()->select('COUNT(*)', 'users_groups', ['group_id' => 1])
            ->execute()
            ->fetchCell();
    }

    /**
     * Returns a array of all user model objects.
     *
     * @param array $where
     * @param null $pagination
     * @return UserModel[]
     * @throws \Ilch\Database\Exception
     */
    public function getUserList(array $where = [], $pagination = null): ?array
    {
        return $this->getBy($where, $pagination);
    }

    /**
     * Returns whether a user exists.
     *
     * @param int $userId
     * @return bool True if a user with this id exists, false otherwise.
     */
    public function userWithIdExists(int $userId): bool
    {
        return (bool)$this->db()->select('id')
            ->from('users')
            ->where(['id' => $userId])
            ->execute()
            ->fetchCell();
    }

    /**
     * Add User to a Group.
     *
     * @param int $userId
     * @param int $groupId
     */
    public function addUserToGroup(int $userId, int $groupId)
    {
        $groupMapper = new GroupMapper();

        if ($this->userWithIdExists($userId) && $groupMapper->groupWithIdExists($groupId) && !\in_array($userId, $groupMapper->getUsersForGroup($groupId))) {
            $this->db()->insert('users_groups')
                ->values(['user_id' => $userId, 'group_id' => $groupId])
                ->execute();
        }
    }

    /**
     * Delete User to a Group.
     *
     * @param int $userId
     * @param int $groupId
     */
    public function deleteUserToGroup(int $userId, int $groupId)
    {
        $groupMapper = new GroupMapper();

        if ($this->userWithIdExists($userId) && $groupMapper->groupWithIdExists($groupId) && \in_array($userId, $groupMapper->getUsersForGroup($groupId))) {
            $this->db()->delete('users_groups')
                ->where(['user_id' => $userId, 'group_id' => $groupId])
                ->execute();
        }
    }

    /**
     * Selects a specific user or user with the specified ID to delete.
     *
     * @param  int|UserModel $userId
     * @param  string $deletedate
     * @return int affected rows
     */
    public function selectsdelete($userId, $deletedate = '1000-01-01 00:00:00'): int
    {
        if (is_a($userId, UserModel::class)) {
            $userId = $userId->getId();
        }

        return $this->db()->update('users')
                ->values(['selectsdelete' => $deletedate])
                ->where(['id' => $userId])
                ->execute();
    }

    /**
     * Delete all Selects Delete finally.
     *
     * @param int $timetodelete
     * @return bool True if success, otherwise false.
     */
    public function deleteselectsdelete(int $timetodelete = 5): bool
    {
        $date = new IlchDate();
        $statisticMapper = new StatisticMapper();

        $date->modify('-' . $timetodelete . ' days');
        $entries = $this->getUserList(['selectsdelete >' => '1000-01-01 00:00:00', 'selectsdelete <=' => $date]);

        foreach ($entries as $user) {
            if ($user->getSelectsDelete() != '') {
                $dateuser = new IlchDate($user->getSelectsDelete());
                if ($dateuser->getTimestamp() <= $date->getTimestamp()) {
                    $this->delete($user->getId());
                    $statisticMapper->deleteUserOnline($user->getId());
                }
            }
        }

        return true;
    }

    /**
     * Deletes a given user or a user with the given id.
     *
     * @param  int|UserModel $userId
     * @return bool True of success, otherwise false.
     */
    public function delete($userId): bool
    {
        if (is_a($userId, UserModel::class)) {
            $userId = $userId->getId();
        }

        // Groups, profile content, user gallery content and media connected to the user gets deleted due to FKCs.
        return $this->db()->delete('users')
            ->where(['id' => $userId])
            ->execute();
    }

    /**
     * Get the dummy user.
     *
     * @return UserModel
     */
    public function getDummyUser(): UserModel
    {
        $user = new UserModel();
        $groups = new GroupMapper();
        $user->setName('No longer exist');
        $user->setAvatar('static/img/noavatar.jpg');
        //$user->setGroups(array(3));

        $user->setGroups([$groups->getGroupById(3)]);

        return $user;
    }
}
