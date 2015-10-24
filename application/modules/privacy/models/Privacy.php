<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Privacy\Models;

class Privacy extends \Ilch\Model
{
    /**
     * The id of the privacy.
     *
     * @var int
     */
    protected $id;

    /**
     * The title of the privacy.
     *
     * @var string
     */
    protected $title;

    /**
     * The urltitle of the privacy.
     *
     * @var string
     */
    protected $urltitle;

    /**
     * The url of the privacy.
     *
     * @var string
     */
    protected $url;

    /**
     * The text of the privacy.
     *
     * @var string
     */
    protected $text;

    /**
     * The show of the privacy.
     *
     * @var int
     */
    protected $show;

    /**
     * Gets the id of the privacy.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the id of the privacy.
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
     * Gets the title of the privacy.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets the title of the privacy.
     *
     * @param string $title
     * @return this
     */
    public function setTitle($title)
    {
        $this->title = (string)$title;

        return $this;
    }

    /**
     * Gets the urltitle of the privacy.
     *
     * @return string
     */
    public function getUrlTitle()
    {
        return $this->urltitle;
    }

    /**
     * Sets the urltitle of the privacy.
     *
     * @param string $urltitle
     * @return this
     */
    public function setUrlTitle($urltitle)
    {
        $this->urltitle = (string)$urltitle;

        return $this;
    }

    /**
     * Gets the url of the privacy.
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Sets the url of the privacy.
     *
     * @param string $url
     * @return this
     */
    public function setName($url)
    {
        $this->url = (string)$url;

        return $this;
    }

    /**
     * Gets the text of the privacy.
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Sets the text of the privacy.
     *
     * @param string $text
     * @return this
     */
    public function setText($text)
    {
        $this->text = (string)$text;

        return $this;
    }

    /**
     * Gets the show of the privacy.
     *
     * @return int
     */
    public function getShow()
    {
        return $this->show;
    }

    /**
     * Sets the show of the privacy.
     *
     * @param int $show
     * @return this
     */
    public function setShow($show)
    {
        $this->show = (int)$show;

        return $this;
    }
}
