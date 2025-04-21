<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Shop\Models;

use Ilch\Model;

/**
 * The model for property.
 *
 * @since 1.4.0
 */
class Property extends Model
{
    /**
     * The id of the property.
     *
     * @var int|null
     */
    protected ?int $id = null;

    /**
     * The name of the property.
     *
     * @var string
     */
    protected string $name = '';

    /**
     * The status (enabled/disabled) of the property.
     *
     * @var bool
     */
    protected bool $enabled = false;

    /**
     * Gets the id of the property.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Sets the id of the property.
     *
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * Gets the name of the property.
     *
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Sets the name of the property.
     *
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * Returns true if this property is enabled.
     *
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * Sets the value for enabled.
     *
     * @param bool $enabled
     * @return void
     */
    public function setEnabled(bool $enabled): void
    {
        $this->enabled = $enabled;
    }
}
