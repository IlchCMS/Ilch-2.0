<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Guestbook\Models;

defined('ACCESS') or die('no direct access');

class Entry extends \Ilch\Model
{
    /**
     * The id of the entry.
     *
     * @var integer
     */
    protected $_id;

    /**
     * The email of the entry.
     *
     * @var string
     */
    protected $_email;

    /**
     * The text of the entry.
     *
     * @var string
     */
    protected $_text;

    /**
     * The name of the entry.
     *
     * @var string
     */
    protected $_name;

    /**
     * The homepage of the entry.
     *
     * @var string
     */
    protected $_homepage;

    /**
     * The datetime of the entry.
     *
     * @var string
     */
    protected $_datetime;

    /**
     * The setfee of the entry.
     *
     * @var integer
     */
    protected $_setFree;

    /**
     * Gets the id of the entry.
     *
     * @return integer
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * Gets the email of the entry.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->_email;
    }

    /**
     * Gets the text of the entry.
     *
     * @return string
     */
    public function getText()
    {
        return $this->_text;
    }

    /**
     * Gets the name of the entry.
     *
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * Gets the homepage of the entry.
     *
     * @return string
     */
    public function getHomepage()
    {
        return $this->_homepage;
    }

    /**
     * Gets the datetime of the entry.
     *
     * @return string
     */
    public function getDatetime()
    {
        return $this->_datetime;
    }

    /**
     * Gets the setfree of the entry.
     *
     * @return string
     */
    public function getFree()
    {
        return $this->_setFree;
    }

    /**
     * Sets the id of the entry.
     *
     * @param integer $id
     */
    public function setId($id)
    {
        $this->_id = (int)$id;
    }

    /**
     * Sets the email.
     *
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->_email = (string)$email;
    }

    /**
     * Sets the text of the entry.
     *
     * @param string $text
     */
    public function setText($text)
    {
        $this->_text = (string)$text;
    }

    /**
     * Sets the name of the entry.
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->_name = (string)$name;
    }

    /**
     * Sets the homepage of the entry.
     *
     * @param string $homepage
     */
    public function setHomepage($homepage)
    {
        $this->_homepage = (string)$homepage;
    }

    /**
     * Sets the datetime of the entry.
     *
     * @param string $datetime
     */
    public function setDatetime($datetime)
    {
        $this->_datetime = (string)$datetime;
    }

    /**
     * Sets the free of the entry.
     *
     * @param integer $free
     */
    public function setFree($free)
    {
        $this->_setFree = (integer)$free;
    }
}
