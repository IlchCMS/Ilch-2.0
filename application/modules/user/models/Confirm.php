<?php
/**
 * Holds class User.
 *
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace User\Models;

defined('ACCESS') or die('no direct access');

/**
 * The user model class.
 *
 * @package ilch
 */
class Confirm extends \Ilch\Model
{
    /**
     * The user check.
     *
     * @var string
     */
    protected $_check;
    
    /**
     * The user name.
     *
     * @var string
     */
    protected $_name;
    
    /**
     * The user email.
     *
     * @var string
     */
    protected $_email;
    
    /**
     * The user password.
     *
     * @var string
     */
    protected $_password;
    
    /**
     * The user date created.
     *
     * @var string
     */
    protected $_date_created;

    /**
     * Returns the user check.
     *
     * @return string
     */
    public function getCheck()
    {
        return $this->_check;
    }

    /**
     * Sets the user check.
     *
     * @param string $id
     */
    public function setCheck($check)
    {
        $this->_check = (string) $check;
    }

    /**
     * Returns the user name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * Sets the user name.
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->_name = (string) $name;
    }

    /**
     * Returns the user email.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->_email;
    }

    /**
     * Sets the user email.
     *
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->_email = (string) $email;
    }

    /**
     * Returns the user password.
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->_password;
    }

    /**
     * Sets the user password.
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->_password = (string) $password;
    }

    /**
     * Returns the user date created.
     *
     * @return string
     */
    public function getDateCreated()
    {
        return $this->_date_created;
    }

    /**
     * Sets the user date created.
     *
     * @param string $date_created
     */
    public function setDateCreated($date_created)
    {
        $this->_date_created = (string) $date_created;
    }
}
