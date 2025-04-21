<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Shop\Models;

use Ilch\Model;

/**
 * The model for a product/item variant.
 * These are the information needed to keep track of a product variant.
 *
 * @since 1.4.0
 */
class Propertyvariant extends Model
{
    /**
     * The id of the variant.
     *
     * @var int|null
     */
    protected ?int $id = null;

    /**
     * The item id of the variant.
     *
     * @var int
     */
    protected int $item_id;

    /**
     * The item variant id.
     *
     * @var int
     */
    protected int $item_variant_id;

    /**
     * The property id.
     *
     * @var int
     */
    protected int $property_id;

    /**
     * The value id.
     *
     * @var int
     */
    protected int $value_id;

    /**
     * Gets the id of the variant.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Sets the id of the variant.
     *
     * @param int|null $id
     * @return $this
     */
    public function setId(?int $id): Propertyvariant
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Gets the item id of the variant.
     *
     * @return int
     */
    public function getItemId(): int
    {
        return $this->item_id;
    }

    /**
     * Sets the item id of the variant.
     *
     * @param int $item_id
     * @return $this
     */
    public function setItemId(int $item_id): Propertyvariant
    {
        $this->item_id = $item_id;
        return $this;
    }

    /**
     * Gets the item variant id of the variant.
     * @return int
     */
    public function getItemVariantId(): int
    {
        return $this->item_variant_id;
    }

    /**
     * Sets the item variant id of the variant.
     * @param int $item_variant_id
     * @return $this
     */
    public function setItemVariantId(int $item_variant_id): Propertyvariant
    {
        $this->item_variant_id = $item_variant_id;
        return $this;
    }

    /**
     * Gets the property id.
     *
     * @return int
     */
    public function getPropertyId(): int
    {
        return $this->property_id;
    }

    /**
     * Sets the property id.
     *
     * @param int $property_id
     * @return $this
     */
    public function setPropertyId(int $property_id): Propertyvariant
    {
        $this->property_id = $property_id;
        return $this;
    }

    /**
     * Gets the value id.
     *
     * @return int
     */
    public function getValueId(): int
    {
        return $this->value_id;
    }

    /**
     * Sets the value id.
     *
     * @param int $value_id
     * @return $this
     */
    public function setValueId(int $value_id): Propertyvariant
    {
        $this->value_id = $value_id;
        return $this;
    }
}
