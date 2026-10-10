<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Forum\Models;

use PHPUnit\Framework\TestCase;

class ForumStatisticsTest extends TestCase
{
    /**
     * Tests that setCountPosts() sets and returns the count of posts.
     */
    public function testSetCountPosts()
    {
        $model = new ForumStatistics();
        $model->setCountPosts(12);

        self::assertSame(12, $model->getCountPosts());
    }

    /**
     * Tests that setCountTopics() sets and returns the count of topics.
     */
    public function testSetCountTopics()
    {
        $model = new ForumStatistics();
        $model->setCountTopics(4);

        self::assertSame(4, $model->getCountTopics());
    }

    /**
     * Tests that setCountUsers() sets and returns the count of users.
     */
    public function testSetCountUsers()
    {
        $model = new ForumStatistics();
        $model->setCountUsers(7);

        self::assertSame(7, $model->getCountUsers());
    }

    /**
     * Tests that setCountPosts() casts to int.
     */
    public function testSetCountPostsCastsToInt()
    {
        $model = new ForumStatistics();
        $model->setCountPosts('12');

        self::assertSame(12, $model->getCountPosts());
        self::assertIsInt($model->getCountPosts());
    }

    /**
     * Tests that setCountPosts(0) stores zero (falsy but valid).
     */
    public function testSetCountPostsZero()
    {
        $model = new ForumStatistics();
        $model->setCountPosts(0);

        self::assertSame(0, $model->getCountPosts());
    }

    /**
     * Tests that overwriting a previously set value works.
     */
    public function testOverwriteValues()
    {
        $model = new ForumStatistics();
        $model->setCountPosts(1);
        $model->setCountTopics(2);
        $model->setCountUsers(3);

        $model->setCountPosts(10);
        $model->setCountTopics(20);
        $model->setCountUsers(30);

        self::assertSame(10, $model->getCountPosts());
        self::assertSame(20, $model->getCountTopics());
        self::assertSame(30, $model->getCountUsers());
    }
}
