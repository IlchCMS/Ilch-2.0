<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Forum\Models;

use PHPUnit\Framework\TestCase;

class ReportTest extends TestCase
{
    /**
     * Tests that setId() sets and returns the id.
     */
    public function testSetId()
    {
        $model = new Report();
        $model->setId(5);

        self::assertSame(5, $model->getId());
    }

    /**
     * Tests that setId() casts to int.
     */
    public function testSetIdCastsToInt()
    {
        $model = new Report();
        $model->setId('42');

        self::assertSame(42, $model->getId());
        self::assertIsInt($model->getId());
    }

    /**
     * Tests that setId(0) stores zero (falsy but valid).
     */
    public function testSetIdZero()
    {
        $model = new Report();
        $model->setId(0);

        self::assertSame(0, $model->getId());
    }

    /**
     * Tests that setDate() sets and returns the date.
     */
    public function testSetDate()
    {
        $model = new Report();
        $model->setDate('2024-01-15 10:30:00');

        self::assertSame('2024-01-15 10:30:00', $model->getDate());
    }

    /**
     * Tests that setForumId() sets and returns the forum id.
     */
    public function testSetForumId()
    {
        $model = new Report();
        $model->setForumId(2);

        self::assertSame(2, $model->getForumId());
    }

    /**
     * Tests that setForumId() accepts null.
     */
    public function testSetForumIdNull()
    {
        $model = new Report();

        self::assertSame($model, $model->setForumId(null));
    }

    /**
     * Tests that setTopicId() sets and returns the topic id.
     */
    public function testSetTopicId()
    {
        $model = new Report();
        $model->setTopicId(1);

        self::assertSame(1, $model->getTopicId());
    }

    /**
     * Tests that setTopicId() accepts null.
     */
    public function testSetTopicIdNull()
    {
        $model = new Report();
        $model->setTopicId(null);

        self::assertNull($model->getTopicId());
    }

    /**
     * Tests that setPostId() sets and returns the post id.
     */
    public function testSetPostId()
    {
        $model = new Report();
        $model->setPostId(1);

        self::assertSame(1, $model->getPostId());
    }

    /**
     * Tests that setReason() sets and returns the reason.
     */
    public function testSetReason()
    {
        $model = new Report();
        $model->setReason('Spam');

        self::assertSame('Spam', $model->getReason());
    }

    /**
     * Tests that setDetails() sets and returns the details.
     */
    public function testSetDetails()
    {
        $model = new Report();
        $model->setDetails('This post contains spam links.');

        self::assertSame('This post contains spam links.', $model->getDetails());
    }

    /**
     * Tests that setUserId() sets and returns the user id.
     */
    public function testSetUserId()
    {
        $model = new Report();
        $model->setUserId(3);

        self::assertSame(3, $model->getUserId());
    }

    /**
     * Tests that setUsername() sets and returns the username.
     */
    public function testSetUsername()
    {
        $model = new Report();
        $model->setUsername('Reporter');

        self::assertSame('Reporter', $model->getUsername());
    }

    /**
     * Tests that chainable setters return $this.
     * Note: setId() does not return $this.
     */
    public function testSettersReturnSelf()
    {
        $model = new Report();

        self::assertSame($model, $model->setDate('2024-01-15 10:30:00'));
        self::assertSame($model, $model->setForumId(2));
        self::assertSame($model, $model->setTopicId(1));
        self::assertSame($model, $model->setPostId(1));
        self::assertSame($model, $model->setReason('Spam'));
        self::assertSame($model, $model->setDetails('Details'));
        self::assertSame($model, $model->setUserId(3));
        self::assertSame($model, $model->setUsername('Reporter'));
    }

    /**
     * Tests that chaining setters builds a complete model.
     */
    public function testChainedSetters()
    {
        $model = (new Report())
            ->setDate('2024-01-15 10:30:00')
            ->setForumId(2)
            ->setTopicId(1)
            ->setPostId(1)
            ->setReason('Spam')
            ->setDetails('This post contains spam links.')
            ->setUserId(3)
            ->setUsername('Reporter');

        self::assertSame('2024-01-15 10:30:00', $model->getDate());
        self::assertSame(2, $model->getForumId());
        self::assertSame(1, $model->getTopicId());
        self::assertSame(1, $model->getPostId());
        self::assertSame('Spam', $model->getReason());
        self::assertSame('This post contains spam links.', $model->getDetails());
        self::assertSame(3, $model->getUserId());
        self::assertSame('Reporter', $model->getUsername());
    }

    /**
     * Tests that default values are null for unset properties.
     */
    public function testDefaultValues()
    {
        $model = new Report();

        self::assertNull($model->getTopicId());
    }

    /**
     * Tests that overwriting a previously set value works.
     */
    public function testOverwriteValues()
    {
        $model = new Report();
        $model->setDate('2024-01-01 08:00:00')
            ->setPostId(1)
            ->setReason('Old reason')
            ->setUsername('Old user');

        $model->setDate('2024-02-02 09:00:00')
            ->setPostId(2)
            ->setReason('New reason')
            ->setUsername('New user');

        self::assertSame('2024-02-02 09:00:00', $model->getDate());
        self::assertSame(2, $model->getPostId());
        self::assertSame('New reason', $model->getReason());
        self::assertSame('New user', $model->getUsername());
    }
}
