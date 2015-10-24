<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\History\Models;

class Entry extends \Ilch\Model
{
    /**
     * The id of the history.
     *
     * @var int
     */
    protected $id;

    /**
     * The date of the history.
     *
     * @var string
     */
    protected $date;

    /**
     * The title of the history.
     *
     * @var string
     */
    protected $title;

    /**
     * The color of the history.
     *
     * @var string
     */
    protected $color;

    /**
     * The typ of the history.
     *
     * @var string
     */
    protected $typ;

    /**
     * The text of the history.
     *
     * @var string
     */
    protected $text;

    /**
     * Gets the id of the history.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the id of the history.
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
     * Gets the date of the history.
     *
     * @return string
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Sets the date of the history.
     *
     * @param string $date
     * @return this
     */
    public function setDate($date)
    {
        $this->date = (string)$date;

        return $this;
    }

    /**
     * Gets the title of the history.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets the title of the history.
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
     * Gets the color of the history.
     *
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Sets the color of the history.
     *
     * @param string $color
     * @return this
     */
    public function setColor($color)
    {
        $this->color = (string)$color;

        return $this;
    }

    /**
     * Gets the typ of the history.
     *
     * @return string
     */
    public function getTyp()
    {
        return $this->typ;
    }

    /**
     * Sets the typ of the history.
     *
     * @param string $typ
     * @return this
     */
    public function setTyp($typ)
    {
        $this->typ = (string)$typ;

        return $this;
    }

    /**
     * Gets the text of the history.
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Sets the text of the history.
     *
     * @param string $text
     * @return this
     */
    public function setText($text)
    {
        $this->text = (string)$text;

        return $this;
    }
}
