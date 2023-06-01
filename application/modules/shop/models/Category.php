<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Shop\Models;

use Ilch\Model;

class Category extends Model
{
    /**
     * The id of the category.
     *
     * @var int|null
     */
    private $id;

    /**
     * The pos of the category.
     *
     * @var int
     */
    private $pos = 0;

    /**
     * The title of the category.
     *
     * @var string
     */
    private $title = '';

    /**
     * Value for read_access.
     *
     * @var string
     */
    private $read_access = '';

    /**
     * Gets the category id.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Sets the id of the category.
     *
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * Gets the category pos.
     *
     * @return int
     */
    public function getPos(): int
    {
        return $this->pos;
    }

    /**
     * Sets the pos of the category.
     *
     * @param int $pos
     */
    public function setPos(int $pos)
    {
        $this->pos = $pos;
    }

    /**
     * Gets the title of the category.
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Sets the title of the category.
     *
     * @param string $title
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    /**
     * Get the value for read_access.
     *
     * @return string
     */
    public function getReadAccess(): string
    {
        return $this->read_access;
    }

    /**
     * Set the value for read_access.
     *
     * @param string $read_access
     * @return $this
     */
    public function setReadAccess(string $read_access): Category
    {
        $this->read_access = $read_access;
        return $this;
    }
}
