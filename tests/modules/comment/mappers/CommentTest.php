<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Comment\Mappers;

use PHPUnit\Ilch\DatabaseTestCase;
use PHPUnit\Ilch\PhpunitDataset;
use Modules\Comment\Config\Config as ModuleConfig;
use Modules\Comment\Mappers\Comment as CommentMapper;
use Modules\Comment\Models\Comment as CommentModel;

class CommentTest extends DatabaseTestCase
{
    /**
     * @var CommentMapper
     */
    protected Comment $out;
    protected PhpunitDataset $phpunitDataset;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database.yml');
        $this->out = new CommentMapper();
    }

    /**
     * Tests if getCommentsByKey() returns comments for an exact key match.
     */
    public function testGetCommentsByKey()
    {
        $comments = $this->out->getCommentsByKey('news/1/');

        self::assertCount(1, $comments);
        self::assertInstanceOf(CommentModel::class, $comments[0]);
        self::assertEquals(1, $comments[0]->getId());
        self::assertEquals('news/1/', $comments[0]->getKey());
        self::assertEquals('First comment on article 1', $comments[0]->getText());
        self::assertEquals(10, $comments[0]->getUserId());
    }

    /**
     * Tests if getCommentsByKey() adds a missing trailing slash automatically.
     */
    public function testGetCommentsByKeyAddsSlash()
    {
        $comments = $this->out->getCommentsByKey('news/1');

        self::assertCount(1, $comments);
        self::assertEquals('news/1/', $comments[0]->getKey());
    }

    /**
     * Tests if getCommentsByKey() returns an empty array for a non-matching key.
     */
    public function testGetCommentsByKeyNoMatch()
    {
        $comments = $this->out->getCommentsByKey('blog/99/');

        self::assertIsArray($comments);
        self::assertCount(0, $comments);
    }

    /**
     * Tests if getCommentsByKey() orders results by id DESC.
     */
    public function testGetCommentsByKeyOrder()
    {
        // Both news/2/ and news/2/1/ start with news/2/
        // But exact match only returns news/2/
        $comments = $this->out->getCommentsByKey('news/2/');

        self::assertCount(1, $comments);
        self::assertEquals(2, $comments[0]->getId());
    }

    /**
     * Tests if getCommentsLikeKey() returns comments matching a key prefix.
     */
    public function testGetCommentsLikeKey()
    {
        $comments = $this->out->getCommentsLikeKey('news/2');

        self::assertCount(2, $comments);

        $keys = array_map(fn($c) => $c->getKey(), $comments);
        self::assertContains('news/2/', $keys);
        self::assertContains('news/2/1/', $keys);
    }

    /**
     * Tests if getCommentsLikeKey() adds a missing trailing slash.
     */
    public function testGetCommentsLikeKeyAddsSlash()
    {
        $comments = $this->out->getCommentsLikeKey('news/2/');

        self::assertCount(2, $comments);
    }

    /**
     * Tests if getCommentsLikeKey() returns an empty array for no match.
     */
    public function testGetCommentsLikeKeyNoMatch()
    {
        $comments = $this->out->getCommentsLikeKey('blog/99/');

        self::assertIsArray($comments);
        self::assertCount(0, $comments);
    }

    /**
     * Tests if getCommentById() returns the correct comment.
     */
    public function testGetCommentById()
    {
        $comment = $this->out->getCommentById(1);

        self::assertNotNull($comment);
        self::assertEquals(1, $comment->getId());
        self::assertEquals('news/1/', $comment->getKey());
        self::assertEquals('First comment on article 1', $comment->getText());
        self::assertEquals('2025-03-01 10:00:00', $comment->getDateCreated());
        self::assertEquals(10, $comment->getUserId());
        self::assertEquals(0, $comment->getFKId());
        self::assertEquals(5, $comment->getUp());
        self::assertEquals(2, $comment->getDown());
        self::assertEquals('10,11,12,13,14', $comment->getVoted());
    }

    /**
     * Tests if getCommentById() returns null for a non-existent id.
     */
    public function testGetCommentByIdNotFound()
    {
        self::assertNull($this->out->getCommentById(9999));
    }

    /**
     * Tests if getCommentsByFKid() returns comments that reference a parent.
     */
    public function testGetCommentsByFKid()
    {
        $comments = $this->out->getCommentsByFKid(2);

        self::assertCount(1, $comments);
        self::assertEquals(3, $comments[0]->getId());
        self::assertEquals('Reply to second comment', $comments[0]->getText());
        self::assertEquals(12, $comments[0]->getUserId());
    }

    /**
     * Tests if getCommentsByFKid() returns an empty array when no children exist.
     */
    public function testGetCommentsByFKidNoMatch()
    {
        $comments = $this->out->getCommentsByFKid(9999);

        self::assertIsArray($comments);
        self::assertCount(0, $comments);
    }

    /**
     * Tests if getComments() returns all comments ordered by id DESC.
     */
    public function testGetComments()
    {
        $comments = $this->out->getComments();

        self::assertCount(4, $comments);
        self::assertEquals(4, $comments[0]->getId());
        self::assertEquals(1, $comments[3]->getId());
    }

    /**
     * Tests if getComments() respects the limit parameter.
     */
    public function testGetCommentsWithLimit()
    {
        $comments = $this->out->getComments(2);

        self::assertCount(2, $comments);
        self::assertEquals(4, $comments[0]->getId());
        self::assertEquals(3, $comments[1]->getId());
    }

    /**
     * Tests if getCountComments() returns the correct count for a key prefix.
     */
    public function testGetCountComments()
    {
        self::assertEquals(2, $this->out->getCountComments('news/2'));
    }

    /**
     * Tests if getCountComments() adds a missing trailing slash.
     */
    public function testGetCountCommentsAddsSlash()
    {
        self::assertEquals(2, $this->out->getCountComments('news/2/'));
    }

    /**
     * Tests if getCountComments() returns 0 for a non-matching key.
     */
    public function testGetCountCommentsNoMatch()
    {
        self::assertEquals(0, $this->out->getCountComments('blog/99/'));
    }

    /**
     * Tests if getDateOfLastCommentByUserId() returns the latest comment date.
     */
    public function testGetDateOfLastCommentByUserId()
    {
        // User 10 has comments with id 1 (2025-03-01) and id 4 (2025-04-05).
        // Ordered by id DESC, limit 1 → id 4.
        $date = $this->out->getDateOfLastCommentByUserId(10);

        self::assertEquals('2025-04-05 16:45:00', $date);
    }

    /**
     * Tests if getDateOfLastCommentByUserId() returns 0 for a user with no comments.
     */
    public function testGetDateOfLastCommentByUserIdNoComments()
    {
        $date = $this->out->getDateOfLastCommentByUserId(9999);

        self::assertEquals(0, $date);
    }

    /**
     * Tests if saveLike() updates the up, down, and voted fields.
     */
    public function testSaveLike()
    {
        $comment = new CommentModel();
        $comment->setId(1)
            ->setUp(7)
            ->setDown(0)
            ->setVoted('10,11');

        $this->out->saveLike($comment);

        $saved = $this->out->getCommentById(1);
        self::assertEquals(7, $saved->getUp());
        self::assertEquals(0, $saved->getDown());
        self::assertEquals('10,11', $saved->getVoted());
    }

    /**
     * Tests if save() inserts a new comment.
     */
    public function testSave()
    {
        $comment = new CommentModel();
        $comment->setKey('news/4')
            ->setText('New comment')
            ->setDateCreated('2025-05-01 08:00:00')
            ->setUserId(15)
            ->setFKId(0);

        $this->out->save($comment);

        // The key should have a slash appended.
        $comments = $this->out->getCommentsByKey('news/4/');
        self::assertCount(1, $comments);
        self::assertEquals('New comment', $comments[0]->getText());
        self::assertEquals(15, $comments[0]->getUserId());
        self::assertEquals(0, $comments[0]->getFKId());
    }

    /**
     * Tests if save() adds a missing trailing slash to the key.
     */
    public function testSaveAddsSlashToKey()
    {
        $comment = new CommentModel();
        $comment->setKey('blog/1')
            ->setText('Blog comment')
            ->setDateCreated('2025-05-02 12:00:00')
            ->setUserId(20)
            ->setFKId(0);

        $this->out->save($comment);

        $comments = $this->out->getCommentsByKey('blog/1/');
        self::assertCount(1, $comments);
        self::assertEquals('blog/1/', $comments[0]->getKey());
    }

    /**
     * Tests if delete() removes a comment and its child comments.
     */
    public function testDeleteCascades()
    {
        // Comment id 2 is the parent of id 3 (fk_id = 2).
        $this->out->delete(2);

        self::assertNull($this->out->getCommentById(2));
        self::assertNull($this->out->getCommentById(3));

        // Other comments remain.
        self::assertCount(2, $this->out->getComments());
    }

    /**
     * Tests if delete() removes a single comment with no children.
     */
    public function testDeleteSingle()
    {
        $this->out->delete(1);

        self::assertNull($this->out->getCommentById(1));
        self::assertCount(3, $this->out->getComments());
    }

    /**
     * Tests if deleteByKey() removes all comments matching a key prefix.
     */
    public function testDeleteByKey()
    {
        $this->out->deleteByKey('news/2');

        // Removes id 2 (news/2/) and id 3 (news/2/1/)
        self::assertNull($this->out->getCommentById(2));
        self::assertNull($this->out->getCommentById(3));
        self::assertCount(2, $this->out->getComments());
    }

    /**
     * Tests if deleteByKey() adds a missing trailing slash.
     */
    public function testDeleteByKeyAddsSlash()
    {
        $this->out->deleteByKey('news/1');

        self::assertNull($this->out->getCommentById(1));
        self::assertCount(3, $this->out->getComments());
    }

    /**
     * Tests if getCommentIdbyFKid() returns the child comment id.
     */
    public function testGetCommentIdbyFKid()
    {
        $id = $this->out->getCommentIdbyFKid(2);

        self::assertEquals(3, $id);
    }

    /**
     * Tests if getCommentIdbyFKid() returns null when no child exists.
     */
    public function testGetCommentIdbyFKidNotFound()
    {
        $id = $this->out->getCommentIdbyFKid(9999);

        self::assertNull($id);
    }

    /**
     * Returns database schema SQL statements to initialize database.
     *
     * @return string
     */
    protected static function getSchemaSQLQueries(): string
    {
        $config = new ModuleConfig();

        return $config->getInstallSql();
    }
}
