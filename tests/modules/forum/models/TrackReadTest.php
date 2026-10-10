<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Forum\Models;

use PHPUnit\Framework\TestCase;

class TrackReadTest extends TestCase
{
    /**
     * Tests that setUserId() sets and returns the user id.
     */
    public function testSetUserId()
    {
        $model = new TrackRead();
        $model->setUserId(1);

        self::assertSame(1, $model->getUserId());
    }

    /**
     * Tests that setForumId() sets and returns the forum id.
     */
    public function testSetForumId()
    {
        $model = new TrackRead();
        $model->setForumId(2);

        self::assertSame(2, $model->getForumId());
    }

    /**
     * Tests that setTopicId() sets and returns the topic id.
     */
    public function testSetTopicId()
    {
        $model = new TrackRead();
        $model->setTopicId(1);

        self::assertSame(1, $model->getTopicId());
    }

    /**
     * Tests that setDatetime() sets and returns the datetime.
     */
    public function testSetDatetime()
    {
        $model = new TrackRead();
        $model->setDatetime(1705314600);

        self::assertSame(1705314600, $model->getDatetime());
    }

    /**
     * Tests that setDatetime() casts to int.
     */
    public function testSetDatetimeCastsToInt()
    {
        $model = new TrackRead();
        $model->setDatetime('1705314600');

        self::assertSame(1705314600, $model->getDatetime());
        self::assertIsInt($model->getDatetime());
    }

    /**
     * Tests that setters are chainable (return $this).
     */
    public function testSettersReturnSelf()
    {
        $model = new TrackRead();

        self::assertSame($model, $model->setUserId(1));
        self::assertSame($model, $model->setForumId(2));
        self::assertSame($model, $model->setTopicId(1));
        self::assertSame($model, $model->setDatetime(1705314600));
    }

    /**
     * Tests that chaining setters builds a complete model.
     */
    public function testChainedSetters()
    {
        $model = (new TrackRead())
            ->setUserId(1)
            ->setForumId(2)
            ->setTopicId(1)
            ->setDatetime(1705314600);

        self::assertSame(1, $model->getUserId());
        self::assertSame(2, $model->getForumId());
        self::assertSame(1, $model->getTopicId());
        self::assertSame(1705314600, $model->getDatetime());
    }

    /**
     * Tests that overwriting a previously set value works.
     */
    public function testOverwriteValues()
    {
        $model = new TrackRead();
        $model->setUserId(1)
            ->setForumId(2)
            ->setTopicId(1)
            ->setDatetime(1705314600);

        $model->setUserId(2)
            ->setForumId(3)
            ->setTopicId(4)
            ->setDatetime(1705401000);

        self::assertSame(2, $model->getUserId());
        self::assertSame(3, $model->getForumId());
        self::assertSame(4, $model->getTopicId());
        self::assertSame(1705401000, $model->getDatetime());
    }
}
