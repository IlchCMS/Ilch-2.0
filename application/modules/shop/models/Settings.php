<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Shop\Models;

use Ilch\Model;

class Settings extends Model
{
    /**
     * The id of the settings.
     *
     * @var int|null
     */
    protected ?int $id = null;

    /**
     * The shopName of the settings.
     *
     * @var string
     */
    protected string $shopName = '';

    /**
     * The shopLogo of the settings.
     *
     * @var string
     */
    protected string $shopLogo = '';

    /**
     * The shopStreet of the settings.
     *
     * @var string
     */
    protected string $shopStreet = '';

    /**
     * The shopPlz of the settings.
     *
     * @var string
     */
    protected string $shopPlz = '';

    /**
     * The shopCity of the settings.
     *
     * @var string
     */
    protected string $shopCity = '';

    /**
     * The shopTel of the settings.
     *
     * @var string
     */
    protected string $shopTel = '';

    /**
     * The shopFax of the settings.
     *
     * @var string
     */
    protected string $shopFax = '';

    /**
     * The shopMail of the settings.
     *
     * @var string
     */
    protected string $shopMail = '';

    /**
     * The shopWeb of the settings.
     *
     * @var string
     */
    protected string $shopWeb = '';

    /**
     * The shopStNr of the settings.
     *
     * @var string
     */
    protected string $shopStNr = '';

    /**
     * The bankName of the settings.
     *
     * @var string
     */
    protected string $bankName = '';

    /**
     * The bankOwner of the settings.
     *
     * @var string
     */
    protected string $bankOwner = '';

    /**
     * The bankIBAN of the settings.
     *
     * @var string
     */
    protected string $bankIBAN = '';

    /**
     * The bankBIC of the settings.
     *
     * @var string
     */
    protected string $bankBIC = '';

    /**
     * The deliveryTextTop of the settings.
     *
     * @var string
     */
    protected string $deliveryTextTop = '';

    /**
     * The invoiceTextTop of the settings.
     *
     * @var string
     */
    protected string $invoiceTextTop = '';

    /**
     * The invoiceTextBottom of the settings.
     *
     * @var string
     */
    protected string $invoiceTextBottom = '';

    /**
     * The agb of the settings.
     *
     * @var string
     */
    protected string $agb = '';

    /**
     * The fixTax of the settings.
     *
     * @var int
     */
    protected int $fixTax = 0;

    /**
     * The fixShippingCosts of the settings.
     *
     * @var string
     */
    protected string $fixShippingCosts = '';

    /**
     * The fixShippingTime of the settings.
     *
     * @var int
     */
    protected int $fixShippingTime = 0;

    /**
     * Allow will collect or not.
     *
     * @var int
     */
    protected int $allowWillCollect = 0;

    /**
     * The paypal client id of the settings.
     *
     * @var string|null
     */
    protected ?string $clientID = null;

    /**
     * The paypal.me name.
     *
     * @var string|null
     */
    protected ?string $paypalMe = null;

    /**
     * Preset the invoice amount when using PayPal.Me
     *
     * @var bool
     */
    protected bool $paypalMePresetAmount = false;

    /**
     * The ifSampleData of the settings.
     *
     * @var int
     */
    protected int $ifSampleData = 0;

    /**
     * Gets the id of the settings.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Sets the id of the settings.
     *
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * Gets the shopName of the settings.
     *
     * @return string
     */
    public function getShopName(): string
    {
        return $this->shopName;
    }

    /**
     * Sets the shopName of the settings.
     *
     * @param string $shopName
     */
    public function setShopName(string $shopName): void
    {
        $this->shopName = $shopName;
    }

    /**
     * Gets the shopLogo of the settings.
     *
     * @return string
     */
    public function getShopLogo(): string
    {
        return $this->shopLogo;
    }

    /**
     * Sets the shopLogo of the settings.
     *
     * @param string $shopLogo
     */
    public function setShopLogo(string $shopLogo): void
    {
        $this->shopLogo = $shopLogo;
    }

    /**
     * Gets the shopStreet of the settings.
     *
     * @return string
     */
    public function getShopStreet(): string
    {
        return $this->shopStreet;
    }

    /**
     * Sets the shopStreet of the settings.
     *
     * @param string $shopStreet
     */
    public function setShopStreet(string $shopStreet): void
    {
        $this->shopStreet = $shopStreet;
    }

