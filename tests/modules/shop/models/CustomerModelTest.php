<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Shop\Models;

use PHPUnit\Framework\TestCase;
use Modules\Shop\Models\Customer as CustomerModel;

class CustomerModelTest extends TestCase
{
    /**
     * Tests that default values are null or empty.
     */
    public function testDefaultValues(): void
    {
        $model = new CustomerModel();

        self::assertNull($model->getId());
        self::assertSame(0, $model->getUserId());
        self::assertSame('', $model->getEmail());
    }

    /**
     * Tests that setId() sets the id.
     */
    public function testSetId(): void
    {
        $model = new CustomerModel();
        $model->setId(5);

        self::assertSame(5, $model->getId());
    }

    /**
     * Tests that setId(0) stores zero.
     */
    public function testSetIdZero(): void
    {
        $model = new CustomerModel();
        $model->setId(0);

        self::assertSame(0, $model->getId());
    }

    /**
     * Tests that setUserId() sets the user id.
     */
    public function testSetUserId(): void
    {
        $model = new CustomerModel();
        $model->setUserId(7);

        self::assertSame(7, $model->getUserId());
    }

    /**
     * Tests that setEmail() sets the email.
     */
    public function testSetEmail(): void
    {
        $model = new CustomerModel();
        $model->setEmail('max@mustermann.de');

        self::assertSame('max@mustermann.de', $model->getEmail());
    }

    /**
     * Tests that Customer setters are chainable.
     */
    public function testSettersReturnSelf(): void
    {
        $model = new CustomerModel();

        self::assertSame($model, $model->setId(1));
        self::assertSame($model, $model->setUserId(2));
        self::assertSame($model, $model->setEmail('test@example.com'));
    }

    /**
     * Tests that chaining setters builds a complete model.
     */
    public function testChainedSetters(): void
    {
        $model = (new CustomerModel())
            ->setId(3)
            ->setUserId(4)
            ->setEmail('customer@example.com');

        self::assertSame(3, $model->getId());
        self::assertSame(4, $model->getUserId());
        self::assertSame('customer@example.com', $model->getEmail());
    }

    /**
     * Tests that overwriting previously set values works.
     */
    public function testOverwriteValues(): void
    {
        $model = new CustomerModel();

        $model
            ->setId(1)
            ->setUserId(2)
            ->setEmail('old@example.com');

        $model
            ->setId(3)
            ->setUserId(4)
            ->setEmail('new@example.com');

        self::assertSame(3, $model->getId());
        self::assertSame(4, $model->getUserId());
        self::assertSame('new@example.com', $model->getEmail());
    }
}
