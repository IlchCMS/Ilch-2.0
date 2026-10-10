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
use Modules\Forum\Mappers\Topic as TopicMapper;
use Modules\Forum\Models\ForumTopic as TopicModel;
use Modules\Forum\Models\ForumPost as PostModel;
use Modules\Forum\Models\Prefix as PrefixModel;
use Modules\User\Models\User as UserModel;

class TopicTest extends DatabaseTestCase
{
    /**
     * @var TopicMapper
     */
    protected TopicMapper $out;
    protected PhpunitDataset $phpunitDataset;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database.yml');
        $this->out = new TopicMapper();
    }

    /**
     * Tests that getTopicsByForumId() returns all topics of the forum.
     */
    public function testGetTopicsByForumId()
    {
        $topics = $this->out->getTopicsByForumId(2);

        self::assertCount(2, $topics);
        self::assertInstanceOf(TopicModel::class, $topics[2]);
        self::assertInstanceOf(TopicModel::class, $topics[1]);
    }

    /**
     * Tests that topics are ordered by type DESC and last_post_id DESC.
     */
    public function testGetTopicsByForumIdOrdering()
    {
        $topics = $this->out->getTopicsByForumId(2);

        self::assertSame([2, 1], array_keys($topics));
    }

    /**
     * Tests that getTopicsByForumId() returns correct fields for both topics.
     */
    public function testGetTopicsByForumIdFields()
    {
        $topics = $this->out->getTopicsByForumId(2);

        // Topic 2
        self::assertEquals(2, $topics[2]->getId());
        self::assertEquals('Second topic', $topics[2]->getTopicTitle());
        self::assertEquals(2, $topics[2]->getForumId());
        self::assertEquals(0, $topics[2]->getType());
        self::assertEquals(0, $topics[2]->getStatus());
        self::assertEquals(0, $topics[2]->getVisits());
        self::assertEquals('2024-01-16 11:00:00', $topics[2]->getDateCreated());
        self::assertEquals(1, $topics[2]->getCountPosts());
        self::assertInstanceOf(UserModel::class, $topics[2]->getAuthor());
        self::assertEquals(2, $topics[2]->getAuthor()->getId());
        self::assertInstanceOf(PrefixModel::class, $topics[2]->getTopicPrefix());
        self::assertEquals(0, $topics[2]->getTopicPrefix()->getId());
        self::assertEquals('', $topics[2]->getTopicPrefix()->getPrefix());

        // Topic 1
        self::assertEquals(1, $topics[1]->getId());
        self::assertEquals('Willkommen bei Ilch!', $topics[1]->getTopicTitle());
        self::assertEquals(1, $topics[1]->getCountPosts());
        self::assertInstanceOf(UserModel::class, $topics[1]->getAuthor());
        self::assertEquals(1, $topics[1]->getAuthor()->getId());
    }

    /**
     * Tests that getTopicsByForumId() returns an empty array for an unknown forum.
     */
    public function testGetTopicsByForumIdUnknownForum()
    {
        self::assertSame([], $this->out->getTopicsByForumId(999));
    }

    /**
     * Tests that getTopicsByForumIds() returns an empty array for an empty id list.
     */
    public function testGetTopicsByForumIdsEmptyIds()
    {
        self::assertSame([], $this->out->getTopicsByForumIds([]));
    }

    /**
     * Tests that getTopicsByForumIds() works with multiple forum ids.
     */
    public function testGetTopicsByForumIdsMultipleForums()
    {
        // Item 1 is a category without topics, item 2 is the forum with both topics.
        $topics = $this->out->getTopicsByForumIds([1, 2]);

        self::assertCount(2, $topics);
        self::assertSame([2, 1], array_keys($topics));
    }

    /**
     * Tests that getTopicsByForumId() paginates correctly.
     */
    public function testGetTopicsByForumIdWithPagination()
    {
        $pagination = new Pagination();
        $pagination->setRowsPerPage(1);

        $topics = $this->out->getTopicsByForumId(2, $pagination);

        self::assertEquals(2, $pagination->getRows());
        self::assertCount(1, $topics);
        self::assertArrayHasKey(2, $topics);
        self::assertEquals('Second topic', $topics[2]->getTopicTitle());
    }

    /**
     * Tests that pagination without matching topics returns an empty array and zero rows.
     */
    public function testGetTopicsByForumIdPaginationWithoutMatchingTopics()
    {
        $pagination = new Pagination();
        $pagination->setRowsPerPage(10);

        $topics = $this->out->getTopicsByForumId(999, $pagination);

        self::assertSame([], $topics);
        self::assertEquals(0, $pagination->getRows());
    }

    /**
     * Tests that the prefix join resolves the prefix text for a topic with a prefix.
     */
    public function testGetTopicsByForumIdWithPrefix()
    {
        $this->out->update(2, 'topic_prefix', 1);

        $topics = $this->out->getTopicsByForumId(2);

        self::assertEquals(1, $topics[2]->getTopicPrefix()->getId());
        self::assertEquals('Pinned', $topics[2]->getTopicPrefix()->getPrefix());
    }

    // -----------------------------------------------------------------
    // getTopicsListByForumId()
    // -----------------------------------------------------------------

    /**
     * Tests that getTopicsListByForumId() returns all topic ids of the forum.
     */
    public function testGetTopicsListByForumId()
    {
        $ids = $this->out->getTopicsListByForumId(2);

        self::assertCount(2, $ids);
        self::assertContains(1, $ids);
        self::assertContains(2, $ids);
    }

    /**
     * Tests that getTopicsListByForumId() returns an empty array for an unknown forum.
     */
    public function testGetTopicsListByForumIdEmpty()
    {
        self::assertSame([], $this->out->getTopicsListByForumId(999));
    }

    /**
     * Tests that getTopics() returns all topics ordered by type DESC, id DESC.
     */
    public function testGetTopics()
    {
        $topics = $this->out->getTopics();

        self::assertCount(2, $topics);
        self::assertEquals(2, $topics[0]->getId());
        self::assertEquals(1, $topics[1]->getId());

        self::assertEquals('Second topic', $topics[0]->getTopicTitle());
        self::assertEquals(2, $topics[0]->getForumId());
        self::assertEquals(0, $topics[0]->getType());
        self::assertEquals(0, $topics[0]->getStatus());
        self::assertEquals(0, $topics[0]->getVisits());
        self::assertEquals('2024-01-16 11:00:00', $topics[0]->getDateCreated());
        self::assertEquals(1, $topics[0]->getCountPosts());
        self::assertInstanceOf(UserModel::class, $topics[0]->getAuthor());
        self::assertEquals(2, $topics[0]->getAuthor()->getId());
    }

    /**
     * Tests that getTopics() respects the limit parameter.
     */
    public function testGetTopicsWithLimit()
    {
        $topics = $this->out->getTopics(null, [1]);

        self::assertCount(1, $topics);
        self::assertEquals(2, $topics[0]->getId());
    }

    /**
     * Tests that getTopics() paginates correctly.
     */
    public function testGetTopicsWithPagination()
    {
        $pagination = new Pagination();
        $pagination->setRowsPerPage(1);

        $topics = $this->out->getTopics($pagination);

        self::assertEquals(2, $pagination->getRows());
        self::assertCount(1, $topics);
        self::assertEquals(2, $topics[0]->getId());
    }

    /**
     * Tests that getTopicById() returns the correct topic.
     */
    public function testGetTopicById()
    {
        $topic = $this->out->getTopicById(1);

        self::assertNotNull($topic);
        self::assertEquals(1, $topic->getId());
        self::assertEquals('Willkommen bei Ilch!', $topic->getTopicTitle());
        self::assertEquals(1, $topic->getCreatorId());
        self::assertEquals(0, $topic->getVisits());
        self::assertEquals('2024-01-15 10:00:00', $topic->getDateCreated());
        self::assertEquals(0, $topic->getStatus());
        self::assertInstanceOf(UserModel::class, $topic->getAuthor());
        self::assertEquals(1, $topic->getAuthor()->getId());
        self::assertInstanceOf(PrefixModel::class, $topic->getTopicPrefix());
        self::assertEquals(0, $topic->getTopicPrefix()->getId());
    }

    /**
     * Tests that getTopicById() returns null for a non-existent id.
     */
    public function testGetTopicByIdNotFound()
    {
        self::assertNull($this->out->getTopicById(9999));
    }

    /**
     * Tests that getLastPostByTopicId() returns the last post of the topic.
     */
    public function testGetLastPostByTopicId()
    {
        $post = $this->out->getLastPostByTopicId(1);

        self::assertNotNull($post);
        self::assertInstanceOf(PostModel::class, $post);
        self::assertEquals(1, $post->getId());
        self::assertEquals(1, $post->getTopicId());
        self::assertEquals('2024-01-15 10:00:00', $post->getDateCreated());
        self::assertInstanceOf(UserModel::class, $post->getAutor());
        self::assertEquals(1, $post->getAutor()->getId());
    }

    /**
     * Tests that getLastPostByTopicId() returns null for a non-existent topic.
     */
    public function testGetLastPostByTopicIdNotFound()
    {
        self::assertNull($this->out->getLastPostByTopicId(9999));
    }

    /**
     * Tests that getLastPostByTopicId() returns null for a topic without posts.
     */
    public function testGetLastPostByTopicIdWithoutPosts()
    {
        $prefix = new PrefixModel();
        $prefix->setId(0);

        $model = new TopicModel();
        $model->setId(0)
            ->setForumId(2)
            ->setCreatorId(1)
            ->setType(0)
            ->setDateCreated('2024-01-17 09:00:00')
            ->setTopicTitle('Topic without posts')
            ->setTopicPrefix($prefix);

        $newId = $this->out->save($model);

        self::assertNull($this->out->getLastPostByTopicId($newId));
    }

    /**
     * Tests that getLastPostsByTopicIds() returns the last posts ordered by date DESC.
     */
    public function testGetLastPostsByTopicIds()
    {
        $posts = $this->out->getLastPostsByTopicIds([1, 2]);

        self::assertNotNull($posts);
        self::assertCount(2, $posts);

        // Ordered by posts.date_created DESC
        self::assertEquals(2, $posts[0]->getId());
        self::assertEquals(2, $posts[0]->getTopicId());
        self::assertEquals(2, $posts[0]->getAutor()->getId());

        self::assertEquals(1, $posts[1]->getId());
        self::assertEquals(1, $posts[1]->getTopicId());
        self::assertEquals(1, $posts[1]->getAutor()->getId());
    }

    /**
     * Tests that getLastPostsByTopicIds() returns null for an empty id list.
     */
    public function testGetLastPostsByTopicIdsEmpty()
    {
        self::assertNull($this->out->getLastPostsByTopicIds([]));
    }

    /**
     * Tests that getLastPostsByTopicIds() returns null for non-existent topic ids.
     */
    public function testGetLastPostsByTopicIdsNotFound()
    {
        self::assertNull($this->out->getLastPostsByTopicIds([9999]));
    }

    /**
     * Tests that the last post is not read when both read timestamps are before the post date.
     */
    public function testGetLastPostByTopicIdNotRead()
    {
        // Fixture: user 1 read topic 1 at 09:30 and the forum at 09:00,
        // both before the last post (10:00).
        $post = $this->out->getLastPostByTopicId(1, 1);

        self::assertNotNull($post);
        self::assertFalse($post->getRead());
    }

    /**
     * Tests that the last post is read when the topic read timestamp is after the post date.
     */
    public function testGetLastPostByTopicIdReadViaTopicRead()
    {
        $this->db->update('forum_topics_read')
            ->values(['datetime' => '2024-01-15 12:00:00'])
            ->where(['user_id' => 1, 'topic_id' => 1])
            ->execute();

        $post = $this->out->getLastPostByTopicId(1, 1);

        self::assertNotNull($post);
        self::assertTrue($post->getRead());
    }

    /**
     * Tests that the last post is read when only the forum-level read timestamp matches.
     */
    public function testGetLastPostByTopicIdReadViaForumRead()
    {
        $this->db->update('forum_read')
            ->values(['datetime' => '2024-01-16 12:00:00'])
            ->where(['user_id' => 1, 'forum_id' => 2])
            ->execute();

        // User 1 has no topic read entry for topic 2, but the forum-level
        // read (2024-01-16 12:00) is newer than the last post (11:00).
        $post = $this->out->getLastPostByTopicId(2, 1);

        self::assertNotNull($post);
        self::assertTrue($post->getRead());
    }

    /**
     * Tests inserting a new topic via save() (id 0).
     */
    public function testSaveInsert()
    {
        $prefix = new PrefixModel();
        $prefix->setId(1);

        $model = new TopicModel();
        $model->setId(0)
            ->setForumId(2)
            ->setCreatorId(1)
            ->setType(0)
            ->setDateCreated('2024-01-17 12:00:00')
            ->setTopicTitle('New topic')
            ->setTopicPrefix($prefix);

        $newId = $this->out->save($model);

        self::assertGreaterThan(2, $newId);

        $topic = $this->out->getTopicById($newId);
        self::assertNotNull($topic);
        self::assertEquals('New topic', $topic->getTopicTitle());
        self::assertEquals(1, $topic->getCreatorId());
        self::assertEquals(0, $topic->getVisits());
        self::assertEquals('2024-01-17 12:00:00', $topic->getDateCreated());
        self::assertEquals(0, $topic->getStatus());
        self::assertEquals(1, $topic->getTopicPrefix()->getId());
        self::assertEquals('Pinned', $topic->getTopicPrefix()->getPrefix());

        $topics = $this->out->getTopicsByForumId(2);
        self::assertCount(3, $topics);
    }

    /**
     * Tests that save() with an existing id moves the topic to another forum item.
     */
    public function testSaveUpdateMovesTopic()
    {
        $model = new TopicModel();
        $model->setId(1)->setForumId(1);

        $result = $this->out->save($model);
        self::assertSame(1, $result);

        // Topic belongs to item 1 now
        $topicsOfItem1 = $this->out->getTopicsByForumIds([1]);
        self::assertCount(1, $topicsOfItem1);
        self::assertEquals('Willkommen bei Ilch!', $topicsOfItem1[1]->getTopicTitle());

        // No longer part of forum 2
        $topicsOfForum2 = $this->out->getTopicsByForumIds([2]);
        self::assertCount(1, $topicsOfForum2);
        self::assertEquals(2, $topicsOfForum2[2]->getId());
    }

    /**
     * Tests that save() with an existing id does not change other fields.
     */
    public function testSaveUpdateKeepsOtherFields()
    {
        $model = new TopicModel();
        $model->setId(2)->setForumId(2);

        $result = $this->out->save($model);
        self::assertSame(2, $result);

        $topic = $this->out->getTopicById(2);
        self::assertNotNull($topic);
        self::assertEquals('Second topic', $topic->getTopicTitle());
        self::assertEquals(2, $topic->getCreatorId());
    }

    /**
     * Tests that updateStatus() toggles the status of a topic.
     */
    public function testUpdateStatus()
    {
        $this->out->updateStatus(1);
        self::assertEquals(1, $this->out->getTopicById(1)->getStatus());

        // Toggling again resets the status
        $this->out->updateStatus(1);
        self::assertEquals(0, $this->out->getTopicById(1)->getStatus());
    }

    /**
     * Tests that updateType() toggles the type of topic.
     */
    public function testUpdateType()
    {
        $this->out->updateType(2);
        self::assertEquals(1, $this->out->getTopicById(2)->getType());

        // Toggling again resets the type
        $this->out->updateType(2);
        self::assertEquals(0, $this->out->getTopicById(2)->getType());
    }

    /**
     * Tests that update() changes the given column.
     */
    public function testUpdateColumn()
    {
        $this->out->update(1, 'visits', 7);

        self::assertEquals(7, $this->out->getTopicById(1)->getVisits());
    }

    /**
     * Tests that getLastActiveTopics() returns topics ordered by last_post_date DESC, last_post_id DESC.
     */
    public function testGetLastActiveTopics()
    {
        $topics = $this->out->getLastActiveTopics();

        self::assertCount(2, $topics);

        self::assertEquals(2, $topics[0]['topic_id']);
        self::assertEquals('Second topic', $topics[0]['topic_title']);
        self::assertEquals(2, $topics[0]['forum_id']);
        self::assertEquals('2024-01-16 11:00:00', $topics[0]['date_created']);

        self::assertEquals(1, $topics[1]['topic_id']);
        self::assertEquals('Willkommen bei Ilch!', $topics[1]['topic_title']);
        self::assertEquals(2, $topics[1]['forum_id']);
        self::assertEquals('2024-01-15 10:00:00', $topics[1]['date_created']);
    }

    /**
     * Tests that getLastActiveTopics() respects the limit.
     */
    public function testGetLastActiveTopicsWithLimit()
    {
        $topics = $this->out->getLastActiveTopics(1);

        self::assertCount(1, $topics);
        self::assertEquals(2, $topics[0]['topic_id']);
    }

    /**
     * Tests that deleteById() removes the topic and its posts (FK cascade).
     */
    public function testDeleteById()
    {
        $this->out->deleteById(1);

        self::assertNull($this->out->getTopicById(1));

        $topics = $this->out->getTopicsByForumId(2);
        self::assertCount(1, $topics);
        self::assertEquals(2, $topics[2]->getId());

        // The post of the deleted topic is removed by the FK cascade
        $postCount = $this->db->select('COUNT(*) AS total')
            ->from('forum_posts')
            ->where(['topic_id' => 1])
            ->execute()
            ->fetchCell();
        self::assertEquals(0, $postCount);
    }

    /**
     * Tests that deleteById() on a non-existent id does not throw and leaves data untouched.
     */
    public function testDeleteByIdNotFound()
    {
        $this->out->deleteById(9999);

        $topics = $this->out->getTopicsByForumId(2);
        self::assertCount(2, $topics);
    }

    /**
     * Tests that saveVisits() updates the visits of a topic.
     */
    public function testSaveVisits()
    {
        $model = new TopicModel();
        $model->setId(1)->setVisits(5);

        $this->out->saveVisits($model);

        self::assertEquals(5, $this->out->getTopicById(1)->getVisits());
    }

    /**
     * Tests that saveVisits() with a falsy visits value does not update the topic.
     */
    public function testSaveVisitsZeroKeepsOldValue()
    {
        $model = new TopicModel();
        $model->setId(1)->setVisits(5);
        $this->out->saveVisits($model);

        $model->setVisits(0);
        $this->out->saveVisits($model);

        self::assertEquals(5, $this->out->getTopicById(1)->getVisits());
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