    /**
     * Gets the shopPlz of the settings.
     *
     * @return string
     */
    public function getShopPlz(): string
    {
        return $this->shopPlz;
    }

    /**
     * Sets the shopPlz of the settings.
     *
     * @param string $shopPlz
     */
    public function setShopPlz(string $shopPlz): void
    {
        $this->shopPlz = $shopPlz;
    }

    /**
     * Gets the shopCity of the settings.
     *
     * @return string
     */
    public function getShopCity(): string
    {
        return $this->shopCity;
    }

    /**
     * Sets the shopCity of the settings.
     *
     * @param string $shopCity
     */
    public function setShopCity(string $shopCity): void
    {
        $this->shopCity = $shopCity;
    }

    /**
     * Gets the shopTel of the settings.
     *
     * @return string
     */
    public function getShopTel(): string
    {
        return $this->shopTel;
    }

    /**
     * Sets the shopTel of the settings.
     *
     * @param string $shopTel
     */
    public function setShopTel(string $shopTel): void
    {
        $this->shopTel = $shopTel;
    }

    /**
     * Gets the shopFax of the settings.
     *
     * @return string
     */
    public function getShopFax(): string
    {
        return $this->shopFax;
    }

    /**
     * Sets the shopFax of the settings.
     *
     * @param string $shopFax
     */
    public function setShopFax(string $shopFax): void
    {
        $this->shopFax = $shopFax;
    }

    /**
     * Gets the shopMail of the settings.
     *
     * @return string
     */
    public function getShopMail(): string
    {
        return $this->shopMail;
    }

    /**
     * Sets the shopMail of the settings.
     *
     * @param string $shopMail
     */
    public function setShopMail(string $shopMail): void
    {
        $this->shopMail = $shopMail;
    }

    /**
     * Gets the shopWeb of the settings.
     *
     * @return string
     */
    public function getShopWeb(): string
    {
        return $this->shopWeb;
    }

    /**
     * Sets the shopWeb of the settings.
     *
     * @param string $shopWeb
     */
    public function setShopWeb(string $shopWeb): void
    {
        $this->shopWeb = $shopWeb;
    }

    /**
     * Gets the shopStNr of the settings.
     *
     * @return string
     */
    public function getShopStNr(): string
    {
        return $this->shopStNr;
    }

    /**
     * Sets the shopStNr of the settings.
     *
     * @param string $shopStNr
     */
    public function setShopStNr(string $shopStNr): void
    {
        $this->shopStNr = $shopStNr;
    }

    /**
     * Gets the bankName of the settings.
     *
     * @return string
     */
    public function getBankName(): string
    {
        return $this->bankName;
    }

    /**
     * Sets the bankName of the settings.
     *
     * @param string $bankName
     */
    public function setBankName(string $bankName): void
    {
        $this->bankName = $bankName;
    }

    /**
     * Gets the bankOwner of the settings.
     *
     * @return string
     */
    public function getBankOwner(): string
    {
        return $this->bankOwner;
    }

    /**
     * Sets the bankOwner of the settings.
     *
     * @param string $bankOwner
     */
    public function setBankOwner(string $bankOwner): void
    {
        $this->bankOwner = $bankOwner;
    }

    /**
     * Gets the bankIBAN of the settings.
     *
     * @return string
     */
    public function getBankIBAN(): string
    {
        return $this->bankIBAN;
    }

    /**
     * Sets the bankIBAN of the settings.
     *
     * @param string $bankIBAN
     */
    public function setBankIBAN(string $bankIBAN): void
    {
        $this->bankIBAN = $bankIBAN;
    }

    /**
     * Gets the bankBIC of the settings.
     *
     * @return string
     */
    public function getBankBIC(): string
    {
        return $this->bankBIC;
    }

    /**
     * Sets the bankBIC of the settings.
     *
     * @param string $bankBIC
     */
    public function setBankBIC(string $bankBIC): void
    {
        $this->bankBIC = $bankBIC;
    }

    /**
     * Gets the deliveryTextTop of the settings.
     *
     * @return string
     */
    public function getDeliveryTextTop(): string
    {
        return $this->deliveryTextTop;
    }

    /**
     * Sets the deliveryTextTop of the settings.
     *
     * @param string $deliveryTextTop
     */
    public function setDeliveryTextTop(string $deliveryTextTop): void
    {
        $this->deliveryTextTop = $deliveryTextTop;
    }

