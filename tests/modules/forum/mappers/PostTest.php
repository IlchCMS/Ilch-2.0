<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Forum\Mappers;

use Ilch\Pagination;
use Modules\Admin\Config\Config as AdminConfig;
use Modules\User\Config\Config as UserConfig;
use PHPUnit\Ilch\DatabaseTestCase;
use PHPUnit\Ilch\PhpunitDataset;
use Modules\Forum\Config\Config as ModuleConfig;
use Modules\Forum\Mappers\Post as PostMapper;
use Modules\Forum\Models\ForumPost as PostModel;

class PostTest extends DatabaseTestCase
{
    /**
     * @var PostMapper
     */
    protected Post $out;
    protected PhpunitDataset $phpunitDataset;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database.yml');
        $this->out = new PostMapper();
    }

    /**
     * Tests that getPostById() returns the correct post with author and vote data.
     */
    public function testGetPostById()
    {
        $post = $this->out->getPostById(1);

        self::assertNotNull($post);
        self::assertEquals(1, $post->getId());
        self::assertEquals('Willkommen im Ilch 2 Forum!', $post->getText());
        self::assertEquals(2, $post->getForumId());
        self::assertEquals('2024-01-15 10:00:00', $post->getDateCreated());
        self::assertEquals(0, $post->getCountOfVotes());
        self::assertFalse($post->getUserHasVoted());

        self::assertNotNull($post->getAutor());
        self::assertEquals('Alice', $post->getAutor()->getName());
        self::assertEquals(1, $post->getAutorAllPost());
    }

    /**
     * Tests that getPostById() returns null for a non-existent id.
     */
    public function testGetPostByIdNotFound()
    {
        self::assertNull($this->out->getPostById(9999));
    }

    /**
     * Tests that getPostById() reports that the given user has voted.
     */
    public function testGetPostByIdWithVotedUser()
    {
        $this->out->saveVotes(1, 1);

        $post = $this->out->getPostById(1, 1);

        self::assertNotNull($post);
        self::assertEquals(1, $post->getCountOfVotes());
        self::assertTrue($post->getUserHasVoted());
    }

    /**
     * Tests that getPostById() counts votes even if the given user did not vote.
     */
    public function testGetPostByIdWithNonVotingUser()
    {
        $this->out->saveVotes(1, 1);

        $post = $this->out->getPostById(1, 2);

        self::assertNotNull($post);
        self::assertEquals(1, $post->getCountOfVotes());
        self::assertFalse($post->getUserHasVoted());
    }

    /**
     * Tests that a post by an unknown user still gets a dummy author.
     */
    public function testGetPostByIdWithUnknownAuthor()
    {
        $this->insertPost(1, 9999, 'Post written by an unknown user');

        $posts = $this->out->getPostsByTopicId(1);
        self::assertCount(2, $posts);

        $ghostPost = $posts[1];
        self::assertEquals('Post written by an unknown user', $ghostPost->getText());
        self::assertNotNull($ghostPost->getAutor());
    }

    /**
     * Tests that getAllPostsByUserId() returns the correct counts.
     */
    public function testGetAllPostsByUserId()
    {
        self::assertEquals(1, $this->out->getAllPostsByUserId(1));
        self::assertEquals(1, $this->out->getAllPostsByUserId(2));
        self::assertEquals(0, $this->out->getAllPostsByUserId(9999));
    }

    /**
     * Tests that getPostsByTopicId() returns the posts of the given topic.
     */
    public function testGetPostsByTopicId()
    {
        $postsTopic1 = $this->out->getPostsByTopicId(1);
        self::assertCount(1, $postsTopic1);
        self::assertEquals(1, $postsTopic1[0]->getId());
        self::assertEquals('Willkommen im Ilch 2 Forum!', $postsTopic1[0]->getText());

        $postsTopic2 = $this->out->getPostsByTopicId(2);
        self::assertCount(1, $postsTopic2);
        self::assertEquals(2, $postsTopic2[0]->getId());
        self::assertEquals('Second post', $postsTopic2[0]->getText());
        self::assertEquals('Bob', $postsTopic2[0]->getAutor()->getName());
    }

    /**
     * Tests that getPostsByTopicId() returns an empty array for a topic without posts.
     */
    public function testGetPostsByTopicIdEmpty()
    {
        self::assertCount(0, $this->out->getPostsByTopicId(9999));
    }

    /**
     * Tests that getPostsByTopicId() orders the posts by creation date.
     */
    public function testGetPostsByTopicIdOrder()
    {
        $this->insertPost(1, 2, 'Another post in the first topic');

        $ascending = $this->out->getPostsByTopicId(1);
        self::assertCount(2, $ascending);
        self::assertEquals(1, $ascending[0]->getId());
        self::assertEquals(3, $ascending[1]->getId());

        $descending = $this->out->getPostsByTopicId(1, null, 1);
        self::assertCount(2, $descending);
        self::assertEquals(3, $descending[0]->getId());
        self::assertEquals(1, $descending[1]->getId());
    }

    /**
     * Tests that getPostsByTopicId() accepts a pagination object.
     */
    public function testGetPostsByTopicIdWithPagination()
    {
        $this->insertPost(1, 2, 'Second post of the first topic');

        $pagination = new Pagination();
        $posts = $this->out->getPostsByTopicId(1, $pagination);

        self::assertCount(2, $posts);
        self::assertEquals(1, $posts[0]->getId());
        self::assertEquals(3, $posts[1]->getId());
    }

    /**
     * Tests that getDateOfLastPostByUserId() returns the date of the newest post.
     */
    public function testGetDateOfLastPostByUserId()
    {
        self::assertEquals('2024-01-15 10:00:00', $this->out->getDateOfLastPostByUserId(1));
        self::assertEquals('2024-01-16 11:00:00', $this->out->getDateOfLastPostByUserId(2));
        self::assertEquals(0, $this->out->getDateOfLastPostByUserId(9999));
    }

    /**
     * Tests that save() inserts a new post and refreshes the denormalized last-post meta.
     */
    public function testSaveInsert()
    {
        $model = new PostModel();
        $model->setId(0)
            ->setTopicId(1)
            ->setText('A brand new post')
            ->setUserId(1)
            ->setForumId(2)
            ->setDateCreated('2024-01-17 12:00:00');

        $this->out->save($model);

        self::assertGreaterThan(2, $model->getId());

        $post = $this->out->getPostById($model->getId());
        self::assertNotNull($post);
        self::assertEquals('A brand new post', $post->getText());
        self::assertEquals(2, $post->getForumId());
        self::assertEquals('Alice', $post->getAutor()->getName());

        // The topic now points to the new post.
        $topic = $this->getTopicLastPostMeta(1);
        self::assertEquals('2024-01-17 12:00:00', $topic['last_post_date']);
        self::assertEquals($model->getId(), $topic['last_post_id']);

        // The forum and its parent category follow the newest topic.
        $forum = $this->getItemLastPostMeta(2);
        self::assertEquals('2024-01-17 12:00:00', $forum['last_post_date']);
        self::assertEquals($model->getId(), $forum['last_post_id']);

        $category = $this->getItemLastPostMeta(1);
        self::assertEquals('2024-01-17 12:00:00', $category['last_post_date']);
        self::assertEquals($model->getId(), $category['last_post_id']);

        // The other post must stay untouched.
        $other = $this->out->getPostById(2);
        self::assertNotNull($other);
        self::assertEquals('Second post', $other->getText());
    }

    /**
     * Tests that a backdated insert keeps the meta of the newer topic for forum and category.
     */
    public function testSaveInsertWithBackdatedPost()
    {
        $model = new PostModel();
        $model->setId(0)
            ->setTopicId(1)
            ->setText('Backdated post')
            ->setUserId(1)
            ->setForumId(2)
            ->setDateCreated('2024-01-14 09:00:00');

        $this->out->save($model);

        // The topic meta always follows the freshly created post.
        $topic = $this->getTopicLastPostMeta(1);
        self::assertEquals('2024-01-14 09:00:00', $topic['last_post_date']);

        // Forum and category keep the meta of the still newer topic 2.
        $forum = $this->getItemLastPostMeta(2);
        self::assertEquals('2024-01-16 11:00:00', $forum['last_post_date']);
        self::assertEquals(2, $forum['last_post_id']);

        $category = $this->getItemLastPostMeta(1);
        self::assertEquals('2024-01-16 11:00:00', $category['last_post_date']);
        self::assertEquals(2, $category['last_post_id']);
    }

    /**
     * Tests that save() updates the text and moves an existing post to another topic.
     */
    public function testSaveUpdate()
    {
        $model = new PostModel();
        $model->setId(1)
            ->setTopicId(2)
            ->setText('Edited post')
            ->setUserId(1)
            ->setForumId(2);

        $this->out->save($model);

        $post = $this->out->getPostById(1);
        self::assertNotNull($post);
        self::assertEquals('Edited post', $post->getText());
        self::assertEquals('2024-01-15 10:00:00', $post->getDateCreated());

        // The post now belongs to topic 2 and no longer to topic 1.
        self::assertCount(0, $this->out->getPostsByTopicId(1));

        // Ordered ascending by date_created: post 1 (01-15) before post 2 (01-16).
        $postsTopic2 = $this->out->getPostsByTopicId(2);
        self::assertCount(2, $postsTopic2);
        self::assertEquals(1, $postsTopic2[0]->getId());
        self::assertEquals(2, $postsTopic2[1]->getId());
    }

    /**
     * Tests that saveVotes() stores a vote for the given post and user.
     */
    public function testSaveVotes()
    {
        $this->out->saveVotes(2, 2);

        $count = $this->db->select(['COUNT(*)'])
            ->from('forum_votes')
            ->where(['post_id' => 2, 'user_id' => 2])
            ->execute()
            ->fetchCell();
        self::assertEquals(1, $count);

        $post = $this->out->getPostById(2, 2);
        self::assertNotNull($post);
        self::assertTrue($post->getUserHasVoted());
        self::assertEquals(1, $post->getCountOfVotes());
    }

    /**
     * Tests that saveForEdit() reassigns the posts of a topic to the given forum.
     */
    public function testSaveForEdit()
    {
        // Create a second forum item to move the topic into.
        $this->createForumItem('Second forum');

        $model = new PostModel();
        $model->setId(2)
            ->setTopicId(2)
            ->setForumId(3);

        $this->out->saveForEdit($model);

        $post = $this->out->getPostById(2);
        self::assertNotNull($post);
        self::assertEquals(3, $post->getForumId());
    }

    /**
     * Tests that saveForEdit() with a zero id does nothing.
     */
    public function testSaveForEditZeroId()
    {
        // Create a second forum item to prove the update was skipped.
        $this->createForumItem('Second forum');

        $model = new PostModel();
        $model->setId(0)
            ->setTopicId(2)
            ->setForumId(3);

        $this->out->saveForEdit($model);

        $post = $this->out->getPostById(2);
        self::assertNotNull($post);
        self::assertEquals(2, $post->getForumId());
    }

    /**
     * Tests that deleteById() removes the post and re-derives the last-post meta.
     */
    public function testDeleteById()
    {
        // Add another post to topic 2 so the topic is not empty after the delete.
        $this->insertPost(2, 2, 'Replacement post');

        $this->out->deleteById(2);

        self::assertNull($this->out->getPostById(2));

        $postsTopic2 = $this->out->getPostsByTopicId(2);
        self::assertCount(1, $postsTopic2);
        self::assertEquals(3, $postsTopic2[0]->getId());

        // Topic 2 now points to its remaining (newest) post.
        $topic = $this->getTopicLastPostMeta(2);
        self::assertEquals('2024-01-17 12:00:00', $topic['last_post_date']);
        self::assertEquals(3, $topic['last_post_id']);

        // Forum and category follow the now newest topic (topic 2).
        $forum = $this->getItemLastPostMeta(2);
        self::assertEquals('2024-01-17 12:00:00', $forum['last_post_date']);
        self::assertEquals(3, $forum['last_post_id']);

        $category = $this->getItemLastPostMeta(1);
        self::assertEquals('2024-01-17 12:00:00', $category['last_post_date']);
        self::assertEquals(3, $category['last_post_id']);

        // Topic 1 stays untouched.
        $topic1 = $this->getTopicLastPostMeta(1);
        self::assertEquals('2024-01-15 10:00:00', $topic1['last_post_date']);
        self::assertEquals(1, $topic1['last_post_id']);
    }

    /**
     * Tests that deleteById() on a non-existent id changes nothing.
     */
    public function testDeleteByIdNotFound()
    {
        $this->out->deleteById(9999);

        self::assertNotNull($this->out->getPostById(1));
        self::assertNotNull($this->out->getPostById(2));

        $forum = $this->getItemLastPostMeta(2);
        self::assertEquals('2024-01-16 11:00:00', $forum['last_post_date']);
        self::assertEquals(2, $forum['last_post_id']);

        $category = $this->getItemLastPostMeta(1);
        self::assertNull($category['last_post_date']);
        self::assertNull($category['last_post_id']);
    }

    /**
     * Tests that isFirstPostOfTopic() only matches the first post of a topic.
     */
    public function testIsFirstPostOfTopic()
    {
        self::assertTrue($this->out->isFirstPostOfTopic(1, 1));
        self::assertFalse($this->out->isFirstPostOfTopic(1, 2));
        self::assertTrue($this->out->isFirstPostOfTopic(2, 2));
        self::assertFalse($this->out->isFirstPostOfTopic(2, 1));
    }

    /**
     * Tests that refreshLastPostMetaForForum() uses the newest topic of the forum.
     */
    public function testRefreshLastPostMetaForForum()
    {
        // Make topic 2 the most recent topic of the forum.
        $this->db->update('forum_topics')
            ->values(['last_post_date' => '2024-01-18 09:00:00', 'last_post_id' => 2])
            ->where(['id' => 2])
            ->execute();

        $this->out->refreshLastPostMetaForForum(2);

        $forum = $this->getItemLastPostMeta(2);
        self::assertEquals('2024-01-18 09:00:00', $forum['last_post_date']);
        self::assertEquals(2, $forum['last_post_id']);

        $category = $this->getItemLastPostMeta(1);
        self::assertEquals('2024-01-18 09:00:00', $category['last_post_date']);
        self::assertEquals(2, $category['last_post_id']);
    }

    /**
     * Tests that refreshLastPostMetaForForum() is a no-op for an item without topics and parent.
     */
    public function testRefreshLastPostMetaForForumWithoutTopics()
    {
        // Item 1 is a category without parent: it has neither topics nor a parent forum.
        $this->out->refreshLastPostMetaForForum(1);

        $item = $this->getItemLastPostMeta(1);
        self::assertNull($item['last_post_date']);
        self::assertNull($item['last_post_id']);
    }

    /**
     * Inserts a post into forum 2 and returns the new id.
     */
    private function insertPost(int $topicId, int $userId, string $text, string $dateCreated = '2024-01-17 12:00:00'): int
    {
        return $this->db->insert('forum_posts')
            ->values([
                'topic_id' => $topicId,
                'forum_id' => 2,
                'text' => $text,
                'user_id' => $userId,
                'date_created' => $dateCreated,
            ])
            ->execute();
    }

    /**
     * Creates a new forum item (type 1) inside the category.
     */
    private function createForumItem(string $title): void
    {
        $this->db->insert('forum_items')
            ->values([
                'sort' => 20,
                'parent_id' => 1,
                'type' => 1,
                'title' => $title,
                'description' => 'Test forum item',
            ])
            ->execute();
    }

    /**
     * Returns the last-post meta of a forum item.
     */
    private function getItemLastPostMeta(int $itemId): array
    {
        return $this->db->select(['last_post_date', 'last_post_id'])
            ->from('forum_items')
            ->where(['id' => $itemId])
            ->execute()
            ->fetchAssoc();
    }

    /**
     * Returns the last-post meta of a topic.
     */
    private function getTopicLastPostMeta(int $topicId): array
    {
        return $this->db->select(['last_post_date', 'last_post_id'])
            ->from('forum_topics')
            ->where(['id' => $topicId])
            ->execute()
            ->fetchAssoc();
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
