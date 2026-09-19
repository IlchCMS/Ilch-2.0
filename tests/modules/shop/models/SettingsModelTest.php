<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Shop\Models;

use PHPUnit\Framework\TestCase;
use Modules\Shop\Models\Settings as SettingsModel;

class SettingsModelTest extends TestCase
{
    /**
     * Tests that default values are null, empty, zero, or false.
     */
    public function testDefaultValues(): void
    {
        $model = new SettingsModel();

        self::assertNull($model->getId());
        self::assertSame('', $model->getShopName());
        self::assertSame('', $model->getShopLogo());
        self::assertSame('', $model->getShopStreet());
        self::assertSame('', $model->getShopPlz());
        self::assertSame('', $model->getShopCity());
        self::assertSame('', $model->getShopTel());
        self::assertSame('', $model->getShopFax());
        self::assertSame('', $model->getShopMail());
        self::assertSame('', $model->getShopWeb());
        self::assertSame('', $model->getShopStNr());
        self::assertSame('', $model->getBankName());
        self::assertSame('', $model->getBankOwner());
        self::assertSame('', $model->getBankIBAN());
        self::assertSame('', $model->getBankBIC());
        self::assertSame('', $model->getDeliveryTextTop());
        self::assertSame('', $model->getInvoiceTextTop());
        self::assertSame('', $model->getInvoiceTextBottom());
        self::assertSame('', $model->getAGB());
        self::assertSame(0, $model->getFixTax());
        self::assertSame('', $model->getFixShippingCosts());
        self::assertSame(0, $model->getFixShippingTime());
        self::assertSame(0, $model->getAllowWillCollect());
        self::assertNull($model->getClientID());
        self::assertNull($model->getPayPalMe());
        self::assertFalse($model->isPaypalMePresetAmount());
        self::assertSame(0, $model->getIfSampleData());
    }

    /**
     * Tests that setId() sets the id.
     */
    public function testSetId(): void
    {
        $model = new SettingsModel();
        $model->setId(5);

        self::assertSame(5, $model->getId());
    }

    /**
     * Tests that setId(0) stores zero.
     */
    public function testSetIdZero(): void
    {
        $model = new SettingsModel();
        $model->setId(0);

        self::assertSame(0, $model->getId());
    }

    /**
     * Tests that setShopName() sets the shop name.
     */
    public function testSetShopName(): void
    {
        $model = new SettingsModel();
        $model->setShopName('ILCH Shop');

        self::assertSame('ILCH Shop', $model->getShopName());
    }

    /**
     * Tests that setShopLogo() sets the shop logo.
     */
    public function testSetShopLogo(): void
    {
        $model = new SettingsModel();
        $model->setShopLogo('application/modules/media/static/upload/shop_logo.jpg');

        self::assertSame('application/modules/media/static/upload/shop_logo.jpg', $model->getShopLogo());
    }

    /**
     * Tests that setShopStreet() sets the shop street.
     */
    public function testSetShopStreet(): void
    {
        $model = new SettingsModel();
        $model->setShopStreet('Shoppingallee 12');

        self::assertSame('Shoppingallee 12', $model->getShopStreet());
    }

    /**
     * Tests that setShopPlz() sets the shop postcode.
     */
    public function testSetShopPlz(): void
    {
        $model = new SettingsModel();
        $model->setShopPlz('12345');

        self::assertSame('12345', $model->getShopPlz());
    }

    /**
     * Tests that setShopCity() sets the shop city.
     */
    public function testSetShopCity(): void
    {
        $model = new SettingsModel();
        $model->setShopCity('Shophausen');

        self::assertSame('Shophausen', $model->getShopCity());
    }

    /**
     * Tests that setShopTel() sets the shop phone number.
     */
    public function testSetShopTel(): void
    {
        $model = new SettingsModel();
        $model->setShopTel('+49 (0) 1234 56789');

        self::assertSame('+49 (0) 1234 56789', $model->getShopTel());
    }

    /**
     * Tests that setShopFax() sets the shop fax number.
     */
    public function testSetShopFax(): void
    {
        $model = new SettingsModel();
        $model->setShopFax('+49 (0) 1234 98765');

        self::assertSame('+49 (0) 1234 98765', $model->getShopFax());
    }

    /**
     * Tests that setShopMail() sets the shop email.
     */
    public function testSetShopMail(): void
    {
        $model = new SettingsModel();
        $model->setShopMail('shop@example.com');

        self::assertSame('shop@example.com', $model->getShopMail());
    }

    /**
     * Tests that setShopWeb() sets the shop website.
     */
    public function testSetShopWeb(): void
    {
        $model = new SettingsModel();
        $model->setShopWeb('www.example.com');

        self::assertSame('www.example.com', $model->getShopWeb());
    }

    /**
     * Tests that setShopStNr() sets the shop tax number.
     */
    public function testSetShopStNr(): void
    {
        $model = new SettingsModel();
        $model->setShopStNr('DE1234567890');

        self::assertSame('DE1234567890', $model->getShopStNr());
    }

