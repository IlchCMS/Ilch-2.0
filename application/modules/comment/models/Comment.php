<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Comment\Models;
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
    protected $id;

    /**
     * @var string
     */
    protected $key;

    /**
     * @var string
     */
    protected $text;

	/**
     * @var integer
     */
    protected $userId;
	
	/**
     * @var DateTime
     */
    protected $dateCreated;

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param integer $id
     * @return this
     */
    public function setId($id)
    {
        $this->id = (int)$id;

        return $this;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param string $key
     * @return this
     */
    public function setKey($key)
    {
        $this->key = (string)$key;

        return $this;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param string $text
     * @return this
     */
    public function setText($text)
    {
        $this->text = (string)$text;

        return $this;
    }
	
	/**
     * @return integer
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param integer $userId
     * @return this
     */
    public function setUserId($userId)
    {
        $this->userId = (int)$userId;

        return $this;
    }
	
	/**
     * @return DateTime
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * @param DateTime $dateCreated
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;
    }
}
