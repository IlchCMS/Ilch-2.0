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
use Modules\Forum\Mappers\Forum as ForumMapper;
use Modules\Forum\Models\ForumItem as ForumItemModel;
use Modules\Forum\Models\ForumPost as PostModel;
use Modules\User\Mappers\User as UserMapper;
use Modules\User\Models\User;

class ForumTest extends DatabaseTestCase
{
    /**
     * @var ForumMapper
     */
    protected Forum $out;
    protected PhpunitDataset $phpunitDataset;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database.yml');
        $this->out = new ForumMapper();
    }

    /**
     * Tests that getForumItemsAdmincenterByParentIds() returns top level categories with their sub items.
     */
    public function testGetForumItemsAdmincenterByParentIds()
    {
        $items = $this->out->getForumItemsAdmincenterByParentIds([0]);

        self::assertIsArray($items);
        self::assertCount(1, $items);

        $category = $items[0];
        self::assertInstanceOf(ForumItemModel::class, $category);
        self::assertEquals(1, $category->getId());
        self::assertEquals(0, $category->getType());
        self::assertSame('Meine Kategorie', $category->getTitle());
        self::assertSame('Meine erste Kategorie', $category->getDesc());
        self::assertEquals(0, $category->getParentId());

        // The category contains forum 2 as its only sub item.
        self::assertCount(1, $category->getSubItems());
        self::assertEquals(2, $category->getSubItems()[0]->getId());
        self::assertSame('Mein Forum', $category->getSubItems()[0]->getTitle());
        self::assertSame([], $category->getSubItems()[0]->getSubItems());
    }

    /**
     * Tests that getForumItemsAdmincenterByParentIds() returns a forum without sub items.
     */
    public function testGetForumItemsAdmincenterByParentIdsForum()
    {
        $items = $this->out->getForumItemsAdmincenterByParentIds([1]);

        self::assertIsArray($items);
        self::assertCount(1, $items);

        $forum = $items[0];
        self::assertEquals(2, $forum->getId());
        self::assertEquals(1, $forum->getParentId());
        self::assertSame('Mein Forum', $forum->getTitle());
        self::assertSame([], $forum->getSubItems());
    }

    /**
     * Tests that getForumItemsAdmincenterByParentIds() returns an empty array without matching items.
     */
    public function testGetForumItemsAdmincenterByParentIdsEmpty()
    {
        self::assertSame([], $this->out->getForumItemsAdmincenterByParentIds([9999]));
        self::assertSame([], $this->out->getForumItemsAdmincenterByParentIds([]));
    }

    /**
     * Tests that getForumItemsAdmincenterByParentIds() returns the full access lists.
     */
    public function testGetForumItemsAdmincenterByParentIdsWithAccesses()
    {
        $this->db->insert('forum_accesses')
            ->values(['item_id' => 2, 'group_id' => 1, 'access_type' => 0])
            ->execute();
        $this->db->insert('forum_accesses')
            ->values(['item_id' => 2, 'group_id' => 2, 'access_type' => 1])
            ->execute();
        $this->db->insert('forum_accesses')
            ->values(['item_id' => 2, 'group_id' => 3, 'access_type' => 2])
            ->execute();

        $items = $this->out->getForumItemsAdmincenterByParentIds([1]);

        self::assertCount(1, $items);
        self::assertSame('1', $items[0]->getReadAccess());
        self::assertSame('2', $items[0]->getReplyAccess());
        self::assertSame('3', $items[0]->getCreateAccess());
    }

    /**
     * Tests that getForumItemsAdmincenterByParentIds() returns the allowed prefixes.
     */
    public function testGetForumItemsAdmincenterByParentIdsWithPrefixes()
    {
        $this->db->insert('forum_prefixes_items')
            ->values(['item_id' => 2, 'prefix_id' => 1])
            ->execute();
        $this->db->insert('forum_prefixes_items')
            ->values(['item_id' => 2, 'prefix_id' => 2])
            ->execute();

        $items = $this->out->getForumItemsAdmincenterByParentIds([1]);

        self::assertCount(1, $items);
        // GROUP_CONCAT does not guarantee an order, so compare without order.
        self::assertEqualsCanonicalizing(['1', '2'], explode(',', $items[0]->getPrefixes()));
    }

    /**
     * Tests that getForumItemsByParentIdsUser() returns the category tree with sub items for a guest.
     */
    public function testGetForumItemsByParentIdsUser()
    {
        $items = $this->out->getForumItemsByParentIdsUser([0]);

        self::assertIsArray($items);
        self::assertCount(1, $items);

        $category = $items[0];
        self::assertInstanceOf(ForumItemModel::class, $category);
        self::assertEquals(1, $category->getId());
        self::assertEquals(0, $category->getTopics());
        self::assertEquals(0, $category->getPosts());

        // The category contains forum 2 as its only sub item.
        self::assertCount(1, $category->getSubItems());
        self::assertEquals(2, $category->getSubItems()[0]->getId());
    }

    /**
     * Tests that getForumItemsByParentIdsUser() fills topic/post counts and the last post of a forum.
     */
    public function testGetForumItemsByParentIdsUserForum()
    {
        $items = $this->out->getForumItemsByParentIdsUser([1]);

        self::assertIsArray($items);
        self::assertCount(1, $items);

        $forum = $items[0];
        self::assertEquals(2, $forum->getId());
        self::assertEquals(2, $forum->getTopics());
        self::assertEquals(2, $forum->getPosts());

        self::assertNotNull($forum->getLastPost());
        self::assertEquals(2, $forum->getLastPost()->getId());
        self::assertSame('Second topic', $forum->getLastPost()->getTopicTitle());
        self::assertSame('2024-01-16 11:00:00', $forum->getLastPost()->getDateCreated());
        self::assertSame('Bob', $forum->getLastPost()->getAutor()->getName());
    }

    /**
     * Tests that getForumItemsByParentIdsUser() also works with a logged in user.
     */
    public function testGetForumItemsByParentIdsUserWithUser()
    {
        $user = (new UserMapper())->getUserById(1);

        self::assertNotNull($user);

        $items = $this->out->getForumItemsByParentIdsUser([1], $user);

        self::assertIsArray($items);
        self::assertCount(1, $items);
        self::assertEquals(2, $items[0]->getId());
        self::assertSame('', $items[0]->getReadAccess());
    }

    /**
     * Tests that getForumItemsByParentIdsUser() returns accesses of the guest group.
     */
    public function testGetForumItemsByParentIdsUserAccessForGuestGroup()
    {
        // The guest group (3) is always part of the checked groups.
        $this->db->insert('forum_accesses')
            ->values(['item_id' => 2, 'group_id' => 3, 'access_type' => 0])
            ->execute();

        $items = $this->out->getForumItemsByParentIdsUser([1]);

        self::assertCount(1, $items);
        self::assertSame('3', $items[0]->getReadAccess());
    }

    /**
     * Tests that getForumItemsByParentIdsUser() hides accesses of foreign groups.
     */
    public function testGetForumItemsByParentIdsUserHidesForeignGroupAccess()
    {
        // Access for the admin group (1), which neither guests nor user 1 have.
        $this->db->insert('forum_accesses')
            ->values(['item_id' => 2, 'group_id' => 1, 'access_type' => 0])
            ->execute();

        $items = $this->out->getForumItemsByParentIdsUser([1], (new UserMapper())->getUserById(1));

        self::assertCount(1, $items);
        self::assertSame('', $items[0]->getReadAccess());
    }

    /**
     * Tests that getForumItemsByParentIdsUser() returns an empty array without matching items.
     */
    public function testGetForumItemsByParentIdsUserEmpty()
    {
        self::assertSame([], $this->out->getForumItemsByParentIdsUser([9999]));
        self::assertSame([], $this->out->getForumItemsByParentIdsUser([]));
    }

    /**
     * Tests that getForumById() returns the correct forum.
     */
    public function testGetForumById()
    {
        $forum = $this->out->getForumById(1);

        self::assertNotNull($forum);
        self::assertInstanceOf(ForumItemModel::class, $forum);
        self::assertEquals(1, $forum->getId());
        self::assertEquals(0, $forum->getType());
        self::assertSame('Meine Kategorie', $forum->getTitle());
        self::assertSame('Meine erste Kategorie', $forum->getDesc());
        self::assertEquals(0, $forum->getParentId());
        self::assertSame('', $forum->getPrefixes());
        self::assertSame('', $forum->getReadAccess());
        self::assertSame('', $forum->getReplyAccess());
        self::assertSame('', $forum->getCreateAccess());
    }

    /**
     * Tests that getForumById() returns null for a non-existent id.
     */
    public function testGetForumByIdNotFound()
    {
        self::assertNull($this->out->getForumById(9999));
    }

    /**
     * Tests that getForumByIdUser() returns the correct forum for a guest.
     */
    public function testGetForumByIdUser()
    {
        $forum = $this->out->getForumByIdUser(2);

        self::assertNotNull($forum);
        self::assertEquals(2, $forum->getId());
        self::assertSame('Mein Forum', $forum->getTitle());
    }

    /**
     * Tests that getForumByIdUser() returns null for a non-existent id.
     */
    public function testGetForumByIdUserNotFound()
    {
        self::assertNull($this->out->getForumByIdUser(9999));
    }

    /**
     * Tests that getForumByIdUser() also works with a logged in user.
     */
    public function testGetForumByIdUserWithUser()
    {
        $user = (new UserMapper())->getUserById(1);

        self::assertNotNull($user);

        $forum = $this->out->getForumByIdUser(1, $user);

        self::assertNotNull($forum);
        self::assertEquals(1, $forum->getId());
    }

    /**
     * Tests that getForumsByIdsUser() returns all requested forums keyed by their ids.
     */
    public function testGetForumsByIdsUser()
    {
        $forums = $this->out->getForumsByIdsUser([1, 2]);

        self::assertIsArray($forums);
        self::assertCount(2, $forums);
        self::assertArrayHasKey(1, $forums);
        self::assertArrayHasKey(2, $forums);
        self::assertSame('Meine Kategorie', $forums[1]->getTitle());
        self::assertSame('Mein Forum', $forums[2]->getTitle());
    }

    /**
     * Tests that getForumsByIdsUser() returns null for an empty id list.
     */
    public function testGetForumsByIdsUserEmptyIds()
    {
        self::assertNull($this->out->getForumsByIdsUser([]));
    }

    /**
     * Tests that getForumsByIdsUser() returns null without matching ids.
     */
    public function testGetForumsByIdsUserNotFound()
    {
        self::assertNull($this->out->getForumsByIdsUser([9999]));
    }

    /**
     * Tests that getForumsByIdsUser() hides accesses of foreign groups for a guest.
     */
    public function testGetForumsByIdsUserHidesForeignGroupAccess()
    {
        $this->db->insert('forum_accesses')
            ->values(['item_id' => 2, 'group_id' => 1, 'access_type' => 0])
            ->execute();

        $forums = $this->out->getForumsByIdsUser([2]);

        self::assertIsArray($forums);
        self::assertSame('', $forums[2]->getReadAccess());
    }

    /**
     * Tests that getForumsByIdsUser() returns the guest group access.
     */
    public function testGetForumsByIdsUserReturnsGuestAccess()
    {
        $this->db->insert('forum_accesses')
            ->values(['item_id' => 2, 'group_id' => 3, 'access_type' => 0])
            ->execute();

        $forums = $this->out->getForumsByIdsUser([2]);

        self::assertIsArray($forums);
        self::assertSame('3', $forums[2]->getReadAccess());
    }

    /**
     * Tests that getForumByTopicId() returns the forum a topic belongs to.
     */
    public function testGetForumByTopicId()
    {
        $forum = $this->out->getForumByTopicId(1);

        self::assertNotNull($forum);
        self::assertEquals(2, $forum->getId());
        self::assertSame('Mein Forum', $forum->getTitle());
    }

    /**
     * Tests that getForumByTopicId() returns null for a non-existent topic.
     */
    public function testGetForumByTopicIdNotFound()
    {
        self::assertNull($this->out->getForumByTopicId(9999));
    }

    /**
     * Tests that getForumByTopicIdUser() returns the forum a topic belongs to for a guest.
     */
    public function testGetForumByTopicIdUser()
    {
        $forum = $this->out->getForumByTopicIdUser(2);

        self::assertNotNull($forum);
        self::assertEquals(2, $forum->getId());
    }

    /**
     * Tests that getForumByTopicIdUser() returns null for a non-existent topic.
     */
    public function testGetForumByTopicIdUserNotFound()
    {
        self::assertNull($this->out->getForumByTopicIdUser(9999));
    }

    /**
     * Tests that getLastPostsByForumIds() returns the last post of a forum.
     */
    public function testGetLastPostsByForumIds()
    {
        $posts = $this->out->getLastPostsByForumIds([2]);

        self::assertIsArray($posts);
        self::assertCount(1, $posts);

        $post = $posts[2];
        self::assertInstanceOf(PostModel::class, $post);
        self::assertEquals(2, $post->getId());
        self::assertEquals(2, $post->getTopicId());
        self::assertSame('Second topic', $post->getTopicTitle());
        self::assertSame('2024-01-16 11:00:00', $post->getDateCreated());
        self::assertInstanceOf(User::class, $post->getAutor());
        self::assertSame('Bob', $post->getAutor()->getName());
    }

    /**
     * Tests that getLastPostsByForumIds() returns null without usable data.
     */
    public function testGetLastPostsByForumIdsWithoutData()
    {
        self::assertNull($this->out->getLastPostsByForumIds([]));
        self::assertNull($this->out->getLastPostsByForumIds([9999]));

        // Item 1 is a category without a last post pointer.
        self::assertNull($this->out->getLastPostsByForumIds([1]));
    }

    /**
     * Tests that getLastPostsByForumIds() marks a post as unread for a user.
     */
    public function testGetLastPostsByForumIdsUnreadForUser()
    {
        // The last post (2024-01-16 11:00:00) is newer than all read marks of user 1 (2024-01-15).
        $posts = $this->out->getLastPostsByForumIds([2], 1);

        self::assertIsArray($posts);
        self::assertFalse($posts[2]->getRead());
    }

    /**
     * Tests that getLastPostsByForumIds() marks a post as read for a user.
     */
    public function testGetLastPostsByForumIdsReadForUser()
    {
        // Make post 1 the last post of forum 2.
        $this->db->update('forum_items')
            ->values(['last_post_id' => 1, 'last_post_date' => '2024-01-15 10:00:00'])
            ->where(['id' => 2])
            ->execute();

        // User 1 read topic 1 after the last post was created.
        $this->db->insert('forum_topics_read')
            ->values(['user_id' => 1, 'forum_id' => 2, 'topic_id' => 1, 'datetime' => '2024-01-15 11:00:00'])
            ->execute();

        $posts = $this->out->getLastPostsByForumIds([2], 1);

        self::assertIsArray($posts);
        self::assertEquals(1, $posts[2]->getId());
        self::assertSame('Alice', $posts[2]->getAutor()->getName());
        self::assertTrue($posts[2]->getRead());
    }

    /**
     * Tests that getLastPostsByForumIds() falls back to the dummy user for unknown authors.
     */
    public function testGetLastPostsByForumIdsFallsBackToDummyUser()
    {
        $postId = $this->db->insert('forum_posts')
            ->values([
                'topic_id' => 1,
                'forum_id' => 2,
                'text' => 'Post by an unknown user',
                'user_id' => 9999,
                'date_created' => '2024-01-17 12:00:00',
            ])
            ->execute();

        $this->db->update('forum_items')
            ->values(['last_post_id' => $postId, 'last_post_date' => '2024-01-17 12:00:00'])
            ->where(['id' => 2])
            ->execute();

        $posts = $this->out->getLastPostsByForumIds([2]);

        self::assertIsArray($posts);
        self::assertEquals($postId, $posts[2]->getId());
        self::assertInstanceOf(User::class, $posts[2]->getAutor());
    }

    /**
     * Tests that getCatByParentId() returns the correct item.
     */
    public function testGetCatByParentId()
    {
        $category = $this->out->getCatByParentId(1);

        self::assertNotNull($category);
        self::assertEquals(1, $category->getId());
        self::assertEquals(0, $category->getType());
        self::assertSame('Meine Kategorie', $category->getTitle());
        self::assertSame('Meine erste Kategorie', $category->getDesc());
        self::assertEquals(0, $category->getParentId());
        self::assertSame('', $category->getPrefixes());
    }

    /**
     * Tests that getCatByParentId() returns null for a non-existent id.
     */
    public function testGetCatByParentIdNotFound()
    {
        self::assertNull($this->out->getCatByParentId(9999));
    }

    /**
     * Tests that getForumItems() returns all items sorted by their sort value.
     */
    public function testGetForumItems()
    {
        $items = $this->out->getForumItems();

        self::assertNotNull($items);
        self::assertCount(2, $items);
        self::assertInstanceOf(ForumItemModel::class, $items[0]);
        self::assertEquals(1, $items[0]->getId());
        self::assertEquals(2, $items[1]->getId());
    }

    /**
     * Tests that getForumItems() returns correct fields for both items.
     */
    public function testGetForumItemsFields()
    {
        $items = $this->out->getForumItems();

        self::assertSame('Meine Kategorie', $items[0]->getTitle());
        self::assertSame('Meine erste Kategorie', $items[0]->getDesc());
        self::assertEquals(0, $items[0]->getParentId());
        self::assertSame('', $items[0]->getPrefixes());

        self::assertSame('Mein Forum', $items[1]->getTitle());
        self::assertSame('Mein erstes Forum', $items[1]->getDesc());
        self::assertEquals(1, $items[1]->getParentId());
        self::assertSame('', $items[1]->getPrefixes());
    }

    /**
     * Tests that getForumItems() returns null when no items exist.
     */
    public function testGetForumItemsEmpty()
    {
        $this->out->deleteItems([1, 2]);

        self::assertNull($this->out->getForumItems());
    }

    /**
     * Tests that getForumItemsUser() returns all items for a guest.
     */
    public function testGetForumItemsUser()
    {
        $items = $this->out->getForumItemsUser();

        self::assertIsArray($items);
        self::assertCount(2, $items);
        self::assertArrayHasKey(1, $items);
        self::assertArrayHasKey(2, $items);
    }

    /**
     * Tests that getForumItemsUser() also works with a logged in user.
     */
    public function testGetForumItemsUserWithUser()
    {
        $user = (new UserMapper())->getUserById(1);

        self::assertNotNull($user);

        $items = $this->out->getForumItemsUser($user);

        self::assertIsArray($items);
        self::assertCount(2, $items);
    }

    /**
     * Tests that getForumItemsUser() returns the access of the own groups for a guest.
     */
    public function testGetForumItemsUserReturnsAccessForOwnGroups()
    {
        // The guest group (3) is always part of the checked groups.
        $this->db->insert('forum_accesses')
            ->values(['item_id' => 2, 'group_id' => 3, 'access_type' => 0])
            ->execute();

        $items = $this->out->getForumItemsUser();

        self::assertSame('3', $items[2]->getReadAccess());
    }

    /**
     * Tests that getForumItemsIds() returns all item ids.
     */
    public function testGetForumItemsIds()
    {
        $ids = $this->out->getForumItemsIds();

        self::assertIsArray($ids);
        self::assertCount(2, $ids);
        self::assertContains(1, $ids);
        self::assertContains(2, $ids);
    }

    /**
     * Tests that getForumItemsIds() returns null when no items exist.
     */
    public function testGetForumItemsIdsEmpty()
    {
        $this->out->deleteItems([1, 2]);

        self::assertNull($this->out->getForumItemsIds());
    }

    /**
     * Tests that getCountPostsById() returns the number of posts of a forum.
     */
    public function testGetCountPostsById()
    {
        self::assertSame(2, $this->out->getCountPostsById(2));
        self::assertSame(0, $this->out->getCountPostsById(1));
        self::assertSame(0, $this->out->getCountPostsById(9999));
    }

    /**
     * Tests that getCountPostsByTopicId() returns the number of posts of a topic.
     */
    public function testGetCountPostsByTopicId()
    {
        self::assertEquals(1, $this->out->getCountPostsByTopicId(1));
        self::assertEquals(1, $this->out->getCountPostsByTopicId(2));
        self::assertSame(0, $this->out->getCountPostsByTopicId(9999));
    }

    /**
     * Tests that getCountPostsByTopicIds() returns the counts keyed by topic ids.
     */
    public function testGetCountPostsByTopicIds()
    {
        $counts = $this->out->getCountPostsByTopicIds([1, 2]);

        self::assertIsArray($counts);
        self::assertCount(2, $counts);
        self::assertEquals(1, $counts[1]);
        self::assertEquals(1, $counts[2]);
    }

    /**
     * Tests that getCountPostsByTopicIds() returns null for an empty id list.
     */
    public function testGetCountPostsByTopicIdsEmpty()
    {
        self::assertNull($this->out->getCountPostsByTopicIds([]));
    }

    /**
     * Tests that getCountPostsByTopicIds() returns null without matching topics.
     */
    public function testGetCountPostsByTopicIdsNotFound()
    {
        self::assertNull($this->out->getCountPostsByTopicIds([9999]));
    }

    /**
     * Tests that getCountTopicsById() returns the number of topics of a forum.
     */
    public function testGetCountTopicsById()
    {
        self::assertEquals(2, $this->out->getCountTopicsById(2));
        self::assertSame(0, $this->out->getCountTopicsById(1));
        self::assertSame(0, $this->out->getCountTopicsById(9999));
    }

    /**
     * Tests that getForumPermas() returns all items keyed by their titles.
     */
    public function testGetForumPermas()
    {
        $permas = $this->out->getForumPermas();

        self::assertIsArray($permas);
        self::assertCount(2, $permas);
        self::assertArrayHasKey('Meine Kategorie', $permas);
        self::assertArrayHasKey('Mein Forum', $permas);
        self::assertSame('Meine erste Kategorie', $permas['Meine Kategorie']['description']);
        self::assertSame('Mein erstes Forum', $permas['Mein Forum']['description']);
    }

    /**
     * Tests that getForumPermas() returns null when no items exist.
     */
    public function testGetForumPermasEmpty()
    {
        $this->out->deleteItems([1, 2]);

        self::assertNull($this->out->getForumPermas());
    }

    /**
     * Tests that getListOfForumIdsWithUnreadTopics() returns an empty list without forum ids.
     */
    public function testGetListOfForumIdsWithUnreadTopicsEmptyForumIds()
    {
        self::assertSame([], $this->out->getListOfForumIdsWithUnreadTopics(1, []));
    }

    /**
     * Tests that getListOfForumIdsWithUnreadTopics() returns forums with unread topics.
     */
    public function testGetListOfForumIdsWithUnreadTopics()
    {
        // User 9999 has no read marks at all.
        $forumIds = $this->out->getListOfForumIdsWithUnreadTopics(9999, [2]);
        self::assertEqualsCanonicalizing([2], $forumIds);

        // User 1's read marks are older than both last post dates.
        $forumIds = $this->out->getListOfForumIdsWithUnreadTopics(1, [2]);
        self::assertEqualsCanonicalizing([2], $forumIds);
    }

    /**
     * Tests that getListOfForumIdsWithUnreadTopics() also checks child forums.
     */
    public function testGetListOfForumIdsWithUnreadTopicsIncludesChildForums()
    {
        // Item 1 is a category; its child (item 2) must be checked as well.
        $forumIds = $this->out->getListOfForumIdsWithUnreadTopics(9999, [1]);

        self::assertEqualsCanonicalizing([2], $forumIds);
    }

    /**
     * Tests that getListOfForumIdsWithUnreadTopics() is empty after the forum was read.
     */
    public function testGetListOfForumIdsWithUnreadTopicsAfterForumRead()
    {
        // Mark the whole forum as read after the last post.
        $this->db->insert('forum_read')
            ->values(['user_id' => 1, 'forum_id' => 2, 'datetime' => '2024-01-17 00:00:00'])
            ->execute();

        $forumIds = $this->out->getListOfForumIdsWithUnreadTopics(1, [2]);

        self::assertSame([], $forumIds);
    }

    /**
     * Tests that getListOfForumIdsWithUnreadTopics() keeps a forum with one still unread topic.
     */
    public function testGetListOfForumIdsWithUnreadTopicsTopicStillUnread()
    {
        // User 1 read topic 1 after its last post, but topic 2 is still unread.
        $this->db->insert('forum_topics_read')
            ->values(['user_id' => 1, 'forum_id' => 2, 'topic_id' => 1, 'datetime' => '2024-01-15 11:00:00'])
            ->execute();

        $forumIds = $this->out->getListOfForumIdsWithUnreadTopics(1, [2]);

        self::assertEqualsCanonicalizing([2], $forumIds);
    }

    /**
     * Tests inserting a new category via saveItem().
     */
    public function testSaveItemInsert()
    {
        $model = new ForumItemModel();
        $model->setId(0)
            ->setSort(5)
            ->setParentId(0)
            ->setType(0)
            ->setTitle('Neue Kategorie')
            ->setDesc('Neue Beschreibung');

        $itemId = $this->out->saveItem($model);

        self::assertGreaterThan(2, $itemId);

        $items = $this->out->getForumItems();
        self::assertCount(3, $items);

        $item = $this->out->getForumById($itemId);
        self::assertNotNull($item);
        self::assertSame('Neue Kategorie', $item->getTitle());
        self::assertSame('Neue Beschreibung', $item->getDesc());
        self::assertEquals(0, $item->getParentId());
        self::assertEquals(0, $item->getType());
    }

    /**
     * Tests inserting a forum including its prefixes and access rights via saveItem().
     */
    public function testSaveItemInsertForumWithPrefixesAndAccesses()
    {
        $model = new ForumItemModel();
        $model->setId(0)
            ->setSort(20)
            ->setParentId(1)
            ->setType(1)
            ->setTitle('Zweites Forum')
            ->setDesc('Zweite Beschreibung')
            ->setPrefixes('1,2')
            ->setReadAccess('1,3')
            ->setReplyAccess('2')
            ->setCreateAccess('');

        $itemId = $this->out->saveItem($model);

        self::assertGreaterThan(2, $itemId);

        $forum = $this->out->getForumById($itemId);
        self::assertNotNull($forum);
        self::assertSame('Zweites Forum', $forum->getTitle());

        // GROUP_CONCAT does not guarantee an order, so compare without order.
        self::assertEqualsCanonicalizing(['1', '2'], explode(',', $forum->getPrefixes()));
        self::assertEqualsCanonicalizing(['1', '3'], explode(',', $forum->getReadAccess()));
        self::assertSame('2', $forum->getReplyAccess());
        self::assertSame('', $forum->getCreateAccess());
    }

    /**
     * Tests updating an existing forum item via saveItem().
     */
    public function testSaveItemUpdate()
    {
        $model = new ForumItemModel();
        $model->setId(2)
            ->setSort(15)
            ->setParentId(1)
            ->setType(1)
            ->setTitle('Mein Forum (neu)')
            ->setDesc('Aktualisierte Beschreibung')
            ->setPrefixes('')
            ->setReadAccess('')
            ->setReplyAccess('')
            ->setCreateAccess('');

        $itemId = $this->out->saveItem($model);

        self::assertSame(2, $itemId);

        $forum = $this->out->getForumById(2);
        self::assertNotNull($forum);
        self::assertSame('Mein Forum (neu)', $forum->getTitle());
        self::assertSame('Aktualisierte Beschreibung', $forum->getDesc());

        $sort = $this->db->select('sort')
            ->from('forum_items')
            ->where(['id' => 2])
            ->execute()
            ->fetchCell();
        self::assertEquals(15, $sort);
    }

    /**
     * Tests that saveItem() only updates the fields that were set on the model.
     */
    public function testSaveItemUpdateOnlyGivenFields()
    {
        $model = new ForumItemModel();
        $model->setId(2)
            ->setTitle('Nur Titel geaendert')
            ->setDesc('Nur Beschreibung geaendert');

        $itemId = $this->out->saveItem($model);

        self::assertSame(2, $itemId);

        $forum = $this->out->getForumById(2);
        self::assertNotNull($forum);
        self::assertSame('Nur Titel geaendert', $forum->getTitle());
        self::assertSame('Nur Beschreibung geaendert', $forum->getDesc());
    }

    /**
     * Tests that saveItem() replaces the stored prefixes and access rights of a forum.
     */
    public function testSaveItemReplacesPrefixesAndAccesses()
    {
        // Initial state: prefix 1 and read access for group 1.
        $this->db->insert('forum_prefixes_items')
            ->values(['item_id' => 2, 'prefix_id' => 1])
            ->execute();
        $this->db->insert('forum_accesses')
            ->values(['item_id' => 2, 'group_id' => 1, 'access_type' => 0])
            ->execute();

        $model = new ForumItemModel();
        $model->setId(2)
            ->setSort(10)
            ->setParentId(1)
            ->setType(1)
            ->setTitle('Mein Forum')
            ->setDesc('Mein erstes Forum')
            ->setPrefixes('2')
            ->setReadAccess('3')
            ->setReplyAccess('')
            ->setCreateAccess('');

        $itemId = $this->out->saveItem($model);

        self::assertSame(2, $itemId);

        $forum = $this->out->getForumById(2);
        self::assertSame('2', $forum->getPrefixes());
        self::assertSame('3', $forum->getReadAccess());
        self::assertSame('', $forum->getReplyAccess());
        self::assertSame('', $forum->getCreateAccess());
    }

    /**
     * Tests that deleteItem() removes an item.
     */
    public function testDeleteItem()
    {
        $this->out->deleteItem(2);

        self::assertNull($this->out->getForumById(2));

        $items = $this->out->getForumItems();
        self::assertCount(1, $items);
        self::assertEquals(1, $items[0]->getId());
    }

    /**
     * Tests that deleteItem() on a non-existent id does not throw.
     */
    public function testDeleteItemNotFound()
    {
        $this->out->deleteItem(9999);

        self::assertCount(2, $this->out->getForumItems());
    }

    /**
     * Tests that deleteItems() removes multiple items.
     */
    public function testDeleteItems()
    {
        $this->out->deleteItems([1, 2]);

        self::assertNull($this->out->getForumItems());
        self::assertNull($this->out->getForumItemsIds());
    }

    /**
     * Tests that deleteItems() with an empty list does nothing.
     */
    public function testDeleteItemsEmpty()
    {
        $this->out->deleteItems([]);

        self::assertCount(2, $this->out->getForumItems());
    }

    /**
     * Tests that deleteItem() cascades to the topics of the forum.
     */
    public function testDeleteItemCascadesToTopics()
    {
        $this->out->deleteItem(2);

        self::assertSame(0, $this->out->getCountTopicsById(2));
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
