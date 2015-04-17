<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Training\Models;

defined('ACCESS') or die('no direct access');

class Training extends \Ilch\Model
{
    /**
     * The id of the training.
     *
     * @var int
     */
    protected $id;

    /**
     * The title of the training.
     *
     * @var string
     */
    protected $title;

    /**
     * The date of the training.
     *
     * @var string
     */
    protected $date;

    /**
     * The place of the training.
     *
     * @var string
     */
    protected $place;

    /**
     * The text of the training.
     *
     * @var string
     */
    protected $text;

    /**
     * Gets the id of the training.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the id of the training.
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
     * Gets the title of the training.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets the title of the training.
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
     * Gets the date of the training.
     *
     * @return string
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Sets the date of the training.
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
     * Gets the place of the training.
     *
     * @return string
     */
    public function getPlace()
    {
        return $this->place;
    }

    /**
     * Sets the place of the training.
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
     * Gets the text of the training.
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Sets the text of the training.
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
