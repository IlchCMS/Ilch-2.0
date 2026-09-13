<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Events\Tests;

use PHPUnit\Framework\TestCase;
use Modules\Events\Models\Currency;

class CurrencyModelTest extends TestCase
{
    /**
     * Tests that default property values are correct.
     */
    public function testDefaults()
    {
        $model = new Currency();

        self::assertEquals(0, $model->getId());
        self::assertEquals('', $model->getName());
    }

    /**
     * Tests that setId() stores the value and returns $this.
     */
    public function testSetId()
    {
        $model = new Currency();
        $result = $model->setId(5);

        self::assertSame($model, $result);
        self::assertEquals(5, $model->getId());
    }

    /**
     * Tests that setName() stores the value and returns $this.
     */
    public function testSetName()
    {
        $model = new Currency();
        $result = $model->setName('EUR (€)');

        self::assertSame($model, $result);
        self::assertEquals('EUR (€)', $model->getName());
    }

    /**
     * Tests that setName() accepts an empty string.
     */
    public function testSetNameEmptyString()
    {
        $model = new Currency();
        $result = $model->setName('');

        self::assertSame($model, $result);
        self::assertEquals('', $model->getName());
    }

    /**
     * Tests that setters are chainable.
     */
    public function testSettersAreChainable()
    {
        $model = new Currency();
        $result = $model->setId(3)->setName('USD ($)');

        self::assertSame($model, $result);
        self::assertEquals(3, $model->getId());
        self::assertEquals('USD ($)', $model->getName());
    }

    /**
     * Tests that setByArray() populates all fields from a complete array.
     */
    public function testSetByArrayComplete()
    {
        $model = new Currency();
        $result = $model->setByArray([
            'id'   => 7,
            'name' => 'CHF',
        ]);

        self::assertSame($model, $result);
        self::assertEquals(7, $model->getId());
        self::assertEquals('CHF', $model->getName());
    }

    /**
     * Tests that setByArray() only updates keys that are present.
     */
    public function testSetByArrayPartial()
    {
        $model = new Currency();
        $model->setId(99);
        $model->setName('Original');

        $model->setByArray([
            'name' => 'Changed Name',
        ]);

        self::assertEquals(99, $model->getId());
        self::assertEquals('Changed Name', $model->getName());
    }

    /**
     * Tests that setByArray() with an empty array leaves defaults intact.
     */
    public function testSetByArrayEmpty()
    {
        $model = new Currency();
        $model->setByArray([]);

        self::assertEquals(0, $model->getId());
        self::assertEquals('', $model->getName());
    }

    /**
     * Tests that getArray() includes id when $withId is true (default).
     */
    public function testGetArrayWithId()
    {
        $model = new Currency();
        $model->setId(4);
        $model->setName('GBP (£)');

        $array = $model->getArray();

        self::assertEquals([
            'id'   => 4,
            'name' => 'GBP (£)',
        ], $array);
    }

    /**
     * Tests that getArray() excludes id when $withId is false.
     */
    public function testGetArrayWithoutId()
    {
        $model = new Currency();
        $model->setId(4);
        $model->setName('GBP (£)');

        $array = $model->getArray(false);

        self::assertArrayNotHasKey('id', $array);
        self::assertEquals([
            'name' => 'GBP (£)',
        ], $array);
    }

    /**
     * Tests that getArray() with defaults returns id=0 and empty name.
     */
    public function testGetArrayDefaults()
    {
        $model = new Currency();

        self::assertEquals([
            'id'   => 0,
            'name' => '',
        ], $model->getArray());
    }

    /**
     * Tests that getArray(false) with defaults returns only empty name.
     */
    public function testGetArrayWithoutIdDefaults()
    {
        $model = new Currency();

        self::assertEquals([
            'name' => '',
        ], $model->getArray(false));
    }

    /**
     * Tests a full round-trip: set → getArray → setByArray → getArray.
     */
    public function testFullRoundTrip()
    {
        $model = new Currency();
        $model->setId(10);
        $model->setName('NZD ($)');

        $array = $model->getArray();

        $model2 = new Currency();
        $model2->setByArray($array);

        self::assertEquals(10, $model2->getId());
        self::assertEquals('NZD ($)', $model2->getName());
        self::assertEquals($model->getArray(), $model2->getArray());
    }
}
