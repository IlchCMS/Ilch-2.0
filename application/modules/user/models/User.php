<?php
/**
 * Holds class User.
 *
 * @author Jainta Martin
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace User\Models;
defined('ACCESS') or die('no direct access');

/**
 * The user model class.
 *
 * @author Jainta Martin
 * @package ilch
 */
class User extends \Ilch\Model
{
    /**
     * The id of the user.
     *
     * @var int
     */
    protected $_id;

    /**
     * The username.
     *
     * @var string
     */
    protected $_name;

    /**
     * The email address of the user.
     *
     * @var string
     */
    protected $_email;

    /**
     * The password of the user.
     *
     * @var string
     */
    protected $_password;

    /**
     * The Ilch_Date of when the user got created.
     *
     * @var Ilch_Date
     */
    protected $_dateCreated;

    /**
     * The Ilch_Date of when the user got confirmed.
     *
     * @var Ilch_Date
     */
    protected $_dateConfirmed;

    /**
     * The associated user group object.
     *
     * @var User\Models\Group[]
     */
    protected $_groups = array();

    /**
     * Returns the id of the user.
     *
     * @return int
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * Saves the id of the user.
     *
     * @param int $id
     */
    public function setId($id)
    {
        $this->_id = (int) $id;
    }

    /**
     * Returns the username.
     *
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * Saves the username.
     *
     * @param string $username
     */
    public function setName($username)
    {
        $this->_name = (string) $username;
    }

    /**
     * Returns the email address of the user.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->_email;
    }

    /**
     * Saves the email address of the user.
     *
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->_email = (string) $email;
    }

    /**
     * Returns the password of the user.
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->_password;
    }

    /**
     * Saves the password of the user.
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->_password = (string) $password;
    }

    /**
     * Saves the groups of the user.
     *
     * @return array
     */
    public function getGroups()
    {
        return $this->_groups;
    }

    /**
     * Saves the groups of the user.
     *
     * @param array $groups
     */
    public function setGroups($groups)
    {
        $this->_groups = (array) $groups;
    }

    /**
     * Returns the date_created Ilch_Date of the user.
     *
     * @return Ilch_Date
     */
    public function getDateCreated()
    {
        return $this->_dateCreated;
    }

    /**
     * Saves the date_created Ilch_Date of the user.
     *
     * @param int|Ilch_Date|string $dateCreated
     */
    public function setDateCreated($dateCreated)
    {
        if (is_numeric($dateCreated)) {
            $timestamp = (int) $dateCreated;
            $dateCreated = new \Ilch\Date();
            $dateCreated->SetTimestamp($timestamp);
        } elseif (is_string($dateCreated)) {
            $dateCreated = new \Ilch\Date($dateCreated);
        } elseif (!is_a($dateCreated, '\\Ilch\\Date')) {
            throw new \InvalidArgumentException('DateCreated must be a timestamp, date-string or Ilch_Date, "'.$dateCreated.'" given.');
        }

        $this->_dateCreated = $dateCreated;
    }

    /**
     * Returns the date_confirmed timestamp of the user.
     *
     * @return Ilch_Date
     */
    public function getDateConfirmed()
    {
        return $this->_dateConfirmed;
    }

    /**
     * Saves the date_confirmed timestamp of the user.
     *
     * @param int|Ilch_Date|string $dateConfirmed
     */
    public function setDateConfirmed($dateConfirmed)
    {
        if (is_numeric($dateConfirmed)) {
            $timestamp = (int) $dateConfirmed;
            $dateConfirmed = new \Ilch\Date();
            $dateConfirmed->SetTimestamp($timestamp);
        } elseif (is_string($dateConfirmed)) {
            $dateConfirmed = new \Ilch\Date($dateConfirmed);
        } elseif (!is_a($dateConfirmed, '\\Ilch\\Date')) {
            throw new \InvalidArgumentException('DateConfirmed must be a timestamp, date-string or Ilch_Date, "'.$dateConfirmed.'" given.');
        }

        $this->_dateConfirmed = $dateConfirmed;
    }
}
