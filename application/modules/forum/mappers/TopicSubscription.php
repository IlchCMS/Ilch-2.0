<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Forum\Mappers;

use Ilch\Date;
use Ilch\Mapper;
use Modules\Forum\Models\TopicSubscription as TopicSubscriptionModel;

class TopicSubscription extends Mapper
{

    /**
     * Get all subscriptions for a topic.
     * Call this when you need to know who should get a notification.
     *
     * @param int $topic_id
     * @return array|TopicSubscriptionModel[]
     */
    public function getSubscriptionsForTopic(int $topic_id): array
    {
        $subscriptionRows = $this->db()->select()
            ->fields(['f.id', 'f.topic_id', 'f.user_id', 'f.last_notification'])
            ->from(['f' => 'forum_topicsubscription'])
            ->where(['f.topic_id' => $topic_id])
            ->join(['u' => 'users'], 'u.id = f.user_id', 'RIGHT', ['u.name', 'u.email', 'u.date_last_activity'])
            ->execute()
            ->fetchRows();

        $subscriptions = [];

        if (empty($subscriptionRows)) {
            return [];
        }

        foreach ($subscriptionRows as $subscriptionRow) {
            $subscriptionModel = new TopicSubscriptionModel();
            $subscriptionModel->setId($subscriptionRow['id']);
            $subscriptionModel->setTopicId($subscriptionRow['topic_id']);
            $subscriptionModel->setUserId($subscriptionRow['user_id']);
            $subscriptionModel->setLastNotification($subscriptionRow['last_notification']);
            $subscriptionModel->setUserName($subscriptionRow['name']);
            $subscriptionModel->setEmailAddress($subscriptionRow['email']);
            $subscriptionModel->setLastActivity($subscriptionRow['date_last_activity']);
            $subscriptions[] = $subscriptionModel;
        }

        return $subscriptions;
    }

    /**
     * Add a subscription.
     *
     * @param int $topic_id
     * @param int $user_id
     */
    public function addSubscription(int $topic_id, int $user_id)
    {
        $this->db()->insert('forum_topicsubscription')
            ->values(['topic_id' => $topic_id, 'user_id' => $user_id])
            ->execute();
    }

    /**
     * Update date of last notification
     *
     * @param int $topic_id
     * @param int $user_id
     */
    public function updateLastNotification(int $topic_id, int $user_id)
    {
        $date = new Date();

        $this->db()->update('forum_topicsubscription')
            ->values(['last_notification' => $date->format('Y-m-d H:i:s', true)])
            ->where(['topic_id' => $topic_id, 'user_id' => $user_id])
            ->execute();
    }

    /**
     * Check if user is subscribed to a topic.
     *
     * @param int $topic_id
     * @param int $user_id
     * @return bool
     */
    public function isSubscribedToTopic(int $topic_id, int $user_id): bool
    {
        $subscriptionRow = $this->db()->select('*')
            ->from('forum_topicsubscription')
            ->where(['topic_id' => $topic_id, 'user_id' => $user_id])
            ->execute()
            ->fetchRow();

        if (empty($subscriptionRow)) {
            return false;
        }

        return true;
    }

    /**
     * Delete subscription for a topic.
     *
     * @param int $topic_id
     * @param int $user_id
     */
    public function deleteSubscription(int $topic_id, int $user_id)
    {
        $this->db()->delete('forum_topicsubscription')
            ->where(['topic_id' => $topic_id, 'user_id' => $user_id])
            ->execute();
    }
}
