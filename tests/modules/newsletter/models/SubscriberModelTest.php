<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Newsletter\Models;

use PHPUnit\Framework\TestCase;
use Modules\Newsletter\Models\Subscriber as SubscriberModel;

class SubscriberModelTest extends TestCase
{
    /**
     * Tests that setId() sets and returns the id.
     */
    public function testSetId()
    {
        $model = new SubscriberModel();
        $model->setId(5);

        self::assertSame(5, $model->getId());
    }

    /**
     * Tests that setId() casts to int.
     */
    public function testSetIdCastsToInt()
    {
        $model = new SubscriberModel();
        $model->setId('42');

        self::assertSame(42, $model->getId());
        self::assertIsInt($model->getId());
    }

    /**
     * Tests that setEmail() sets and returns the email.
     */
    public function testSetEmail()
    {
        $model = new SubscriberModel();
        $model->setEmail('john@example.com');

        self::assertSame('john@example.com', $model->getEmail());
    }

    /**
     * Tests that setEmail() casts to string.
     */
    public function testSetEmailCastsToString()
    {
        $model = new SubscriberModel();
        $model->setEmail(123);

        self::assertSame('123', $model->getEmail());
        self::assertIsString($model->getEmail());
    }

    /**
     * Tests that setSelector() sets and returns the selector.
     */
    public function testSetSelector()
    {
        $model = new SubscriberModel();
        $model->setSelector('abc123def456ghi7');

        self::assertSame('abc123def456ghi7', $model->getSelector());
    }

    /**
     * Tests that setSelector() casts to string.
     */
    public function testSetSelectorCastsToString()
    {
        $model = new SubscriberModel();
        $model->setSelector(456);

        self::assertSame('456', $model->getSelector());
        self::assertIsString($model->getSelector());
    }

    /**
     * Tests that setConfirmCode() sets and returns the confirmCode.
     */
    public function testSetConfirmCode()
    {
        $model = new SubscriberModel();
        $model->setConfirmCode('hashvalue123');

        self::assertSame('hashvalue123', $model->getConfirmCode());
    }

    /**
     * Tests that setConfirmCode() casts to string.
     */
    public function testSetConfirmCodeCastsToString()
    {
        $model = new SubscriberModel();
        $model->setConfirmCode(789);

        self::assertSame('789', $model->getConfirmCode());
        self::assertIsString($model->getConfirmCode());
    }

    /**
     * Tests that setDoubleOptInDate() sets and returns the doubleOptInDate.
     */
    public function testSetDoubleOptInDate()
    {
        $model = new SubscriberModel();
        $model->setDoubleOptInDate('2024-05-01 10:00:00');

        self::assertSame('2024-05-01 10:00:00', $model->getDoubleOptInDate());
    }

    /**
     * Tests that setDoubleOptInDate() casts to string.
     */
    public function testSetDoubleOptInDateCastsToString()
    {
        $model = new SubscriberModel();
        $model->setDoubleOptInDate(1714552800);

        self::assertSame('1714552800', $model->getDoubleOptInDate());
        self::assertIsString($model->getDoubleOptInDate());
    }

    /**
     * Tests that setDoubleOptInConfirmed() sets and returns the boolean.
     */
    public function testSetDoubleOptInConfirmed()
    {
        $model = new SubscriberModel();
        $model->setDoubleOptInConfirmed(true);

        self::assertSame(true, $model->getDoubleOptInConfirmed());
    }

    /**
     * Tests that setDoubleOptInConfirmed() casts to bool.
     */
    public function testSetDoubleOptInConfirmedCastsToBool()
    {
        $model = new SubscriberModel();
        $model->setDoubleOptInConfirmed(1);

        self::assertSame(true, $model->getDoubleOptInConfirmed());
        self::assertIsBool($model->getDoubleOptInConfirmed());

        $model->setDoubleOptInConfirmed(0);

        self::assertSame(false, $model->getDoubleOptInConfirmed());
    }

    /**
     * Tests that setNewsletter() sets and returns the boolean.
     */
    public function testSetNewsletter()
    {
        $model = new SubscriberModel();
        $model->setNewsletter(true);

        self::assertSame(true, $model->getNewsletter());
    }

    /**
     * Tests that setNewsletter() casts to bool.
     */
    public function testSetNewsletterCastsToBool()
    {
        $model = new SubscriberModel();
        $model->setNewsletter(1);

        self::assertSame(true, $model->getNewsletter());
        self::assertIsBool($model->getNewsletter());

        $model->setNewsletter(0);

        self::assertSame(false, $model->getNewsletter());
    }

    /**
     * Tests that setters are chainable (return $this).
     */
    public function testSettersReturnSelf()
    {
        $model = new SubscriberModel();

        self::assertSame($model, $model->setId(1));
        self::assertSame($model, $model->setEmail('test@example.com'));
        self::assertSame($model, $model->setSelector('sel123'));
        self::assertSame($model, $model->setConfirmCode('code456'));
        self::assertSame($model, $model->setDoubleOptInDate('2024-01-01 00:00:00'));
        self::assertSame($model, $model->setDoubleOptInConfirmed(true));
        self::assertSame($model, $model->setNewsletter(false));
    }

    /**
     * Tests that chaining setters builds a complete model.
     */
    public function testChainedSetters()
    {
        $model = (new SubscriberModel())
            ->setId(3)
            ->setEmail('jane@example.com')
            ->setSelector('xyz789')
            ->setConfirmCode('hash789')
            ->setDoubleOptInDate('2024-06-15 08:00:00')
            ->setDoubleOptInConfirmed(true)
            ->setNewsletter(true);

        self::assertSame(3, $model->getId());
        self::assertSame('jane@example.com', $model->getEmail());
        self::assertSame('xyz789', $model->getSelector());
        self::assertSame('hash789', $model->getConfirmCode());
        self::assertSame('2024-06-15 08:00:00', $model->getDoubleOptInDate());
        self::assertSame(true, $model->getDoubleOptInConfirmed());
        self::assertSame(true, $model->getNewsletter());
    }

    /**
     * Tests that default value for id is null.
     */
    public function testDefaultValues()
    {
        $model = new SubscriberModel();

        self::assertNull($model->getId());
    }

    /**
     * Tests that setId(0) stores zero (falsy but valid).
     */
    public function testSetIdZero()
    {
        $model = new SubscriberModel();
        $model->setId(0);

        self::assertSame(0, $model->getId());
    }

    /**
     * Tests that overwriting a previously set value works.
     */
    public function testOverwriteValues()
    {
        $model = new SubscriberModel();
        $model->setId(1)
            ->setEmail('old@example.com')
            ->setSelector('oldsel')
            ->setConfirmCode('oldcode')
            ->setDoubleOptInDate('2024-01-01 00:00:00')
            ->setDoubleOptInConfirmed(true)
            ->setNewsletter(true);

        $model->setId(2)
            ->setEmail('new@example.com')
            ->setSelector('newsel')
            ->setConfirmCode('newcode')
            ->setDoubleOptInDate('2024-06-01 12:00:00')
            ->setDoubleOptInConfirmed(false)
            ->setNewsletter(false);

        self::assertSame(2, $model->getId());
        self::assertSame('new@example.com', $model->getEmail());
        self::assertSame('newsel', $model->getSelector());
        self::assertSame('newcode', $model->getConfirmCode());
        self::assertSame('2024-06-01 12:00:00', $model->getDoubleOptInDate());
        self::assertSame(false, $model->getDoubleOptInConfirmed());
        self::assertSame(false, $model->getNewsletter());
    }
}
