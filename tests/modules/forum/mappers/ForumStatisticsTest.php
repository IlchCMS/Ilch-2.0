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
use Modules\Forum\Mappers\ForumStatistics as ForumStatisticsMapper;
use Modules\Forum\Models\ForumStatistics as ForumStatisticsModel;

class ForumStatisticsTest extends DatabaseTestCase
{
    /**
     * @var ForumStatisticsMapper
     */
    protected ForumStatistics $out;
    protected PhpunitDataset $phpunitDataset;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database.yml');
        $this->out = new ForumStatisticsMapper();
    }

    /**
     * Tests that getForumStatistics() returns the current counts.
     */
    public function testGetForumStatistics()
    {
        $statistics = $this->out->getForumStatistics();

        self::assertInstanceOf(ForumStatisticsModel::class, $statistics);
        self::assertEquals(2, $statistics->getCountPosts());
        self::assertEquals(2, $statistics->getCountTopics());
        self::assertEquals(2, $statistics->getCountUsers());
    }

    /**
     * Tests that the post count reflects newly inserted posts.
     */
    public function testGetForumStatisticsCountsNewPosts()
    {
        $this->db->insert('forum_posts')
            ->values([
                'topic_id' => 1,
                'forum_id' => 2,
                'text' => 'A third post',
                'user_id' => 1,
                'date_created' => '2024-01-17 12:00:00'
            ])
            ->execute();

        $statistics = $this->out->getForumStatistics();

        self::assertEquals(3, $statistics->getCountPosts());
        self::assertEquals(2, $statistics->getCountTopics());
        self::assertEquals(2, $statistics->getCountUsers());
    }

    /**
     * Tests that the topic count reflects newly inserted topics.
     */
    public function testGetForumStatisticsCountsNewTopics()
    {
        $this->db->insert('forum_topics')
            ->values([
                'forum_id' => 2,
                'topic_title' => 'Third topic',
                'creator_id' => 1,
                'date_created' => '2024-01-17 12:00:00'
            ])
            ->execute();

        $statistics = $this->out->getForumStatistics();

        self::assertEquals(2, $statistics->getCountPosts());
        self::assertEquals(3, $statistics->getCountTopics());
        self::assertEquals(2, $statistics->getCountUsers());
    }

    /**
     * Tests that getForumStatistics() returns zero when the tables are empty.
     */
    public function testGetForumStatisticsEmpty()
    {
        foreach ([1, 2] as $id) {
            $this->db->delete('forum_posts', ['id' => $id])->execute();
            $this->db->delete('forum_topics', ['id' => $id])->execute();
            $this->db->delete('users', ['id' => $id])->execute();
        }

        $statistics = $this->out->getForumStatistics();

        self::assertEquals(0, $statistics->getCountPosts());
        self::assertEquals(0, $statistics->getCountTopics());
        self::assertEquals(0, $statistics->getCountUsers());
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
