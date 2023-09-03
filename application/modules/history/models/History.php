<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\History\Models;

class History extends \Ilch\Model
{
    /**
     * The id of the history.
     *
     * @var int
     */
    protected $id = 0;

    /**
     * The date of the history.
     *
     * @var string
     */
    protected $date = '';

    /**
     * The title of the history.
     *
     * @var string
     */
    protected $title = '';

    /**
     * The type of the history.
     *
     * @var string
     */
    protected $type  = '';

    /**
     * The color of the history.
     *
     * @var string
     */
    protected $color = '';

    /**
     * The text of the history.
     *
     * @var string
     */
    protected $text = '';

    /**
     * @param array $entries
     * @return $this
     */
    public function setByArray(array $entries): History
    {
        if (isset($entries['id'])) {
            $this->setId($entries['id']);
        }
        if (isset($entries['date'])) {
            $this->setDate($entries['date']);
        }
        if (isset($entries['title'])) {
            $this->setTitle($entries['title']);
        }
        if (isset($entries['type'])) {
            $this->setType($entries['type']);
        }
        if (isset($entries['color'])) {
            $this->setColor($entries['color']);
        }
        if (isset($entries['text'])) {
            $this->setText($entries['text']);
        }
        return $this;
    }

    /**
     * Gets the id of the history.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Sets the id of the history.
     *
     * @param int $id
     * @return $this
     */
    public function setId(int $id): History
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Gets the date of the history.
     *
     * @return string
     */
    public function getDate(): string
    {
        return $this->date;
    }

    /**
     * Sets the date of the history.
     *
     * @param string $date
     * @return $this
     */
    public function setDate(string $date): History
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Gets the title of the history.
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Sets the title of the history.
     *
     * @param string $title
     * @return $this
     */
    public function setTitle(string $title): History
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Gets the type of the history.
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Sets the type of the history.
     *
     * @param string $type
     * @return $this
     */
    public function setType(string $type): History
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Gets the color of the history.
     *
     * @return string
     */
    public function getColor(): string
    {
        return $this->color;
    }

    /**
     * Sets the color of the history.
     *
     * @param string $color
     * @return $this
     */
    public function setColor(string $color): History
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Gets the text of the history.
     *
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * Sets the text of the history.
     *
     * @param string $text
     * @return $this
     */
    public function setText(string $text): History
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Gets the Array of Model.
     *
     * @param bool $withId
     * @return array
     */
    public function getArray(bool $withId = true): array
    {
        return array_merge(
            ($withId ? ['id' => $this->getId()] : []),
            [
                'date'  => $this->getDate(),
                'title' => $this->getTitle(),
                'type'  => $this->getType(),
                'color' => $this->getColor(),
                'text'  => $this->getText()
            ]
        );
    }
}
