<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Forum\Mappers;

use Ilch\Database\Exception;
use Ilch\Mapper;
use Ilch\Pagination;
use Modules\Forum\Models\ForumTopic as TopicModel;
use Modules\User\Mappers\User as UserMapper;
use Modules\Forum\Models\ForumPost as PostModel;
use Modules\Forum\Models\Prefix as PrefixModel;

class Topic extends Mapper
{
    /**
     * @param int $id
     * @param Pagination|null $pagination
     * @return array|TopicModel[]
     * @throws Exception
     */
    public function getTopicsByForumId(int $id, ?Pagination $pagination = null): array
    {
        return $this->getTopicsByForumIds([$id], $pagination);
    }

    /**
     * @param array $ids
     * @param Pagination|null $pagination
     * @return array|TopicModel[]
     * @throws Exception
     */
    public function getTopicsByForumIds(array $ids, ?Pagination $pagination = null): array
    {
        if (empty($ids)) {
            return [];
        }

        $sql = $this->db()->select([
            'topics.id',
            'topics.forum_id',
            'topics.topic_prefix',
            'topics.topic_title',
            'topics.visits',
            'topics.creator_id',
            'topics.date_created',
            'topics.type',
            'topics.status',
            'latest_post' => 'MAX(posts.id)',
            'countPosts' => 'COUNT(posts.id)',
        ])
            ->from(['topics' => 'forum_topics'])
            ->join(['posts' => 'forum_posts'], 'topics.id = posts.topic_id', 'LEFT')
            ->join(['prefix' => 'forum_prefixes'], 'topics.topic_prefix = prefix.id', 'LEFT', ['prefix.prefix'])
            ->where(['topics.forum_id' => $ids])
            ->group(['topics.id', 'topics.forum_id', 'topics.topic_prefix', 'topics.topic_title', 'topics.visits', 'topics.creator_id', 'topics.date_created', 'topics.type', 'topics.status'])
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
        if (empty($topicRows)) {
            return [];
        }

        $userMapper = new UserMapper();
        $usersById = $userMapper->getUserByIds(array_column($topicRows, 'creator_id'));
        $dummyUser = null;

        $topics = [];
        foreach ($topicRows as $topicRow) {
            $topicModel = new TopicModel();
            $topicModel->setId($topicRow['id']);
            $topicModel->setVisits($topicRow['visits']);
            $topicModel->setForumId($topicRow['forum_id']);
            $topicModel->setType($topicRow['type']);
            $topicModel->setStatus($topicRow['status']);

            $author = $usersById[$topicRow['creator_id']] ?? null;
            if ($author === null) {
                $dummyUser = $dummyUser ?? $userMapper->getDummyUser();
                $author = $dummyUser;
            }
            $topicModel->setAuthor($author);

            $prefixModel = new PrefixModel();
            $prefixModel->setId($topicRow['topic_prefix']);
            $prefixModel->setPrefix($topicRow['prefix'] ?? '');
            $topicModel->setTopicPrefix($prefixModel);
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
    public function getTopics(?Pagination $pagination = null, ?array $limit = null): array
    {
        $sql = $this->db()->select(['topics.type', 'topics.id', 'topics.topic_prefix', 'topics.topic_title', 'topics.visits', 'topics.creator_id', 'topics.date_created', 'topics.forum_id', 'topics.status'])
            ->from(['topics' => 'forum_topics'])
            ->join(['posts' => 'forum_posts'], 'topics.id = posts.topic_id', 'LEFT', ['countPosts' => 'COUNT(posts.id)'])
            ->join(['prefix' => 'forum_prefixes'], 'topics.topic_prefix = prefix.id', 'LEFT', ['prefix.prefix'])
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

            $prefixModel = new PrefixModel();
            $prefixModel->setId($topicRow['topic_prefix']);
            $prefixModel->setPrefix($topicRow['prefix']);
            $topicModel->setTopicPrefix($prefixModel);
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
        $topic = $this->db()->select(['topics.id', 'topics.topic_prefix', 'topics.topic_title', 'topics.creator_id', 'topics.visits', 'topics.date_created', 'topics.status'])
            ->from(['topics' => 'forum_topics'])
            ->join(['prefix' => 'forum_prefixes'], 'topics.topic_prefix = prefix.id', 'LEFT', ['prefix.prefix'])
            ->where(['topics.id' => $id])
            ->execute()
            ->fetchAssoc();

        if (empty($topic)) {
            return null;
        }

        $topicModel = new TopicModel();
        $userMapper = new UserMapper();
        $topicModel->setId($topic['id']);
        $prefixModel = new PrefixModel();
        $prefixModel->setId($topic['topic_prefix']);
        $prefixModel->setPrefix($topic['prefix'] ?? '');
        $topicModel->setTopicPrefix($prefixModel);
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
    public function getLastPostByTopicId(int $id, ?int $userId = null): ?PostModel
    {
        $lastPost = $this->getLastPostsByTopicIds([$id], $userId);
        if (!empty($lastPost)) {
            return reset($lastPost);
        }

        return null;
    }

    /**
     * Get last posts by topic ids and user id.
     *
     * @param array $ids
     * @param int|null $userId
     * @return PostModel[]|null
     * @throws Exception
     */
    public function getLastPostsByTopicIds(array $ids, ?int $userId = null): ?array
    {
        if (empty($ids)) {
            return null;
        }
        $ids = array_values(array_unique(array_map('intval', $ids)));

        // 1. Id of the newest post per topic (loose index scan on topic_id).
        $lastPostIds = $this->db()->select(['topic_id', 'postId' => 'MAX(id)'])
            ->from('forum_posts')
            ->where(['topic_id' => $ids])
            ->group(['topic_id'])
            ->execute()
            ->fetchList('postId', 'topic_id'); // [topic_id => last post id]

        if (empty($lastPostIds)) {
            return null;
        }

        // 2. Fetch the actual last posts (consistent rows, newest first).
        $lastPostsRows = $this->db()->select(['id', 'topic_id', 'forum_id', 'user_id', 'date_created'])
            ->from('forum_posts')
            ->where(['id' => array_values($lastPostIds)])
            ->order(['date_created' => 'DESC'])
            ->execute()
            ->fetchRows();

        // 3. Read status: one aggregate lookup per table instead of two joins.
        $topicLastRead = [];
        $forumLastRead = [];
        if ($userId) {
            $topicLastRead = $this->db()->select(['topic_id', 'lastRead' => 'MAX(datetime)'])
                ->from('forum_topics_read')
                ->where(['user_id' => $userId, 'topic_id' => $ids])
                ->group(['topic_id'])
                ->execute()
                ->fetchList('lastRead', 'topic_id'); // [topic_id => last read]

            $forumIds = array_values(array_unique(array_map('intval', array_column($lastPostsRows, 'forum_id'))));
            $forumLastRead = $this->db()->select(['forum_id', 'lastRead' => 'MAX(datetime)'])
                ->from('forum_read')
                ->where(['user_id' => $userId, 'forum_id' => $forumIds])
                ->group(['forum_id'])
                ->execute()
                ->fetchList('lastRead', 'forum_id'); // [forum_id => last read]
        }

        // 4. Build models; all authors fetched in one batch.
        $userIds = array_values(array_unique(array_map('intval', array_column($lastPostsRows, 'user_id'))));
        $userMapper = new UserMapper();
        $usersById = $userMapper->getUserByIds($userIds);
        $dummyUser = null;

        $lastPosts = [];
        foreach ($lastPostsRows as $row) {
            $uid = (int) $row['user_id'];
            $author = $usersById[$uid] ?? null;
            if ($author === null) {
                $dummyUser = $dummyUser ?? $userMapper->getDummyUser();
                $author = $dummyUser;
            }

            $postModel = new PostModel();
            $postModel->setId($row['id']);
            $postModel->setDateCreated($row['date_created']);
            $postModel->setTopicId($row['topic_id']);
            $postModel->setAutor($author);

            if ($userId) {
                $topicRead = $topicLastRead[$row['topic_id']] ?? null;
                $forumRead = $forumLastRead[$row['forum_id']] ?? null;
                $postModel->setRead(
                    ($topicRead !== null && $topicRead >= $row['date_created'])
                    || ($forumRead !== null && $forumRead >= $row['date_created'])
                );
            }

            $lastPosts[] = $postModel;
        }

        return $lastPosts;
    }

    /**
     * Inserts or updates a topic.
     *
     * @param TopicModel $model
     * @return int
     */
    public function save(TopicModel $model): int
    {
        if ($model->getId()) {
            $this->db()->update('forum_topics')
                ->values(['forum_id' => $model->getForumId()])
                ->where(['id' => $model->getId()])
                ->execute();
            return $model->getId();
        } else {
            return $this->db()->insert('forum_topics')
                ->values([
                    'topic_prefix' => $model->getTopicPrefix()->getId(),
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
    public function getLastActiveTopics(?int $limit = null): array
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
            $sql .= ' LIMIT ' . $limit;
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
