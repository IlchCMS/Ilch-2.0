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
use Modules\Forum\Mappers\TopicSubscription as TopicSubscriptionMapper;
use Modules\Forum\Models\TopicSubscription as TopicSubscriptionModel;

class TopicSubscriptionTest extends DatabaseTestCase
{
    /**
     * @var TopicSubscriptionMapper
     */
    protected TopicSubscription $out;
    protected PhpunitDataset $phpunitDataset;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database.yml');
        $this->out = new TopicSubscriptionMapper();
    }

    /**
     * Tests that getSubscriptionsForTopic() returns all subscriptions of a topic.
     */
    public function testGetSubscriptionsForTopic()
    {
        $subscriptions = $this->out->getSubscriptionsForTopic(1);

        self::assertCount(2, $subscriptions);
        self::assertInstanceOf(TopicSubscriptionModel::class, $subscriptions[0]);

        $userIds = [];
        foreach ($subscriptions as $subscription) {
            $userIds[] = $subscription->getUserId();
            self::assertEquals(1, $subscription->getTopicId());
        }
        sort($userIds);
        self::assertEquals([1, 2], $userIds);
    }

    /**
     * Tests that getSubscriptionsForTopic() returns the joined user fields.
     */
    public function testGetSubscriptionsForTopicFields()
    {
        $subscriptions = $this->out->getSubscriptionsForTopic(1);

        $usernames = [];
        $emails = [];
        foreach ($subscriptions as $subscription) {
            $usernames[] = $subscription->getUsername();
            $emails[] = $subscription->getEmailAddress();
        }
        sort($usernames);
        sort($emails);

        self::assertEquals(['Alice', 'Bob'], $usernames);
        self::assertEquals(['alice@example.com', 'bob@example.com'], $emails);
    }

    /**
     * Tests that getSubscriptionsForTopic() returns an empty array when no subscriptions exist.
     */
    public function testGetSubscriptionsForTopicEmpty()
    {
        $subscriptions = $this->out->getSubscriptionsForTopic(9999);

        self::assertIsArray($subscriptions);
        self::assertCount(0, $subscriptions);
    }

    /**
     * Tests that addSubscription() adds a subscription.
     */
    public function testAddSubscription()
    {
        $this->out->addSubscription(2, 2);

        self::assertTrue($this->out->isSubscribedToTopic(2, 2));
        self::assertCount(2, $this->out->getSubscriptionsForTopic(2));
    }

    /**
     * Tests that updateLastNotification() updates the last notification date.
     */
    public function testUpdateLastNotification()
    {
        $this->out->updateLastNotification(1, 1);

        $lastNotification = $this->db->select('last_notification')
            ->from('forum_topicsubscription')
            ->where(['topic_id' => 1, 'user_id' => 1])
            ->execute()
            ->fetchCell();

        self::assertNotSame('2024-01-15 12:00:00', $lastNotification, 'The last notification date was not updated.');
    }

    /**
     * Tests that isSubscribedToTopic() returns true for existing subscriptions.
     */
    public function testIsSubscribedToTopicTrue()
    {
        self::assertTrue($this->out->isSubscribedToTopic(1, 1));
        self::assertTrue($this->out->isSubscribedToTopic(1, 2));
    }

    /**
     * Tests that isSubscribedToTopic() returns false for non-existing subscriptions.
     */
    public function testIsSubscribedToTopicFalse()
    {
        self::assertFalse($this->out->isSubscribedToTopic(2, 2));
        self::assertFalse($this->out->isSubscribedToTopic(9999, 1));
    }

    /**
     * Tests that deleteSubscription() removes a subscription.
     */
    public function testDeleteSubscription()
    {
        $this->out->deleteSubscription(1, 1);

        self::assertFalse($this->out->isSubscribedToTopic(1, 1));

        // The other subscription of topic 1 should still be present
        self::assertTrue($this->out->isSubscribedToTopic(1, 2));
        self::assertCount(1, $this->out->getSubscriptionsForTopic(1));
    }

    /**
     * Tests that deleteSubscription() on a non-existing subscription does not throw.
     */
    public function testDeleteSubscriptionNotFound()
    {
        $this->out->deleteSubscription(9999, 9999);

        self::assertCount(2, $this->out->getSubscriptionsForTopic(1));
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