    /**
     * Tests that setBankName() sets the bank name.
     */
    public function testSetBankName(): void
    {
        $model = new SettingsModel();
        $model->setBankName('Bankinstitut Shophausen');

        self::assertSame('Bankinstitut Shophausen', $model->getBankName());
    }

    /**
     * Tests that setBankOwner() sets the bank account owner.
     */
    public function testSetBankOwner(): void
    {
        $model = new SettingsModel();
        $model->setBankOwner('Max Mustermann');

        self::assertSame('Max Mustermann', $model->getBankOwner());
    }

    /**
     * Tests that setBankIBAN() sets the bank IBAN.
     */
    public function testSetBankIBAN(): void
    {
        $model = new SettingsModel();
        $model->setBankIBAN('DE12123456780000012345');

        self::assertSame('DE12123456780000012345', $model->getBankIBAN());
    }

    /**
     * Tests that setBankBIC() sets the bank BIC.
     */
    public function testSetBankBIC(): void
    {
        $model = new SettingsModel();
        $model->setBankBIC('GENODE99ABC');

        self::assertSame('GENODE99ABC', $model->getBankBIC());
    }

    /**
     * Tests that setDeliveryTextTop() sets the delivery text top.
     */
    public function testSetDeliveryTextTop(): void
    {
        $model = new SettingsModel();
        $model->setDeliveryTextTop('Delivery text top');

        self::assertSame('Delivery text top', $model->getDeliveryTextTop());
    }

    /**
     * Tests that setInvoiceTextTop() sets the invoice text top.
     */
    public function testSetInvoiceTextTop(): void
    {
        $model = new SettingsModel();
        $model->setInvoiceTextTop('Invoice text top');

        self::assertSame('Invoice text top', $model->getInvoiceTextTop());
    }

    /**
     * Tests that setInvoiceTextBottom() sets the invoice text bottom.
     */
    public function testSetInvoiceTextBottom(): void
    {
        $model = new SettingsModel();
        $model->setInvoiceTextBottom('Invoice text bottom');

        self::assertSame('Invoice text bottom', $model->getInvoiceTextBottom());
    }

    /**
     * Tests that setAGB() sets the AGB text.
     */
    public function testSetAGB(): void
    {
        $model = new SettingsModel();
        $model->setAGB('Allgemeine Geschäftsbedingungen');

        self::assertSame('Allgemeine Geschäftsbedingungen', $model->getAGB());
    }

    /**
     * Tests that setFixTax() sets the fixed tax.
     */
    public function testSetFixTax(): void
    {
        $model = new SettingsModel();
        $model->setFixTax(19);

        self::assertSame(19, $model->getFixTax());
    }

    /**
     * Tests that setFixShippingCosts() sets the fixed shipping costs.
     */
    public function testSetFixShippingCosts(): void
    {
        $model = new SettingsModel();
        $model->setFixShippingCosts('5.00');

        self::assertSame('5.00', $model->getFixShippingCosts());
    }

    /**
     * Tests that setFixShippingTime() sets the fixed shipping time.
     */
    public function testSetFixShippingTime(): void
    {
        $model = new SettingsModel();
        $model->setFixShippingTime(7);

        self::assertSame(7, $model->getFixShippingTime());
    }

    /**
     * Tests that setAllowWillCollect() allows will collect.
     */
    public function testSetAllowWillCollectAllowed(): void
    {
        $model = new SettingsModel();
        $model->setAllowWillCollect(1);

        self::assertSame(1, $model->getAllowWillCollect());
    }

    /**
     * Tests that setAllowWillCollect() disables will collect.
     */
    public function testSetAllowWillCollectNotAllowed(): void
    {
        $model = new SettingsModel();
        $model->setAllowWillCollect(0);

        self::assertSame(0, $model->getAllowWillCollect());
    }

    /**
     * Tests that setAllowWillCollect() returns the model instance.
     */
    public function testSetAllowWillCollectReturnsSelf(): void
    {
        $model = new SettingsModel();

        self::assertSame($model, $model->setAllowWillCollect(1));
    }

    /**
     * Tests that setClientID() sets the PayPal client id.
     */
    public function testSetClientID(): void
    {
        $model = new SettingsModel();
        $model->setClientID('paypal-client-id');

        self::assertSame('paypal-client-id', $model->getClientID());
    }

    /**
     * Tests that setPayPalMe() sets the PayPal.Me name.
     */
    public function testSetPayPalMe(): void
    {
        $model = new SettingsModel();
        $model->setPayPalMe('paypal.me/example');

        self::assertSame('paypal.me/example', $model->getPayPalMe());
    }

    /**
     * Tests that setPaypalMePresetAmount() enables preset amount.
     */
    public function testSetPaypalMePresetAmountTrue(): void
    {
        $model = new SettingsModel();
        $model->setPaypalMePresetAmount(true);

        self::assertTrue($model->isPaypalMePresetAmount());
    }

