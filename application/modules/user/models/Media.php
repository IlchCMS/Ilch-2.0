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
     * @return integer
     */

    public function getId()
    {
        return $this->id;
    }

    /**
     * Gets the userid of the media.
     *
     * @return integer
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
     * @param integer $id
     */
    public function setId($id)
    {
        $this->id = (int)$id;
    }

    /**
     * Sets the userid of the media.
     *
     * @param integer $userid
     */
    public function setUserId($userid)
    {
        $this->userid = (int)$userid;
    }

    /**
     * Sets the url.
     *
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = (string)$url;
    }

    /**
     * Sets the urlthumb.
     *
     * @param string $urlthumb
     */
    public function setUrlThumb($urlthumb)
    {
        $this->urlthumb = (string)$urlthumb;
    }

    /**
     * Sets the ending.
     *
     * @param string $ending
     */
    public function setEnding($ending)
    {
        $this->ending = (string)$ending;
    }

    /**
     * Sets the name.
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = (string)$name;
    }

    /**
     * Sets the datetime.
     *
     * @param string $datetime
     */
    public function setDatetime($datetime)
    {
        $this->datetime = (string)$datetime;
    }
}
