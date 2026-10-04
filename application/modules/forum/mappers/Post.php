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
use Modules\Forum\Models\ForumPost as PostModel;
use Modules\User\Mappers\User as UserMapper;

class Post extends Mapper
{
    /**
     * Get post by id.
     *
     * @param int $id
     * @param int|null $userId Used to determine if a user voted for this post.
     * @return PostModel|null
     * @throws Exception
     */
    public function getPostById(int $id, ?int $userId = null): ?PostModel
    {
        $postRow = $this->db()->select(['p.id', 'p.text', 'p.date_created', 'p.forum_id', 'p.user_id'])
            ->from(['p' => 'forum_posts'])
            ->where(['id' => $id])
            ->join(['v' => 'forum_votes'], 'p.id = v.post_id', 'LEFT', ['countOfVotes' => 'COUNT(v.user_id)'])
            ->join(['vu' => 'forum_votes'], ['p.id = vu.post_id', 'vu.user_id' => $userId], 'LEFT', ['userHasVoted' => 'vu.user_id'])
            ->group(['p.id'])
            ->execute()
            ->fetchAssoc();

        if (empty($postRow)) {
            return null;
        }

        $postModel = new PostModel();
        $userMapper = new UserMapper();
        $postModel->setId($postRow['id']);
        $postModel->setText($postRow['text']);
        $postModel->setDateCreated($postRow['date_created']);
        $postModel->setForumId($postRow['forum_id']);
        $user = $userMapper->getUserById($postRow['user_id']);
        if ($user) {
            $postModel->setAutor($user);
        } else {
            $postModel->setAutor($userMapper->getDummyUser());
        }
        $postModel->setAutorAllPost($this->getAllPostsByUserId($postRow['user_id']));
        $postModel->setCountOfVotes($postRow['countOfVotes']);
        $postModel->setUserHasVoted((bool)$postRow['userHasVoted']);

        return $postModel;
    }

    /**
     * Return count of posts by User id.
     *
     * @param int $userId
     * @return int
     */
    public function getAllPostsByUserId(int $userId): int
    {
        return (int) $this->db()->select(['COUNT(*)'])
            ->from('forum_posts')
            ->where(['user_id' => $userId])
            ->execute()
            ->fetchCell();
    }

    /**
     * Get all posts by topic id (posts of a topic)
     *
     * @param int $topicId
     * @param Pagination|null $pagination
     * @param int $descorder
     * @param int|null $userId Used to determine if a user voted for these posts.
     * @return array
     * @throws Exception
     */
    public function getPostsByTopicId(int $topicId, ?Pagination $pagination = null, int $descorder = 0, ?int $userId = null): array
    {
        $select = $this->db()->select(['p.id', 'p.topic_id', 'p.text', 'p.date_created', 'p.forum_id', 'p.user_id'])
            ->from(['p' => 'forum_posts'])
            ->where(['p.topic_id' => $topicId])
            ->join(['v' => 'forum_votes'], 'p.id = v.post_id', 'LEFT', ['countOfVotes' => 'COUNT(v.user_id)'])
            ->join(['vu' => 'forum_votes'], ['p.id = vu.post_id', 'vu.user_id' => $userId], 'LEFT', ['userHasVoted' => 'vu.user_id'])
            ->group(['p.id'])
            ->order(['p.date_created' => ($descorder ? 'DESC' : 'ASC')]);

        if ($pagination !== null) {
            $select->limit($pagination->getLimit())
                ->useFoundRows();
            $result = $select->execute();
            $pagination->setRows($result->getFoundRows());
        } else {
            $result = $select->execute();
        }

        $userMapper = new UserMapper();
        $postsArray = $result->fetchRows();
        $posts = [];
        $dummyUser = null;
        $cache = [];

        foreach ($postsArray as $post) {
            $postModel = new PostModel();
            $postModel->setId($post['id']);
            $postModel->setText($post['text']);
            $postModel->setDateCreated($post['date_created']);
            if (\array_key_exists($post['user_id'], $cache)) {
                $postModel->setAutor($cache[$post['user_id']]['user']);
            } else {
                $user = $userMapper->getUserById($post['user_id']);
                if ($user) {
                    $cache[$post['user_id']]['user'] = $user;
                    $postModel->setAutor($user);
                    $cache[$post['user_id']]['allPosts'] = $this->getAllPostsByUserId($post['user_id']);
                    $postModel->setAutorAllPost($cache[$post['user_id']]['allPosts']);
                } else {
                    if (!$dummyUser) {
                        $dummyUser = $userMapper->getDummyUser();
                    }
                    $postModel->setAutor($dummyUser);
                }
            }
            $postModel->setCountOfVotes($post['countOfVotes']);
            $postModel->setUserHasVoted((bool)$post['userHasVoted']);

            $posts[] = $postModel;
        }

        return $posts;
    }

    /**
     * Get date of last post created by user.
     *
     * @param int $userId
     * @return int|string
     */
    public function getDateOfLastPostByUserId(int $userId)
    {
        $select = $this->db()->select('date_created')
            ->from('forum_posts')
            ->where(['user_id' => $userId])
            ->order(['id' => 'DESC'])
            ->limit(1)
            ->execute()
            ->fetchCell();

        if (empty($select)) {
            return 0;
        }

        return $select;
    }

