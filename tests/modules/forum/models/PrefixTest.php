<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Forum\Models;

use PHPUnit\Framework\TestCase;

class PrefixTest extends TestCase
{
    /**
     * Tests that setId() sets and returns the id.
     */
    public function testSetId()
    {
        $model = new Prefix();
        $model->setId(5);

        self::assertSame(5, $model->getId());
    }

    /**
     * Tests that setId() casts to int.
     */
    public function testSetIdCastsToInt()
    {
        $model = new Prefix();
        $model->setId('42');

        self::assertSame(42, $model->getId());
        self::assertIsInt($model->getId());
    }

    /**
     * Tests that setId() accepts null.
     */
    public function testSetIdNull()
    {
        $model = new Prefix();
        $model->setId(null);

        self::assertNull($model->getId());
    }

    /**
     * Tests that setId(0) stores zero (falsy but valid).
     */
    public function testSetIdZero()
    {
        $model = new Prefix();
        $model->setId(0);

        self::assertSame(0, $model->getId());
    }

    /**
     * Tests that setPrefix() sets and returns the prefix.
     */
    public function testSetPrefix()
    {
        $model = new Prefix();
        $model->setPrefix('Pinned');

        self::assertSame('Pinned', $model->getPrefix());
    }

    /**
     * Tests that setPrefix() casts to string.
     */
    public function testSetPrefixCastsToString()
    {
        $model = new Prefix();
        $model->setPrefix(123);

        self::assertSame('123', $model->getPrefix());
        self::assertIsString($model->getPrefix());
    }

    /**
     * Tests that setters are chainable (return $this).
     */
    public function testSettersReturnSelf()
    {
        $model = new Prefix();

        self::assertSame($model, $model->setId(1));
        self::assertSame($model, $model->setPrefix('Pinned'));
    }

    /**
     * Tests that chaining setters builds a complete model.
     */
    public function testChainedSetters()
    {
        $model = (new Prefix())
            ->setId(1)
            ->setPrefix('Pinned');

        self::assertSame(1, $model->getId());
        self::assertSame('Pinned', $model->getPrefix());
    }

    /**
     * Tests that default values are null for unset properties.
     */
    public function testDefaultValues()
    {
        $model = new Prefix();

        self::assertNull($model->getId());
    }

    /**
     * Tests that overwriting a previously set value works.
     */
    public function testOverwriteValues()
    {
        $model = new Prefix();
        $model->setId(1)->setPrefix('Old prefix');

        $model->setId(2)->setPrefix('New prefix');

        self::assertSame(2, $model->getId());
        self::assertSame('New prefix', $model->getPrefix());
    }
}
