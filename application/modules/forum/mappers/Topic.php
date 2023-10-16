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
     * @param int $id
     * @param Pagination|null $pagination
     * @return array|TopicModel[]
     * @throws Exception
     */
    public function getTopicsByForumId(int $id, Pagination $pagination = null): array
    {
        return $this->getTopicsByForumIds([$id], $pagination);
    }

    /**
     * @param array $ids
     * @param Pagination|null $pagination
     * @return array
     * @throws Exception
     */
    public function getTopicsByForumIds(array $ids, Pagination $pagination = null): array
    {
        $sql = $this->db()->select(['*', 'topics.id', 'topics.visits', 'latest_post' => 'MAX(posts.date_created)', 'countPosts' => 'COUNT(posts.id)'])
            ->from(['topics' => 'forum_topics'])
            ->join(['posts' => 'forum_posts'], 'topics.id = posts.topic_id', 'LEFT')
            ->where(['topics.forum_id' => $ids])
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

        $topicRows = $result->fetchRows();

        $userMapper = new UserMapper();
        $topics = [];
        $dummyUser = null;
        $userCache = [];

        foreach ($topicRows as $topicRow) {
            $topicModel = new TopicModel();
            $topicModel->setId($topicRow['id']);
            $topicModel->setVisits($topicRow['visits']);
            $topicModel->setForumId($topicRow['forum_id']);
            $topicModel->setType($topicRow['type']);
            $topicModel->setStatus($topicRow['status']);

            if (\array_key_exists($topicRow['creator_id'], $userCache)) {
                $topicModel->setAuthor($userCache[$topicRow['creator_id']]);
            } else {
                $user = $userMapper->getUserById($topicRow['creator_id']);
                if ($user) {
                    $userCache[$topicRow['creator_id']] = $user;
                    $topicModel->setAuthor($user);
                } else {
                    if (!$dummyUser) {
                        $dummyUser = $userMapper->getDummyUser();
                    }
                    $topicModel->setAuthor($dummyUser);
                }
            }

            $topicModel->setTopicPrefix($topicRow['topic_prefix']);
            $topicModel->setTopicTitle($topicRow['topic_title']);
            $topicModel->setDateCreated($topicRow['date_created']);
            $topicModel->setCountPosts($topicRow['countPosts']);
            $topics[$topicRow['id']] = $topicModel;
        }

        return $topics;
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

        $topicRows = $result->fetchRows();

        $userMapper = new UserMapper();
        $topics = [];
        $dummyUser = null;
        $userCache = [];

        foreach ($topicRows as $topicRow) {
            $topicModel = new TopicModel();
            $topicModel->setId($topicRow['id']);
            $topicModel->setForumId($topicRow['forum_id']);
            $topicModel->setVisits($topicRow['visits']);
            $topicModel->setType($topicRow['type']);
            $topicModel->setStatus($topicRow['status']);

            if (\array_key_exists($topicRow['creator_id'], $userCache)) {
                $topicModel->setAuthor($userCache[$topicRow['creator_id']]);
            } else {
                $user = $userMapper->getUserById($topicRow['creator_id']);
                if ($user) {
                    $userCache[$topicRow['creator_id']] = $user;
                    $topicModel->setAuthor($user);
                } else {
                    if (!$dummyUser) {
                        $dummyUser = $userMapper->getDummyUser();
                    }
                    $topicModel->setAuthor($dummyUser);
                }
            }

            $topicModel->setTopicPrefix($topicRow['topic_prefix']);
            $topicModel->setTopicTitle($topicRow['topic_title']);
            $topicModel->setDateCreated($topicRow['date_created']);
            $topicModel->setCountPosts($topicRow['countPosts']);
            $topics[] = $topicModel;
        }

        return $topics;
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

        $topicModel = new TopicModel();
        $userMapper = new UserMapper();
        $topicModel->setId($topic['id']);
        $topicModel->setTopicPrefix($topic['topic_prefix']);
        $topicModel->setTopicTitle($topic['topic_title']);
        $topicModel->setCreatorId($topic['creator_id']);
        $topicModel->setVisits($topic['visits']);
        $user = $userMapper->getUserById($topic['creator_id']);
        if ($user) {
            $topicModel->setAuthor($user);
        } else {
            $topicModel->setAuthor($userMapper->getDummyUser());
        }
        $topicModel->setDateCreated($topic['date_created']);
        $topicModel->setStatus($topic['status']);

        return $topicModel;
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

        $postModel = new PostModel();
        $userMapper = new UserMapper();
        $postModel->setId($lastPostRow['id']);
        $user = $userMapper->getUserById($lastPostRow['user_id']);

        if ($user) {
            $postModel->setAutor($user);
        } else {
            $postModel->setAutor($userMapper->getDummyUser());
        }

        $postModel->setDateCreated($lastPostRow['date_created']);
        $postModel->setTopicId($lastPostRow['topic_id']);

        if ($userId) {
            $postModel->setRead($lastPostRow['topic_read'] || $lastPostRow['forum_read']);
        }

        return $postModel;
    }

    /**
     *  Get last posts by topic ids and user id.
     *
     * @param array $ids
     * @param int|null $userId
     * @return PostModel[]|null
     * @throws Exception
     */
    public function getLastPostsByTopicIds(array $ids, int $userId = null): ?array
    {
        $select = $this->db()->select(['p.id', 'p.topic_id', 'date_created' => 'MAX(p.date_created)', 'p.user_id', 'p.forum_id'])
            ->from(['p' => 'forum_posts']);

        if ($userId) {
            $select->join(['tr' => 'forum_topics_read'], ['tr.user_id' => $userId, 'tr.topic_id = p.topic_id', 'tr.datetime >= p.date_created'], 'LEFT', ['topic_read' => 'tr.datetime'])
                ->join(['fr' => 'forum_read'], ['fr.user_id' => $userId, 'fr.forum_id = p.forum_id', 'fr.datetime >= p.date_created'], 'LEFT', ['forum_read' => 'fr.datetime']);
        }

        $lastPostsRows = $select->where(['p.topic_id' => $ids])
            ->order(['p.date_created' => 'DESC'])
            ->group(['p.topic_id'])
            ->execute()
            ->fetchRows();

        if (empty($lastPostsRows)) {
            return null;
        }

        $lastPosts = [];
        foreach ($lastPostsRows as $lastPostRow) {
            $postModel = new PostModel();
            $userMapper = new UserMapper();
            $postModel->setId($lastPostRow['id']);
            $user = $userMapper->getUserById($lastPostRow['user_id']);

            if ($user) {
                $postModel->setAutor($user);
            } else {
                $postModel->setAutor($userMapper->getDummyUser());
            }

            $postModel->setDateCreated($lastPostRow['date_created']);
            $postModel->setTopicId($lastPostRow['topic_id']);

            if ($userId) {
                $postModel->setRead($lastPostRow['topic_read'] || $lastPostRow['forum_read']);
            }
            $lastPosts[] = $postModel;
        }

        return $lastPosts;
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
