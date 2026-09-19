<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Shop\Models;

use PHPUnit\Framework\TestCase;
use Modules\Shop\Models\Address as AddressModel;
use Modules\Shop\Models\Order as OrderModel;
use Modules\Shop\Models\Orderdetails as OrderdetailsModel;

class OrderModelTest extends TestCase
{
    /**
     * Tests that default values are null, empty, or zero.
     */
    public function testDefaultValues(): void
    {
        $order = new OrderModel();

        self::assertNull($order->getId());
        self::assertSame('', $order->getDatetime());
        self::assertSame(0, $order->getCurrencyId());
        self::assertSame(0, $order->getCustomerId());
        self::assertInstanceOf(AddressModel::class, $order->getInvoiceAddress());
        self::assertInstanceOf(AddressModel::class, $order->getDeliveryAddress());
        self::assertSame('', $order->getEmail());
        self::assertNull($order->getInvoiceFilename());
        self::assertSame('', $order->getDatetimeInvoiceSent());
        self::assertSame(0, $order->getWillCollect());
        self::assertNull($order->getSelector());
        self::assertNull($order->getConfirmCode());
        self::assertNull($order->getStatus());
    }

    /**
     * Tests that setId() sets the id.
     */
    public function testSetId(): void
    {
        $order = new OrderModel();
        $order->setId(5);

        self::assertSame(5, $order->getId());
    }

    /**
     * Tests that setId(0) stores zero.
     */
    public function testSetIdZero(): void
    {
        $order = new OrderModel();
        $order->setId(0);

        self::assertSame(0, $order->getId());
    }

    /**
     * Tests that setDatetime() sets the datetime.
     */
    public function testSetDatetime(): void
    {
        $order = new OrderModel();
        $order->setDatetime('2020-04-22 11:47:27');

        self::assertSame('2020-04-22 11:47:27', $order->getDatetime());
    }

    /**
     * Tests that setCurrencyId() sets the currency id.
     */
    public function testSetCurrencyId(): void
    {
        $order = new OrderModel();
        $order->setCurrencyId(1);

        self::assertSame(1, $order->getCurrencyId());
    }

    /**
     * Tests that setCustomerId() sets the customer id.
     */
    public function testSetCustomerId(): void
    {
        $order = new OrderModel();
        $order->setCustomerId(2);

        self::assertSame(2, $order->getCustomerId());
    }

    /**
     * Tests that setInvoiceAddress() sets the invoice address.
     */
    public function testSetInvoiceAddress(): void
    {
        $order = new OrderModel();
        $address = new AddressModel();
        $address->setId(1);

        $order->setInvoiceAddress($address);

        self::assertSame($address, $order->getInvoiceAddress());
    }

    /**
     * Tests that setDeliveryAddress() sets the delivery address.
     */
    public function testSetDeliveryAddress(): void
    {
        $order = new OrderModel();
        $address = new AddressModel();
        $address->setId(2);

        $order->setDeliveryAddress($address);

        self::assertSame($address, $order->getDeliveryAddress());
    }

    /**
     * Tests that setEmail() sets the email.
     */
    public function testSetEmail(): void
    {
        $order = new OrderModel();
        $order->setEmail('max@mustermann.de');

        self::assertSame('max@mustermann.de', $order->getEmail());
    }

    /**
     * Tests that setOrderdetails() sets an array of order details.
     */
    public function testSetOrderdetails(): void
    {
        $order = new OrderModel();
        $details = [
            new OrderdetailsModel(),
            new OrderdetailsModel(),
        ];

        $order->setOrderdetails($details);

        self::assertCount(2, $order->getOrderdetails());
        self::assertInstanceOf(OrderdetailsModel::class, $order->getOrderdetails()[0]);
        self::assertInstanceOf(OrderdetailsModel::class, $order->getOrderdetails()[1]);
    }

    /**
     * Tests that setOrderdetails() accepts an empty array.
     */
    public function testSetOrderdetailsEmpty(): void
    {
        $order = new OrderModel();

        $order->setOrderdetails([]);

        self::assertCount(0, $order->getOrderdetails());
    }

    /**
     * Tests that setInvoiceFilename() sets the invoice filename.
     */
    public function testSetInvoiceFilename(): void
    {
        $order = new OrderModel();
        $order->setInvoiceFilename('invoice_1.pdf');

        self::assertSame('invoice_1.pdf', $order->getInvoiceFilename());
    }

    /**
     * Tests that setDatetimeInvoiceSent() sets the datetime when the invoice was sent.
     */
    public function testSetDatetimeInvoiceSent(): void
    {
        $order = new OrderModel();
        $order->setDatetimeInvoiceSent('2020-04-23 12:00:00');

        self::assertSame('2020-04-23 12:00:00', $order->getDatetimeInvoiceSent());
    }

    /**
     * Tests that setWillCollect() sets the will collect flag.
     */
    public function testSetWillCollect(): void
    {
        $order = new OrderModel();
        $order->setWillCollect(1);

        self::assertSame(1, $order->getWillCollect());
    }

