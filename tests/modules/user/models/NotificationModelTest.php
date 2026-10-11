<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\User\Models;

use PHPUnit\Framework\TestCase;
use Modules\User\Models\Notification as NotificationModel;

class NotificationModelTest extends TestCase
{
    /**
     * Tests that setId() sets and returns the id.
     */
    public function testSetId()
    {
        $model = new NotificationModel();
        $model->setId(5);

        self::assertSame(5, $model->getId());
    }

    /**
     * Tests that setId() coerces a numeric string to int.
     */
    public function testSetIdCoercesToInt()
    {
        $model = new NotificationModel();
        $model->setId('42');

        self::assertSame(42, $model->getId());
        self::assertIsInt($model->getId());
    }

    /**
     * Tests that setId(0) stores zero (falsy but valid).
     */
    public function testSetIdZero()
    {
        $model = new NotificationModel();
        $model->setId(0);

        self::assertSame(0, $model->getId());
    }

    /**
     * Tests that setTimestamp() sets and returns the timestamp.
     */
    public function testSetTimestamp()
    {
        $model = new NotificationModel();
        $model->setTimestamp('2024-01-01 12:00:00');

        self::assertSame('2024-01-01 12:00:00', $model->getTimestamp());
        self::assertIsString($model->getTimestamp());
    }

    /**
     * Tests that setUserId() sets and returns the user id.
     */
    public function testSetUserId()
    {
        $model = new NotificationModel();
        $model->setUserId(5);

        self::assertSame(5, $model->getUserId());
    }

    /**
     * Tests that setUserId() coerces a numeric string to int.
     */
    public function testSetUserIdCoercesToInt()
    {
        $model = new NotificationModel();
        $model->setUserId('42');

        self::assertSame(42, $model->getUserId());
        self::assertIsInt($model->getUserId());
    }

    /**
     * Tests that setModule() sets and returns the module.
     */
    public function testSetModule()
    {
        $model = new NotificationModel();
        $model->setModule('user');

        self::assertSame('user', $model->getModule());
    }

    /**
     * Tests that setMessage() sets and returns the message.
     */
    public function testSetMessage()
    {
        $model = new NotificationModel();
        $model->setMessage('You have a new message');

        self::assertSame('You have a new message', $model->getMessage());
    }

    /**
     * Tests that setURL() sets and returns the url.
     */
    public function testSetURL()
    {
        $model = new NotificationModel();
        $model->setURL('/user/panel/index');

        self::assertSame('/user/panel/index', $model->getURL());
    }

    /**
     * Tests that setType() sets and returns the type.
     */
    public function testSetType()
    {
        $model = new NotificationModel();
        $model->setType('adminModuleUpdatesAvailable');

        self::assertSame('adminModuleUpdatesAvailable', $model->getType());
    }

    /**
     * Tests that setters are chainable (return $this).
     */
    public function testSettersReturnSelf()
    {
        $model = new NotificationModel();

        self::assertSame($model, $model->setId(1));
        self::assertSame($model, $model->setTimestamp('2024-01-01 12:00:00'));
        self::assertSame($model, $model->setUserId(2));
        self::assertSame($model, $model->setModule('user'));
        self::assertSame($model, $model->setMessage('Message'));
        self::assertSame($model, $model->setURL('/url'));
        self::assertSame($model, $model->setType('type'));
    }

    /**
     * Tests that chaining setters builds a complete model.
     */
    public function testChainedSetters()
    {
        $model = (new NotificationModel())
            ->setId(1)
            ->setTimestamp('2024-01-01 12:00:00')
            ->setUserId(2)
            ->setModule('user')
            ->setMessage('You have a new message')
            ->setURL('/user/panel/index')
            ->setType('adminModuleUpdatesAvailable');

        self::assertSame(1, $model->getId());
        self::assertSame('2024-01-01 12:00:00', $model->getTimestamp());
        self::assertSame(2, $model->getUserId());
        self::assertSame('user', $model->getModule());
        self::assertSame('You have a new message', $model->getMessage());
        self::assertSame('/user/panel/index', $model->getURL());
        self::assertSame('adminModuleUpdatesAvailable', $model->getType());
    }

    /**
     * Tests that default values are null.
     */
    public function testDefaultValues()
    {
        $model = new NotificationModel();

        self::assertNull($model->getId());
        self::assertNull($model->getTimestamp());
        self::assertNull($model->getUserId());
        self::assertNull($model->getModule());
        self::assertNull($model->getMessage());
        self::assertNull($model->getURL());
        self::assertNull($model->getType());
    }

    /**
     * Tests that overwriting a previously set value works.
     */
    public function testOverwriteValues()
    {
        $model = new NotificationModel();
        $model->setId(1)
            ->setTimestamp('2024-01-01 00:00:00')
            ->setUserId(1)
            ->setModule('user')
            ->setMessage('Old')
            ->setURL('/old')
            ->setType('old');

        $model->setId(2)
            ->setTimestamp('2024-02-02 00:00:00')
            ->setUserId(2)
            ->setModule('page')
            ->setMessage('New')
            ->setURL('/new')
            ->setType('new');

        self::assertSame(2, $model->getId());
        self::assertSame('2024-02-02 00:00:00', $model->getTimestamp());
        self::assertSame(2, $model->getUserId());
        self::assertSame('page', $model->getModule());
        self::assertSame('New', $model->getMessage());
        self::assertSame('/new', $model->getURL());
        self::assertSame('new', $model->getType());
    }
}
