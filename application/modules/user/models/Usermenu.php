<?php

/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\User\Models;

class Usermenu extends \Ilch\Model
{
    /**
     * Id of the menu.
     *
     * @var int|null
     */
    protected ?int $id = null;

    /**
     * Key of the menu.
     *
     * @var string|null
     */
    protected ?string $key = null;

    /**
     * Icon of the menu.
     *
     * @var string|null
     */
    protected ?string $icon = null;

    /**
     * Position of the menu.
     *
     * @var int|null
     */
    protected ?int $position = null;

    /**
     * Sets the menu id.
     *
     * @param int $id
     * @return Usermenu
     */
    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
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
     * Sets the menu key.
     *
     * @param string $key
     * @return Usermenu
     */
    public function setKey(string $key): static
    {
        $this->key = $key;

        return $this;
    }

    /**
     * Gets the menu key.
     *
     * @return string|null
     */
    public function getKey(): ?string
    {
        return $this->key;
    }

    /**
     * Sets the menu icon.
     *
     * @param string $icon
     * @return Usermenu
     */
    public function setIcon(string $icon): static
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * Gets the menu icon.
     *
     * @return string|null
     */
    public function getIcon(): ?string
    {
        return $this->icon;
    }

    /**
     * Sets the menu position.
     *
     * @param int $position
     * @return Usermenu
     */
    public function setPosition(int $position): static
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Gets the menu position.
     *
     * @return int|null
     */
    public function getPosition(): ?int
    {
        return $this->position;
    }
}
