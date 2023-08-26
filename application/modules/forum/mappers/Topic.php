<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Forum\Mappers;

use Ilch\Database\Exception;
use Ilch\Database\Mysql\Result;
use Ilch\Mapper;
use Ilch\Pagination;
use Modules\Forum\Models\ForumTopic as TopicModel;
use Modules\User\Mappers\User as UserMapper;
use Modules\Forum\Models\ForumPost as PostModel;

class Topic extends Mapper
{
    /**
     * @param $id
     * @param $pagination
     * @return array|TopicModel[]
     * @throws Exception
     */
    public function getTopicsByForumId($id, $pagination = null): array
    {
        $sql = $this->db()->select(['*', 'topics.id', 'topics.visits', 'latest_post' => 'MAX(posts.date_created)', 'countPosts' => 'COUNT(posts.id)'])
            ->from(['topics' => 'forum_topics'])
            ->join(['posts' => 'forum_posts'], 'topics.id = posts.topic_id', 'LEFT')
            ->where(['topics.forum_id' => (int)$id])
            ->group(['topics.type', 'topics.id', 'topics.topic_prefix', 'topics.topic_title', 'topics.visits', 'topics.creator_id', 'topics.date_created', 'topics.forum_id', 'topics.status'])
            ->order(['topics.type' => 'DESC', 'latest_post' => 'DESC']);

        if ($pagination !== null) {
            $sql->limit($pagination->getLimit())
                ->useFoundRows();
            $result = $sql->execute();
            $pagination->setRows($result->getFoundRows());
        } else {
            $result = $sql->execute();
        }

        $topicArray = $result->fetchRows();

        $entry = [];
        $dummyUser = null;
        $userCache = [];

        foreach ($topicArray as $entries) {
            $entryModel = new TopicModel();
            $userMapper = new UserMapper();
            $entryModel->setId($entries['id']);
            $entryModel->setVisits($entries['visits']);
            $entryModel->setType($entries['type']);
            $entryModel->setStatus($entries['status']);

            if (\array_key_exists($entries['creator_id'], $userCache)) {
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
            $entryModel->setCountPosts($entries['countPosts']);
            $entry[] = $entryModel;
        }

        return $entry;
    }

    /**
     * Get a list of topic ids of topics in a forum.
     *
     * @param int $id
     * @return array array of topic ids
     */
    public function getTopicsListByForumId(int $id): array
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
     * @param Pagination|null $pagination
     * @param array|null $limit
     * @return array|TopicModel[]
     * @throws Exception
     */
    public function getTopics(Pagination $pagination = null, array $limit = null): array
    {
        $sql = $this->db()->select(['topics.type', 'topics.id', 'topics.topic_prefix', 'topics.topic_title', 'topics.visits', 'topics.creator_id', 'topics.date_created', 'topics.forum_id', 'topics.status'])
            ->from(['topics' => 'forum_topics'])
            ->join(['posts' => 'forum_posts'], 'topics.id = posts.topic_id', 'LEFT', ['countPosts' => 'COUNT(posts.id)'])
            ->group(['topics.type', 'topics.id', 'topics.topic_prefix', 'topics.topic_title', 'topics.visits', 'topics.creator_id', 'topics.date_created', 'topics.forum_id', 'topics.status'])
            ->order(['topics.type' => 'DESC', 'topics.id' => 'DESC']);

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
        $dummyUser = null;
        $userCache = [];

        foreach ($topicsArray as $entries) {
            $entryModel = new TopicModel();
            $userMapper = new UserMapper();
            $entryModel->setId($entries['id']);
            $entryModel->setForumId($entries['forum_id']);
            $entryModel->setVisits($entries['visits']);
            $entryModel->setType($entries['type']);
            $entryModel->setStatus($entries['status']);

            if (\array_key_exists($entries['creator_id'], $userCache)) {
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
            $entryModel->setCountPosts($entries['countPosts']);
            $entry[] = $entryModel;
        }

        return $entry;
    }

    /**
     * Get topic by id.
     *
     * @param int $id
     * @return TopicModel|null
     */
    public function getTopicById(int $id): ?TopicModel
    {
        $topic = $this->db()->select('*')
            ->from('forum_topics')
            ->where(['id' => $id])
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
     * Get last post by topic id and user id.
     *
     * @param int $id topic id
     * @param int|null $userId user id
     * @return PostModel|null
     * @throws Exception
     */
    public function getLastPostByTopicId(int $id, int $userId = null): ?PostModel
    {
        $select = $this->db()->select(['p.id', 'p.topic_id', 'p.date_created', 'p.user_id', 'p.forum_id'])
            ->from(['p' => 'forum_posts']);

        if ($userId) {
            $select->join(['tr' => 'forum_topics_read'], ['tr.user_id' => $userId, 'tr.topic_id = p.topic_id', 'tr.datetime >= p.date_created'], 'LEFT', ['topic_read' => 'tr.datetime'])
                ->join(['fr' => 'forum_read'], ['fr.user_id' => $userId, 'fr.forum_id = p.forum_id', 'fr.datetime >= p.date_created'], 'LEFT', ['forum_read' => 'fr.datetime']);
        }

        $lastPostRow = $select->where(['p.topic_id' => $id])
            ->order(['p.date_created' => 'DESC'])
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

        if ($userId) {
            $entryModel->setRead($lastPostRow['topic_read'] || $lastPostRow['forum_read']);
        }

        return $entryModel;
    }

    /**
     * Inserts or updates a topic.
     *
     * @param TopicModel $model
     * @return Result|int
     */
    public function save(TopicModel $model)
    {
        if ($model->getId()) {
            return $this->db()->update('forum_topics')
                ->values(['forum_id' => $model->getForumId()])
                ->where(['id' => $model->getId()])
                ->execute();
        } else {
            return $this->db()->insert('forum_topics')
                ->values([
                    'topic_prefix' => $model->getTopicPrefix(),
                    'topic_title' => $model->getTopicTitle(),
                    'forum_id' => $model->getForumId(),
                    'creator_id' => $model->getCreatorId(),
                    'type' => $model->getType(),
                    'date_created' => $model->getDateCreated()
                ])
                ->execute();
        }
    }

    /**
     * Updates topic status with given id.
     *
     * @param int $id
     */
    public function updateStatus(int $id)
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
     * @param int $id
     */
    public function updateType(int $id)
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
    public function update($id, $column, $value)
    {
        $this->db()->update('forum_topics')
            ->values([$column => $value])
            ->where(['id' => $id])
            ->execute();
    }

    /**
     * Get x topics with latest activity where x is specified by the limit.
     *
     * @param int|null $limit
     * @return array[]
     */
    public function getLastActiveTopics(int $limit = null): array
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
            $sql .= ' LIMIT '. $limit;
        }

        return $this->db()->queryArray($sql);
    }

    /**
     * Delete topic by id.
     *
     * @param int $id
     */
    public function deleteById(int $id)
    {
        // Posts get deleted by FKC.
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
