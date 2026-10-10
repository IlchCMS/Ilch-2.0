<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Forum\Mappers;

use Modules\Admin\Config\Config as AdminConfig;
use Modules\User\Config\Config as UserConfig;
use PHPUnit\Ilch\DatabaseTestCase;
use PHPUnit\Ilch\PhpunitDataset;
use Modules\Forum\Config\Config as ModuleConfig;
use Modules\Forum\Mappers\Remember as RememberMapper;
use Modules\Forum\Models\Remember as RememberModel;

class RememberTest extends DatabaseTestCase
{
    /**
     * @var RememberMapper
     */
    protected Remember $out;
    protected PhpunitDataset $phpunitDataset;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database.yml');
        $this->out = new RememberMapper();
    }

    /**
     * Tests that getRememberedPostsByUserId() returns the remembered posts of a user.
     */
    public function testGetRememberedPostsByUserId()
    {
        $remembers = $this->out->getRememberedPostsByUserId(1);

        self::assertCount(1, $remembers);
        self::assertInstanceOf(RememberModel::class, $remembers[0]);
        self::assertEquals(1, $remembers[0]->getPostId());
        self::assertEquals(1, $remembers[0]->getTopicId());
        self::assertEquals(2, $remembers[0]->getForumId());
        self::assertEquals('Willkommen bei Ilch!', $remembers[0]->getTopicTitle());
    }

    /**
     * Tests that getRememberedPostsByUserId() returns an empty array for a user without remembered posts.
     */
    public function testGetRememberedPostsByUserIdEmpty()
    {
        $remembers = $this->out->getRememberedPostsByUserId(9999);

        self::assertIsArray($remembers);
        self::assertCount(0, $remembers);
    }

    /**
     * Tests that getRememberById() returns the correct remember.
     */
    public function testGetRememberById()
    {
        $remember = $this->out->getRememberById(1);

        self::assertNotNull($remember);
        self::assertEquals(1, $remember->getId());
        self::assertEquals('2024-01-15 12:00:00', $remember->getDate());
        self::assertEquals('Remember this post', $remember->getNote());
        self::assertEquals(1, $remember->getUserId());
        self::assertEquals(1, $remember->getPostId());
        self::assertEquals(1, $remember->getTopicId());
        self::assertEquals(2, $remember->getForumId());
        self::assertEquals('Willkommen bei Ilch!', $remember->getTopicTitle());
    }

    /**
     * Tests that getRememberById() returns null for a non-existent id.
     */
    public function testGetRememberByIdNotFound()
    {
        self::assertNull($this->out->getRememberById(9999));
    }

    /**
     * Tests that getRememberedPostsByTopicId() returns the remembered posts of a topic for a user.
     */
    public function testGetRememberedPostsByTopicId()
    {
        self::assertCount(1, $this->out->getRememberedPostsByTopicId(1, 1));
        self::assertCount(1, $this->out->getRememberedPostsByTopicId(2, 2));
        self::assertCount(0, $this->out->getRememberedPostsByTopicId(1, 2));
        self::assertCount(0, $this->out->getRememberedPostsByTopicId(9999, 1));
    }

    /**
     * Tests that hasRememberedPostWithPostId() returns true for an existing post.
     */
    public function testHasRememberedPostWithPostIdTrue()
    {
        self::assertTrue($this->out->hasRememberedPostWithPostId(1));
    }

    /**
     * Tests that hasRememberedPostWithPostId() returns false for a post without a remember entry.
     */
    public function testHasRememberedPostWithPostIdFalse()
    {
        self::assertFalse($this->out->hasRememberedPostWithPostId(9999));
    }

    /**
     * Tests inserting a new remember via save().
     */
    public function testSaveInsert()
    {
        $model = new RememberModel();
        $model->setPostId(1);
        $model->setNote('New remember');
        $model->setUserId(2);

        $this->out->save($model);

        $remembers = $this->out->getRememberedPostsByUserId(2);
        self::assertCount(2, $remembers);

        $new = null;
        foreach ($remembers as $remember) {
            if ($remember->getNote() === 'New remember') {
                $new = $remember;
            }
        }

        self::assertNotNull($new);
        self::assertEquals(1, $new->getPostId());
        self::assertEquals(2, $new->getUserId());
    }

    /**
     * Tests updating an existing remember via save().
     */
    public function testSaveUpdate()
    {
        $model = new RememberModel();
        $model->setId(1);
        $model->setNote('Updated note');

        $this->out->save($model);

        $remember = $this->out->getRememberById(1);
        self::assertNotNull($remember);
        self::assertEquals('Updated note', $remember->getNote());
    }

    /**
     * Tests that update does not affect other entries.
     */
    public function testSaveUpdateDoesNotAffectOthers()
    {
        $model = new RememberModel();
        $model->setId(1);
        $model->setNote('Changed note');

        $this->out->save($model);

        $other = $this->out->getRememberById(2);
        self::assertNotNull($other);
        self::assertEquals('Remember that post', $other->getNote());
    }

    /**
     * Tests that delete() removes a remember entry of a user.
     */
    public function testDelete()
    {
        $this->out->delete(1, 1);

        self::assertNull($this->out->getRememberById(1));
        self::assertCount(0, $this->out->getRememberedPostsByUserId(1));
        self::assertCount(1, $this->out->getRememberedPostsByUserId(2));
    }

    /**
     * Tests that delete() with a wrong user id does not delete the entry.
     */
    public function testDeleteWrongUser()
    {
        $this->out->delete(1, 2);

        self::assertNotNull($this->out->getRememberById(1));
    }

    /**
     * Tests that delete() on a non-existent id does not throw.
     */
    public function testDeleteNotFound()
    {
        $this->out->delete(9999, 1);

        self::assertCount(1, $this->out->getRememberedPostsByUserId(1));
    }

    /**
     * Returns database schema SQL statements to initialize database.
     *
     * @return string
     */
    protected static function getSchemaSQLQueries(): string
    {
        $config = new ModuleConfig();
        $userConfig = new UserConfig();
        $adminConfig = new AdminConfig();

        return $adminConfig->getInstallSql() . $userConfig->getInstallSql() . $config->getInstallSql();
    }
}
