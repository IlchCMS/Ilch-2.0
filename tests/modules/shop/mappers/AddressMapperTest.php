<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Shop\Mappers;

use PHPUnit\Ilch\PhpunitDataset;
use PHPUnit\Ilch\DatabaseTestCase;
use Modules\Shop\Config\Config as ModuleConfig;
use Modules\Shop\Mappers\Address as AddressMapper;
use Modules\Shop\Models\Address as AddressModel;

class AddressMapperTest extends DatabaseTestCase
{
    /**
     * @var AddressMapper
     */
    protected Address $out;
    protected PhpunitDataset $phpunitDataset;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database.yml');
        $this->out = new AddressMapper();
    }

    /**
     * Tests that getAddresses() returns all addresses.
     */
    public function testGetAddresses()
    {
        $addresses = $this->out->getAddresses();

        self::assertIsArray($addresses);
        self::assertCount(4, $addresses);
        self::assertInstanceOf(AddressModel::class, $addresses[0]);
    }

    /**
     * Tests that getAddresses() returns correct fields for the first address.
     */
    public function testGetAddressesFields()
    {
        $addresses = $this->out->getAddresses();

        self::assertEquals(1, $addresses[0]->getId());
        self::assertEquals(1, $addresses[0]->getCustomerID());
        self::assertEquals('Max', $addresses[0]->getPrename());
        self::assertEquals('Mustermann', $addresses[0]->getLastname());
        self::assertEquals('Musterstr. 1', $addresses[0]->getStreet());
        self::assertEquals('12345', $addresses[0]->getPostcode());
        self::assertEquals('Musterstadt', $addresses[0]->getCity());
        self::assertEquals('Deutschland', $addresses[0]->getCountry());
    }

    /**
     * Tests that getAddresses() returns correct fields for the second address.
     */
    public function testGetAddressesSecond()
    {
        $addresses = $this->out->getAddresses();

        self::assertEquals(2, $addresses[1]->getId());
        self::assertEquals(2, $addresses[1]->getCustomerID());
        self::assertEquals('Eva', $addresses[1]->getPrename());
        self::assertEquals('Musterfrau', $addresses[1]->getLastname());
        self::assertEquals('Musterstr. 7', $addresses[1]->getStreet());
        self::assertEquals('98765', $addresses[1]->getPostcode());
        self::assertEquals('Musterhausen', $addresses[1]->getCity());
        self::assertEquals('Deutschland', $addresses[1]->getCountry());
    }

    /**
     * Tests that getAddresses() returns an empty array when no addresses exist.
     */
    public function testGetAddressesEmpty()
    {
        $this->out->delete(1);
        $this->out->delete(2);
        $this->out->delete(3);
        $this->out->delete(4);

        $addresses = $this->out->getAddresses();

        self::assertIsArray($addresses);
        self::assertCount(0, $addresses);
    }

    /**
     * Tests that getAddressById() returns the correct address.
     */
    public function testGetAddressById()
    {
        $address = $this->out->getAddressById(1);

        self::assertInstanceOf(AddressModel::class, $address);
        self::assertEquals(1, $address->getId());
        self::assertEquals(1, $address->getCustomerID());
        self::assertEquals('Max', $address->getPrename());
        self::assertEquals('Mustermann', $address->getLastname());
        self::assertEquals('Musterstr. 1', $address->getStreet());
        self::assertEquals('12345', $address->getPostcode());
        self::assertEquals('Musterstadt', $address->getCity());
        self::assertEquals('Deutschland', $address->getCountry());
    }

    /**
     * Tests that getAddressById() returns false for a non-existent id.
     */
    public function testGetAddressByIdNotFound()
    {
        $address = $this->out->getAddressById(9999);

        self::assertFalse($address);
    }

    /**
     * Tests that getAddressesByCustomerId() returns addresses for the given customer.
     */
    public function testGetAddressesByCustomerId()
    {
        $addresses = $this->out->getAddressesByCustomerId(1);

        self::assertIsArray($addresses);
        self::assertCount(1, $addresses);
        self::assertEquals(1, $addresses[0]->getId());
        self::assertEquals('Max', $addresses[0]->getPrename());
        self::assertEquals('Mustermann', $addresses[0]->getLastname());
    }

    /**
     * Tests that getAddressesByCustomerId() returns an empty array for a non-existent customer.
     */
    public function testGetAddressesByCustomerIdNotFound()
    {
        $addresses = $this->out->getAddressesByCustomerId(9999);

        self::assertIsArray($addresses);
        self::assertCount(0, $addresses);
    }

    /**
     * Tests inserting a new address via save().
     */
    public function testSaveInsert()
    {
        $model = new AddressModel();
        $model->setId(0)
            ->setCustomerID(1)
            ->setPrename('New')
            ->setLastname('Address')
            ->setStreet('Neue Str. 1')
            ->setPostcode('99999')
            ->setCity('Neustadt')
            ->setCountry('Deutschland');

        $newId = $this->out->save($model);

        self::assertIsInt($newId);
        self::assertGreaterThan(4, $newId);

        $address = $this->out->getAddressById($newId);
        self::assertInstanceOf(AddressModel::class, $address);
        self::assertEquals($newId, $address->getId());
        self::assertEquals(1, $address->getCustomerID());
        self::assertEquals('New', $address->getPrename());
        self::assertEquals('Address', $address->getLastname());
        self::assertEquals('Neue Str. 1', $address->getStreet());
        self::assertEquals('99999', $address->getPostcode());
        self::assertEquals('Neustadt', $address->getCity());
        self::assertEquals('Deutschland', $address->getCountry());
    }

    /**
     * Tests updating an existing address via save().
     */
    public function testSaveUpdate()
    {
        $model = new AddressModel();
        $model->setId(1)
            ->setCustomerID(1)
            ->setPrename('Updated')
            ->setLastname('Webmaster')
            ->setStreet('Updated Str. 5')
            ->setPostcode('54321')
            ->setCity('Updatestadt')
            ->setCountry('Deutschland');

        $newId = $this->out->save($model);

        self::assertIsInt($newId);
        self::assertEquals(1, $newId);

        $address = $this->out->getAddressById(1);
        self::assertInstanceOf(AddressModel::class, $address);
        self::assertEquals(1, $address->getId());
        self::assertEquals('Updated', $address->getPrename());
        self::assertEquals('Webmaster', $address->getLastname());
        self::assertEquals('Updated Str. 5', $address->getStreet());
        self::assertEquals('54321', $address->getPostcode());
        self::assertEquals('Updatestadt', $address->getCity());
    }

    /**
     * Tests that update does not affect other addresses.
     */
    public function testSaveUpdateDoesNotAffectOthers()
    {
        $model = new AddressModel();
        $model->setId(1)
            ->setCustomerID(1)
            ->setPrename('Changed')
            ->setLastname('Name')
            ->setStreet('Changed Str. 1')
            ->setPostcode('00000')
            ->setCity('Changed City')
            ->setCountry('Changed Country');

        $newId = $this->out->save($model);

        self::assertIsInt($newId);
        self::assertEquals(1, $newId);

        $other = $this->out->getAddressById(2);
        self::assertInstanceOf(AddressModel::class, $other);
        self::assertEquals('Eva', $other->getPrename());
        self::assertEquals('Musterfrau', $other->getLastname());
        self::assertEquals('Musterstr. 7', $other->getStreet());
        self::assertEquals('98765', $other->getPostcode());
        self::assertEquals('Musterhausen', $other->getCity());
        self::assertEquals('Deutschland', $other->getCountry());
    }

    /**
     * Tests that save() with id 0 performs an insert, not an update.
     */
    public function testSaveZeroIdInserts()
    {
        $before = $this->out->getAddresses();
        self::assertCount(4, $before);

        $model = new AddressModel();
        $model->setId(0)
            ->setCustomerID(1)
            ->setPrename('New Entry')
            ->setLastname('Test')
            ->setStreet('Test Str. 1')
            ->setPostcode('11111')
            ->setCity('Teststadt')
            ->setCountry('Deutschland');

        $newId = $this->out->save($model);

        self::assertIsInt($newId);
        self::assertGreaterThan(4, $newId);

        $after = $this->out->getAddresses();
        self::assertCount(5, $after);

        // Original addresses untouched
        self::assertEquals('Max', $after[0]->getPrename());
        self::assertEquals('Eva', $after[1]->getPrename());
        self::assertEquals('Bernd', $after[2]->getPrename());
        self::assertEquals('Ingrid', $after[3]->getPrename());
    }

    /**
     * Tests that delete() removes an address.
     */
    public function testDelete()
    {
        $deleted = $this->out->delete(1);

        self::assertTrue($deleted);
        self::assertFalse($this->out->getAddressById(1));

        // Remaining addresses should still be present
        $addresses = $this->out->getAddresses();
        self::assertCount(3, $addresses);
    }

    /**
     * Tests that delete() on a non-existent id does not throw.
     */
    public function testDeleteNotFound()
    {
        $deleted = $this->out->delete(9999);

        self::assertFalse($deleted);

        // Existing addresses should be unaffected
        $addresses = $this->out->getAddresses();
        self::assertCount(4, $addresses);
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
