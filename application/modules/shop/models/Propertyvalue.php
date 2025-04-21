<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Shop\Models;

use Ilch\Model;

/**
 * The model for a property value.
 *
 * @since 1.4.0
 */
class Propertyvalue extends Model
{
    /**
     * The id of the value.
     *
     * @var int|null
     */
    protected ?int $id = null;

    /**
     * The id of the property.
     *
     * @var int|null
     */
    protected ?int $property_id = null;

    /**
     * The position of the value.
     *
     * @var int|null
     */
    protected ?int $position = 0;

    /**
     * The value.
     *
     * @var string
     */
    protected string $value = '';

    /**
     * Gets the id of the value.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Sets the id of the value.
     *
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * Gets the property id.
     *
     * @return int|null
     */
    public function getPropertyId(): ?int
    {
        return $this->property_id;
    }

    /**
     * Sets the property id.
     *
     * @param int|null $property_id
     * @return $this
     */
    public function setPropertyId(?int $property_id): Propertyvalue
    {
        $this->property_id = $property_id;
        return $this;
    }

    /**
     * Gets the value.
     *
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * Sets the value.
     *
     * @param string $value
     * @return $this
     */
    public function setValue(string $value): Propertyvalue
    {
        $this->value = $value;
        return $this;
    }

    /**
     * Get position of value.
     * This is used to determine the order of the values for a property.
     *
     * @return int|null
     */
    public function getPosition(): ?int
    {
        return $this->position;
    }

    /**
     * Set position of value.
     * This is used to determine the order of the values for a property.
     *
     * @param int|null $position
     * @return $this
     */
    public function setPosition(?int $position): Propertyvalue
    {
        $this->position = $position;
        return $this;
    }
}
