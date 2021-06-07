<?php
/**
 * @copyright Ilch 2
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
        if ($pagination) {
            $sqlCountOfRows = 'SELECT COUNT(*)
                FROM `[prefix]_forum_topics` AS topics
                LEFT JOIN `[prefix]_forum_posts` AS posts ON topics.id = posts.topic_id
                WHERE topics.forum_id = '.(int)$id.'
                GROUP by topics.type, topics.id, topics.topic_id, topics.topic_prefix, topics.topic_title, topics.visits, topics.creator_id, topics.date_created, topics.forum_id, topics.status';

            $sqlCountOfRows = 'SELECT COUNT(*) FROM ('.$sqlCountOfRows.') AS countOfRows';
            $pagination->setRows($this->db()->querycell($sqlCountOfRows));
        }

        $sql = 'SELECT SQL_CALC_FOUND_ROWS *, topics.id, topics.visits, MAX(posts.date_created) AS latest_post
                FROM `[prefix]_forum_topics` AS topics
                LEFT JOIN `[prefix]_forum_posts` AS posts ON topics.id = posts.topic_id
                WHERE topics.forum_id = '.(int)$id.'
                GROUP by topics.type, topics.id, topics.topic_id, topics.topic_prefix, topics.topic_title, topics.visits, topics.creator_id, topics.date_created, topics.forum_id, topics.status
                ORDER by topics.type DESC, latest_post DESC';

        if (!empty($pagination)) {
            $sql .= ' LIMIT '.implode(',',$pagination->getLimit());
        }
        $topicArray = $this->db()->queryArray($sql);

        $entry = [];
        $user = null;
        $dummyUser = null;
        $userCache = [];

        foreach ($topicArray as $entries) {
            $entryModel = new TopicModel();
            $userMapper = new UserMapper();
            $entryModel->setId($entries['id']);
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

    /**
     * Get a list of topic ids of topics in a forum.
     *
     * @param integer $id
     * @return array array of topic ids
     */
    public function getTopicsListByForumId($id)
    {
        $result = $this->db()->select('id')
            ->from('forum_topics')
            ->where(['forum_id' => $id])
            ->execute()
            ->fetchArray();

        if (empty($result)) {
            return [];
        }

        return $result;
    }

    /**
     * Get the topics.
     *
     * @param null|\Ilch\Pagination $pagination
     * @param null|array $limit
     * @return array
     * @throws \Ilch\Database\Exception
     */
    public function getTopics($pagination = NULL, $limit = NULL)
    {
        $sql = $this->db()->select('*')
            ->from(['forum_topics'])
            ->group(['type', 'id', 'topic_id', 'topic_prefix', 'topic_title', 'visits', 'creator_id', 'date_created', 'forum_id', 'status'])
            ->order(['type' => 'DESC', 'id' => 'DESC']);

        if ($pagination !== null) {
            $sql->limit($pagination->getLimit())
                ->useFoundRows();
            $result = $sql->execute();
            $pagination->setRows($result->getFoundRows());
        } elseif ($limit != null) {
            $sql = $sql->limit((int)$limit);
            $result = $sql->execute();
        } else {
            $result = $sql->execute();
        }

        $topicsArray = $result->fetchRows();


        $entry = [];
        $user = null;
        $dummyUser = null;
        $userCache = [];

        foreach ($topicsArray as $entries) {
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

    /**
     * Get topic by id.
     *
     * @param integer $id
     * @return TopicModel
     */
    public function getTopicById($id)
    {
        $topic = $this->db()->select('*')
            ->from('forum_topics')
            ->where(['id' => (int)$id])
            ->execute()
            ->fetchAssoc();

        if (empty($topic)) {
            return null;
        }

        $entryModel = new TopicModel();
        $userMapper = new UserMapper();
        $entryModel->setId($topic['id']);
        $entryModel->setTopicPrefix($topic['topic_prefix']);
        $entryModel->setTopicTitle($topic['topic_title']);
        $entryModel->setCreatorId($topic['creator_id']);
        $entryModel->setVisits($topic['visits']);
        $user = $userMapper->getUserById($topic['creator_id']);
        if ($user) {
            $entryModel->setAuthor($user);
        } else {
            $entryModel->setAuthor($userMapper->getDummyUser());
        }
        $entryModel->setDateCreated($topic['date_created']);
        $entryModel->setStatus($topic['status']);

        return $entryModel;
    }

    /**
     * Get last post by topic id.
     *
     * @param int $id topic id
     * @return PostModel|null
     * @throws \Ilch\Database\Exception
     */
    public function getLastPostByTopicId($id)
    {
        $lastPostRow = $this->db()->select()
            ->fields(['id', 'topic_id', 'date_created', 'user_id', 'read'])
            ->from('forum_posts')
            ->where(['topic_id' => $id])
            ->order(['date_created' => 'DESC'])
            ->limit(1)
            ->execute()
            ->fetchAssoc();

        if (empty($lastPostRow)) {
            return null;
        }

        $entryModel = new PostModel();
        $userMapper = new UserMapper();
        $entryModel->setId($lastPostRow['id']);
        $user = $userMapper->getUserById($lastPostRow['user_id']);
        if ($user) {
            $entryModel->setAutor($user);
        } else {
            $entryModel->setAutor($userMapper->getDummyUser());
        }
        $entryModel->setDateCreated($lastPostRow['date_created']);
        $entryModel->setTopicId($lastPostRow['topic_id']);
        $entryModel->setRead($lastPostRow['read']);

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

    /**
     * @return mixed
     */
    public function getLastInsertId()
    {
        return $this->last_insert_id;
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
            $sql .= ' LIMIT '.(int)$limit;
        }

        return $this->db()->queryArray($sql);
    }

    /**
     * Delete topic by id.
     *
     * @param int $id
     * @throws \Ilch\Database\Exception
     */
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
