<?php
/**
 * Holds class User_UserModel.
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
class User_UserModel extends Ilch_Model
{
    /**
     * The id of the user.
     *
     * @var int
     */
    protected $_id = null;

    /**
     * The username.
     *
     * @var string
     */
    protected $_name = '';

    /**
     * The email address of the user.
     *
     * @var string
     */
    protected $_email = '';

    /**
     * The timestamp of when the user got created.
     *
     * @var int
     */
    protected $_dateCreated = 0;

    /**
     * The timestamp of when the user got confirmed.
     *
     * @var int
     */
    protected $_dateConfirmed = 0;

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
        $this->_id = (int)$id;
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
        $this->_name = (string)$username;
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
        $this->_email = (string)$email;
    }

    /**
     * Returns the date_created timestamp of the user.
     *
     * @return int
     */
    public function getDateCreated()
    {
        return $this->_dateCreated;
    }

    /**
     * Saves the date_created timestamp of the user.
     *
     * @param int|DateTime $dateCreated
     */
    public function setDateCreated($dateCreated)
    {
        if(is_a($dateCreated, 'DateTime'))
        {
            $dateCreated = $dateCreated->getTimestamp();
        }

        $this->_dateCreated = (int)$dateCreated;
    }

    /**
     * Returns the date_confirmed timestamp of the user.
     *
     * @return int
     */
    public function getDateConfirmed()
    {
        return $this->_dateConfirmed;
    }

    /**
     * Saves the date_confirmed timestamp of the user.
     *
     * @param int|DateTime $dateConfirmed
     */
    public function setDateConfirmed($dateConfirmed)
    {
        if(is_a($dateConfirmed, 'DateTime'))
        {
            $dateConfirmed = $dateConfirmed->getTimestamp();
        }

        $this->_dateConfirmed = (int)$dateConfirmed;
    }
}