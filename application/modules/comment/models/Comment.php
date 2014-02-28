<?php
/**
 * Holds Comment\Models\Comment.
 *
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Comment\Models;
defined('ACCESS') or die('no direct access');

/**
 * The comment model class.
 *
 * @package ilch
 */
class Comment extends \Ilch\Model
{
    /**
     * @var integer
     */
    protected $_id;

    /**
     * @var string
     */
    protected $_key;

    /**
     * @var string
     */
    protected $_text;

	/**
     * @var integer
     */
    protected $_userId;
	
	/**
     * @var DateTime
     */
    protected $_dateCreated;

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @param integer $id
     * @return this
     */
    public function setId($id)
    {
        $this->_id = (int)$id;

        return $this;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->_key;
    }

    /**
     * @param string $key
     * @return this
     */
    public function setKey($key)
    {
        $this->_key = (string)$key;

        return $this;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->_text;
    }

    /**
     * @param string $text
     * @return this
     */
    public function setText($text)
    {
        $this->_text = (string)$text;

        return $this;
    }
	
	/**
     * @return integer
     */
    public function getUserId()
    {
        return $this->_userId;
    }

    /**
     * @param integer $userId
     * @return this
     */
    public function setUserId($userId)
    {
        $this->_userId = (int)$userId;

        return $this;
    }
	
	/**
     * @return DateTime
     */
    public function getDateCreated()
    {
        return $this->_dateCreated;
    }

    /**
     * @param DateTime $dateCreated
     */
    public function setDateCreated($dateCreated)
    {
        $this->_dateCreated = $dateCreated;
    }
}