    /**
     * Tests that setSelector() sets the selector.
     */
    public function testSetSelector(): void
    {
        $order = new OrderModel();
        $order->setSelector('selector18chars');

        self::assertSame('selector18chars', $order->getSelector());
    }

    /**
     * Tests that setConfirmCode() sets the confirm code.
     */
    public function testSetConfirmCode(): void
    {
        $order = new OrderModel();
        $order->setConfirmCode('confirmcode64chars');

        self::assertSame('confirmcode64chars', $order->getConfirmCode());
    }

    /**
     * Tests that setStatus() sets the status.
     */
    public function testSetStatus(): void
    {
        $order = new OrderModel();
        $order->setStatus(3);

        self::assertSame(3, $order->getStatus());
    }

    /**
     * Tests that setStatus(0) stores zero.
     */
    public function testSetStatusZero(): void
    {
        $order = new OrderModel();
        $order->setStatus(0);

        self::assertSame(0, $order->getStatus());
    }

    /**
     * Tests that Order setters are chainable.
     */
    public function testSettersReturnSelf(): void
    {
        $order = new OrderModel();
        $invoiceAddress = new AddressModel();
        $deliveryAddress = new AddressModel();
        $orderdetail = new OrderdetailsModel();

        self::assertSame($order, $order->setId(1));
        self::assertSame($order, $order->setDatetime('2020-04-22 11:47:27'));
        self::assertSame($order, $order->setCurrencyId(1));
        self::assertSame($order, $order->setCustomerId(1));
        self::assertSame($order, $order->setInvoiceAddress($invoiceAddress));
        self::assertSame($order, $order->setDeliveryAddress($deliveryAddress));
        self::assertSame($order, $order->setEmail('test@example.com'));
        self::assertSame($order, $order->setOrderdetails([$orderdetail]));
        self::assertSame($order, $order->setInvoiceFilename('invoice.pdf'));
        self::assertSame($order, $order->setDatetimeInvoiceSent('2020-04-23 12:00:00'));
        self::assertSame($order, $order->setWillCollect(1));
        self::assertSame($order, $order->setSelector('selector'));
        self::assertSame($order, $order->setConfirmCode('confirm'));
        self::assertSame($order, $order->setStatus(1));
    }

    /**
     * Tests that overwriting previously set values works.
     */
    public function testOverwriteValues(): void
    {
        $order = new OrderModel();
        $oldInvoiceAddress = new AddressModel();
        $oldDeliveryAddress = new AddressModel();
        $oldDetail = new OrderdetailsModel();
        $newInvoiceAddress = new AddressModel();
        $newDeliveryAddress = new AddressModel();
        $newDetail = new OrderdetailsModel();

        $order
            ->setId(1)
            ->setDatetime('2020-04-22 11:47:27')
            ->setCurrencyId(1)
            ->setCustomerId(1)
            ->setInvoiceAddress($oldInvoiceAddress)
            ->setDeliveryAddress($oldDeliveryAddress)
            ->setEmail('old@example.com')
            ->setOrderdetails([$oldDetail])
            ->setInvoiceFilename('old.pdf')
            ->setDatetimeInvoiceSent('2020-04-23 12:00:00')
            ->setWillCollect(1)
            ->setSelector('old-selector')
            ->setConfirmCode('old-confirm')
            ->setStatus(1);

        $order
            ->setId(2)
            ->setDatetime('2020-04-25 05:39:12')
            ->setCurrencyId(2)
            ->setCustomerId(2)
            ->setInvoiceAddress($newInvoiceAddress)
            ->setDeliveryAddress($newDeliveryAddress)
            ->setEmail('new@example.com')
            ->setOrderdetails([$newDetail])
            ->setInvoiceFilename('new.pdf')
            ->setDatetimeInvoiceSent('2020-04-26 12:00:00')
            ->setWillCollect(0)
            ->setSelector('new-selector')
            ->setConfirmCode('new-confirm')
            ->setStatus(2);

        self::assertSame(2, $order->getId());
        self::assertSame('2020-04-25 05:39:12', $order->getDatetime());
        self::assertSame(2, $order->getCurrencyId());
        self::assertSame(2, $order->getCustomerId());
        self::assertSame($newInvoiceAddress, $order->getInvoiceAddress());
        self::assertSame($newDeliveryAddress, $order->getDeliveryAddress());
        self::assertSame('new@example.com', $order->getEmail());
        self::assertCount(1, $order->getOrderdetails());
        self::assertSame($newDetail, $order->getOrderdetails()[0]);
        self::assertSame('new.pdf', $order->getInvoiceFilename());
        self::assertSame('2020-04-26 12:00:00', $order->getDatetimeInvoiceSent());
        self::assertSame(0, $order->getWillCollect());
        self::assertSame('new-selector', $order->getSelector());
        self::assertSame('new-confirm', $order->getConfirmCode());
        self::assertSame(2, $order->getStatus());
    }
}
