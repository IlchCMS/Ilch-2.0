<?php
/**
 * Holds Contact\Models\Receiver.
 *
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Contact\Models;
defined('ACCESS') or die('no direct access');

/**
 * The receiver model class.
 *
 * @package ilch
 */
class Receiver extends \Ilch\Model
{
    /**
     * The id of the receiver.
     *
     * @var int
     */
    protected $_id;

    /**
     * The name of the receiver.
     *
     * @var string
     */
    protected $_name;

    /**
     * The email of the receiver.
     *
     * @var string
     */
    protected $_email;

    /**
     * Gets the id of the receiver.
     *
     * @return int
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * Sets the id of the receiver.
     *
     * @param int $id
     * @return this
     */
    public function setId($id)
    {
        $this->_id = (int)$id;

        return $this;
    }

    /**
     * Gets the name of the receiver.
     *
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * Sets the name of the receiver.
     *
     * @param string $name
     * @return this
     */
    public function setName($name)
    {
        $this->_name = (string)$name;

        return $this;
    }

    /**
     * Gets the email of the receiver.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->_email;
    }

    /**
     * Sets the email of the receiver.
     *
     * @param string $email
     * @return this
     */
    public function setEmail($email)
    {
        $this->_email = (string)$email;

        return $this;
    }
}
