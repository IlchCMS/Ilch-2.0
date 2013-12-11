<?php
/**
 * Holds class Group.
 *
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace User\Models;

defined('ACCESS') or die('no direct access');

/**
 * The user group model class.
 *
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
}
