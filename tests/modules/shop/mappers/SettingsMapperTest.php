<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Shop\Mappers;

use PHPUnit\Ilch\DatabaseTestCase;
use PHPUnit\Ilch\PhpunitDataset;
use Modules\Shop\Config\Config as ModuleConfig;
use Modules\Shop\Mappers\Settings as SettingsMapper;
use Modules\Shop\Models\Settings as SettingsModel;

class SettingsMapperTest extends DatabaseTestCase
{
    /**
     * @var SettingsMapper
     */
    protected SettingsMapper $out;
    protected PhpunitDataset $phpunitDataset;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database.yml');
        $this->out = new SettingsMapper();
    }

    /**
     * Tests that getSettings() returns a SettingsModel.
     */
    public function testGetSettings()
    {
        $settings = $this->out->getSettings();

        self::assertNotNull($settings);
        self::assertInstanceOf(SettingsModel::class, $settings);
    }

    /**
     * Tests that getSettings() returns correct shop fields.
     */
    public function testGetSettingsShopFields()
    {
        $settings = $this->out->getSettings();

        self::assertEquals(1, $settings->getId());
        self::assertEquals('ILCH Shop', $settings->getShopName());
        self::assertEquals('logo.jpg', $settings->getShopLogo());
        self::assertEquals('Shoppingallee 12', $settings->getShopStreet());
        self::assertEquals('12345', $settings->getShopPlz());
        self::assertEquals('Shophausen', $settings->getShopCity());
        self::assertEquals('+49 (0) 1234 56789', $settings->getShopTel());
        self::assertEquals('+49 (0) 1234 98765', $settings->getShopFax());
        self::assertEquals('ilch@shop.de', $settings->getShopMail());
        self::assertEquals('www.ilch.de', $settings->getShopWeb());
        self::assertEquals('DE1234567890', $settings->getShopStNr());
    }

    /**
     * Tests that getSettings() returns correct bank fields.
     */
    public function testGetSettingsBankFields()
    {
        $settings = $this->out->getSettings();

        self::assertEquals('Bankinstitut Shophausen', $settings->getBankName());
        self::assertEquals('Max Mustermann', $settings->getBankOwner());
        self::assertEquals('DE12123456780000012345', $settings->getBankIBAN());
        self::assertEquals('GENODE99ABC', $settings->getBankBIC());
    }

    /**
     * Tests that getSettings() returns correct default fields.
     */
    public function testGetSettingsDefaultFields()
    {
        $settings = $this->out->getSettings();

        self::assertEquals(19, $settings->getFixTax());
        self::assertEquals('0.00', $settings->getFixShippingCosts());
        self::assertEquals(7, $settings->getFixShippingTime());
        self::assertEquals(0, $settings->getAllowWillCollect());
        self::assertEquals('Delivery text top', $settings->getDeliveryTextTop());
        self::assertEquals('Invoice text top', $settings->getInvoiceTextTop());
        self::assertEquals('Invoice text bottom', $settings->getInvoiceTextBottom());
        self::assertEquals('AGB content', $settings->getAGB());
    }

    /**
     * Tests that getSettings() returns correct payment fields.
     */
    public function testGetSettingsPaymentFields()
    {
        $settings = $this->out->getSettings();

        self::assertEquals('', $settings->getClientID());
        self::assertEquals('', $settings->getPayPalMe());
        self::assertTrue($settings->isPaypalMePresetAmount());
        self::assertEquals(1, $settings->getIfSampleData());
    }

    /**
     * Tests that getSettings() returns null when no settings row exists.
     */
    public function testGetSettingsEmpty()
    {
        $this->db->delete('shop_settings')
            ->where(['id' => 1])
            ->execute();

        $settings = $this->out->getSettings();

        self::assertNull($settings);
    }

    /**
     * Tests that updateSettingShop() updates shop-related fields.
     */
    public function testUpdateSettingShop()
    {
        $model = new SettingsModel();
        $model->setShopName('Updated Shop');
        $model->setShopLogo('newlogo.jpg');
        $model->setShopStreet('New Street 1');
        $model->setShopPlz('99999');
        $model->setShopCity('NewCity');
        $model->setShopTel('+49 111 222 333');
        $model->setShopFax('+49 444 555 666');
        $model->setShopMail('new@shop.de');
        $model->setShopWeb('www.newshop.de');
        $model->setShopStNr('DE9999999999');

        $result = $this->out->updateSettingShop($model);

        self::assertSame(1, $result);

        $settings = $this->out->getSettings();
        self::assertEquals('Updated Shop', $settings->getShopName());
        self::assertEquals('newlogo.jpg', $settings->getShopLogo());
        self::assertEquals('New Street 1', $settings->getShopStreet());
        self::assertEquals('99999', $settings->getShopPlz());
        self::assertEquals('NewCity', $settings->getShopCity());
        self::assertEquals('+49 111 222 333', $settings->getShopTel());
        self::assertEquals('+49 444 555 666', $settings->getShopFax());
        self::assertEquals('new@shop.de', $settings->getShopMail());
        self::assertEquals('www.newshop.de', $settings->getShopWeb());
        self::assertEquals('DE9999999999', $settings->getShopStNr());
    }

    /**
     * Tests that updateSettingShop() does not affect bank fields.
     */
    public function testUpdateSettingShopDoesNotAffectBank()
    {
        $model = new SettingsModel();
        $model->setShopName('Changed Name');

        $result = $this->out->updateSettingShop($model);

        self::assertSame(1, $result);

        $settings = $this->out->getSettings();
        self::assertEquals('Bankinstitut Shophausen', $settings->getBankName());
        self::assertEquals('Max Mustermann', $settings->getBankOwner());
    }

    /**
     * Tests that updateSettingBank() updates bank-related fields.
     */
    public function testUpdateSettingBank()
    {
        $model = new SettingsModel();
        $model->setBankName('New Bank');
        $model->setBankOwner('New Owner');
        $model->setBankIBAN('DE0000111122223333');
        $model->setBankBIC('NEWBIC999');

        $result = $this->out->updateSettingBank($model);

        self::assertSame(1, $result);

        $settings = $this->out->getSettings();
        self::assertEquals('New Bank', $settings->getBankName());
        self::assertEquals('New Owner', $settings->getBankOwner());
        self::assertEquals('DE0000111122223333', $settings->getBankIBAN());
        self::assertEquals('NEWBIC999', $settings->getBankBIC());
    }

    /**
     * Tests that updateSettingBank() does not affect shop fields.
     */
    public function testUpdateSettingBankDoesNotAffectShop()
    {
        $model = new SettingsModel();
        $model->setBankName('Changed Bank');

        $result = $this->out->updateSettingBank($model);

        self::assertSame(1, $result);

        $settings = $this->out->getSettings();
        self::assertEquals('ILCH Shop', $settings->getShopName());
        self::assertEquals('logo.jpg', $settings->getShopLogo());
    }

    /**
     * Tests that updateSettingDefault() updates default-related fields.
     */
    public function testUpdateSettingDefault()
    {
        $model = new SettingsModel();
        $model->setFixTax(25);
        $model->setFixShippingCosts('4.99');
        $model->setFixShippingTime(3);
        $model->setAllowWillCollect(1);
        $model->setInvoiceTextTop('New Invoice Top');
        $model->setInvoiceTextBottom('New Invoice Bottom');

        $result = $this->out->updateSettingDefault($model);

        self::assertSame(1, $result);

        $settings = $this->out->getSettings();
        self::assertEquals(25, $settings->getFixTax());
        self::assertEquals('4.99', $settings->getFixShippingCosts());
        self::assertEquals(3, $settings->getFixShippingTime());
        self::assertEquals(1, $settings->getAllowWillCollect());
        self::assertEquals('New Invoice Top', $settings->getInvoiceTextTop());
        self::assertEquals('New Invoice Bottom', $settings->getInvoiceTextBottom());
    }

    /**
     * Tests that updateSettingDefault() does not affect shop or bank fields.
     */
    public function testUpdateSettingDefaultDoesNotAffectOthers()
    {
        $model = new SettingsModel();
        $model->setFixTax(25);

        $result = $this->out->updateSettingDefault($model);

        self::assertSame(1, $result);

        $settings = $this->out->getSettings();
        self::assertEquals('ILCH Shop', $settings->getShopName());
        self::assertEquals('Bankinstitut Shophausen', $settings->getBankName());
    }

    /**
     * Tests that updateSettingAGB() updates the AGB field.
     */
    public function testUpdateSettingAGB()
    {
        $model = new SettingsModel();
        $model->setAGB('Updated AGB text');

        $result = $this->out->updateSettingAGB($model);

        self::assertSame(1, $result);

        $settings = $this->out->getSettings();
        self::assertEquals('Updated AGB text', $settings->getAGB());
    }

    /**
     * Tests that updateSettingAGB() does not affect other fields.
     */
    public function testUpdateSettingAGBDoesNotAffectOthers()
    {
        $model = new SettingsModel();
        $model->setAGB('New AGB');

        $result = $this->out->updateSettingAGB($model);

        self::assertSame(1, $result);

        $settings = $this->out->getSettings();
        self::assertEquals('ILCH Shop', $settings->getShopName());
        self::assertEquals(19, $settings->getFixTax());
    }

    /**
     * Tests that updateSettingPayment() updates payment-related fields.
     */
    public function testUpdateSettingPayment()
    {
        $model = new SettingsModel();
        $model->setClientID('new-client-id-123');
        $model->setPayPalMe('newpaypal');
        $model->setPaypalMePresetAmount(false);

        $result = $this->out->updateSettingPayment($model);

        self::assertSame(1, $result);

        $settings = $this->out->getSettings();
        self::assertEquals('new-client-id-123', $settings->getClientID());
        self::assertEquals('newpaypal', $settings->getPayPalMe());
        self::assertFalse($settings->isPaypalMePresetAmount());
    }

    /**
     * Tests that updateSettingPayment() does not affect shop fields.
     */
    public function testUpdateSettingPaymentDoesNotAffectShop()
    {
        $model = new SettingsModel();
        $model->setClientID('test-client');

        $result = $this->out->updateSettingPayment($model);

        self::assertSame(1, $result);

        $settings = $this->out->getSettings();
        self::assertEquals('ILCH Shop', $settings->getShopName());
        self::assertEquals('Bankinstitut Shophausen', $settings->getBankName());
    }

    /**
     * Tests that the update methods return null when the settings row does not exist.
     */
    public function testUpdateMethodsReturnNullWithoutSettingsRow()
    {
        $this->db->delete('shop_settings')
            ->where(['id' => 1])
            ->execute();

        $model = new SettingsModel();

        self::assertNull($this->out->updateSettingShop($model));
        self::assertNull($this->out->updateSettingBank($model));
        self::assertNull($this->out->updateSettingDefault($model));
        self::assertNull($this->out->updateSettingAGB($model));
        self::assertNull($this->out->updateSettingPayment($model));
        self::assertNull($this->out->keepSampleData());
    }

    /**
     * Tests that deleteSampleData() sets ifSampleData to 0.
     */
    public function testDeleteSampleDataSetsFlag()
    {
        $deleted = $this->out->deleteSampleData();

        self::assertIsInt($deleted);
        self::assertGreaterThan(0, $deleted);

        $settings = $this->out->getSettings();
        self::assertEquals(0, $settings->getIfSampleData());
    }

    /**
     * Tests that deleteSampleData() removes sample categories.
     */
    public function testDeleteSampleDataRemovesCats()
    {
        $countBefore = $this->db->select('COUNT(*)')
            ->from('shop_cats')
            ->execute()
            ->fetchCell();

        self::assertEquals(3, (int)$countBefore);

        $deleted = $this->out->deleteSampleData();

        self::assertIsInt($deleted);
        self::assertGreaterThan(0, $deleted);

        $countAfter = $this->db->select('COUNT(*)')
            ->from('shop_cats')
            ->execute()
            ->fetchCell();

        self::assertEquals(0, (int)$countAfter);
    }

    /**
     * Tests that deleteSampleData() removes sample items.
     */
    public function testDeleteSampleDataRemovesItems()
    {
        $countBefore = $this->db->select('COUNT(*)')
            ->from('shop_items')
            ->execute()
            ->fetchCell();

        self::assertEquals(7, (int)$countBefore);

        $deleted = $this->out->deleteSampleData();

        self::assertIsInt($deleted);
        self::assertGreaterThan(0, $deleted);

        $countAfter = $this->db->select('COUNT(*)')
            ->from('shop_items')
            ->execute()
            ->fetchCell();

        self::assertEquals(0, (int)$countAfter);
    }

    /**
     * Tests that deleteSampleData() removes sample customers.
     */
    public function testDeleteSampleDataRemovesCustomers()
    {
        $countBefore = $this->db->select('COUNT(*)')
            ->from('shop_customers')
            ->execute()
            ->fetchCell();

        self::assertEquals(4, (int)$countBefore);

        $deleted = $this->out->deleteSampleData();

        self::assertIsInt($deleted);
        self::assertGreaterThan(0, $deleted);

        $countAfter = $this->db->select('COUNT(*)')
            ->from('shop_customers')
            ->execute()
            ->fetchCell();

        self::assertEquals(0, (int)$countAfter);
    }

    /**
     * Tests that deleteSampleData() removes sample orders.
     */
    public function testDeleteSampleDataRemovesOrders()
    {
        $countBefore = $this->db->select('COUNT(*)')
            ->from('shop_orders')
            ->execute()
            ->fetchCell();

        self::assertEquals(4, (int)$countBefore);

        $deleted = $this->out->deleteSampleData();

        self::assertIsInt($deleted);
        self::assertGreaterThan(0, $deleted);

        $countAfter = $this->db->select('COUNT(*)')
            ->from('shop_orders')
            ->execute()
            ->fetchCell();

        self::assertEquals(0, (int)$countAfter);
    }

    /**
     * Tests that keepSampleData() sets ifSampleData to 0 without deleting rows.
     */
    public function testKeepSampleData()
    {
        $result = $this->out->keepSampleData();

        self::assertSame(1, $result);

        $settings = $this->out->getSettings();
        self::assertEquals(0, $settings->getIfSampleData());

        // Categories should still exist
        $count = $this->db->select('COUNT(*)')
            ->from('shop_cats')
            ->execute()
            ->fetchCell();

        self::assertEquals(3, (int)$count);
    }

    /**
     * Tests that keepSampleData() does not delete sample items.
     */
    public function testKeepSampleDataDoesNotDeleteItems()
    {
        $result = $this->out->keepSampleData();

        self::assertSame(1, $result);

        $count = $this->db->select('COUNT(*)')
            ->from('shop_items')
            ->execute()
            ->fetchCell();

        self::assertEquals(7, (int)$count);
    }

    /**
     * Returns database schema SQL statements to initialize database.
     *
     * @return string
     */
    protected static function getSchemaSQLQueries(): string
    {
        $coreSql = 'CREATE TABLE IF NOT EXISTS `[prefix]_emails` (
                    `moduleKey` VARCHAR(255) NOT NULL,
                    `type` VARCHAR(255) NOT NULL,
                    `desc` VARCHAR(255) NOT NULL,
                    `text` TEXT NOT NULL,
                    `locale` VARCHAR(255) NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

                CREATE TABLE IF NOT EXISTS `[prefix]_groups` (
                    `id` INT(11) NOT NULL AUTO_INCREMENT,
                    `name` VARCHAR(255) NOT NULL,
                    PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

                INSERT INTO `[prefix]_groups` (`id`, `name`) VALUES
                    (1, "Admin"),
                    (2, "Member"),
                    (3, "Guest");';

        $config = new ModuleConfig();

        return $coreSql . "\n" . $config->getInstallSql();
    }
}
