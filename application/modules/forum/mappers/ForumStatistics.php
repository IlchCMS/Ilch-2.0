<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Forum\Mappers;

use Ilch\Database\Exception;
use Ilch\Mapper;
use Modules\Forum\Models\ForumStatistics as ForumStatisticsModel;

class ForumStatistics extends Mapper
{
    /**
     * @return ForumStatisticsModel
     * @throws Exception
     */
    public function getForumStatistics(): ForumStatisticsModel
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
        $statisticsRow = $this->db()->queryRow($sql);

        $entryModel = new ForumStatisticsModel();
        $entryModel->setCountPosts($statisticsRow['pid']);
        $entryModel->setCountTopics($statisticsRow['tid']);
        $entryModel->setCountUsers($statisticsRow['uid']);

        return $entryModel;
    }
}
