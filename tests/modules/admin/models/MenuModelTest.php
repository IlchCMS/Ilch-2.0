<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Admin\Models;

use PHPUnit\Framework\TestCase;

class MenuModelTest extends TestCase
{
    /**
     * Tests that setId() sets and returns the id.
     */
    public function testSetId()
    {
        $model = new Menu();
        $model->setId(5);

        self::assertSame(5, $model->getId());
    }

    /**
     * Tests that setId() casts to int.
     */
    public function testSetIdCastsToInt()
    {
        $model = new Menu();
        $model->setId('42');

        self::assertSame(42, $model->getId());
        self::assertIsInt($model->getId());
    }

    /**
     * Tests that setTitle() sets and returns the title.
     */
    public function testSetTitle()
    {
        $model = new Menu();
        $model->setTitle('Main');

        self::assertSame('Main', $model->getTitle());
    }

    /**
     * Tests that setTitle() casts to string.
     */
    public function testSetTitleCastsToString()
    {
        $model = new Menu();
        $model->setTitle(123);

        self::assertSame('123', $model->getTitle());
        self::assertIsString($model->getTitle());
    }

    /**
     * Tests that overwriting previously set values works.
     */
    public function testOverwriteValues()
    {
        $model = new Menu();
        $model->setId(1);
        $model->setTitle('Old');

        $model->setId(2);
        $model->setTitle('New');

        self::assertSame(2, $model->getId());
        self::assertSame('New', $model->getTitle());
    }

    /**
     * Tests that default values are null.
     */
    public function testDefaultValues()
    {
        $model = new Menu();

        self::assertNull($model->getId());
        self::assertNull($model->getTitle());
    }
}
