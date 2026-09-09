<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Checkoutbasic\Models;

use PHPUnit\Framework\TestCase;
use Modules\Checkoutbasic\Models\Entry as EntryModel;

class EntryModelTest extends TestCase
{
    /**
     * Tests default property values.
     */
    public function testDefaults()
    {
        $model = new EntryModel();

        self::assertEquals(0, $model->getId());
        self::assertEquals('', $model->getDatetime());
        self::assertEquals('', $model->getName());
        self::assertEquals('', $model->getUsage());
        self::assertEquals(0.0, $model->getAmount());
    }

    /**
     * Tests that setters are chainable.
     */
    public function testSettersAreChainable()
    {
        $model = new EntryModel();
        $result = $model->setId(1)
            ->setDatetime('2025-06-01 12:00:00')
            ->setName('Alice')
            ->setUsage('Fee')
            ->setAmount(50.00);

        self::assertSame($model, $result);
        self::assertEquals(1, $model->getId());
        self::assertEquals('2025-06-01 12:00:00', $model->getDatetime());
        self::assertEquals('Alice', $model->getName());
        self::assertEquals('Fee', $model->getUsage());
        self::assertEquals(50.00, $model->getAmount());
    }

    /**
     * Tests setByArray() with a complete array.
     *
     * Note: the array key is 'date_created' but the model property is 'datetime'.
     * This mapping is the whole point of setByArray — worth pinning down.
     */
    public function testSetByArrayComplete()
    {
        $model = new EntryModel();
        $model->setByArray([
            'id'           => 5,
            'date_created' => '2025-03-15 09:30:00',
            'name'         => 'Bob',
            'usage'        => 'Equipment',
            'amount'       => -25.50,
        ]);

        self::assertEquals(5, $model->getId());
        self::assertEquals('2025-03-15 09:30:00', $model->getDatetime());
        self::assertEquals('Bob', $model->getName());
        self::assertEquals('Equipment', $model->getUsage());
        self::assertEquals(-25.50, $model->getAmount());
    }

    /**
     * Tests setByArray() with missing keys preserves defaults.
     */
    public function testSetByArrayPartial()
    {
        $model = new EntryModel();
        $model->setByArray([
            'name'   => 'Carol',
            'amount' => 10.00,
        ]);

        self::assertEquals(0, $model->getId());
        self::assertEquals('', $model->getDatetime());
        self::assertEquals('Carol', $model->getName());
        self::assertEquals('', $model->getUsage());
        self::assertEquals(10.00, $model->getAmount());
    }

    /**
     * Tests setByArray() accepts zero values (isset() is true for 0, '', 0.0).
     *
     * This differs from the Awards model which uses !empty() and would skip zeros.
     */
    public function testSetByArrayZeroValues()
    {
        $model = new EntryModel();
        $model->setByArray([
            'id'           => 0,
            'date_created' => '',
            'name'         => '',
            'usage'        => '',
            'amount'       => 0.0,
        ]);

        // isset() returns true for all of these, so they ARE set (to their zero values)
        self::assertEquals(0, $model->getId());
        self::assertEquals('', $model->getDatetime());
        self::assertEquals('', $model->getName());
        self::assertEquals('', $model->getUsage());
        self::assertEquals(0.0, $model->getAmount());
    }

    /**
     * Tests setByArray() with a null value is NOT set (isset(null) is false).
     */
    public function testSetByArrayNullValueSkipped()
    {
        $model = new EntryModel();
        $model->setByArray([
            'id'   => null,
            'name' => 'Dave',
        ]);

        self::assertEquals(0, $model->getId());  // null → isset is false → not set
        self::assertEquals('Dave', $model->getName());
    }

    /**
     * Tests setByArray() returns $this for chaining.
     */
    public function testSetByArrayReturnsSelf()
    {
        $model = new EntryModel();
        $result = $model->setByArray(['name' => 'Eve']);

        self::assertSame($model, $result);
    }

    /**
     * Tests getArray() includes id and uses 'date_created' as the key
     * (not 'datetime').
     */
    public function testGetArrayWithId()
    {
        $model = new EntryModel();
        $model->setId(8)
            ->setDatetime('2025-04-20 11:00:00')
            ->setName('Frank')
            ->setUsage('Sponsorship')
            ->setAmount(500.00);

        $array = $model->getArray();

        self::assertEquals([
            'id'           => 8,
            'date_created' => '2025-04-20 11:00:00',
            'name'         => 'Frank',
            'usage'        => 'Sponsorship',
            'amount'       => 500.00,
        ], $array);

        // Pin the key name — this is what the mapper's INSERT/UPDATE uses
        self::assertArrayHasKey('date_created', $array);
        self::assertArrayNotHasKey('datetime', $array);
    }

    /**
     * Tests getArray() excludes id when $withId is false.
     */
    public function testGetArrayWithoutId()
    {
        $model = new EntryModel();
        $model->setId(8)
            ->setDatetime('2025-04-20 11:00:00')
            ->setName('Frank')
            ->setUsage('Sponsorship')
            ->setAmount(500.00);

        $array = $model->getArray(false);

        self::assertArrayNotHasKey('id', $array);
        self::assertEquals([
            'date_created' => '2025-04-20 11:00:00',
            'name'         => 'Frank',
            'usage'        => 'Sponsorship',
            'amount'       => 500.00,
        ], $array);
    }
}
