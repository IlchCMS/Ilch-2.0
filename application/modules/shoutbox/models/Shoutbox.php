<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Shoutbox\Models;

defined('ACCESS') or die('no direct access');

class Shoutbox extends \Ilch\Model
{
    /**
     * The id of the shoutbox.
     *
     * @var int
     */
    protected $_id;

    /**
     * The uid of the shoutbox.
     *
     * @var string
     */
    protected $_uid;

    /**
     * The name of the shoutbox.
     *
     * @var string
     */
    protected $_name;

    /**
     * The textarea of the shoutbox.
     *
     * @var string
     */
    protected $_textarea;

    /**
     * The time of the shoutbox.
     *
     * @var string
     */
    protected $_time;

    /**
     * Gets the id of the shoutbox.
     *
     * @return int
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * Sets the id of the shoutbox.
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
     * Gets the uid of the shoutbox.
     *
     * @return string
     */
    public function getUid()
    {
        return $this->_uid;
    }

    /**
     * Sets the uid of the shoutbox.
     *
     * @param string $uid
     * @return this
     */
    public function setUid($uid)
    {
        $this->_uid = (string)$uid;

        return $this;
    }

    /**
     * Gets the name of the shoutbox.
     *
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * Sets the name of the shoutbox.
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
     * Gets the textarea of the shoutbox.
     *
     * @return string
     */
    public function getTextarea()
    {
        return $this->_textarea;
    }

    /**
     * Sets the textarea of the shoutbox.
     *
     * @param string $textarea
     * @return this
     */
    public function setTextarea($textarea)
    {
        $this->_textarea = (string)$textarea;

        return $this;
    }

    /**
     * Gets the time of the shoutbox.
     *
     * @return string
     */
    public function getTime()
    {
        return $this->_time;
    }

    /**
     * Sets the time of the shoutbox.
     *
     * @param string $time
     * @return this
     */
    public function setTime($time)
    {
        $this->_time = (string)$time;

        return $this;
    }
}
