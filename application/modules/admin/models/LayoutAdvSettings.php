<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Admin\Models;

class LayoutAdvSettings extends \Ilch\Model
{
    /**
     * @var int
     */
    private int $id = 0;

    /**
     * @var string
     */
    private string $layoutKey = '';

    /**
     * @var string
     */
    private string $key = '';

    /**
     * @var string
     */
    private string $value = '';

    /**
     * Get the id.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set the id.
     *
     * @param int $id
     * @return $this
     */
    public function setId(int $id): LayoutAdvSettings
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Get the layout key.
     *
     * @return string
     */
    public function getLayoutKey(): string
    {
        return $this->layoutKey;
    }

    /**
     * Set the layout key.
     *
     * @param string $layoutKey
     * @return $this
     */
    public function setLayoutKey(string $layoutKey): LayoutAdvSettings
    {
        $this->layoutKey = $layoutKey;
        return $this;
    }

    /**
     * Get the key.
     *
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * Set the key.
     *
     * @param string $key
     * @return $this
     */
    public function setKey(string $key): LayoutAdvSettings
    {
        $this->key = $key;
        return $this;
    }

    /**
     * Get the value.
     *
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * Set the value.
     *
     * @param string $value
     * @return $this
     */
    public function setValue(string $value): LayoutAdvSettings
    {
        $this->value = $value;
        return $this;
    }
}
