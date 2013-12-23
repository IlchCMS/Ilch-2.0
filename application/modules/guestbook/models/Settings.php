<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Guestbook\Models;

defined('ACCESS') or die('no direct access');

class Settings extends \Ilch\Model 
{
    /**
     * The id of the entry.
     *
     * @var int
     */
    protected $_id;
    
    /**
     * The email of the entry.
     *
     * @var int
     */
    protected $_email;
    
    /**
     * The text of the entry.
     *
     * @var int
     */
    protected $_text;
    
    /**
     * The name of the entry.
     *
     * @var int
     */
    protected $_name;
    
    /**
     * The homepage of the entry.
     *
     * @var int
     */
    protected $_homepage;
    
    /**
     * The datetime of the entry.
     *
     * @var int
     */
    protected $_datetime;
    
    /**
     * The settings of the entry.
     *
     * @var int
     */
    protected $_entrySettings;
    
    /**
     * The free of the entry.
     *
     * @var int
     */
    protected $_free;
    
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
     * @return integer
     */
    
    public function getEmail()
    {
        return $this->_email;
    }
    
    /**
     * Gets the text of the entry.
     *
     * @return integer
     */
    
    public function getText()
    {
        return $this->_text;
    }
    
    /**
     * Gets the name of the entry.
     *
     * @return integer
     */
    
    public function getName()
    {
        return $this->_name;
    }
    
    /**
     * Gets the homepage of the entry.
     *
     * @return integer
     */
    
    public function getHomepage()
    {
        return $this->_homepage;
    }
    
    /**
     * Gets the datetime of the entry.
     *
     * @return integer
     */
    
    public function getDatetime()
    {
        return $this->_datetime;
    }
    
    /**
     * Gets the settings of the entry.
     *
     * @return integer
     */
    
    public function getentrySettings()
    {
        return $this->_entrySettings;
    }
    
    /**
     * Gets the free of the entry.
     *
     * @return integer
     */
    
    public function getFree()
    {
        return $this->_free;
    }
    
    /**
     * Set the id of the entry.
     *
     * @return int
     */
    
    public function setId($id)
    {
        $this->_id = (string) $id;
    }
    
    /**
     * Set the Email of the entry.
     *
     * @return int
     */
    
    public function setEmail($email)
    {
        $this->_email = (string) $email;
    }
    
    /**
     * Set the text of the entry.
     *
     * @return int
     */
    
    public function setText($text)
    {
        $this->_text = (string) $text;
    }
    
    /**
     * Set the Name of the entry.
     *
     * @return int
     */
    
    public function setName($name)
    {
        $this->_name = (string) $name;
    }
    
    /**
     * Set the Homepage of the entry.
     *
     * @return int
     */
    
    public function setHomepage($homepage)
    {
        $this->_homepage = (string) $homepage;
    }
    
    /**
     * Set the datetime of the entry.
     *
     * @return int
     */
    
    public function setDatetime($datetime)
    {
        $this->_datetime = (string) $datetime;
    }
    
    /**
     * Set the entrySettings of the eGbook.
     *
     * @return int
     */
    
    public function setEntrySettings($entrySettings)
    {
        $this->_entrySettings = (string) $entrySettings;
    }
    
    /**
     * Set the free of the entry.
     *
     * @return int
     */
    
    public function setFree($free)
    {
        $this->_free = (string) $free;
    }
}