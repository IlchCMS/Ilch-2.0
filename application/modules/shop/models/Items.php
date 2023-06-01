<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Shop\Models;

use Ilch\Model;

class Items extends Model
{
    /**
     * The id of the item.
     *
     * @var int|null
     */
    protected $id;

    /**
     * The cat_id of the item.
     *
     * @var int
     */
    protected $catId = 0;

    /**
     * The name of the item.
     *
     * @var string
     */
    protected $name = '';

    /**
    * The code of the item.
    *
    * @var string
    */
    protected $code = '';

    /**
    * The itemnumber of the item.
    *
    * @var string
    */
    protected $itemnumber = '';

    /**
    * The stock of the item.
    *
    * @var int
    */
    protected $stock = 0;

    /**
    * The unitName of the item.
    *
    * @var string
    */
    protected $unitName = '';

    /**
    * The cordon of the item.
    *
    * @var int
    */
    protected $cordon = 0;

    /**
    * The cordonText of the item.
    *
    * @var string
    */
    protected $cordonText = '';

    /**
    * The cordonColor of the item.
    *
    * @var string
    */
    protected $cordonColor = '';

    /**
    * The price of the item.
    *
    * @var string
    */
    protected $price = '';

    /**
    * The tax of the item.
    *
    * @var int
    */
    protected $tax = 0;

    /**
    * The shippingCosts of the item.
    *
    * @var string
    */
    protected $shippingCosts = '';

    /**
    * The shippingTime of the item.
    *
    * @var int
    */
    protected $shippingTime = 0;

    /**
    * The image of the item.
    *
    * @var string
    */
    protected $image = '';

    /**
    * The image1 of the item.
    *
    * @var string
    */
    protected $image1 = '';

    /**
    * The image2 of the item.
    *
    * @var string
    */
    protected $image2 = '';

    /**
    * The image3 of the item.
    *
    * @var string
    */
    protected $image3 = '';

    /**
    * The info of the item.
    *
    * @var string
    */
    protected $info = '';

    /**
    * The desc of the item.
    *
    * @var string
    */
    protected $desc = '';

    /**
    * The status of the item.
    *
    * @var int
    */
    protected $status = 0;

    /**
     * Gets the id of the item.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Sets the id of the item.
     *
     * @param int $id
     * @return $this
     */
    public function setId(int $id): Items
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Gets the catId of the item.
     *
     * @return int
     */
    public function getCatId(): int
    {
        return $this->catId;
    }

    /**
     * Sets the catId of the item.
     *
     * @param int $catId
     * @return $this
     */
    public function setCatId(int $catId): Items
    {
        $this->catId = $catId;

        return $this;
    }

    /**
     * Gets the name of the item.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Sets the name of the item.
     *
     * @param string $name
     * @return $this
     */
    public function setName(string $name): Items
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Gets the itemnumber of the item.
     *
     * @return string
     */
    public function getItemnumber(): string
    {
        return $this->itemnumber;
    }

    /**
     * Sets the itemnumber of the item.
     *
     * @param string $itemnumber
     * @return $this
     */
    public function setItemnumber(string $itemnumber): Items
    {
        $this->itemnumber = $itemnumber;

        return $this;
    }

    /**
     * Gets the code of the item.
     *
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * Sets the code of the item.
     *
     * @param string $code
     * @return $this
     */
    public function setCode(string $code): Items
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Gets the stock of the item.
     *
     * @return int
     */
    public function getStock(): int
    {
        return $this->stock;
    }

    /**
     * Sets the stock of the item.
     *
     * @param int $stock
     * @return $this
     */
    public function setStock(int $stock): Items
    {
        $this->stock = $stock;

        return $this;
    }

    /**
     * Gets the unitName of the item.
     *
     * @return string
     */
    public function getUnitName(): string
    {
        return $this->unitName;
    }

    /**
     * Sets the unitName of the item.
     *
     * @param string $unitName
     * @return $this
     */
    public function setUnitName(string $unitName): Items
    {
        $this->unitName = $unitName;

        return $this;
    }

    /**
     * Gets the cordon of the item.
     *
     * @return int
     */
    public function getCordon(): int
    {
        return $this->cordon;
    }

    /**
     * Sets the cordon of the item.
     *
     * @param int $cordon
     * @return $this
     */
    public function setCordon(int $cordon): Items
    {
        $this->cordon = $cordon;

        return $this;
    }

