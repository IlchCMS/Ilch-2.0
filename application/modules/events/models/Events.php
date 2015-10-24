<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Events\Models;

class Events extends \Ilch\Model
{
    /**
     * The id of the event.
     *
     * @var int
     */
    protected $id;

    /**
     * The user of the event.
     *
     * @var int
     */
    protected $userId;

    /**
     * The start date of the event.
     *
     * @var string
     */
    protected $start;

    /**
     * The end date of the event.
     *
     * @var string
     */
    protected $end;

    /**
     * The title of the event.
     *
     * @var string
     */
    protected $title;

    /**
     * The place of the event.
     *
     * @var string
     */
    protected $place;

    /**
     * The image of the event.
     *
     * @var string
     */
    protected $image;

    /**
     * The text of the event.
     *
     * @var string
     */
    protected $text;

    /**
     * The show of the event.
     *
     * @var int
     */
    protected $show;

    /**
     * Gets the id of the event.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the id of the event.
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
     * Gets the user of the event.
     *
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Sets the userid of the event.
     *
     * @param int $userId
     * @return this
     */
    public function setUserId($userId)
    {
        $this->userId = (int)$userId;

        return $this;
    }

    /**
     * Gets the start date of the event.
     *
     * @return DateTime
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * Sets the start date of the event.
     *
     * @param DateTime $start
     * @return this
     */
    public function setStart($start)
    {
        $this->start = (string)$start;

        return $this;
    }

    /**
     * Gets the end date of the event.
     *
     * @return DateTime
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * Sets the end date of the event.
     *
     * @param DateTime $end
     * @return this
     */
    public function setEnd($end)
    {
        $this->end = (string)$end;

        return $this;
    }

    /**
     * Gets the title of the event.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets the title of the event.
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
     * Gets the place of the event.
     *
     * @return string
     */
    public function getPlace()
    {
        return $this->place;
    }

    /**
     * Sets the place of the event.
     *
     * @param string $place
     * @return this
     */
    public function setPlace($place)
    {
        $this->place = (string)$place;

        return $this;
    }

    /**
     * Gets the image of the event.
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Sets the image of the event.
     *
     * @param string $image
     * @return this
     */
    public function setImage($image)
    {
        $this->image = (string)$image;

        return $this;
    }

    /**
     * Gets the text of the event.
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Sets the text of the event.
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
     * Gets the show of the event.
     *
     * @return int
     */
    public function getShow()
    {
        return $this->show;
    }

    /**
     * Sets the show of the event.
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
