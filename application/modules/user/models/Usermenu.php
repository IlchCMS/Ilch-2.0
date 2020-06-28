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
     * @var integer
     */
    protected $id;

    /**
     * Key of the menu.
     *
     * @var string
     */
    protected $key;

    /**
     * Icon of the menu.
     *
     * @var string
     */
    protected $icon;

    /**
     * Position of the menu.
     *
     * @var integer
     */
    protected $position;

    /**
     * Sets the menu id.
     *
     * @param integer $id
     * @return $this
     */
    public function setId($id): self
    {
        $this->id = (int)$id;

        return $this;
    }

    /**
     * Gets the menu id.
     *
     * @return integer
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Sets the menu key.
     *
     * @param int $key
     * @return $this
     */
    public function setKey($key): self
    {
        $this->key = (string)$key;

        return $this;
    }

    /**
     * Gets the menu key.
     *
     * @return int
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Sets the menu icon.
     *
     * @param string $icon
     * @return $this
     */
    public function setIcon($icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * Gets the menu icon.
     *
     * @return string
     */
    public function getIcon(): string
    {
        return $this->icon;
    }

    /**
     * Sets the menu position.
     *
     * @param int $position
     * @return $this
     */
    public function setPosition($position): self
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Gets the menu position.
     *
     * @return int
     */
    public function getPosition(): int
    {
        return $this->position;
    }
}
