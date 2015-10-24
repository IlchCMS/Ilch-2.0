<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Forum\Mappers;

use Modules\Forum\Models\ForumStatistics as ForumStatisticsModel;

class ForumStatistics extends \Ilch\Mapper
{
    public function getForumStatisticsTopics()
    {
        $sql = 'SELECT COUNT(id)
                FROM [prefix]_forum_topics';
        $topics = $this->db()->queryCell($sql);

        if (empty($topics)) {
            return '0';
        }

        return $topics;
    }

    public function getForumStatisticsPosts()
    {
        $sql = 'SELECT COUNT(id)
                FROM [prefix]_forum_posts';
        $topics = $this->db()->queryCell($sql);

        if (empty($topics)) {
            return '0';
        }

        return $topics;
    }

    public function getForumStatistics()
    {
        $sql = 'SELECT
                    (
                        SELECT COUNT(id)
                        FROM [prefix]_forum_posts
                    ) AS pid,
                    (
                        SELECT  COUNT(id)
                        FROM [prefix]_forum_topics
                    )AS tid,
                    (
                        SELECT  COUNT(id)
                        FROM [prefix]_users
                    )AS uid
                ';
        $fileRow = $this->db()->queryRow($sql);

        $entryModel = new ForumStatisticsModel();
        $entryModel->setCountPosts($fileRow['pid']);
        $entryModel->setCountTopics($fileRow['tid']);
        $entryModel->setCountUsers($fileRow['uid']);

        return $entryModel;
    }
}
