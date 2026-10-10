<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Admin\Models;

use PHPUnit\Framework\TestCase;

class UpdateserverModelTest extends TestCase
{
    /**
     * Tests that setId() sets and returns the id.
     */
    public function testSetId()
    {
        $model = new Updateserver();
        $model->setId(5);

        self::assertSame(5, $model->getId());
    }

    /**
     * Tests that setId() casts to int.
     */
    public function testSetIdCastsToInt()
    {
        $model = new Updateserver();
        $model->setId('42');

        self::assertSame(42, $model->getId());
        self::assertIsInt($model->getId());
    }

    /**
     * Tests that setURL() stores the url.
     */
    public function testSetURL()
    {
        $model = new Updateserver();
        $model->setURL('https://updates.example.com');

        self::assertSame('https://updates.example.com', $model->getURL());
    }

    /**
     * Tests that setOperator() stores the operator.
     */
    public function testSetOperator()
    {
        $model = new Updateserver();
        $model->setOperator('Example Operator');

        self::assertSame('Example Operator', $model->getOperator());
    }

    /**
     * Tests that setCountry() stores the country.
     */
    public function testSetCountry()
    {
        $model = new Updateserver();
        $model->setCountry('Germany');

        self::assertSame('Germany', $model->getCountry());
    }

    /**
     * Tests that overwriting previously set values works.
     */
    public function testOverwriteValues()
    {
        $model = new Updateserver();
        $model->setId(1);
        $model->setURL('https://old.example.com');
        $model->setOperator('Old');
        $model->setCountry('Old Country');

        $model->setId(2);
        $model->setURL('https://new.example.com');
        $model->setOperator('New');
        $model->setCountry('New Country');

        self::assertSame(2, $model->getId());
        self::assertSame('https://new.example.com', $model->getURL());
        self::assertSame('New', $model->getOperator());
        self::assertSame('New Country', $model->getCountry());
    }

    /**
     * Tests that default values are null.
     */
    public function testDefaultValues()
    {
        $model = new Updateserver();

        self::assertNull($model->getId());
        self::assertNull($model->getURL());
        self::assertNull($model->getOperator());
        self::assertNull($model->getCountry());
    }
}
