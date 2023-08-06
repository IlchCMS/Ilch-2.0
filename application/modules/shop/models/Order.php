<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Shop\Models;

use Ilch\Model;
use Modules\Shop\Models\Address as AddressModel;

class Order extends Model
{
    /**
     * The id of the order.
     *
     * @var int|null
     */
    protected $id;

    /**
     * The datetime of the order.
     *
     * @var string
     */
    protected $datetime = '';

    /**
     * The currency id of the order.
     *
     * @var int
     */
    protected $currencyId = 0;

    /**
     * The customer id of the order.
     *
     * @var int
     */
    protected $customerId = 0;

    /**
     * The invoice address of the order.
     *
     * @var Address
     */
    protected $invoiceAddress;

    /**
     * The delivery address of the order.
     *
     * @var Address
     */
    protected $deliveryAddress;

    /**
     * The email of the order.
     *
     * @var string
     */
    protected $email = '';

    /**
     * The array of the order details models.
     *
     * @var Orderdetails[]
     */
    protected $orderdetails = [];

    /**
     * The filename of the invoice.
     *
     * @var string|null
     */
    protected $invoicefilename;

    /**
     * The datetime when the invoice was sent to the customer.
     *
     * @var string
     */
    protected $datetimeInvoiceSent = '';

    /**
     * Wether the customer collects the order or it needs to be shipped.
     *
     * @var int
     */
    protected $willCollect = 0;

    /**
     * A 18 char long selector.
     *
     * @var string|null
     */
    protected $selector;

    /**
     * A 64 char long confirmCode.
     *
     * @var string|null
     */
    protected $confirmCode;

    /**
     * The status of the order.
     *
     * @var int|null
     */
    protected $status;

    public function __construct()
    {
        $this->deliveryAddress = new AddressModel();
        $this->invoiceAddress = new AddressModel();
        $this->orderdetails = new Orderdetails();
    }

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
    public function setId(int $id): Order
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Gets the datetime of the order.
     *
     * @return string
     */
    public function getDatetime(): string
    {
        return $this->datetime;
    }

    /**
     * Sets the datetime of the order.
     *
     * @param string $datetime
     * @return $this
     */
    public function setDatetime(string $datetime): Order
    {
        $this->datetime = $datetime;

        return $this;
    }

    /**
     * Get the currency id of the order.
     *
     * @return int
     */
    public function getCurrencyId(): int
    {
        return $this->currencyId;
    }

    /**
     * Set the currency id of the order.
     *
     * @param int $currencyId
     * @return Order
     */
    public function setCurrencyId(int $currencyId): Order
    {
        $this->currencyId = $currencyId;
        return $this;
    }

    /**
     * Get the customer id.
     *
     * @return int
     */
    public function getCustomerId(): int
    {
        return $this->customerId;
    }

    /**
     * Set the customer id.
     *
     * @param int $customerId
     * @return Order
     */
    public function setCustomerId(int $customerId): Order
    {
        $this->customerId = $customerId;
        return $this;
    }

    /**
     * Get the innvoice address.
     *
     * @return Address
     */
    public function getInvoiceAddress(): Address
    {
        return $this->invoiceAddress;
    }

    /**
     * Set the invoice address
     *
     * @param Address $invoiceAddress
     * @return Order
     */
    public function setInvoiceAddress(Address $invoiceAddress): Order
    {
        $this->invoiceAddress = $invoiceAddress;
        return $this;
    }

    /**
     * Get the delivery address.
     *
     * @return Address
     */
    public function getDeliveryAddress(): Address
    {
        return $this->deliveryAddress;
    }

    /**
     * Set the delivery address.
     *
     * @param Address $deliveryAddress
     * @return Order
     */
    public function setDeliveryAddress(Address $deliveryAddress): Order
    {
        $this->deliveryAddress = $deliveryAddress;
        return $this;
    }

    /**
     * Gets the email of the order.
     *
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Sets the email of the order.
     *
     * @param string $email
     * @return $this
     */
    public function setEmail(string $email): Order
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the orderdetails
     *
     * @return Orderdetails[]
     */
    public function getOrderdetails(): array
    {
        return $this->orderdetails;
    }

    /**
     * Set the orderdetails
     *
     * @param Orderdetails[] $orderdetails
     * @return Order
     */
    public function setOrderdetails(array $orderdetails): Order
    {
        $this->orderdetails = $orderdetails;
        return $this;
    }

    /**
     * Gets the filename of the invoice.
     *
     * @return string|null
     */
    public function getInvoiceFilename(): ?string
    {
        return $this->invoicefilename;
    }

    /**
     * Sets the filename of the invoice.
     *
     * @param string $invoicefilename
     * @return $this
     */
    public function setInvoiceFilename(string $invoicefilename): Order
    {
        $this->invoicefilename = $invoicefilename;

        return $this;
    }

    /**
     * Gets the datetime when the invoice was sent to the customer.
     *
     * @return string
     */
    public function getDatetimeInvoiceSent(): ?string
    {
        return $this->datetimeInvoiceSent;
    }

    /**
     * Sets the datetime when the invoice was sent to the customer.
     *
     * @param string $datetimeInvoiceSent
     * @return $this
     */
    public function setDatetimeInvoiceSent(string $datetimeInvoiceSent): Order
    {
        $this->datetimeInvoiceSent = $datetimeInvoiceSent;

        return $this;
    }

    /**
     * Get the value of will collect.
     * 1: customer collects the order
     * 0: shipping
     *
     * @return int
     */
    public function getWillCollect(): int
    {
        return $this->willCollect;
    }

    /**
     * Set the value of will collect.
     *
     * @param int $willCollect 0: shipping, 1: customer collects the order
     * @return Order
     */
    public function setWillCollect(int $willCollect): Order
    {
        $this->willCollect = $willCollect;
        return $this;
    }

    /**
     * Get the 18 char long selector.
     *
     * @return string|null
     */
    public function getSelector(): ?string
    {
        return $this->selector;
    }

    /**
     * Set the 18 char long selector.
     *
     * @param string $selector
     * @return $this
     */
    public function setSelector(string $selector): Order
    {
        $this->selector = $selector;
        return $this;
    }

    /**
     * Get the 64 char long confirm code.
     *
     * @return string|null
     */
    public function getConfirmCode(): ?string
    {
        return $this->confirmCode;
    }

    /**
     * Set the 64 char long confirm code.
     *
     * @param string $confirmCode
     * @return Order
     */
    public function setConfirmCode(string $confirmCode): Order
    {
        $this->confirmCode = $confirmCode;
        return $this;
    }

    /**
     * Gets the status of the order.
     *
     * @return int|null
     */
    public function getStatus(): ?int
    {
        return $this->status;
    }

    /**
     * Sets the status of the order.
     *
     * @param int $status
     * @return $this
     */
    public function setStatus(int $status): Order
    {
        $this->status = $status;

        return $this;
    }
}
