<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Forum\Models;

use PHPUnit\Framework\TestCase;

class RememberTest extends TestCase
{
    /**
     * Tests that setId() sets and returns the id.
     */
    public function testSetId()
    {
        $model = new Remember();
        $model->setId(5);

        self::assertSame(5, $model->getId());
    }

    /**
     * Tests that setId() casts to int.
     */
    public function testSetIdCastsToInt()
    {
        $model = new Remember();
        $model->setId('42');

        self::assertSame(42, $model->getId());
        self::assertIsInt($model->getId());
    }

    /**
     * Tests that setDate() sets and returns the date.
     */
    public function testSetDate()
    {
        $model = new Remember();
        $model->setDate('2024-01-15 10:30:00');

        self::assertSame('2024-01-15 10:30:00', $model->getDate());
    }

    /**
     * Tests that setForumId() sets and returns the forum id.
     */
    public function testSetForumId()
    {
        $model = new Remember();
        $model->setForumId(2);

        self::assertSame(2, $model->getForumId());
    }

    /**
     * Tests that setTopicId() sets and returns the topic id.
     */
    public function testSetTopicId()
    {
        $model = new Remember();
        $model->setTopicId(1);

        self::assertSame(1, $model->getTopicId());
    }

    /**
     * Tests that setPostId() sets and returns the post id.
     */
    public function testSetPostId()
    {
        $model = new Remember();
        $model->setPostId(1);

        self::assertSame(1, $model->getPostId());
    }

    /**
     * Tests that setNote() sets and returns the note.
     */
    public function testSetNote()
    {
        $model = new Remember();
        $model->setNote('Remember this post');

        self::assertSame('Remember this post', $model->getNote());
    }

    /**
     * Tests that setUserId() sets and returns the user id.
     */
    public function testSetUserId()
    {
        $model = new Remember();
        $model->setUserId(3);

        self::assertSame(3, $model->getUserId());
    }

    /**
     * Tests that setTopicTitle() sets and returns the topic title.
     */
    public function testSetTopicTitle()
    {
        $model = new Remember();
        $model->setTopicTitle('Willkommen bei Ilch!');

        self::assertSame('Willkommen bei Ilch!', $model->getTopicTitle());
    }

    /**
     * Tests that chainable setters return $this.
     * Note: setId() and setTopicTitle() do not return $this.
     */
    public function testSettersReturnSelf()
    {
        $model = new Remember();

        self::assertSame($model, $model->setDate('2024-01-15 10:30:00'));
        self::assertSame($model, $model->setForumId(2));
        self::assertSame($model, $model->setTopicId(1));
        self::assertSame($model, $model->setPostId(1));
        self::assertSame($model, $model->setNote('Note'));
        self::assertSame($model, $model->setUserId(3));
    }

    /**
     * Tests that chaining setters builds a complete model.
     */
    public function testChainedSetters()
    {
        $model = (new Remember())
            ->setDate('2024-01-15 10:30:00')
            ->setForumId(2)
            ->setTopicId(1)
            ->setPostId(1)
            ->setNote('Remember this post')
            ->setUserId(3);

        self::assertSame('2024-01-15 10:30:00', $model->getDate());
        self::assertSame(2, $model->getForumId());
        self::assertSame(1, $model->getTopicId());
        self::assertSame(1, $model->getPostId());
        self::assertSame('Remember this post', $model->getNote());
        self::assertSame(3, $model->getUserId());
    }

    /**
     * Tests that default values are null for unset properties.
     */
    public function testDefaultValues()
    {
        $model = new Remember();

        self::assertNull($model->getId());
    }

    /**
     * Tests that overwriting a previously set value works.
     */
    public function testOverwriteValues()
    {
        $model = new Remember();
        $model->setDate('2024-01-01 08:00:00')
            ->setPostId(1)
            ->setNote('Old note')
            ->setUserId(1);
        $model->setTopicTitle('Old title');

        $model->setDate('2024-02-02 09:00:00')
            ->setPostId(2)
            ->setNote('New note')
            ->setUserId(2);
        $model->setTopicTitle('New title');

        self::assertSame('2024-02-02 09:00:00', $model->getDate());
        self::assertSame(2, $model->getPostId());
        self::assertSame('New note', $model->getNote());
        self::assertSame(2, $model->getUserId());
        self::assertSame('New title', $model->getTopicTitle());
    }
}
