<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Forum\Mappers;

use Modules\Forum\Models\ForumTopic as TopicModel;
use Modules\User\Mappers\User as UserMapper;
use Modules\Forum\Models\ForumPost as PostModel;
use Modules\Forum\Mappers\Post as PostMapper;

class Topic extends \Ilch\Mapper
{
    public function getTopicsByForumId($id, $pagination = NULL)
    {
        $sql = 'SELECT SQL_CALC_FOUND_ROWS *
                FROM `[prefix]_forum_topics`
                WHERE forum_id = '.$id.'
                GROUP by type, `id`, `topic_id`, `topic_prefix`, `topic_title`, `visits`, `creator_id`, `date_created`, `forum_id`, `status`
                ORDER by type DESC, id DESC';

        if (!empty($pagination)) {
            $sql .= ' LIMIT '.implode(',',$pagination->getLimit());
            $fileArray = $this->db()->queryArray($sql);
            $pagination->setRows($this->db()->querycell('SELECT FOUND_ROWS()'));
        } else {
            $fileArray = $this->db()->queryArray($sql);
        }
        
        $entry = [];
        $user = null;
        $dummyUser = null;
        $userCache = [];

        foreach ($fileArray as $entries) {
            $entryModel = new TopicModel();
            $userMapper = new UserMapper();
            $entryModel->setId($entries['id']);
            $entryModel->setTopicId($id);
            $entryModel->setVisits($entries['visits']);
            $entryModel->setType($entries['type']);
            $entryModel->setStatus($entries['status']);

            if (array_key_exists($entries['creator_id'], $userCache)) {
                $entryModel->setAuthor($userCache[$entries['creator_id']]);
            } else {
                $user = $userMapper->getUserById($entries['creator_id']);
                if ($user) {
                    $userCache[$entries['creator_id']] = $user;
                    $entryModel->setAuthor($user);
                } else {
                    if (!$dummyUser) {
                        $dummyUser = $userMapper->getDummyUser();
                    }
                    $entryModel->setAuthor($dummyUser);
                }
            }

            $entryModel->setTopicPrefix($entries['topic_prefix']);
            $entryModel->setTopicTitle($entries['topic_title']);
            $entryModel->setDateCreated($entries['date_created']);
            $entry[] = $entryModel;
        }

        return $entry;
    }

    public function getTopics($pagination = NULL, $limit = NULL)
    {
        $sql = 'SELECT SQL_CALC_FOUND_ROWS *
                FROM `[prefix]_forum_topics`
                GROUP by type, `id`, `topic_id`, `topic_prefix`, `topic_title`, `visits`, `creator_id`, `date_created`, `forum_id`, `status`
                ORDER by type DESC, id DESC';
        if ($pagination != null) {
            $sql .= ' LIMIT '.implode(',',$pagination->getLimit());
        } elseif ($limit != null) {
            $sql .= ' LIMIT '.$limit;  
        }

        $fileArray = $this->db()->queryArray($sql);

        if ($pagination != null) {
            $pagination->setRows($this->db()->querycell('SELECT FOUND_ROWS()'));
        }

        $entry = [];
        $user = null;
        $dummyUser = null;
        $userCache = [];

        foreach ($fileArray as $entries) {
            $entryModel = new TopicModel();
            $userMapper = new UserMapper();
            $entryModel->setId($entries['id']);
            $entryModel->setForumId($entries['forum_id']);
            $entryModel->setTopicId($entries['topic_id']);
            $entryModel->setVisits($entries['visits']);
            $entryModel->setType($entries['type']);
            $entryModel->setStatus($entries['status']);

            if (array_key_exists($entries['creator_id'], $userCache)) {
                $entryModel->setAuthor($userCache[$entries['creator_id']]);
            } else {
                $user = $userMapper->getUserById($entries['creator_id']);
                if ($user) {
                    $userCache[$entries['creator_id']] = $user;
                    $entryModel->setAuthor($user);
                } else {
                    if (!$dummyUser) {
                        $dummyUser = $userMapper->getDummyUser();
                    }
                    $entryModel->setAuthor($dummyUser);
                }
            }

            $entryModel->setTopicPrefix($entries['topic_prefix']);
            $entryModel->setTopicTitle($entries['topic_title']);
            $entryModel->setDateCreated($entries['date_created']);
            $entry[] = $entryModel;
        }

        return $entry;
    }

    public function getPostById($id)
    {
        $sql = 'SELECT *
                FROM `[prefix]_forum_topics`
                WHERE id = '.$id;
        $fileRow = $this->db()->queryRow($sql);

        $entryModel = new TopicModel();
        $userMapper = new UserMapper();
        $entryModel->setId($fileRow['id']);
        $entryModel->setTopicPrefix($fileRow['topic_prefix']);
        $entryModel->setTopicTitle($fileRow['topic_title']);
        $entryModel->setCreatorId($fileRow['creator_id']);
        $entryModel->setVisits($fileRow['visits']);
        $user = $userMapper->getUserById($fileRow['creator_id']);
        if ($user) {
            $entryModel->setAuthor($user);
        } else {
            $entryModel->setAuthor($userMapper->getDummyUser());
        }
        $entryModel->setDateCreated($fileRow['date_created']);
        $entryModel->setStatus($fileRow['status']);

        return $entryModel;
    }

