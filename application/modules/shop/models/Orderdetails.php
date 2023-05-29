<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Shop\Models;

use Ilch\Model;

/**
 * Details for the items that are part of an order at time of the order.
 */
class Orderdetails extends Model
{
    /**
     * The id of the order detail.
     *
     * @var int
     */
    protected $id;

    /**
     * The id of the order.
     *
     * @var int
     */
    protected $orderId;

    /**
     * The id of the item.
     *
     * @var int
     */
    protected $itemId;

    /**
     * The price of the item.
     *
     * @var double
     */
    protected $price;

    /**
     * The quantity of the item ordered.
     *
     * @var int
     */
    protected $quantity;

    /**
     * The tax on the item.
     *
     * @var int
     */
    protected $tax;

    /**
     * The shipping costs for this item.
     *
     * @var double
     */
    protected $shippingCosts;

    /**
     * Gets the id of the order.
     *
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Sets the id of the order.
     *
     * @param int $id
     * @return $this
     */
    public function setId(int $id): Orderdetails
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Gets the id of the order.
     *
     * @return int
     */
    public function getOrderId(): int
    {
        return $this->orderId;
    }

    /**
     * Sets the id of the order.
     *
     * @param int $orderId
     * @return Orderdetails
     */
    public function setOrderId(int $orderId): Orderdetails
    {
        $this->orderId = $orderId;
        return $this;
    }

    /**
     * Get the item id.
     *
     * @return int
     */
    public function getItemId(): int
    {
        return $this->itemId;
    }

    /**
     * Set the item id.
     *
     * @param int $itemId
     * @return Orderdetails
     */
    public function setItemId(int $itemId): Orderdetails
    {
        $this->itemId = $itemId;
        return $this;
    }

    /**
     * Gets the price of the item.
     *
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * Sets the price of the item.
     *
     * @param float $price
     * @return Orderdetails
     */
    public function setPrice(float $price): Orderdetails
    {
        $this->price = $price;
        return $this;
    }

    /**
     * Gets the quantity of the item ordered.
     *
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * Sets the quantity of the item ordered.
     *
     * @param int $quantity
     * @return Orderdetails
     */
    public function setQuantity(int $quantity): Orderdetails
    {
        $this->quantity = $quantity;
        return $this;
    }

    /**
     * Gets the tax on the item.
     *
     * @return int
     */
    public function getTax(): int
    {
        return $this->tax;
    }

    /**
     * Sets the tax on the item.
     *
     * @param int $tax
     * @return Orderdetails
     */
    public function setTax(int $tax): Orderdetails
    {
        $this->tax = $tax;
        return $this;
    }

    /**
     * Gets the shipping costs for the item.
     *
     * @return float
     */
    public function getShippingCosts(): float
    {
        return $this->shippingCosts;
    }

    /**
     * Sets the shipping costs for the item.
     *
     * @param float $shippingCosts
     * @return Orderdetails
     */
    public function setShippingCosts(float $shippingCosts): Orderdetails
    {
        $this->shippingCosts = $shippingCosts;
        return $this;
    }
}
