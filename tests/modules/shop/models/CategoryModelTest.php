<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Shop\Models;

use PHPUnit\Framework\TestCase;
use Modules\Shop\Models\Category as CategoryModel;

class CategoryModelTest extends TestCase
{
    /**
     * Tests that default values are null or empty.
     */
    public function testDefaultValues(): void
    {
        $model = new CategoryModel();

        self::assertNull($model->getId());
        self::assertSame(0, $model->getPos());
        self::assertSame('', $model->getTitle());
        self::assertSame('', $model->getReadAccess());
    }

    /**
     * Tests that setId() sets the id.
     */
    public function testSetId(): void
    {
        $model = new CategoryModel();
        $model->setId(5);

        self::assertSame(5, $model->getId());
    }

    /**
     * Tests that setId(0) stores zero.
     */
    public function testSetIdZero(): void
    {
        $model = new CategoryModel();
        $model->setId(0);

        self::assertSame(0, $model->getId());
    }

    /**
     * Tests that setPos() sets the position.
     */
    public function testSetPos(): void
    {
        $model = new CategoryModel();
        $model->setPos(3);

        self::assertSame(3, $model->getPos());
    }

    /**
     * Tests that setTitle() sets the title.
     */
    public function testSetTitle(): void
    {
        $model = new CategoryModel();
        $model->setTitle('T-Shirts');

        self::assertSame('T-Shirts', $model->getTitle());
    }

    /**
     * Tests that setReadAccess() sets the read access value.
     */
    public function testSetReadAccess(): void
    {
        $model = new CategoryModel();
        $model->setReadAccess('admin');

        self::assertSame('admin', $model->getReadAccess());
    }

    /**
     * Tests that setReadAccess() returns the model instance.
     */
    public function testSetReadAccessReturnsSelf(): void
    {
        $model = new CategoryModel();

        self::assertSame($model, $model->setReadAccess('admin'));
    }

    /**
     * Tests that overwriting previously set values works.
     */
    public function testOverwriteValues(): void
    {
        $model = new CategoryModel();

        $model->setId(1);
        $model->setPos(1);
        $model->setTitle('Old');
        $model->setReadAccess('old');

        $model->setId(2);
        $model->setPos(2);
        $model->setTitle('New');
        $model->setReadAccess('new');

        self::assertSame(2, $model->getId());
        self::assertSame(2, $model->getPos());
        self::assertSame('New', $model->getTitle());
        self::assertSame('new', $model->getReadAccess());
    }
}