    /**
     * Tests that setPaypalMePresetAmount() disables preset amount.
     */
    public function testSetPaypalMePresetAmountFalse(): void
    {
        $model = new SettingsModel();
        $model->setPaypalMePresetAmount(false);

        self::assertFalse($model->isPaypalMePresetAmount());
    }

    /**
     * Tests that setIfSampleData() sets the sample data flag.
     */
    public function testSetIfSampleData(): void
    {
        $model = new SettingsModel();
        $model->setIfSampleData(1);

        self::assertSame(1, $model->getIfSampleData());
    }

    /**
     * Tests that overwriting previously set values works.
     */
    public function testOverwriteValues(): void
    {
        $model = new SettingsModel();

        $model->setId(1);
        $model->setShopName('Old Shop');
        $model->setShopLogo('old_logo.jpg');
        $model->setShopStreet('Old Street');
        $model->setShopPlz('11111');
        $model->setShopCity('Old City');
        $model->setShopTel('Old Tel');
        $model->setShopFax('Old Fax');
        $model->setShopMail('old@example.com');
        $model->setShopWeb('old.example.com');
        $model->setShopStNr('Old StNr');
        $model->setBankName('Old Bank');
        $model->setBankOwner('Old Owner');
        $model->setBankIBAN('Old IBAN');
        $model->setBankBIC('Old BIC');
        $model->setDeliveryTextTop('Old Delivery Text');
        $model->setInvoiceTextTop('Old Invoice Top');
        $model->setInvoiceTextBottom('Old Invoice Bottom');
        $model->setAGB('Old AGB');
        $model->setFixTax(19);
        $model->setFixShippingCosts('1.00');
        $model->setFixShippingTime(1);
        $model->setAllowWillCollect(1);
        $model->setClientID('old-client-id');
        $model->setPayPalMe('paypal.me/old');
        $model->setPaypalMePresetAmount(true);
        $model->setIfSampleData(1);

        $model->setId(2);
        $model->setShopName('New Shop');
        $model->setShopLogo('new_logo.jpg');
        $model->setShopStreet('New Street');
        $model->setShopPlz('22222');
        $model->setShopCity('New City');
        $model->setShopTel('New Tel');
        $model->setShopFax('New Fax');
        $model->setShopMail('new@example.com');
        $model->setShopWeb('new.example.com');
        $model->setShopStNr('New StNr');
        $model->setBankName('New Bank');
        $model->setBankOwner('New Owner');
        $model->setBankIBAN('New IBAN');
        $model->setBankBIC('New BIC');
        $model->setDeliveryTextTop('New Delivery Text');
        $model->setInvoiceTextTop('New Invoice Top');
        $model->setInvoiceTextBottom('New Invoice Bottom');
        $model->setAGB('New AGB');
        $model->setFixTax(15);
        $model->setFixShippingCosts('2.00');
        $model->setFixShippingTime(2);
        $model->setAllowWillCollect(0);
        $model->setClientID('new-client-id');
        $model->setPayPalMe('paypal.me/new');
        $model->setPaypalMePresetAmount(false);
        $model->setIfSampleData(0);

        self::assertSame(2, $model->getId());
        self::assertSame('New Shop', $model->getShopName());
        self::assertSame('new_logo.jpg', $model->getShopLogo());
        self::assertSame('New Street', $model->getShopStreet());
        self::assertSame('22222', $model->getShopPlz());
        self::assertSame('New City', $model->getShopCity());
        self::assertSame('New Tel', $model->getShopTel());
        self::assertSame('New Fax', $model->getShopFax());
        self::assertSame('new@example.com', $model->getShopMail());
        self::assertSame('new.example.com', $model->getShopWeb());
        self::assertSame('New StNr', $model->getShopStNr());
        self::assertSame('New Bank', $model->getBankName());
        self::assertSame('New Owner', $model->getBankOwner());
        self::assertSame('New IBAN', $model->getBankIBAN());
        self::assertSame('New BIC', $model->getBankBIC());
        self::assertSame('New Delivery Text', $model->getDeliveryTextTop());
        self::assertSame('New Invoice Top', $model->getInvoiceTextTop());
        self::assertSame('New Invoice Bottom', $model->getInvoiceTextBottom());
        self::assertSame('New AGB', $model->getAGB());
        self::assertSame(15, $model->getFixTax());
        self::assertSame('2.00', $model->getFixShippingCosts());
        self::assertSame(2, $model->getFixShippingTime());
        self::assertSame(0, $model->getAllowWillCollect());
        self::assertSame('new-client-id', $model->getClientID());
        self::assertSame('paypal.me/new', $model->getPayPalMe());
        self::assertFalse($model->isPaypalMePresetAmount());
        self::assertSame(0, $model->getIfSampleData());
    }
}
