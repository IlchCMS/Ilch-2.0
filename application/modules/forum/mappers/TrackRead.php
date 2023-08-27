<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Forum\Mappers;

use Ilch\Database\Mysql\Result;
use Ilch\Date;
use Ilch\Mapper;

class TrackRead extends Mapper
{
    /**
     * Mark forums as read.
     *
     * @param int $userId
     * @param array $forumIds An array of forum ids.
     * @return void
     */
    public function markForumsAsRead(int $userId, array $forumIds)
    {
        $dateTime = new Date();

        $preparedValues = [];
        foreach ($forumIds as $forumId) {
            $preparedValues[] = [$userId, $forumId, $dateTime];
        }

        if (count($preparedValues)) {
            $this->db()->delete('forum_read')
                ->where(['user_id' => $userId, 'forum_id' => $forumIds])
                ->execute();

            // Multiple rows of read topics can be replaced by one row of read forums.
            // So delete the read topics for the read forum.
            $this->db()->delete('forum_topics_read')
                ->where(['user_id' => $userId, 'forum_id' => $forumIds])
                ->execute();

            // Add read forums in chunks of 25 to the table. This prevents reaching the limit of 1000 rows per insert.
            // Unlikely that someone has close to 1000 forums tho.
            $chunks = array_chunk($preparedValues, 25);
            foreach ($chunks as $chunk) {
                $this->db()->insert('forum_read')
                    ->columns(['user_id', 'forum_id', 'datetime'])
                    ->values($chunk)
                    ->execute();
            }
        }
    }

    /**
     * Mark forum as read.
     *
     * @param int $userId
     * @param int $forumId
     * @return Result|int
     */
    public function markForumAsRead(int $userId, int $forumId)
    {
        $dateTime = new Date();

        $this->db()->delete('forum_topics_read')
            ->where(['user_id' => $userId, 'forum_id' => $forumId])
            ->execute();

        $affectedRows = $this->db()->update('forum_read')
            ->values(['user_id' => $userId, 'forum_id' => $forumId, 'datetime' => $dateTime])
            ->where(['user_id' => $userId, 'forum_id' => $forumId])
            ->execute();

        if (!$affectedRows) {
            return $this->db()->insert('forum_read')
                ->values(['user_id' => $userId, 'forum_id' => $forumId, 'datetime' => $dateTime])
                ->execute();
        }

        return $affectedRows;
    }

    /**
     * Mark topics of a forum as read.
     *
     * @param int $userId
     * @param array $topicIds
     * @param int $forumId
     * @return void
     */
    public function markTopicsAsRead(int $userId, array $topicIds, int $forumId)
    {
        $dateTime = new Date();

        $preparedValues = [];
        foreach ($topicIds as $topicId) {
            $preparedValues[] = [$userId, $forumId, $topicId, $dateTime];
        }

        if (count($preparedValues)) {
            $this->db()->delete('forum_topics_read')
                ->where(['user_id' => $userId, 'topic_id' => $topicIds])
                ->execute();

            // Add read topics in chunks of 25 to the table. This prevents reaching the limit of 1000 rows per insert.
            $chunks = array_chunk($preparedValues, 25);
            foreach ($chunks as $chunk) {
                $this->db()->insert('forum_topics_read')
                    ->columns(['user_id', 'forum_id', 'topic_id', 'datetime'])
                    ->values($chunk)
                    ->execute();
            }
        }
    }

    /**
     * Mark single topic as read.
     *
     * @param int $userId
     * @param int $topicId
     * @param int $forumId
     * @return Result|int
     */
    public function markTopicAsRead(int $userId, int $topicId, int $forumId)
    {
        $dateTime = new Date();

        $affectedRows = $this->db()->update('forum_topics_read')
            ->values(['user_id' => $userId, 'forum_id' => $forumId, 'topic_id' => $topicId, 'datetime' => $dateTime])
            ->where(['user_id' => $userId, 'topic_id' => $topicId])
            ->execute();

        if (!$affectedRows) {
            return $this->db()->insert('forum_topics_read')
                ->values(['user_id' => $userId, 'forum_id' => $forumId, 'topic_id' => $topicId, 'datetime' => $dateTime])
                ->execute();
        }

        return $affectedRows;
    }
}
