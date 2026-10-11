<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Admin\Models;

class Menu extends \Ilch\Model
{
    /**
     * Id of the menu.
     */
    protected ?int $id = null;

    /**
     * Title of the menu.
     */
    protected ?string $title = null;

    /**
     * Sets the menu id.
     *
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * Gets the menu id.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Sets the menu title.
     *
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * Gets the menu title.
     *
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }
}