    /**
     * Save the given post.
     *
     * Existing posts (with an id) are updated (topic and text only). New
     * posts are inserted, the new id is set on the model, and the
     * denormalized last-post meta of the topic, the forum item and its
     * parent category are refreshed.
     *
     * @param PostModel $model
     * @return void
     */
    public function save(PostModel $model)
    {
        if ($model->getId()) {
            $this->db()->update('forum_posts')
                ->values([
                    'topic_id' => $model->getTopicId(),
                    'text' => $model->getText()
                ])
                ->where(['id' => $model->getId()])
                ->execute();
        } else {
            $postId = $this->db()->insert('forum_posts')
                ->values([
                    'text' => $model->getText(),
                    'topic_id' => $model->getTopicId(),
                    'user_id' => $model->getUserId(),
                    'forum_id' => $model->getForumId(),
                    'date_created' => $model->getDateCreated()
                ])
                ->execute();

            $model->setId($postId);

            $this->db()->update('forum_topics')
                ->values(['last_post_date' => $model->getDateCreated(), 'last_post_id' => $postId])
                ->where(['id' => $model->getTopicId()])
                ->execute();

            // Forum + parent category in one consistent call.
            $this->refreshLastPostMetaForForum($model->getForumId());
        }
    }

    /**
     * Save post vote/like.
     *
     * @param int $id
     * @param int $userId
     */
    public function saveVotes(int $id, int $userId)
    {
        $this->db()->insert('forum_votes')
            ->values(['post_id' => $id, 'user_id' => $userId])
            ->execute();
    }

    /**
     * @param PostModel $model
     * @return void
     */
    public function saveForEdit(PostModel $model)
    {
        if ($model->getId()) {
            $this->db()->update('forum_posts')
                ->values([
                    'topic_id' => $model->getTopicId(),
                    'forum_id' => $model->getForumId()
                ])
                ->where(['topic_id' => $model->getTopicId()])
                ->execute();
        }
    }

    /**
     * Delete post by id.
     *
     * @param int $id
     * @return Result|int
     */
    /**
     * Delete post by id.
     *
     * @param int $id
     * @return Result|int
     */
    public function deleteById(int $id)
    {
        // Must be read BEFORE the delete — afterwards the row no longer exists.
        $postRow = $this->db()->select(['topic_id', 'forum_id'])
            ->from('forum_posts')
            ->where(['id' => $id])
            ->execute()
            ->fetchAssoc();

        $result = $this->db()->delete('forum_posts')
            ->where(['id' => $id])
            ->execute();

        if (!empty($postRow)) {
            // Re-derive the topic's last post from the remaining posts.
            $last = $this->db()->select(['id', 'date_created'])
                ->from('forum_posts')
                ->where(['topic_id' => $postRow['topic_id']])
                ->order(['date_created' => 'DESC', 'id' => 'DESC'])
                ->limit(1)
                ->execute()
                ->fetchAssoc(); // empty array => topic is now empty

            $this->db()->update('forum_topics')
                ->values([
                    'last_post_date' => $last['date_created'] ?? null,
                    'last_post_id'   => $last['id'] ?? null,
                ])
                ->where(['id' => $postRow['topic_id']])
                ->execute();

            // Forum + parent category from the topic rows.
            $this->refreshLastPostMetaForForum((int) $postRow['forum_id']);
        }

        return $result;
    }

    /**
     * Check if a post is the first one of a topic.
     *
     * @param int $topicId
     * @param int $postId
     * @return bool
     */
    public function isFirstPostOfTopic(int $topicId, int $postId): bool
    {
        $row = $this->db()->select('id')
            ->from('forum_posts')
            ->where(['topic_id' => $topicId])
            ->execute()
            ->fetchAssoc();

        return ($row['id'] == $postId);
    }

    /**
     * Re-derives forum_items.last_post_date/-id for the given forum and
     * its parent category from the (denormalized) topic rows.
     *
     * Updates are skipped when no known value is left, because the target
     * row already holds NULL in that case - and an UPDATE whose values are
     * all NULL is rejected by the query builder.
     *
     * @param int $forumId
     */
    public function refreshLastPostMetaForForum(int $forumId): void
    {
        // Latest topic of the forum. With ORDER BY ... DESC, MySQL sorts
        // NULL last, so topics with posts win over empty ones; same-second
        // ties are broken by the post id.
        $lastTopic = $this->db()->select(['last_post_date', 'last_post_id'])
            ->from('forum_topics')
            ->where(['forum_id' => $forumId])
            ->order(['last_post_date' => 'DESC', 'last_post_id' => 'DESC'])
            ->limit(1)
            ->execute()
            ->fetchAssoc();

        $values = [];
        if (is_array($lastTopic) && !empty($lastTopic['last_post_date'])) {
            $values['last_post_date'] = $lastTopic['last_post_date'];
        }
        if (is_array($lastTopic) && !empty($lastTopic['last_post_id'])) {
            $values['last_post_id'] = (int)$lastTopic['last_post_id'];
        }
        if ($values !== []) {
            $this->db()->update('forum_items')
                ->values($values)
                ->where(['id' => $forumId])
                ->execute();
        }

        // Parent category: latest among all of its child forums.
        $parentId = $this->db()->select('parent_id')
            ->from('forum_items')
            ->where(['id' => $forumId])
            ->execute()
            ->fetchCell();

        if (!empty($parentId)) {
            $lastForum = $this->db()->select(['last_post_date', 'last_post_id'])
                ->from('forum_items')
                ->where(['parent_id' => $parentId, 'type' => 1])
                ->order(['last_post_date' => 'DESC', 'last_post_id' => 'DESC'])
                ->limit(1)
                ->execute()
                ->fetchAssoc();

            $values = [];
            if (is_array($lastForum) && !empty($lastForum['last_post_date'])) {
                $values['last_post_date'] = $lastForum['last_post_date'];
            }
            if (is_array($lastForum) && !empty($lastForum['last_post_id'])) {
                $values['last_post_id'] = (int)$lastForum['last_post_id'];
            }
            if ($values !== []) {
                $this->db()->update('forum_items')
                    ->values($values)
                    ->where(['id' => (int)$parentId])
                    ->execute();
            }
        }
    }
}
