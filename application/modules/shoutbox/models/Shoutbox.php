<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Shoutbox\Models;

class Shoutbox extends \Ilch\Model
{
    /**
     * The id of the shoutbox.
     *
     * @var int
     */
    protected $id;

    /**
     * The uid of the shoutbox.
     *
     * @var string
     */
    protected $uid;

    /**
     * The name of the shoutbox.
     *
     * @var string
     */
    protected $name;

    /**
     * The textarea of the shoutbox.
     *
     * @var string
     */
    protected $textarea;

    /**
     * The time of the shoutbox.
     *
     * @var string
     */
    protected $time;

    /**
     * Gets the id of the shoutbox.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the id of the shoutbox.
     *
     * @param int $id
     * @return this
     */
    public function setId($id)
    {
        $this->id = (int)$id;

        return $this;
    }

    /**
     * Gets the uid of the shoutbox.
     *
     * @return string
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * Sets the uid of the shoutbox.
     *
     * @param string $uid
     * @return this
     */
    public function setUid($uid)
    {
        $this->uid = (string)$uid;

        return $this;
    }

    /**
     * Gets the name of the shoutbox.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the name of the shoutbox.
     *
     * @param string $name
     * @return this
     */
    public function setName($name)
    {
        $this->name = (string)$name;

        return $this;
    }

    /**
     * Gets the textarea of the shoutbox.
     *
     * @return string
     */
    public function getTextarea()
    {
        return $this->textarea;
    }

    /**
     * Sets the textarea of the shoutbox.
     *
     * @param string $textarea
     * @return this
     */
    public function setTextarea($textarea)
    {
        $this->textarea = (string)$textarea;

        return $this;
    }

    /**
     * Gets the time of the shoutbox.
     *
     * @return string
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Sets the time of the shoutbox.
     *
     * @param string $time
     * @return this
     */
    public function setTime($time)
    {
        $this->time = (string)$time;

        return $this;
    }
}
