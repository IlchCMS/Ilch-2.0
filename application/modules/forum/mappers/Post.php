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
    public function getPostById(int $id, int $userId = null): ?PostModel
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
     * @param int $userId
     * @return int|string
     */
    public function getAllPostsByUserId(int $userId)
    {
        $this->db()->select('id')
            ->from('forum_posts')
            ->where(['user_id' => $userId])
            ->execute()
            ->fetchRows();
        $topics = $this->db()->getAffectedRows();

        if (empty($topics)) {
            return '0';
        }

        return $topics;
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
    public function getPostsByTopicId(int $topicId, Pagination $pagination = null, int $descorder = 0, int $userId = null): array
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
            $this->db()->insert('forum_posts')
                ->values([
                    'text' => $model->getText(),
                    'topic_id' => $model->getTopicId(),
                    'user_id' => $model->getUserId(),
                    'forum_id' => $model->getForumId(),
                    'date_created' => $model->getDateCreated()
                ])
                ->execute();
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
    public function deleteById(int $id)
    {
        return $this->db()->delete('forum_posts')
            ->where(['id' => $id])
            ->execute();
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
}
