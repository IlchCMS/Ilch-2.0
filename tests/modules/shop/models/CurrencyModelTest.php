<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Shop\Models;

use PHPUnit\Framework\TestCase;
use Modules\Shop\Models\Currency as CurrencyModel;

class CurrencyModelTest extends TestCase
{
    /**
     * Tests that default values are null or empty.
     */
    public function testDefaultValues(): void
    {
        $model = new CurrencyModel();

        self::assertNull($model->getId());
        self::assertSame('', $model->getName());
        self::assertNull($model->getCode());
    }

    /**
     * Tests that setId() sets the id.
     */
    public function testSetId(): void
    {
        $model = new CurrencyModel();
        $model->setId(5);

        self::assertSame(5, $model->getId());
    }

    /**
     * Tests that setId(0) stores zero.
     */
    public function testSetIdZero(): void
    {
        $model = new CurrencyModel();
        $model->setId(0);

        self::assertSame(0, $model->getId());
    }

    /**
     * Tests that setName() sets the name.
     */
    public function testSetName(): void
    {
        $model = new CurrencyModel();
        $model->setName('EUR (€)');

        self::assertSame('EUR (€)', $model->getName());
    }

    /**
     * Tests that setCode() sets the currency code.
     */
    public function testSetCode(): void
    {
        $model = new CurrencyModel();
        $model->setCode('EUR');

        self::assertSame('EUR', $model->getCode());
    }

    /**
     * Tests that overwriting previously set values works.
     */
    public function testOverwriteValues(): void
    {
        $model = new CurrencyModel();

        $model->setId(1);
        $model->setName('Old Name');
        $model->setCode('OLD');

        $model->setId(2);
        $model->setName('New Name');
        $model->setCode('NEW');

        self::assertSame(2, $model->getId());
        self::assertSame('New Name', $model->getName());
        self::assertSame('NEW', $model->getCode());
    }
}
