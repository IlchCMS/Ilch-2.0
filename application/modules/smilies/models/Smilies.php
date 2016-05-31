<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Smilies\Models;

class Smilies extends \Ilch\Model
{
    /**
     * The id of the smilie.
     *
     * @var int
     */
    protected $id;

    /**
     * The name of the smilie.
     *
     * @var string
     */
    protected $name;

    /**
     * The url of the smilie.
     *
     * @var string
     */
    protected $url;

    /**
     * The urlthumb of the smilie.
     *
     * @var string
     */
    protected $urlthumb;

    /**
     * The ending of the smilie.
     *
     * @var string
     */
    protected $ending;

    /**
     * Gets the id of the smilie.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the id of the smilie.
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
     * Gets the name of the smilie.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the name of the smilie.
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
     * Gets the url of the smilie.
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Sets the url of the smilie.
     *
     * @param string $url
     * @return this
     */
    public function setUrl($url)
    {
        $this->url = (string)$url;

        return $this;
    }

    /**
     * Gets urlthumb of the smilie.
     *
     * @return string
     */
    public function getUrlThumb()
    {
        return $this->urlthumb;
    }

    /**
     * Sets the urlthumb of the smilie.
     *
     * @param string $urlthumb
     */
    public function setUrlThumb($urlthumb)
    {
        $this->urlthumb = (string)$urlthumb;
    }

    /**
     * Gets ending of the smilie.
     *
     * @return string
     */
    public function getEnding()
    {
        return $this->ending;
    }

    /**
     * Sets the ending of the smilie.
     *
     * @param string $ending
     */
    public function setEnding($ending)
    {
        $this->ending = (string)$ending;
    }
}
