<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Shop\Models;

use PHPUnit\Framework\TestCase;
use Modules\Shop\Models\Address as AddressModel;

class AddressModelTest extends TestCase
{
    /**
     * Tests that default values are null or empty.
     */
    public function testDefaultValues(): void
    {
        $model = new AddressModel();

        self::assertNull($model->getId());
        self::assertSame(0, $model->getCustomerID());
        self::assertSame('', $model->getPrename());
        self::assertSame('', $model->getLastname());
        self::assertSame('', $model->getStreet());
        self::assertSame('', $model->getPostcode());
        self::assertSame('', $model->getCity());
        self::assertSame('', $model->getCountry());
    }

    /**
     * Tests that setId() sets and returns the id.
     */
    public function testSetId(): void
    {
        $model = new AddressModel();
        $model->setId(5);

        self::assertSame(5, $model->getId());
    }

    /**
     * Tests that setId(0) stores zero.
     */
    public function testSetIdZero(): void
    {
        $model = new AddressModel();
        $model->setId(0);

        self::assertSame(0, $model->getId());
    }

    /**
     * Tests that setCustomerID() sets and returns the customer id.
     */
    public function testSetCustomerID(): void
    {
        $model = new AddressModel();
        $model->setCustomerID(7);

        self::assertSame(7, $model->getCustomerID());
    }

    /**
     * Tests that setPrename() sets and returns the prename.
     */
    public function testSetPrename(): void
    {
        $model = new AddressModel();
        $model->setPrename('Max');

        self::assertSame('Max', $model->getPrename());
    }

    /**
     * Tests that setLastname() sets and returns the lastname.
     */
    public function testSetLastname(): void
    {
        $model = new AddressModel();
        $model->setLastname('Mustermann');

        self::assertSame('Mustermann', $model->getLastname());
    }

    /**
     * Tests that setStreet() sets and returns the street.
     */
    public function testSetStreet(): void
    {
        $model = new AddressModel();
        $model->setStreet('Musterstr. 1');

        self::assertSame('Musterstr. 1', $model->getStreet());
    }

    /**
     * Tests that setPostcode() sets and returns the postcode.
     */
    public function testSetPostcode(): void
    {
        $model = new AddressModel();
        $model->setPostcode('12345');

        self::assertSame('12345', $model->getPostcode());
    }

    /**
     * Tests that setCity() sets and returns the city.
     */
    public function testSetCity(): void
    {
        $model = new AddressModel();
        $model->setCity('Musterstadt');

        self::assertSame('Musterstadt', $model->getCity());
    }

    /**
     * Tests that setCountry() sets and returns the country.
     */
    public function testSetCountry(): void
    {
        $model = new AddressModel();
        $model->setCountry('Deutschland');

        self::assertSame('Deutschland', $model->getCountry());
    }

    /**
     * Tests that Address setters are chainable.
     */
    public function testSettersReturnSelf(): void
    {
        $model = new AddressModel();

        self::assertSame($model, $model->setId(1));
        self::assertSame($model, $model->setCustomerID(2));
        self::assertSame($model, $model->setPrename('Max'));
        self::assertSame($model, $model->setLastname('Mustermann'));
        self::assertSame($model, $model->setStreet('Musterstr. 1'));
        self::assertSame($model, $model->setPostcode('12345'));
        self::assertSame($model, $model->setCity('Musterstadt'));
        self::assertSame($model, $model->setCountry('Deutschland'));
    }

    /**
     * Tests that chaining setters builds a complete model.
     */
    public function testChainedSetters(): void
    {
        $model = (new AddressModel())
            ->setId(1)
            ->setCustomerID(2)
            ->setPrename('Max')
            ->setLastname('Mustermann')
            ->setStreet('Musterstr. 1')
            ->setPostcode('12345')
            ->setCity('Musterstadt')
            ->setCountry('Deutschland');

        self::assertSame(1, $model->getId());
        self::assertSame(2, $model->getCustomerID());
        self::assertSame('Max', $model->getPrename());
        self::assertSame('Mustermann', $model->getLastname());
        self::assertSame('Musterstr. 1', $model->getStreet());
        self::assertSame('12345', $model->getPostcode());
        self::assertSame('Musterstadt', $model->getCity());
        self::assertSame('Deutschland', $model->getCountry());
    }

    /**
     * Tests that overwriting previously set values works.
     */
    public function testOverwriteValues(): void
    {
        $model = new AddressModel();

        $model
            ->setId(1)
            ->setCustomerID(2)
            ->setPrename('Old')
            ->setLastname('Old')
            ->setStreet('Old Street')
            ->setPostcode('00000')
            ->setCity('Old City')
            ->setCountry('Old Country');

        $model
            ->setId(3)
            ->setCustomerID(4)
            ->setPrename('New')
            ->setLastname('New')
            ->setStreet('New Street')
            ->setPostcode('11111')
            ->setCity('New City')
            ->setCountry('New Country');

        self::assertSame(3, $model->getId());
        self::assertSame(4, $model->getCustomerID());
        self::assertSame('New', $model->getPrename());
        self::assertSame('New', $model->getLastname());
        self::assertSame('New Street', $model->getStreet());
        self::assertSame('11111', $model->getPostcode());
        self::assertSame('New City', $model->getCity());
        self::assertSame('New Country', $model->getCountry());
    }
}