    /**
     * Gets the invoiceTextTop of the settings.
     *
     * @return string
     */
    public function getInvoiceTextTop(): string
    {
        return $this->invoiceTextTop;
    }

    /**
     * Sets the invoiceTextTop of the settings.
     *
     * @param string $invoiceTextTop
     */
    public function setInvoiceTextTop(string $invoiceTextTop): void
    {
        $this->invoiceTextTop = $invoiceTextTop;
    }

    /**
     * Gets the invoiceTextBottom of the settings.
     *
     * @return string
     */
    public function getInvoiceTextBottom(): string
    {
        return $this->invoiceTextBottom;
    }

    /**
     * Sets the invoiceTextBottom of the settings.
     *
     * @param string $invoiceTextBottom
     */
    public function setInvoiceTextBottom(string $invoiceTextBottom): void
    {
        $this->invoiceTextBottom = $invoiceTextBottom;
    }

    /**
     * Gets the agb of the settings.
     *
     * @return string
     */
    public function getAGB(): string
    {
        return $this->agb;
    }

    /**
     * Sets the agb of the settings.
     *
     * @param string $agb
     */
    public function setAGB(string $agb): void
    {
        $this->agb = $agb;
    }

    /**
     * Gets the fixTax of the settings.
     *
     * @return int
     */
    public function getFixTax(): int
    {
        return $this->fixTax;
    }

    /**
     * Sets the fixTax of the settings.
     *
     * @param int $fixTax
     */
    public function setFixTax(int $fixTax): void
    {
        $this->fixTax = $fixTax;
    }

    /**
     * Gets the fixShippingCosts of the settings.
     *
     * @return string
     */
    public function getFixShippingCosts(): string
    {
        return $this->fixShippingCosts;
    }

    /**
     * Sets the fixShippingCosts of the settings.
     *
     * @param string $fixShippingCosts
     */
    public function setFixShippingCosts(string $fixShippingCosts): void
    {
        $this->fixShippingCosts = $fixShippingCosts;
    }

    /**
     * Gets the fixShippingTime of the settings.
     *
     * @return int
     */
    public function getFixShippingTime(): int
    {
        return $this->fixShippingTime;
    }

    /**
     * Sets the fixShippingTime of the settings.
     *
     * @param int $fixShippingTime
     */
    public function setFixShippingTime(int $fixShippingTime): void
    {
        $this->fixShippingTime = $fixShippingTime;
    }

    /**
     * Get the value for allow will collect.
     * 0: not allowed
     * 1: allowed
     *
     * @return int
     */
    public function getAllowWillCollect(): int
    {
        return $this->allowWillCollect;
    }

    /**
     * Set the value for allow will collect.
     *
     * @param int $allowWillCollect 0: not allowed, 1: allowed
     * @return Settings
     */
    public function setAllowWillCollect(int $allowWillCollect): Settings
    {
        $this->allowWillCollect = $allowWillCollect;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getClientID(): ?string
    {
        return $this->clientID;
    }

    /**
     * @param string $clientID
     */
    public function setClientID(string $clientID): void
    {
        $this->clientID = $clientID;
    }

    /**
     * Get the PayPal.Me name.
     *
     * @return string|null
     */
    public function getPayPalMe(): ?string
    {
        return $this->paypalMe;
    }

    /**
     * Set the PayPal.Me name.
     *
     * @param string $name
     */
    public function setPayPalMe(string $name): void
    {
        $this->paypalMe = $name;
    }

    /**
     * Preset the invoice amount when using PayPal.Me
     *
     * @return bool
     */
    public function isPaypalMePresetAmount(): bool
    {
        return $this->paypalMePresetAmount;
    }

    /**
     * Set if the invoice amount should be preset when using PayPal.Me
     *
     * @param bool $paypalMePresetAmount
     */
    public function setPaypalMePresetAmount(bool $paypalMePresetAmount): void
    {
        $this->paypalMePresetAmount = $paypalMePresetAmount;
    }

    /**
     * Gets the ifSampleData of the settings.
     *
     * @return int
     */
    public function getIfSampleData(): int
    {
        return $this->ifSampleData;
    }

    /**
     * Sets the ifSampleData of the settings.
     *
     * @param int $ifSampleData
     */
    public function setIfSampleData(int $ifSampleData): void
    {
        $this->ifSampleData = $ifSampleData;
    }
}
