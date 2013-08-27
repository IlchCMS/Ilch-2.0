<?php
/**
 * Holds class User_UserMapper.
 *
 * @author Jainta Martin
 * @copyright Ilch CMS 2.0
 * @package ilch
 */

defined('ACCESS') or die('no direct access');

/**
 * The user model class.
 *
 * @author Jainta Martin
 * @package ilch
 */
class User_UserMapper extends Ilch_Mapper
{
    /**
     * Returns user model found by the id or false if none found.
     *
     * @param mixed[] $where
     * @return boolean|User_UserModel
     */
    public function getUserById($id)
    {
        $where = array
        (
            'id' => (int)$id,
        );
        $users = $this->_getBy($where);

        if($users)
        {
            return current($users);
        }
        else
        {
            return false;
        }
    }

    /**
     * Returns user model found by the username or false if none found.
     *
     * @param mixed[] $where
     * @return boolean|User_UserModel
     */
    public function getUserByName($name)
    {
        $where = array
        (
            'username' => (string)$name,
        );
        $users = $this->_getBy($where);

        if($users)
        {
            return current($users);
        }
        else
        {
            return false;
        }
    }

    /**
     * Returns an array with user models found by the where clause of false if
     * none found.
     *
     * @param mixed[] $where
     * @return boolean|User_UserModel
     */
    protected function _getBy($where = null)
    {
        $userRows = $this->getDatabase()->selectArray
        (
            '*',
            'users',
            $where
        );

        if($userRows)
        {
            $users = array();

            foreach($userRows as $userRow)
            {
                $users[] = $this->loadFromArray($userRow);
            }

            return $users;
        }
        else
        {
            return false;
        }
    }

    /**
     * Returns a user created using an array with user data.
     *
     * @param mixed[] $userRow
     * @return User_UserModel
     */
    public function loadFromArray($userRow = array())
    {
        $user = new User_UserModel();

        if(isset($userRow['id']))
        {
            $user->setId($userRow['id']);
        }

        if(isset($userRow['username']))
        {
            $user->setUsername($userRow['username']);
        }

        if(isset($userRow['email']))
        {
            $user->setEmail($userRow['email']);
        }

        if(isset($userRow['date_created']))
        {
            $user->setDateCreated($userRow['date_created']);
        }

        if(isset($userRow['date_confirmed']))
        {
            $user->setDateConfirmed($userRow['date_confirmed']);
        }

        return $user;
    }

    /**
     * Inserts or updates a user model in the database.
     *
     * @param User_UserModel $user
     */
    public function save(User_UserModel $user)
    {
        $fields = array
        (
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'date_created' => $user->getDateCreated(),
            'date_confirmed' => $user->getDateConfirmed(),
        );

        if($user->getId() && $this->getUserById($user->getId()))
        {
            /*
             * User does exist already, update.
             */
            $this->getDatabase()->update
            (
                $fields,
                'users',
                array
                (
                    'id' => $user->getId(),
                )
            );
        }
        else
        {
            /*
             * User does not exist yet, insert.
             */
            $this->getDatabase()->insert
            (
                $fields,
                'users'
            );
        }
    }
}