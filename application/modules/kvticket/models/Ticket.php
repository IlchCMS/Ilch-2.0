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
     * @var int|null
     */
    protected ?int $id = null;

    /**
     * The Title.
     *
     * @var string
     */
    protected string $title = '';

    /**
     * The Text.
     *
     * @var string
     */
    protected string $text = '';

    /**
     * The Status.
     *
     * @var int
     */
    protected int $status = 0;

    /**
     * The Editor.
     *
     * @var int|null
     */
    protected ?int $editor = null;

    /**
     * The Creator.
     *
     * @var int|null
     */
    protected ?int $creator = null;

    /**
     * The Category.
     *
     * @var int
     */
    protected int $cat;

    /**
     * The created at Datetime.
     *
     * @var string
     */
    protected string $created_at;

    /**
     * The updated at Datetime.
     *
     * @var string
     */
    protected string $updated_at;

    /**
     * Get the id.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Sets the id.
     *
     * @param int $id
     * @return $this
     */
    public function setId(int $id): Ticket
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Gets the Title.
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Sets the Title.
     *
     * @param string $title
     * @return $this
     */
    public function setTitle(string $title): Ticket
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Gets the Text.
     *
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * Sets the Text.
     *
     * @param string $text
     * @return $this
     */
    public function setText(string $text): Ticket
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Gets the Status.
     *
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * Sets the Status.
     *
     * @param int $status
     * @return $this
     */
    public function setStatus(int $status): Ticket
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Gets the Editor.
     *
     * @return int|null
     */
    public function getEditor(): ?int
    {
        return $this->editor;
    }

    /**
     * Sets the Editor.
     *
     * @param int $editor
     * @return $this
     */
    public function setEditor(int $editor): Ticket
    {
        $this->editor = $editor;

        return $this;
    }

    /**
     * Gets the Creator.
     *
     * @return int|null
     */
    public function getCreator(): ?int
    {
        return $this->creator;
    }

    /**
     * Sets the Creator.
     *
     * @param $creator
     * @return $this
     */
    public function setCreator($creator): Ticket
    {
        $this->creator = (int)$creator;

        return $this;
    }

    /**
     * Gets the Category.
     *
     * @return int
     */
    public function getCat(): int
    {
        return $this->cat;
    }

    /**
     * Sets the Category.
     *
     * @param int $cat
     * @return $this
     */
    public function setCat(int $cat): Ticket
    {
        $this->cat = $cat;

        return $this;
    }

    /**
     * Gets the created at Datetime.
     *
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    /**
     * Sets the created at Datetime.
     *
     * @param string $created_at
     * @return $this
     */
    public function setCreatedAt(string $created_at): Ticket
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * Gets the updated at Datetime.
     *
     * @return string
     */
    public function getUpdatedAt(): string
    {
        return $this->updated_at;
    }

    /**
     * Sets the updated at Datetime.
     *
     * @param string $updated_at
     * @return $this
     */
    public function setUpdatedAt(string $updated_at): Ticket
    {
        $this->updated_at = $updated_at;

        return $this;
    }
}
