<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Contact\Models;

use PHPUnit\Framework\TestCase;
use Modules\Contact\Models\Receiver as ReceiverModel;

class ReceiverModelTest extends TestCase
{
    /**
     * Tests that setId() sets and returns the id.
     */
    public function testSetId()
    {
        $model = new ReceiverModel();
        $model->setId(5);

        self::assertSame(5, $model->getId());
    }

    /**
     * Tests that setId() casts to int.
     */
    public function testSetIdCastsToInt()
    {
        $model = new ReceiverModel();
        $model->setId('42');

        self::assertSame(42, $model->getId());
        self::assertIsInt($model->getId());
    }

    /**
     * Tests that setName() sets and returns the name.
     */
    public function testSetName()
    {
        $model = new ReceiverModel();
        $model->setName('Webmaster');

        self::assertSame('Webmaster', $model->getName());
    }

    /**
     * Tests that setName() casts to string.
     */
    public function testSetNameCastsToString()
    {
        $model = new ReceiverModel();
        $model->setName(123);

        self::assertSame('123', $model->getName());
        self::assertIsString($model->getName());
    }

    /**
     * Tests that setEmail() sets and returns the email.
     */
    public function testSetEmail()
    {
        $model = new ReceiverModel();
        $model->setEmail('webmaster@example.com');

        self::assertSame('webmaster@example.com', $model->getEmail());
    }

    /**
     * Tests that setEmail() casts to string.
     */
    public function testSetEmailCastsToString()
    {
        $model = new ReceiverModel();
        $model->setEmail(456);

        self::assertSame('456', $model->getEmail());
        self::assertIsString($model->getEmail());
    }

    /**
     * Tests that setters are chainable (return $this).
     */
    public function testSettersReturnSelf()
    {
        $model = new ReceiverModel();

        self::assertSame($model, $model->setId(1));
        self::assertSame($model, $model->setName('Test'));
        self::assertSame($model, $model->setEmail('test@example.com'));
    }

    /**
     * Tests that chaining setters builds a complete model.
     */
    public function testChainedSetters()
    {
        $model = (new ReceiverModel())
            ->setId(3)
            ->setName('Support')
            ->setEmail('support@example.com');

        self::assertSame(3, $model->getId());
        self::assertSame('Support', $model->getName());
        self::assertSame('support@example.com', $model->getEmail());
    }

    /**
     * Tests that default values are 0 for id and empty string for name/email.
     */
    public function testDefaultValues()
    {
        $model = new ReceiverModel();

        self::assertNull($model->getId());
        self::assertNull($model->getName());
        self::assertNull($model->getEmail());
    }

    /**
     * Tests that setId(0) stores zero (falsy but valid).
     */
    public function testSetIdZero()
    {
        $model = new ReceiverModel();
        $model->setId(0);

        self::assertSame(0, $model->getId());
    }

    /**
     * Tests that overwriting a previously set value works.
     */
    public function testOverwriteValues()
    {
        $model = new ReceiverModel();
        $model->setId(1)->setName('Old')->setEmail('old@example.com');

        $model->setId(2)->setName('New')->setEmail('new@example.com');

        self::assertSame(2, $model->getId());
        self::assertSame('New', $model->getName());
        self::assertSame('new@example.com', $model->getEmail());
    }
}
