<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\User\Mappers;

use Modules\User\Models\User as UserModel;
use Modules\User\Mappers\Group as GroupMapper;
use Ilch\Date as IlchDate;

class User extends \Ilch\Mapper
{
    /**
     * Returns user model found by the id.
     *
     * @param  int $id
     * @return null|\Modules\User\Models\User
     * @throws \Ilch\Database\Exception
     */
    public function getUserById($id)
    {
        $where = [
            'id' => (int)$id,
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
     * @param  string $name
     * @return null|\Modules\User\Models\User
     * @throws \Ilch\Database\Exception
     */
    public function getUserByName($name)
    {
        $where = [
            'name' => (string)$name,
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
     * @param  string $email
     * @return null|\Modules\User\Models\User
     * @throws \Ilch\Database\Exception
     */
    public function getUserByEmail($email)
    {
        $where = [
            'email' => (string)$email,
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
     * @param  string $selector
     * @return null|\Modules\User\Models\User
     * @throws \Ilch\Database\Exception
     */
    public function getUserBySelector($selector)
    {
        $where = [
            'selector' => (string)$selector,
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
     * @param $groupId
     * @param int $confirmed
     * @param null $pagination
     * @return array
     * @since 2.1.20
     */
    public function getUserListByGroupId($groupId, $confirmed = 0, $pagination = null)
    {
        $select = $this->db()->select()
            ->fields(['u.id', 'u.name', 'u.opt_mail', 'u.date_created', 'u.date_last_activity', 'u.confirmed'])
            ->from(['u' => 'users'])
            ->join(['g' => 'users_groups'], 'u.id = g.user_id', 'LEFT', ['group_id' => 'g.group_id'])
            ->where(['group_id' => $groupId, 'confirmed' => $confirmed]);
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
     * @param  array $where
     * @param null $pagination
     * @return null|\Modules\User\Models\User[]
     * @throws \Ilch\Database\Exception
     */
    protected function getBy($where = [], $pagination = null)
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
     * @param  mixed[] $userRow
     * @return UserModel
     */
    public function loadFromArray($userRow = [])
    {
        $user = new UserModel();

        if (isset($userRow['id'])) {
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
            }  else {
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

        return $user;
    }

    /**
     * Inserts or updates a user model in the database.
     *
     * @param UserModel $user
     *
     * @return int The userId of the updated or inserted user.
     */
    public function save(UserModel $user)
    {
        $fields = [];
        $name = $user->getName();
        $password = $user->getPassword();
        $email = $user->getEmail();
        $dateConfirmed = $user->getDateConfirmed();
        $dateLastActivity = $user->getDateLastActivity();
        $dateCreated = $user->getDateCreated();
        $confirmed = $user->getConfirmed();
        $confirmedCode = $user->getConfirmedCode();
        $selector = $user->getSelector();

        if (!empty($name)) {
            $fields['name'] = $user->getName();
        }

        if (!empty($password)) {
            $fields['password'] = $user->getPassword();
        }

        if (!empty($email)) {
            $fields['email'] = $user->getEmail();
        }

        if (!empty($dateCreated)) {
            $fields['date_created'] = $user->getDateCreated();
        }

        if (!empty($dateConfirmed)) {
            $fields['date_confirmed'] = $user->getDateConfirmed();
        }

        if (!empty($dateLastActivity)) {
            $fields['date_last_activity'] = $user->getDateLastActivity();
        }

        if ($confirmed !== null) {
            $fields['confirmed'] = $confirmed;
        }

        if ($confirmedCode !== null) {
            $fields['confirmed_code'] = $confirmedCode;
        }

        if ($selector !== null) {
            $fields['selector'] = $selector;
        }

        if ($user->getExpires() !== null) {
            $fields['expires'] = $user->getExpires();
        }

        if ($user->getLocked() !== null) {
            $fields['locked'] = $user->getLocked();
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
        $fields['opt_gallery'] = $user->getOptGallery();

        $userId = (int)$this->db()->select('id')
            ->from('users')
            ->where(['id' => $user->getId()])
            ->execute()
            ->fetchCell();

        if ($userId) {
            /*
             * User does exist already, update.
             */
            $this->db()->update('users')
                ->values($fields)
                ->where(['id' => $userId])
                ->execute();
        } else {

            /*
             * User does not exist yet, insert.
             */
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

    /*
     * @param string $homepage
     * @return string
     */
    public function getHomepage($homepage) {
        $homepage = trim($homepage);
        if (preg_match('~^https?://~', $homepage) === 0) {
            $homepage = 'http://' . $homepage;
        }

        return $homepage;
    }

    /**
     * Gets the counter of all users with group "administrator".
     *
     * @return integer
     */
    public function getAdministratorCount()
    {
        return $this->db()->select('COUNT(*)', 'users_groups', ['group_id' => 1])
            ->execute()
            ->fetchCell();
    }

    /**
     * Returns a array of all user model objects.
     *
     * @param array|mixed $where
     * @param null $pagination
     * @return UserModel[]
     * @throws \Ilch\Database\Exception
     */
    public function getUserList($where = [], $pagination = null)
    {
        return $this->getBy($where, $pagination);
    }

    /**
     * Returns whether a user exists.
     *
     * @param  int $userId
     *
     * @return boolean True if a user with this id exists, false otherwise.
     */
    public function userWithIdExists($userId)
    {
        $userExists = (bool)$this->db()->select('id')
            ->from('users')
            ->where(['id' => $userId])
            ->execute()
            ->fetchCell();

        return $userExists;
    }

    /**
     * Add User to a Group.
     *
     * @param int $userId
     * @param int $groupId
     */
    public function addUserToGroup($userId, $groupId)
    {
        $groupMapper = new GroupMapper();

        if ($this->userWithIdExists($userId) AND $groupMapper->groupWithIdExists($groupId) AND !in_array($userId, $groupMapper->getUsersForGroup($groupId))) {
            $this->db()->insert('users_groups')
                ->values(['user_id' => $userId, 'group_id' => $groupId])
                ->execute();
        }
    }

    /**
     * Deletes a given user or a user with the given id.
     *
     * @param  int|UserModel $userId
     *
     * @return boolean True of success, otherwise false.
     */
    public function delete($userId)
    {
        if (is_a($userId, '\Modules\User\Models\User')) {
            $userId = $userId->getId();
        }

        $this->db()->delete('users_groups')
            ->where(['user_id' => $userId])
            ->execute();

        $this->db()->delete('profile_content')
            ->where(['user_id' => $userId])
            ->execute();

        $this->db()->delete('users_gallery_imgs')
            ->where(['user_id' => $userId])
            ->execute();

        $this->db()->delete('users_gallery_items')
            ->where(['user_id' => $userId])
            ->execute();

        $this->db()->delete('users_media')
            ->where(['user_id' => $userId])
            ->execute();

        return $this->db()->delete('users')
            ->where(['id' => $userId])
            ->execute();
    }

    public function getDummyUser()
    {
        $user = new UserModel();
        $groups = new GroupMapper();
        $user->setId('');
        $user->setName('No longer exist');
        $user->setAvatar('static/img/noavatar.jpg');
        //$user->setGroups(array(3));

        $user->setGroups($groups->getGroupById(3));

        return $user;
    }
}
