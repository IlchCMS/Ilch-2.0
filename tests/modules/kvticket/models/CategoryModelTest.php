<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Kvticket\Models;

use PHPUnit\Framework\TestCase;
use Modules\Kvticket\Models\Category as CategoryModel;

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
     * Tests that setTitle() sets and returns the title.
     */
    public function testSetTitle()
    {
        $model = new CategoryModel();
        $model->setTitle('Bug Report');

        self::assertSame('Bug Report', $model->getTitle());
    }

    /**
     * Tests that setTitle() casts to string.
     */
    public function testSetTitleCastsToString()
    {
        $model = new CategoryModel();
        $model->setTitle(123);

        self::assertSame('123', $model->getTitle());
        self::assertIsString($model->getTitle());
    }

    /**
     * Tests that setters are chainable (return $this).
     */
    public function testSettersReturnSelf()
    {
        $model = new CategoryModel();

        self::assertSame($model, $model->setId(1));
        self::assertSame($model, $model->setTitle('Test'));
    }

    /**
     * Tests that chaining setters builds a complete model.
     */
    public function testChainedSetters()
    {
        $model = (new CategoryModel())
            ->setId(3)
            ->setTitle('Feature Request');

        self::assertSame(3, $model->getId());
        self::assertSame('Feature Request', $model->getTitle());
    }

    /**
     * Tests that default values are null for id and empty string for title.
     */
    public function testDefaultValues()
    {
        $model = new CategoryModel();

        self::assertNull($model->getId());
        self::assertSame('', $model->getTitle());
    }

    /**
     * Tests that setId(0) stores zero (falsy but valid).
     */
    public function testSetIdZero()
    {
        $model = new CategoryModel();
        $model->setId(0);

        self::assertSame(0, $model->getId());
    }

    /**
     * Tests that overwriting a previously set value works.
     */
    public function testOverwriteValues()
    {
        $model = new CategoryModel();
        $model->setId(1)->setTitle('Old');

        $model->setId(2)->setTitle('New');

        self::assertSame(2, $model->getId());
        self::assertSame('New', $model->getTitle());
    }
}
