<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Forum\Mappers;

use Modules\Forum\Models\TopicSubscription as TopicSubscriptionModel;

class TopicSubscription extends \Ilch\Mapper
{

    /**
     * Get all subscriptions for a topic.
     * Call this when you need to know who should get a notification.
     *
     * @param int $topic_id
     * @return array
     */
    public function getSubscriptionsForTopic($topic_id)
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
    public function addSubscription($topic_id, $user_id)
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
    public function updateLastNotification($topic_id, $user_id)
    {
        $date = new \Ilch\Date();

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
    public function isSubscribedToTopic($topic_id, $user_id)
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
    public function deleteSubscription($topic_id, $user_id)
    {
        $this->db()->delete('forum_topicsubscription')
            ->where(['topic_id' => $topic_id, 'user_id' => $user_id])
            ->execute();
    }

    /**
     * Delete all subscriptions of a user.
     * Call this when the user gets deleted.
     *
     * @param int $user_id
     */
    public function deleteAllSubscriptionsOfUser($user_id)
    {
        $this->db()->delete('forum_topicsubscription')
            ->where(['user_id' => $user_id])
            ->execute();
    }

    /**
     * Delete all subscriptions for a topic.
     * Call this when the topic gets deleted.
     *
     * @param int $topic_id
     */
    public function deleteAllSubscriptionsForTopic($topic_id)
    {
        $this->db()->delete('forum_topicsubscription')
            ->where(['topic_id' => $topic_id])
            ->execute();
    }

    /**
     * Delete all subscriptions.
     * You might call this when the feature gets disabled by an administrator.
     *
     */
    public function deleteAllSubscriptions()
    {
        $this->db()->truncate('[prefix]_forum_topicsubscription');
    }
}
