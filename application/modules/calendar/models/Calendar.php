<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Calendar\Models;

class Calendar extends \Ilch\Model
{
    /**
     * The id of the calendar.
     *
     * @var int
     */
    protected $id;

    /**
     * The title of the calendar.
     *
     * @var string
     */
    protected $title;

    /**
     * The place of the calendar.
     *
     * @var string
     */
    protected $place;

    /**
     * The start date of the calendar.
     *
     * @var string
     */
    protected $start;

    /**
     * The end date of the calendar.
     *
     * @var string
     */
    protected $end;

    /**
     * The text of the calendar.
     *
     * @var string
     */
    protected $text;

    /**
     * The color of the calendar.
     *
     * @var string
     */
    protected $color;

    /**
     * Gets the id of the calendar.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the id of the calendar.
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
     * Gets the title of the calendar.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets the title of the calendar.
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
     * Gets the place of the calendar.
     *
     * @return string
     */
    public function getPlace()
    {
        return $this->place;
    }

    /**
     * Sets the place of the calendar.
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
     * Gets the start date of the calendar.
     *
     * @return string
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * Sets the start date of the calendar.
     *
     * @param string $start
     * @return this
     */
    public function setStart($start)
    {
        $this->start = (string)$start;

        return $this;
    }

    /**
     * Gets the end date of the calendar.
     *
     * @return string
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * Sets the end date of the calendar.
     *
     * @param string $end
     * @return this
     */
    public function setEnd($end)
    {
        $this->end = (string)$end;

        return $this;
    }

    /**
     * Gets the text of the calendar.
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Sets the text of the calendar.
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
     * Gets the color of the calendar.
     *
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Sets the color of the calendar.
     *
     * @param string $color
     * @return this
     */
    public function setColor($color)
    {
        $this->color = (string)$color;

        return $this;
    }
}
