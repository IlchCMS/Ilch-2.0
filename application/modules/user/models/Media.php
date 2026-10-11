<?php

/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\User\Models;

class Media extends \Ilch\Model
{
    /**
     * The id of the media.
     *
     * @var int
     */
    protected $id;

    /**
     * The userid of the media.
     *
     * @var int
     */
    protected $userid;

    /**
     * The url of the media.
     *
     * @var string
     */
    protected $url;

    /**
     * The urlthumb of the media.
     *
     * @var string
     */
    protected $urlthumb;

    /**
     * The ending of the media.
     *
     * @var string
     */
    protected $ending;

    /**
     * The name of the media.
     *
     * @var string
     */
    protected $name;

    /**
     * The datetime of the media.
     *
     * @var string
     */
    protected $datetime;

    /**
     * Gets the id of the media.
     *
     * @return int
     */

    public function getId()
    {
        return $this->id;
    }

    /**
     * Gets the userid of the media.
     *
     * @return int
     */

    public function getUserId()
    {
        return $this->userid;
    }

    /**
     * Gets url of the media.
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Gets urlthumb of the media.
     *
     * @return string
     */
    public function getUrlThumb()
    {
        return $this->urlthumb;
    }

    /**
     * Gets ending of the media.
     *
     * @return string
     */
    public function getEnding()
    {
        return $this->ending;
    }

    /**
     * Gets name of the media.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Gets datetime of the media.
     *
     * @return string
     */
    public function getDatetime()
    {
        return $this->datetime;
    }

    /**
     * Sets the id of the media.
     *
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = (int)$id;

        return $this;
    }

    /**
     * Sets the userid of the media.
     *
     * @param int $userid
     * @return $this
     */
    public function setUserId($userid)
    {
        $this->userid = (int)$userid;

        return $this;
    }

    /**
     * Sets the url.
     *
     * @param string $url
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = (string)$url;

        return $this;
    }

    /**
     * Sets the urlthumb.
     *
     * @param string $urlthumb
     * @return $this
     */
    public function setUrlThumb($urlthumb)
    {
        $this->urlthumb = (string)$urlthumb;

        return $this;
    }

    /**
     * Sets the ending.
     *
     * @param string $ending
     * @return $this
     */
    public function setEnding($ending)
    {
        $this->ending = (string)$ending;

        return $this;
    }

    /**
     * Sets the name.
     *
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = (string)$name;

        return $this;
    }

    /**
     * Sets the datetime.
     *
     * @param string $datetime
     * @return $this
     */
    public function setDatetime($datetime)
    {
        $this->datetime = (string)$datetime;

        return $this;
    }
}
