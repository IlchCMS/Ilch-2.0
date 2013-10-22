<?php
/**
 * Holds class Group.
 *
 * @author Jainta Martin
 * @copyright Ilch Pluto
 * @package ilch
 */

namespace user\models;
defined('ACCESS') or die('no direct access');

/**
 * The user group model class.
 *
 * @author Jainta Martin
 * @package ilch
 */
class Group extends \Ilch\Mapper
{
    /**
     * The id of the user group.
     *
     * @var int
     */
    private $_id;

    /**
     * The name of the user group.
     *
     * @var string
     */
    private $_name = '';

    /**
     * The ids of the associated users.
     *
     * @var int[]
     */
    private $_users = array();

    /**
     * Returns the user group id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * Sets the user group id.
     *
     * @param int $id
     */
    public function setId($id)
    {
        $this->_id = (int) $id;
    }

    /**
     * Returns the user group name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * Sets the user group name.
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->_name = (string) $name;
    }

    /**
     * Returns the user ids of the group.
     *
     * @return int[]
     */
    public function getUsers()
    {
        return $this->_users;
    }

    /**
     * Sets the user ids of the group.
     *
     * @param int[] $users
     */
    public function setUsers($users)
    {
        $this->_users = (array) $users;
    }
}