    public function getLastPostByTopicId($id)
    {
        $sql = 'SELECT p.id, p.topic_id, p.date_created, p.user_id, p.read
                FROM [prefix]_forum_posts as p 
                WHERE p.topic_id = '.$id.'
                ORDER BY p.date_created DESC';
        $fileRow = $this->db()->queryRow($sql);

        if (empty($fileRow)) {
            return null;
        }

        $entryModel = new PostModel();
        $userMapper = new UserMapper();
        $entryModel->setId($fileRow['id']);
        $user = $userMapper->getUserById($fileRow['user_id']);
        if ($user) {
            $entryModel->setAutor($user);
        } else {
            $entryModel->setAutor($userMapper->getDummyUser());
        }
        $entryModel->setDateCreated($fileRow['date_created']);
        $entryModel->setTopicId($fileRow['topic_id']);
        $entryModel->setRead($fileRow['read']);

        return $entryModel;
    }

    /**
     * Inserts or updates File entry.
     *
     * @param TopicModel $model
     */
    public function save(TopicModel $model)
    {
        if ($model->getId()) {
            $this->db()->update('forum_topics')
                ->values(['topic_id' => $model->getTopicId(), 'forum_id' => $model->getForumId()])
                ->where(['id' => $model->getId()])
                ->execute();
        } else {
            $this->db()->insert('forum_topics')
                ->values([
                    'topic_prefix' => $model->getTopicPrefix(),
                    'topic_title' => $model->getTopicTitle(),
                    'topic_id' => $model->getTopicId(),
                    'forum_id' => $model->getForumId(),
                    'creator_id' => $model->getCreatorId(),
                    'type' => $model->getType(),
                    'date_created' => $model->getDateCreated()
                ])
                ->execute();
                $this->last_insert_id = $this->db()->getLastInsertId();
        }
    }

    /**
     * Updates topic status with given id.
     *
     * @param integer $id
     */
    public function updateStatus($id)
    {
        $status = (int) $this->db()->select('status')
                        ->from('forum_topics')
                        ->where(['id' => $id])
                        ->execute()
                        ->fetchCell();

        $this->db()->update('forum_topics')
            ->values(['status' => !$status])
            ->where(['id' => $id])
            ->execute();
    }

    /**
     * Updates topic type with given id.
     *
     * @param integer $id
     */
    public function updateType($id)
    {
        $type = (int) $this->db()->select('type')
            ->from('forum_topics')
            ->where(['id' => $id])
            ->execute()
            ->fetchCell();

        $this->db()->update('forum_topics')
            ->values(['type' => !$type])
            ->where(['id' => $id])
            ->execute();
    }

    /**
     * Update specific column of a topic
     *
     * @param $id
     * @param $column
     * @param $value
     */
    public function update($id, $column, $value) {
        $this->db()->update('forum_topics')
            ->values([$column => $value])
            ->where(['id' => $id])
            ->execute();
    }

    public function getLastInsertId()
    {
        return $this->last_insert_id;
    }

    public function getPostByTopicId($id, $pagination = NULL)
    {
        $sql = 'SELECT SQL_CALC_FOUND_ROWS *
                FROM `[prefix]_forum_topics`
                WHERE topic_id = '.$id.'
                LIMIT '.implode(',',$pagination->getLimit());

        $fileArray = $this->db()->queryArray($sql);
        $pagination->setRows($this->db()->querycell('SELECT FOUND_ROWS()'));

        $entry = [];

        foreach ($fileArray as $entries) {
            $entryModel = new TopicModel();
            $entryModel->setId($entries['id']);
            $entryModel->setTopicId($id);
            $entryModel->setTopicTitle($entries['topic_title']);
            $entry[] = $entryModel;
        }

        return $entry;
    }

    /**
     * Get x topics with latest activity where x is specified by the limit.
     *
     * @param null|integer $limit
     * @return array[]
     */
    public function getLastActiveTopics($limit = null)
    {
        $sql = 'SELECT * 
                FROM 
                    (   SELECT DISTINCT(`p`.`topic_id`),`t`.`topic_title` AS `topic_title`,`t`.`forum_id` AS `forum_id`,`p`.`date_created` 
                        FROM `[prefix]_forum_posts` AS `p` 
                        LEFT JOIN `[prefix]_forum_topics` AS `t` ON `p`.`topic_id` = `t`.`id` 
                        ORDER BY `p`.`date_created` DESC 
                    ) AS `innerfrom` 
                GROUP BY `innerfrom`.`topic_id` 
                ORDER BY `innerfrom`.`date_created` DESC';

        if ($limit !== null) {
            $sql .= ' LIMIT '.$limit;
        }

        return $this->db()->queryArray($sql);
    }

    public function deleteById($id)
    {
        $postMapper = new PostMapper();
        $posts = $postMapper->getPostsByTopicId($id);
        foreach ($posts as $post) {
            $postMapper->deleteById($post->getId());
        }
        $this->db()->delete('forum_topics')
        ->where(['id' => $id])
        ->execute();
    }

    /**
     * Updates visits.
     *
     * @param TopicModel $model
     */
    public function saveVisits(TopicModel $model)
    {
        if ($model->getVisits()) {
            $this->db()->update('forum_topics')
                    ->values(['visits' => $model->getVisits()])
                    ->where(['id' => $model->getId()])
                    ->execute();
        }
    }
}
