<?php
/**
 * Holds class User.
 *
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace User\Mappers;

use User\Models\User as UserModel;
use Ilch\Date as IlchDate;

defined('ACCESS') or die('no direct access');

/**
 * The user mapper class.
 *
 * @package ilch
 */
class User extends \Ilch\Mapper
{
    /**
     * Returns user model found by the id.
     *
     * @param  mixed[]             $id
     * @return null|User_UserModel
     */
    public function getUserById($id)
    {
        $where = array
        (
            'id' => (int) $id,
        );

        $users = $this->_getBy($where);

        if (!empty($users)) {
            return reset($users);
        }

        return null;
    }

    /**
     * Returns user model found by the username.
     *
     * @param  string              $name
     * @return null|User_UserModel
     */
    public function getUserByName($name)
    {
        $where = array
        (
            'name' => (string) $name,
        );

        $users = $this->_getBy($where);

        if (!empty($users)) {
            return reset($users);
        }

        return null;
    }

    /**
     * Returns user model found by the email.
     *
     * @param  string              $email
     * @return null|User_UserModel
     */
    public function getUserByEmail($email)
    {
        $where = array
        (
            'email' => (string) $email,
        );

        $users = $this->_getBy($where);

        if (!empty($users)) {
            return reset($users);
        }

        return null;
    }

    /**
     * Returns an array with user models found by the where clause of false if
     * none found.
     *
     * @param  mixed[]             $where
     * @return null|User_UserModel
     */
    protected function _getBy($where = null)
    {
        $userRows = $this->db()->selectArray
        (
            '*',
            'users',
            $where
        );

        if (!empty($userRows)) {
            $users = array();

            foreach ($userRows as $userRow) {
                $groups = array();
                $sql = 'SELECT g.*
                        FROM [prefix]_groups AS g
                        INNER JOIN [prefix]_users_groups AS ug ON g.id = ug.group_id
                        WHERE ug.user_id = '.$userRow['id'];
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
     * @param  mixed[]   $userRow
     * @return UserModel
     */
    public function loadFromArray($userRow = array())
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

        if (isset($userRow['password'])) {
            $user->setPassword($userRow['password']);
        }

        if (isset($userRow['date_created'])) {
            $dateCreated = new IlchDate($userRow['date_created']);
            $users['date_created'] = $dateCreated;
            $user->setDateCreated($dateCreated);
        }

        if (isset($userRow['date_confirmed'])) {
            $dateConfirmed = new IlchDate($userRow['date_confirmed']);
            $users['date_confirmed'] = $dateConfirmed;
            $user->setDateConfirmed($dateConfirmed);
        }

        if (isset($userRow['date_last_activity'])) {
            $dateLastActivity = new IlchDate($userRow['date_last_activity']);
            $users['date_last_activity'] = $dateLastActivity;
            $user->setDateLastActivity($dateLastActivity);
        }

        return $user;
    }

    /**
     * Inserts or updates a user model in the database.
     *
     * @param UserModel $user
     *
     * @return The userId of the updated or inserted user.
     */
    public function save(UserModel $user)
    {
        $fields = array();
        $name = $user->getName();
        $password = $user->getPassword();
        $email = $user->getEmail();
        $dateConfirmed = $user->getDateConfirmed();
        $dateLastActivity = $user->getDateLastActivity();
        $dateCreated = $user->getDateCreated();

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
            $fields['date_created'] = $user->getDateCreated()->toDb();
        }

        if (!empty($dateConfirmed)) {
            $fields['date_confirmed'] = $user->getDateConfirmed()->toDb();
        }

        if (!empty($dateLastActivity)) {
            $fields['date_last_activity'] = $user->getDateLastActivity()->toDb();
        }

        $userId = (int) $this->db()->selectCell
        (
            'id',
            'users',
            array
            (
                'id' => $user->getId(),
            )
        );

        if ($userId) {
            /*
             * User does exist already, update.
             */
            $this->db()->update
            (
                $fields,
                'users',
                array
                (
                    'id' => $userId,
                )
            );
        } else {
            /*
             * User does not exist yet, insert.
             */
            $userId = $this->db()->insert
            (
                $fields,
                'users'
            );
        }

        if ($user->getGroups()) {
            $this->db()->delete
            (
                'users_groups',
                array('user_id' => $userId)
            );

            foreach ($user->getGroups() as $group) {
                $this->db()->insert
                (
                    array
                    (
                        'user_id' => $userId,
                        'group_id' => $group->getId()
                    ),
                    'users_groups'
                );
            }
        }

        return $userId;
    }

    /**
     * Gets the counter of all users with group "administrator".
     *
     * @return integer
     */
    public function getAdministratorCount()
    {
        $sql = 'SELECT COUNT(*)
                FROM `[prefix]_users_groups`
                WHERE `group_id` = 1';

        return (int)$this->db()->queryCell($sql);
    }

    /**
     * Returns a array of all user model objects.
     *
     * @return UserModel[]
     */
    public function getUserList()
    {
        return $this->_getBy();
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
        $userExists = (boolean) $this->db()->selectCell
        (
            'id',
            'users',
            array
            (
                'id' => $userId,
            )
        );

        return $userExists;
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
        if(is_a($userId, '\User\Models\User'))
        {
            $userId = $userId->getId();
        }

        $this->db()->delete('users_groups', array('user_id' => $userId));
        return $this->db()->delete('users', array('id' => $userId));
    }
}
