<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Shop\Models;

use PHPUnit\Framework\TestCase;
use Modules\Shop\Models\Property as PropertyModel;

class PropertyModelTest extends TestCase
{
    /**
     * Tests that default values are null, empty, or false.
     */
    public function testDefaultValues(): void
    {
        $model = new PropertyModel();

        self::assertNull($model->getId());
        self::assertSame('', $model->getName());
        self::assertFalse($model->isEnabled());
    }

    /**
     * Tests that setId() sets the id.
     */
    public function testSetId(): void
    {
        $model = new PropertyModel();
        $model->setId(5);

        self::assertSame(5, $model->getId());
    }

    /**
     * Tests that setId(0) stores zero.
     */
    public function testSetIdZero(): void
    {
        $model = new PropertyModel();
        $model->setId(0);

        self::assertSame(0, $model->getId());
    }

    /**
     * Tests that setName() sets the name.
     */
    public function testSetName(): void
    {
        $model = new PropertyModel();
        $model->setName('Size');

        self::assertSame('Size', $model->getName());
    }

    /**
     * Tests that setEnabled() enables the property.
     */
    public function testSetEnabledTrue(): void
    {
        $model = new PropertyModel();
        $model->setEnabled(true);

        self::assertTrue($model->isEnabled());
    }

    /**
     * Tests that setEnabled() disables the property.
     */
    public function testSetEnabledFalse(): void
    {
        $model = new PropertyModel();
        $model->setEnabled(false);

        self::assertFalse($model->isEnabled());
    }

    /**
     * Tests that overwriting previously set values works.
     */
    public function testOverwriteValues(): void
    {
        $model = new PropertyModel();

        $model->setId(1);
        $model->setName('Old');
        $model->setEnabled(true);

        $model->setId(2);
        $model->setName('New');
        $model->setEnabled(false);

        self::assertSame(2, $model->getId());
        self::assertSame('New', $model->getName());
        self::assertFalse($model->isEnabled());
    }
}
