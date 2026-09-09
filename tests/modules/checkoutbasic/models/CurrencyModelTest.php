<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Checkoutbasic\Models;

use PHPUnit\Framework\TestCase;
use Modules\Checkoutbasic\Models\Currency as CurrencyModel;

class CurrencyModelTest extends TestCase
{
    /**
     * Tests default property values.
     */
    public function testDefaults()
    {
        $model = new CurrencyModel();

        self::assertEquals(0, $model->getId());
        self::assertEquals('', $model->getName());
    }

    /**
     * Tests that setters are chainable.
     */
    public function testSettersAreChainable()
    {
        $model = new CurrencyModel();
        $result = $model->setId(4)->setName('JPY (¥)');

        self::assertSame($model, $result);
        self::assertEquals(4, $model->getId());
        self::assertEquals('JPY (¥)', $model->getName());
    }

    /**
     * Tests setByArray() with a complete array.
     */
    public function testSetByArrayComplete()
    {
        $model = new CurrencyModel();
        $model->setByArray([
            'id'   => 7,
            'name' => 'CHF',
        ]);

        self::assertEquals(7, $model->getId());
        self::assertEquals('CHF', $model->getName());
    }

    /**
     * Tests setByArray() with missing keys preserves defaults.
     */
    public function testSetByArrayPartial()
    {
        $model = new CurrencyModel();
        $model->setByArray([
            'name' => 'JPY (¥)',
        ]);

        self::assertEquals(0, $model->getId());
        self::assertEquals('JPY (¥)', $model->getName());
    }

    /**
     * Tests setByArray() returns $this for chaining.
     */
    public function testSetByArrayReturnsSelf()
    {
        $model = new CurrencyModel();
        $result = $model->setByArray(['id' => 1, 'name' => 'EUR']);

        self::assertSame($model, $result);
    }

    /**
     * Tests getArray() includes id by default.
     */
    public function testGetArrayWithId()
    {
        $model = new CurrencyModel();
        $model->setId(3)->setName('GBP (£)');

        self::assertEquals([
            'id'   => 3,
            'name' => 'GBP (£)',
        ], $model->getArray());
    }

    /**
     * Tests getArray() excludes id when $withId is false.
     */
    public function testGetArrayWithoutId()
    {
        $model = new CurrencyModel();
        $model->setId(3)->setName('GBP (£)');

        $array = $model->getArray(false);

        self::assertArrayNotHasKey('id', $array);
        self::assertEquals(['name' => 'GBP (£)'], $array);
    }
}
