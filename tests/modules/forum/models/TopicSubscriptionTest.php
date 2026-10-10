<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Forum\Models;

use PHPUnit\Framework\TestCase;

class TopicSubscriptionTest extends TestCase
{
    /**
     * Tests that setId() sets and returns the id.
     */
    public function testSetId()
    {
        $model = new TopicSubscription();
        $model->setId(5);

        self::assertSame(5, $model->getId());
    }

    /**
     * Tests that setId() casts to int.
     */
    public function testSetIdCastsToInt()
    {
        $model = new TopicSubscription();
        $model->setId('42');

        self::assertSame(42, $model->getId());
        self::assertIsInt($model->getId());
    }

    /**
     * Tests that setId(0) stores zero (falsy but valid).
     */
    public function testSetIdZero()
    {
        $model = new TopicSubscription();
        $model->setId(0);

        self::assertSame(0, $model->getId());
    }

    /**
     * Tests that setTopicId() sets and returns the topic id.
     */
    public function testSetTopicId()
    {
        $model = new TopicSubscription();
        $model->setTopicId(1);

        self::assertSame(1, $model->getTopicId());
    }

    /**
     * Tests that setUserId() sets and returns the user id.
     */
    public function testSetUserId()
    {
        $model = new TopicSubscription();
        $model->setUserId(3);

        self::assertSame(3, $model->getUserId());
    }

    /**
     * Tests that setLastNotification() sets and returns the last notification date.
     */
    public function testSetLastNotification()
    {
        $model = new TopicSubscription();
        $model->setLastNotification('2024-01-15 10:30:00');

        self::assertSame('2024-01-15 10:30:00', $model->getLastNotification());
    }

    /**
     * Tests that setLastNotification() accepts null.
     */
    public function testSetLastNotificationNull()
    {
        $model = new TopicSubscription();
        $model->setLastNotification(null);

        self::assertNull($model->getLastNotification());
    }

    /**
     * Tests that setUsername() sets and returns the username.
     */
    public function testSetUsername()
    {
        $model = new TopicSubscription();
        $model->setUsername('Admin');

        self::assertSame('Admin', $model->getUsername());
    }

    /**
     * Tests that setEmailAddress() sets and returns the email address.
     */
    public function testSetEmailAddress()
    {
        $model = new TopicSubscription();
        $model->setEmailAddress('admin@example.com');

        self::assertSame('admin@example.com', $model->getEmailAddress());
    }

    /**
     * Tests that setLastActivity() sets and returns the last activity date.
     */
    public function testSetLastActivity()
    {
        $model = new TopicSubscription();
        $model->setLastActivity('2024-02-01 08:00:00');

        self::assertSame('2024-02-01 08:00:00', $model->getLastActivity());
    }

    /**
     * Tests that setLastActivity() accepts null (user never active).
     */
    public function testSetLastActivityNull()
    {
        $model = new TopicSubscription();
        $model->setLastActivity(null);

        self::assertNull($model->getLastActivity());
    }

    /**
     * Tests that chainable setters return $this.
     * Note: setId() does not return $this.
     */
    public function testSettersReturnSelf()
    {
        $model = new TopicSubscription();

        self::assertSame($model, $model->setTopicId(1));
        self::assertSame($model, $model->setUserId(3));
        self::assertSame($model, $model->setLastNotification('2024-01-15 10:30:00'));
        self::assertSame($model, $model->setUsername('Admin'));
        self::assertSame($model, $model->setEmailAddress('admin@example.com'));
        self::assertSame($model, $model->setLastActivity('2024-02-01 08:00:00'));
    }

    /**
     * Tests that chaining setters builds a complete model.
     */
    public function testChainedSetters()
    {
        $model = (new TopicSubscription())
            ->setTopicId(1)
            ->setUserId(3)
            ->setLastNotification('2024-01-15 10:30:00')
            ->setUsername('Admin')
            ->setEmailAddress('admin@example.com')
            ->setLastActivity('2024-02-01 08:00:00');

        self::assertSame(1, $model->getTopicId());
        self::assertSame(3, $model->getUserId());
        self::assertSame('2024-01-15 10:30:00', $model->getLastNotification());
        self::assertSame('Admin', $model->getUsername());
        self::assertSame('admin@example.com', $model->getEmailAddress());
        self::assertSame('2024-02-01 08:00:00', $model->getLastActivity());
    }

    /**
     * Tests that default values are null for unset properties.
     * Only getLastActivity() is safe to call on a fresh instance: the other
     * getters access typed properties that are not initialized by default.
     */
    public function testDefaultValues()
    {
        $model = new TopicSubscription();

        self::assertNull($model->getLastActivity());
    }

    /**
     * Tests that overwriting a previously set value works.
     */
    public function testOverwriteValues()
    {
        $model = new TopicSubscription();
        $model->setTopicId(1)
            ->setUserId(1)
            ->setUsername('Old user')
            ->setEmailAddress('old@example.com');

        $model->setTopicId(2)
            ->setUserId(2)
            ->setUsername('New user')
            ->setEmailAddress('new@example.com');

        self::assertSame(2, $model->getTopicId());
        self::assertSame(2, $model->getUserId());
        self::assertSame('New user', $model->getUsername());
        self::assertSame('new@example.com', $model->getEmailAddress());
    }
}
