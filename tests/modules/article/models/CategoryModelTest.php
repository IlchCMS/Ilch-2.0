<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Article\Models;

use PHPUnit\Framework\TestCase;
use Modules\Article\Models\Category as CategoryModel;

class CategoryModelTest extends TestCase
{
    /**
     * Tests that setId() sets and returns the id.
     */
    public function testSetId()
    {
        $model = new CategoryModel();
        $model->setId(5);

        self::assertSame(5, $model->getId());
    }

    /**
     * Tests that setId() casts to int.
     */
    public function testSetIdCastsToInt()
    {
        $model = new CategoryModel();
        $model->setId('42');

        self::assertSame(42, $model->getId());
        self::assertIsInt($model->getId());
    }

    /**
     * Tests that setName() sets and returns the name.
     */
    public function testSetName()
    {
        $model = new CategoryModel();
        $model->setName('Allgemein');

        self::assertSame('Allgemein', $model->getName());
    }

    /**
     * Tests that setName() casts to string.
     */
    public function testSetNameCastsToString()
    {
        $model = new CategoryModel();
        $model->setName(123);

        self::assertSame('123', $model->getName());
        self::assertIsString($model->getName());
    }

    /**
     * Tests that setters are chainable (return $this).
     */
    public function testSettersReturnSelf()
    {
        $model = new CategoryModel();

        self::assertSame($model, $model->setId(1));
        self::assertSame($model, $model->setName('Test'));
    }

    /**
     * Tests that chaining setters builds a complete model.
     */
    public function testChainedSetters()
    {
        $model = (new CategoryModel())
            ->setId(3)
            ->setName('Support');

        self::assertSame(3, $model->getId());
        self::assertSame('Support', $model->getName());
    }

    /**
     * Tests that default values are null.
     */
    public function testDefaultValues()
    {
        $model = new CategoryModel();

        self::assertNull($model->getId());
        self::assertNull($model->getName());
    }

    /**
     * Tests that overwriting a previously set value works.
     */
    public function testOverwriteValues()
    {
        $model = new CategoryModel();
        $model->setId(1)->setName('Old');

        $model->setId(2)->setName('New');

        self::assertSame(2, $model->getId());
        self::assertSame('New', $model->getName());
    }
}
