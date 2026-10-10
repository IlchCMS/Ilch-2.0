<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Forum\Models;

use PHPUnit\Framework\TestCase;

class ForumItemTest extends TestCase
{
    /**
     * Tests that setId() sets and returns the id.
     */
    public function testSetId()
    {
        $model = new ForumItem();
        $model->setId(5);

        self::assertSame(5, $model->getId());
    }

    /**
     * Tests that setId() casts to int.
     */
    public function testSetIdCastsToInt()
    {
        $model = new ForumItem();
        $model->setId('42');

        self::assertSame(42, $model->getId());
        self::assertIsInt($model->getId());
    }

    /**
     * Tests that setId(0) stores zero (falsy but valid).
     */
    public function testSetIdZero()
    {
        $model = new ForumItem();
        $model->setId(0);

        self::assertSame(0, $model->getId());
    }

    /**
     * Tests that setSort() sets and returns the sort.
     */
    public function testSetSort()
    {
        $model = new ForumItem();
        $model->setSort(10);

        self::assertSame(10, $model->getSort());
    }

    /**
     * Tests that setType() sets and returns the type.
     */
    public function testSetType()
    {
        $model = new ForumItem();
        $model->setType(1);

        self::assertSame(1, $model->getType());
    }

    /**
     * Tests that setParentId() sets and returns the parent id.
     */
    public function testSetParentId()
    {
        $model = new ForumItem();
        $model->setParentId(1);

        self::assertSame(1, $model->getParentId());
    }

    /**
     * Tests that setTitle() sets and returns the title.
     */
    public function testSetTitle()
    {
        $model = new ForumItem();
        $model->setTitle('Mein Forum');

        self::assertSame('Mein Forum', $model->getTitle());
    }

    /**
     * Tests that setTitle() casts to string.
     */
    public function testSetTitleCastsToString()
    {
        $model = new ForumItem();
        $model->setTitle(123);

        self::assertSame('123', $model->getTitle());
        self::assertIsString($model->getTitle());
    }

    /**
     * Tests that setDesc() sets and returns the desc.
     */
    public function testSetDesc()
    {
        $model = new ForumItem();
        $model->setDesc('Mein erstes Forum');

        self::assertSame('Mein erstes Forum', $model->getDesc());
    }

    /**
     * Tests that setReadAccess() sets and returns the read access.
     */
    public function testSetReadAccess()
    {
        $model = new ForumItem();
        $model->setReadAccess('1,2,3');

        self::assertSame('1,2,3', $model->getReadAccess());
    }

    /**
     * Tests that setReplyAccess() sets and returns the reply access.
     */
    public function testSetReplyAccess()
    {
        $model = new ForumItem();
        $model->setReplyAccess('1,2');

        self::assertSame('1,2', $model->getReplyAccess());
    }

    /**
     * Tests that setCreateAccess() sets and returns the create access.
     */
    public function testSetCreateAccess()
    {
        $model = new ForumItem();
        $model->setCreateAccess('1');

        self::assertSame('1', $model->getCreateAccess());
    }

    /**
     * Tests that setSubItems() sets and returns the sub items.
     */
    public function testSetSubItems()
    {
        $subItems = [new ForumItem(), new ForumItem()];

        $model = new ForumItem();
        $model->setSubItems($subItems);

        self::assertSame($subItems, $model->getSubItems());
        self::assertCount(2, $model->getSubItems());
    }

    /**
     * Tests that setTopics() sets and returns the topics count.
     */
    public function testSetTopics()
    {
        $model = new ForumItem();
        $model->setTopics(15);

        self::assertSame(15, $model->getTopics());
    }

    /**
     * Tests that setLastPost() sets and returns the last post.
     */
    public function testSetLastPost()
    {
        $lastPost = new ForumPost();

        $model = new ForumItem();
        $model->setLastPost($lastPost);

        self::assertSame($lastPost, $model->getLastPost());
    }

    /**
     * Tests that setLastPost() accepts null.
     */
    public function testSetLastPostNull()
    {
        $model = new ForumItem();
        $model->setLastPost(null);

        self::assertNull($model->getLastPost());
    }

    /**
     * Tests that setPosts() sets and returns the posts count.
     */
    public function testSetPosts()
    {
        $model = new ForumItem();
        $model->setPosts(42);

        self::assertSame(42, $model->getPosts());
    }

    /**
     * Tests that setPrefixes() sets and returns the prefixes.
     */
    public function testSetPrefixes()
    {
        $model = new ForumItem();
        $model->setPrefixes('Pinned,Announcement');

        self::assertSame('Pinned,Announcement', $model->getPrefixes());
    }

    /**
     * Tests that setters are chainable (return $this).
     */
    public function testSettersReturnSelf()
    {
        $model = new ForumItem();

        self::assertSame($model, $model->setId(1));
        self::assertSame($model, $model->setSort(0));
        self::assertSame($model, $model->setType(0));
        self::assertSame($model, $model->setParentId(0));
        self::assertSame($model, $model->setTitle('Test'));
        self::assertSame($model, $model->setDesc('Test description'));
        self::assertSame($model, $model->setReadAccess('1'));
        self::assertSame($model, $model->setReplyAccess('1'));
        self::assertSame($model, $model->setCreateAccess('1'));
        self::assertSame($model, $model->setSubItems([]));
        self::assertSame($model, $model->setTopics(0));
        self::assertSame($model, $model->setLastPost(null));
        self::assertSame($model, $model->setPosts(0));
        self::assertSame($model, $model->setPrefixes(''));
    }

    /**
     * Tests that chaining setters builds a complete model.
     */
    public function testChainedSetters()
    {
        $model = (new ForumItem())
            ->setId(2)
            ->setSort(10)
            ->setType(1)
            ->setParentId(1)
            ->setTitle('Mein Forum')
            ->setDesc('Mein erstes Forum');

        self::assertSame(2, $model->getId());
        self::assertSame(10, $model->getSort());
        self::assertSame(1, $model->getType());
        self::assertSame(1, $model->getParentId());
        self::assertSame('Mein Forum', $model->getTitle());
        self::assertSame('Mein erstes Forum', $model->getDesc());
    }

    /**
     * Tests that default values are null for unset properties.
     */
    public function testDefaultValues()
    {
        $model = new ForumItem();

        self::assertNull($model->getId());
        self::assertNull($model->getSort());
        self::assertNull($model->getType());
        self::assertNull($model->getParentId());
        self::assertNull($model->getTitle());
        self::assertNull($model->getDesc());
        self::assertNull($model->getReadAccess());
        self::assertNull($model->getReplyAccess());
        self::assertNull($model->getCreateAccess());
        self::assertNull($model->getPrefixes());
        self::assertNull($model->getLastPost());
    }

    /**
     * Tests that overwriting a previously set value works.
     */
    public function testOverwriteValues()
    {
        $model = new ForumItem();
        $model->setId(1)
            ->setType(0)
            ->setTitle('Old Title')
            ->setDesc('Old description');

        $model->setId(2)
            ->setType(1)
            ->setTitle('New Title')
            ->setDesc('New description');

        self::assertSame(2, $model->getId());
        self::assertSame(1, $model->getType());
        self::assertSame('New Title', $model->getTitle());
        self::assertSame('New description', $model->getDesc());
    }
}
