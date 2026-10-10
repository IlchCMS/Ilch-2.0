<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Forum\Models;

use PHPUnit\Framework\TestCase;
use Modules\User\Models\User;

class ForumTopicTest extends TestCase
{
    /**
     * Tests that setId() sets and returns the id.
     */
    public function testSetId()
    {
        $model = new ForumTopic();
        $model->setId(5);

        self::assertSame(5, $model->getId());
    }

    /**
     * Tests that setId() casts to int.
     */
    public function testSetIdCastsToInt()
    {
        $model = new ForumTopic();
        $model->setId('42');

        self::assertSame(42, $model->getId());
        self::assertIsInt($model->getId());
    }

    /**
     * Tests that setId(0) stores zero (falsy but valid).
     */
    public function testSetIdZero()
    {
        $model = new ForumTopic();
        $model->setId(0);

        self::assertSame(0, $model->getId());
    }

    /**
     * Tests that setTopicTitle() sets and returns the topic title.
     */
    public function testSetTopicTitle()
    {
        $model = new ForumTopic();
        $model->setTopicTitle('Willkommen bei Ilch!');

        self::assertSame('Willkommen bei Ilch!', $model->getTopicTitle());
    }

    /**
     * Tests that setTopicTitle() casts to string.
     */
    public function testSetTopicTitleCastsToString()
    {
        $model = new ForumTopic();
        $model->setTopicTitle(123);

        self::assertSame('123', $model->getTopicTitle());
        self::assertIsString($model->getTopicTitle());
    }

    /**
     * Tests that setAuthor() sets and returns the author.
     */
    public function testSetAuthor()
    {
        $author = new User();

        $model = new ForumTopic();
        $model->setAuthor($author);

        self::assertSame($author, $model->getAuthor());
    }

    /**
     * Tests that setTopicPrefix() sets and returns the topic prefix.
     */
    public function testSetTopicPrefix()
    {
        $prefix = new Prefix();

        $model = new ForumTopic();
        $model->setTopicPrefix($prefix);

        self::assertSame($prefix, $model->getTopicPrefix());
    }

    /**
     * Tests that setVisits() sets and returns the visits.
     */
    public function testSetVisits()
    {
        $model = new ForumTopic();
        $model->setVisits(25);

        self::assertSame(25, $model->getVisits());
    }

    /**
     * Tests that setForumId() sets and returns the forum id.
     */
    public function testSetForumId()
    {
        $model = new ForumTopic();
        $model->setForumId(2);

        self::assertSame(2, $model->getForumId());
    }

    /**
     * Tests that setCreatorId() sets and returns the creator id.
     */
    public function testSetCreatorId()
    {
        $model = new ForumTopic();
        $model->setCreatorId(1);

        self::assertSame(1, $model->getCreatorId());
    }

    /**
     * Tests that setType() sets and returns the type.
     */
    public function testSetType()
    {
        $model = new ForumTopic();
        $model->setType(1);

        self::assertSame(1, $model->getType());
    }

    /**
     * Tests that setDateCreated() sets and returns the date created.
     */
    public function testSetDateCreated()
    {
        $model = new ForumTopic();
        $model->setDateCreated('2024-01-15 10:30:00');

        self::assertSame('2024-01-15 10:30:00', $model->getDateCreated());
    }

    /**
     * Tests that setStatus() sets and returns the status.
     */
    public function testSetStatus()
    {
        $model = new ForumTopic();
        $model->setStatus(1);

        self::assertSame(1, $model->getStatus());
    }

    /**
     * Tests that setCountPosts() sets and returns the count of posts.
     */
    public function testSetCountPosts()
    {
        $model = new ForumTopic();
        $model->setCountPosts(3);

        self::assertSame(3, $model->getCountPosts());
    }

    /**
     * Tests that setters are chainable (return $this).
     */
    public function testSettersReturnSelf()
    {
        $model = new ForumTopic();

        self::assertSame($model, $model->setId(1));
        self::assertSame($model, $model->setTopicPrefix(new Prefix()));
        self::assertSame($model, $model->setTopicTitle('Test'));
        self::assertSame($model, $model->setAuthor(new User()));
        self::assertSame($model, $model->setVisits(0));
        self::assertSame($model, $model->setForumId(2));
        self::assertSame($model, $model->setCreatorId(1));
        self::assertSame($model, $model->setType(0));
        self::assertSame($model, $model->setDateCreated('2024-01-15 10:30:00'));
        self::assertSame($model, $model->setStatus(0));
        self::assertSame($model, $model->setCountPosts(0));
    }

    /**
     * Tests that chaining setters builds a complete model.
     */
    public function testChainedSetters()
    {
        $model = (new ForumTopic())
            ->setId(1)
            ->setTopicTitle('Willkommen bei Ilch!')
            ->setForumId(2)
            ->setCreatorId(0)
            ->setDateCreated('2024-01-15 10:30:00')
            ->setVisits(0)
            ->setCountPosts(1);

        self::assertSame(1, $model->getId());
        self::assertSame('Willkommen bei Ilch!', $model->getTopicTitle());
        self::assertSame(2, $model->getForumId());
        self::assertSame(0, $model->getCreatorId());
        self::assertSame('2024-01-15 10:30:00', $model->getDateCreated());
        self::assertSame(0, $model->getVisits());
        self::assertSame(1, $model->getCountPosts());
    }

    /**
     * Tests that default values are null for unset properties.
     */
    public function testDefaultValues()
    {
        $model = new ForumTopic();

        self::assertNull($model->getId());
    }

    /**
     * Tests that overwriting a previously set value works.
     */
    public function testOverwriteValues()
    {
        $model = new ForumTopic();
        $model->setId(1)
            ->setTopicTitle('Old title')
            ->setVisits(5)
            ->setCountPosts(1);

        $model->setId(2)
            ->setTopicTitle('New title')
            ->setVisits(10)
            ->setCountPosts(2);

        self::assertSame(2, $model->getId());
        self::assertSame('New title', $model->getTopicTitle());
        self::assertSame(10, $model->getVisits());
        self::assertSame(2, $model->getCountPosts());
    }
}