    /**
     * Gets the cordonText of the item.
     *
     * @return string
     */
    public function getCordonText(): string
    {
        return $this->cordonText;
    }

    /**
     * Sets the cordonText of the item.
     *
     * @param string $cordonText
     * @return $this
     */
    public function setCordonText(string $cordonText): Items
    {
        $this->cordonText = $cordonText;

        return $this;
    }

    /**
     * Gets the cordonColor of the item.
     *
     * @return string
     */
    public function getCordonColor(): string
    {
        return $this->cordonColor;
    }

    /**
     * Sets the cordonColor of the item.
     *
     * @param string $cordonColor
     * @return $this
     */
    public function setCordonColor(string $cordonColor): Items
    {
        $this->cordonColor = $cordonColor;

        return $this;
    }

    /**
     * Gets the price of the item.
     *
     * @return string
     */
    public function getPrice(): string
    {
        return $this->price;
    }

    /**
     * Sets the price of the item.
     *
     * @param string $price
     * @return $this
     */
    public function setPrice(string $price): Items
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Gets the tax of the item.
     *
     * @return int
     */
    public function getTax(): int
    {
        return $this->tax;
    }

    /**
     * Sets the tax of the item.
     *
     * @param int $tax
     * @return $this
     */
    public function setTax(int $tax): Items
    {
        $this->tax = $tax;

        return $this;
    }

    /**
     * Gets the shippingCosts of the item.
     *
     * @return string
     */
    public function getShippingCosts(): string
    {
        return $this->shippingCosts;
    }

    /**
     * Sets the shippingCosts of the item.
     *
     * @param string $shippingCosts
     * @return $this
     */
    public function setShippingCosts(string $shippingCosts): Items
    {
        $this->shippingCosts = $shippingCosts;

        return $this;
    }

    /**
     * Gets the shippingTime of the item.
     *
     * @return int
     */
    public function getShippingTime(): int
    {
        return $this->shippingTime;
    }

    /**
     * Sets the shippingTime of the item.
     *
     * @param int $shippingTime
     * @return $this
     */
    public function setShippingTime(int $shippingTime): Items
    {
        $this->shippingTime = $shippingTime;

        return $this;
    }

    /**
     * Gets the preview image of the item.
     *
     * @return string
     */
    public function getImage(): string
    {
        return $this->image;
    }

    /**
     * Sets the preview image of the item.
     *
     * @param string $image
     * @return $this
     */
    public function setImage(string $image): Items
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Gets the image1 of the item.
     *
     * @return string
     */
    public function getImage1(): string
    {
        return $this->image1;
    }

    /**
     * Sets the image1 of the item.
     *
     * @param string $image1
     * @return $this
     */
    public function setImage1(string $image1): Items
    {
        $this->image1 = $image1;

        return $this;
    }

    /**
     * Gets the image2 of the item.
     *
     * @return string
     */
    public function getImage2(): string
    {
        return $this->image2;
    }

    /**
     * Sets the image2 of the item.
     *
     * @param string $image2
     * @return $this
     */
    public function setImage2(string $image2): Items
    {
        $this->image2 = $image2;

        return $this;
    }

    /**
     * Gets the image3 of the item.
     *
     * @return string
     */
    public function getImage3(): string
    {
        return $this->image3;
    }

    /**
     * Sets the image3 of the item.
     *
     * @param string $image3
     * @return $this
     */
    public function setImage3(string $image3): Items
    {
        $this->image3 = $image3;

        return $this;
    }

    /**
     * Gets the short info of the item.
     *
     * @return string
     */
    public function getInfo(): string
    {
        return $this->info;
    }

    /**
     * Sets the short info of the item.
     *
     * @param string $info
     * @return $this
     */
    public function setInfo(string $info): Items
    {
        $this->info = $info;

        return $this;
    }

    /**
     * Gets the description of the item.
     *
     * @return string
     */
    public function getDesc(): string
    {
        return $this->desc;
    }

    /**
     * Sets the description of the item.
     *
     * @param string $desc
     * @return $this
     */
    public function setDesc(string $desc): Items
    {
        $this->desc = $desc;

        return $this;
    }

    /**
     * Gets the status of the item.
     *
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * Sets the description of the item.
     *
     * @param int $status
     * @return $this
     */
    public function setStatus(int $status): Items
    {
        $this->status = $status;

        return $this;
    }
}
