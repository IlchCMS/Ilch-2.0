<?php
/**
 * @copyright Kevin Veldscholten
 * @package ilch
 */

namespace Modules\Kvticket\Models;

class Ticket extends \Ilch\Model
{
    /**
     * The Id.
     *
     * @var int
     */
    protected $id;

    /**
     * The Title.
     *
     * @var string
     */
    protected $title;

    /**
     * The Text.
     *
     * @var string
     */
    protected $text;

    /**
     * The Status.
     *
     * @var int
     */
    protected $status;

    /**
     * The Editor.
     *
     * @var int
     */
    protected $editor;

    /**
     * The Creator.
     *
     * @var int
     */
    protected $creator;

    /**
     * The Category.
     *
     * @var int
     */
    protected $cat;

    /**
     * The created at Datetime.
     *
     * @var string
     */
    protected $created_at;

    /**
     * The updated at Datetime.
     *
     * @var string
     */
    protected $updated_at;

    /**
     * Sets the Id.
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
     * Gets the Title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets the Title.
     *
     * @param string $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = (string)$title;

        return $this;
    }

    /**
     * Gets the Text.
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Sets the Text.
     *
     * @param string $text
     * @return $this
     */
    public function setText($text)
    {
        $this->text = (string)$text;

        return $this;
    }

    /**
     * Gets the Status.
     *
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Sets the Status.
     *
     * @param int $status
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = (int)$status;

        return $this;
    }

    /**
     * Gets the Editor.
     *
     * @return int
     */
    public function getEditor()
    {
        return $this->editor;
    }

    /**
     * Sets the Editor.
     *
     * @param int $editor
     * @return $this
     */
    public function setEditor($editor)
    {
        $this->editor = (int)$editor;

        return $this;
    }

    /**
     * Gets the Creator.
     *
     * @return int
     */
    public function getCreator()
    {
        return $this->creator;
    }

    /**
     * Sets the Creator.
     *
     * @param $creator
     * @return $this
     */
    public function setCreator($creator)
    {
        $this->creator = (int)$creator;

        return $this;
    }

    /**
     * Gets the Category.
     *
     * @return int
     */
    public function getCat()
    {
        return $this->cat;
    }

    /**
     * Sets the Category.
     *
     * @param int $cat
     * @return $this
     */
    public function setCat($cat)
    {
        $this->cat = (int)$cat;

        return $this;
    }

    /**
     * Gets the created at Datetime.
     *
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Sets the created at Datetime.
     *
     * @param string $created_at
     * @return $this
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = (string)$created_at;

        return $this;
    }

    /**
     * Gets the updated at Datetime.
     *
     * @return string
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * Sets the updated at Datetime.
     *
     * @param string $updated_at
     * @return $this
     */
    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = (string)$updated_at;

        return $this;
    }
}
