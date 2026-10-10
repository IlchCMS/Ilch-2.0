<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Forum\Mappers;

use PHPUnit\Ilch\DatabaseTestCase;
use PHPUnit\Ilch\PhpunitDataset;
use Modules\Forum\Config\Config as ModuleConfig;
use Modules\User\Config\Config as UserConfig;
use Modules\Admin\Config\Config as AdminConfig;
use Modules\Forum\Mappers\TrackRead as TrackReadMapper;

class TrackReadTest extends DatabaseTestCase
{
    /**
     * @var TrackReadMapper
     */
    protected TrackRead $out;
    protected PhpunitDataset $phpunitDataset;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database.yml');
        $this->out = new TrackReadMapper();
    }

    /**
     * Tests that markForumAsRead() inserts a new row when none exists.
     */
    public function testMarkForumAsReadInserts()
    {
        $this->out->markForumAsRead(2, 2);

        $row = $this->db->select('*')
            ->from('forum_read')
            ->where(['user_id' => 2, 'forum_id' => 2])
            ->execute()
            ->fetchAssoc();

        self::assertNotEmpty($row);
        self::assertNotEmpty($row['datetime']);
    }

    /**
     * Tests that markForumAsRead() updates the existing row instead of inserting a duplicate.
     */
    public function testMarkForumAsReadUpdates()
    {
        $this->out->markForumAsRead(1, 2);

        $rows = $this->db->select('*')
            ->from('forum_read')
            ->where(['user_id' => 1, 'forum_id' => 2])
            ->execute()
            ->fetchRows();

        self::assertCount(1, $rows);
        self::assertNotSame('2024-01-15 09:00:00', $rows[0]['datetime'], 'The datetime of the read forum was not updated.');
    }

    /**
     * Tests that markForumAsRead() does not touch other users.
     */
    public function testMarkForumAsReadDoesNotAffectOtherUsers()
    {
        $this->out->markForumAsRead(1, 2);

        $row = $this->db->select('*')
            ->from('forum_read')
            ->where(['user_id' => 2, 'forum_id' => 2])
            ->execute()
            ->fetchRow();

        self::assertEmpty($row);
    }

    /**
     * Tests that markForumsAsRead() replaces the existing rows and removes the read topics of the forums.
     */
    public function testMarkForumsAsRead()
    {
        $this->out->markForumsAsRead(1, [2]);

        $rows = $this->db->select('*')
            ->from('forum_read')
            ->where(['user_id' => 1, 'forum_id' => 2])
            ->execute()
            ->fetchRows();
        self::assertCount(1, $rows);

        $topicRows = $this->db->select('*')
            ->from('forum_topics_read')
            ->where(['user_id' => 1, 'forum_id' => 2])
            ->execute()
            ->fetchRows();
        self::assertCount(0, $topicRows);
    }

    /**
     * Tests that markForumsAsRead() does nothing for an empty list of forum ids.
     */
    public function testMarkForumsAsReadEmpty()
    {
        $this->out->markForumsAsRead(2, []);

        $rows = $this->db->select('*')
            ->from('forum_read')
            ->where(['user_id' => 2])
            ->execute()
            ->fetchRows();
        self::assertCount(0, $rows);
    }

    /**
     * Tests that markTopicsAsRead() inserts one row per topic.
     */
    public function testMarkTopicsAsRead()
    {
        $this->out->markTopicsAsRead(1, [1, 2], 2);

        $rows = $this->db->select('*')
            ->from('forum_topics_read')
            ->where(['user_id' => 1])
            ->execute()
            ->fetchRows();

        self::assertCount(2, $rows);

        $topicIds = array_column($rows, 'topic_id');
        sort($topicIds);
        self::assertEquals([1, 2], $topicIds);
    }

    /**
     * Tests that markTopicsAsRead() does nothing for an empty list of topic ids.
     */
    public function testMarkTopicsAsReadEmpty()
    {
        $this->out->markTopicsAsRead(2, [], 2);

        $rows = $this->db->select('*')
            ->from('forum_topics_read')
            ->where(['user_id' => 2])
            ->execute()
            ->fetchRows();
        self::assertCount(0, $rows);
    }

    /**
     * Tests that markTopicAsRead() inserts a new row when none exists.
     */
    public function testMarkTopicAsReadInserts()
    {
        $this->out->markTopicAsRead(2, 1, 2);

        $rows = $this->db->select('*')
            ->from('forum_topics_read')
            ->where(['user_id' => 2, 'topic_id' => 1])
            ->execute()
            ->fetchRows();

        self::assertCount(1, $rows);
        self::assertEquals(2, $rows[0]['forum_id']);
    }

    /**
     * Tests that markTopicAsRead() updates the existing row instead of inserting a duplicate.
     */
    public function testMarkTopicAsReadUpdates()
    {
        $this->out->markTopicAsRead(1, 1, 2);

        $rows = $this->db->select('*')
            ->from('forum_topics_read')
            ->where(['user_id' => 1, 'topic_id' => 1])
            ->execute()
            ->fetchRows();

        self::assertCount(1, $rows);
        self::assertNotSame('2024-01-15 09:30:00', $rows[0]['datetime'], 'The datetime of the read topic was not updated.');
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
