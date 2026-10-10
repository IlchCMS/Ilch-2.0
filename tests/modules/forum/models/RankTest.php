<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Forum\Models;

use PHPUnit\Ilch\TestCase;
use Modules\Forum\Models\Rank as RankModel;

/**
 * Tests the rank model class.
 *
 * @package ilch_phpunit
 */
class RankTest extends TestCase
{
    /**
     * Tests if the rank model can save and return an id.
     */
    public function testId()
    {
        $model = new RankModel();
        $model->setId(1);

        self::assertEquals(1, $model->getId(), 'The rank id was not saved correctly.');
    }

    /**
     * Tests if the rank model saves zero for the id (falsy but valid).
     */
    public function testIdZero()
    {
        $model = new RankModel();
        $model->setId(0);

        self::assertEquals(0, $model->getId(), 'The rank id was not saved correctly.');
        self::assertIsInt($model->getId(), 'The rank id is not an int.');
    }

    /**
     * Tests if the rank model accepts null for the id.
     */
    public function testIdNull()
    {
        $model = new RankModel();
        $model->setId(null);

        self::assertNull($model->getId(), 'The rank id was not saved correctly.');
    }

    /**
     * Tests if the rank model casts the id to int.
     */
    public function testIdCastsToInt()
    {
        $model = new RankModel();
        $model->setId('42');

        self::assertEquals(42, $model->getId(), 'The rank id was not saved correctly.');
        self::assertIsInt($model->getId(), 'The rank id is not an int.');
    }

    /**
     * Tests if the rank model can save and return a title.
     */
    public function testTitle()
    {
        $model = new RankModel();
        $model->setTitle('TestTitle');

        self::assertEquals('TestTitle', $model->getTitle(), 'The rank title was not saved correctly.');
    }

    /**
     * Tests if the rank model casts the title to string.
     */
    public function testTitleCastsToString()
    {
        $model = new RankModel();
        $model->setTitle(123);

        self::assertEquals('123', $model->getTitle(), 'The rank title was not saved correctly.');
        self::assertIsString($model->getTitle(), 'The rank title is not a string.');
    }

    /**
     * Tests if the rank model can save and return the required posts.
     */
    public function testPosts()
    {
        $model = new RankModel();
        $model->setPosts(100);

        self::assertEquals(100, $model->getPosts(), 'The rank posts were not saved correctly.');
    }

    /**
     * Tests if the rank model saves zero for the required posts (falsy but valid).
     */
    public function testPostsZero()
    {
        $model = new RankModel();
        $model->setPosts(0);

        self::assertEquals(0, $model->getPosts(), 'The rank posts were not saved correctly.');
        self::assertIsInt($model->getPosts(), 'The rank posts are not an int.');
    }

    /**
     * Tests if the rank model casts the required posts to int.
     */
    public function testPostsCastsToInt()
    {
        $model = new RankModel();
        $model->setPosts('100');

        self::assertEquals(100, $model->getPosts(), 'The rank posts were not saved correctly.');
        self::assertIsInt($model->getPosts(), 'The rank posts are not an int.');
    }

    /**
     * Tests if the default value of the id is null.
     */
    public function testDefaultValues()
    {
        $model = new RankModel();

        self::assertNull($model->getId(), 'The default rank id is not null.');
    }

    /**
     * Tests if overwriting previously set values works.
     */
    public function testOverwriteValues()
    {
        $model = new RankModel();
        $model->setId(1);
        $model->setTitle('Old title');
        $model->setPosts(50);

        $model->setId(2);
        $model->setTitle('New title');
        $model->setPosts(100);

        self::assertEquals(2, $model->getId(), 'The rank id was not overwritten correctly.');
        self::assertEquals('New title', $model->getTitle(), 'The rank title was not overwritten correctly.');
        self::assertEquals(100, $model->getPosts(), 'The rank posts were not overwritten correctly.');
    }
}
