<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Forum\Models;

use PHPUnit\Framework\TestCase;
use Modules\User\Models\User;

class ForumPostTest extends TestCase
{
    /**
     * Tests that setId() sets and returns the id.
     */
    public function testSetId()
    {
        $model = new ForumPost();
        $model->setId(5);

        self::assertSame(5, $model->getId());
    }

    /**
     * Tests that setId() casts to int.
     */
    public function testSetIdCastsToInt()
    {
        $model = new ForumPost();
        $model->setId('42');

        self::assertSame(42, $model->getId());
        self::assertIsInt($model->getId());
    }

    /**
     * Tests that setId(0) stores zero (falsy but valid).
     */
    public function testSetIdZero()
    {
        $model = new ForumPost();
        $model->setId(0);

        self::assertSame(0, $model->getId());
    }

    /**
     * Tests that setTopicId() sets and returns the topic id.
     */
    public function testSetTopicId()
    {
        $model = new ForumPost();
        $model->setTopicId(1);

        self::assertSame(1, $model->getTopicId());
    }

    /**
     * Tests that setTopicTitle() sets and returns the topic title.
     */
    public function testSetTopicTitle()
    {
        $model = new ForumPost();
        $model->setTopicTitle('Willkommen bei Ilch!');

        self::assertSame('Willkommen bei Ilch!', $model->getTopicTitle());
    }

    /**
     * Tests that setTopicTitle() casts to string.
     */
    public function testSetTopicTitleCastsToString()
    {
        $model = new ForumPost();
        $model->setTopicTitle(123);

        self::assertSame('123', $model->getTopicTitle());
        self::assertIsString($model->getTopicTitle());
    }

    /**
     * Tests that setText() sets and returns the text.
     */
    public function testSetText()
    {
        $model = new ForumPost();
        $model->setText('Willkommen im Ilch 2 Forum!');

        self::assertSame('Willkommen im Ilch 2 Forum!', $model->getText());
    }

    /**
     * Tests that setForumId() sets and returns the forum id.
     */
    public function testSetForumId()
    {
        $model = new ForumPost();
        $model->setForumId(2);

        self::assertSame(2, $model->getForumId());
    }

    /**
     * Tests that setUserId() sets and returns the user id.
     */
    public function testSetUserId()
    {
        $model = new ForumPost();
        $model->setUserId(3);

        self::assertSame(3, $model->getUserId());
    }

    /**
     * Tests that setRead() sets and returns the read flag.
     */
    public function testSetRead()
    {
        $model = new ForumPost();
        $model->setRead(true);

        self::assertTrue($model->getRead());
    }

    /**
     * Tests that setRead() stores a falsy value (read = false).
     */
    public function testSetReadFalse()
    {
        $model = new ForumPost();
        $model->setRead(false);

        self::assertFalse($model->getRead());
    }

    /**
     * Tests that setDateCreated() sets and returns the date created.
     */
    public function testSetDateCreated()
    {
        $model = new ForumPost();
        $model->setDateCreated('2024-01-15 10:30:00');

        self::assertSame('2024-01-15 10:30:00', $model->getDateCreated());
    }

    /**
     * Tests that setAutor() sets and returns the autor.
     */
    public function testSetAutor()
    {
        $autor = new User();

        $model = new ForumPost();
        $model->setAutor($autor);

        self::assertSame($autor, $model->getAutor());
    }

    /**
     * Tests that setAutorAllPost() sets and returns the autor all post.
     */
    public function testSetAutorAllPost()
    {
        $model = new ForumPost();
        $model->setAutorAllPost('Admin');

        self::assertSame('Admin', $model->getAutorAllPost());
    }

    /**
     * Tests that setCountOfVotes() sets and returns the count of votes.
     */
    public function testSetCountOfVotes()
    {
        $model = new ForumPost();
        $model->setCountOfVotes(5);

        self::assertSame(5, $model->getCountOfVotes());
    }

    /**
     * Tests that setCountOfVotes(0) stores zero (falsy but valid).
     */
    public function testSetCountOfVotesZero()
    {
        $model = new ForumPost();
        $model->setCountOfVotes(0);

        self::assertSame(0, $model->getCountOfVotes());
    }

    /**
     * Tests that setUserHasVoted() sets and returns the voted flag.
     */
    public function testSetUserHasVoted()
    {
        $model = new ForumPost();
        $model->setUserHasVoted(true);

        self::assertTrue($model->isUserHasVoted());
    }

    /**
     * Tests that setUserHasVoted() stores a falsy value (has not voted).
     */
    public function testSetUserHasVotedFalse()
    {
        $model = new ForumPost();
        $model->setUserHasVoted(false);

        self::assertFalse($model->isUserHasVoted());
    }

    /**
     * Tests that setters are chainable (return $this).
     */
    public function testSettersReturnSelf()
    {
        $model = new ForumPost();

        self::assertSame($model, $model->setId(1));
        self::assertSame($model, $model->setTopicId(1));
        self::assertSame($model, $model->setTopicTitle('Test'));
        self::assertSame($model, $model->setText('Test text'));
        self::assertSame($model, $model->setForumId(2));
        self::assertSame($model, $model->setUserId(3));
        self::assertSame($model, $model->setRead(true));
        self::assertSame($model, $model->setDateCreated('2024-01-15 10:30:00'));
        self::assertSame($model, $model->setAutor(new User()));
        self::assertSame($model, $model->setAutorAllPost('Admin'));
        self::assertSame($model, $model->setCountOfVotes(0));
        self::assertSame($model, $model->setUserHasVoted(false));
    }

    /**
     * Tests that chaining setters builds a complete model.
     */
    public function testChainedSetters()
    {
        $model = (new ForumPost())
            ->setId(1)
            ->setTopicId(1)
            ->setTopicTitle('Willkommen bei Ilch!')
            ->setText('Willkommen im Ilch 2 Forum!')
            ->setForumId(2)
            ->setUserId(0)
            ->setDateCreated('2024-01-15 10:30:00');

        self::assertSame(1, $model->getId());
        self::assertSame(1, $model->getTopicId());
        self::assertSame('Willkommen bei Ilch!', $model->getTopicTitle());
        self::assertSame('Willkommen im Ilch 2 Forum!', $model->getText());
        self::assertSame(2, $model->getForumId());
        self::assertSame(0, $model->getUserId());
        self::assertSame('2024-01-15 10:30:00', $model->getDateCreated());
    }

    /**
     * Tests that default values are null for unset properties.
     */
    public function testDefaultValues()
    {
        $model = new ForumPost();

        self::assertNull($model->getId());
        self::assertNull($model->getAutorAllPost());
    }

    /**
     * Tests that overwriting a previously set value works.
     */
    public function testOverwriteValues()
    {
        $model = new ForumPost();
        $model->setId(1)
            ->setTopicTitle('Old title')
            ->setText('Old text')
            ->setCountOfVotes(2);

        $model->setId(2)
            ->setTopicTitle('New title')
            ->setText('New text')
            ->setCountOfVotes(5);

        self::assertSame(2, $model->getId());
        self::assertSame('New title', $model->getTopicTitle());
        self::assertSame('New text', $model->getText());
        self::assertSame(5, $model->getCountOfVotes());
    }
}
