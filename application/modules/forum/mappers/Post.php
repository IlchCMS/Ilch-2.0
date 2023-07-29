<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Forum\Mappers;

use Ilch\Database\Exception;
use Modules\Forum\Models\ForumPost as PostModel;
use Modules\User\Mappers\User as UserMapper;
use Modules\Forum\Mappers\Remember as RememberMapper;

class Post extends \Ilch\Mapper
{
    /**
     * Get post by id.
     *
     * @param int $id
     * @return PostModel|null
     * @throws Exception
     */
    public function getPostById(int $id): ?PostModel
    {
        $postRow = $this->db()->select('*')
            ->from('forum_posts')
            ->where(['id' => $id])
            ->execute()
            ->fetchAssoc();

        if (empty($postRow)) {
            return null;
        }

        $postModel = new PostModel();
        $userMapper = new UserMapper();
        $postModel->setId($postRow['id']);
        $postModel->setText($postRow['text']);
        $postModel->setVotes($postRow['votes']);
        $postModel->setDateCreated($postRow['date_created']);
        $postModel->setForumId($postRow['forum_id']);
        $user = $userMapper->getUserById($postRow['user_id']);
        if ($user) {
            $postModel->setAutor($user);
        } else {
            $postModel->setAutor($userMapper->getDummyUser());
        }
        $postModel->setAutorAllPost($this->getAllPostsByUserId($postRow['user_id']));

        return $postModel;
    }

    public function getAllPostsByUserId($userId)
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
     * @param null|\Ilch\Pagination $pagination
     * @param int $descorder
     * @return array
     * @throws \Ilch\Database\Exception
     */
    public function getPostsByTopicId($topicId, $pagination = null, $descorder = 0)
    {
        $select = $this->db()->select('*')
            ->from('forum_posts')
            ->where(['topic_id' => $topicId])
            ->order(['date_created' => ($descorder?'DESC':'ASC')]);

        if ($pagination !== null) {
            $select->limit($pagination->getLimit())
                ->useFoundRows();
            $result = $select->execute();
            $pagination->setRows($result->getFoundRows());
        } else {
            $result = $select->execute();
        }

        $postsArray = $result->fetchRows();
        $postEntry = [];
        $dummyUser = null;
        $cache = [];

        foreach ($postsArray as $post) {
            $postModel = new PostModel();
            $postModel->setId($post['id']);
            $postModel->setText($post['text']);
            $postModel->setVotes($post['votes']);
            $postModel->setDateCreated($post['date_created']);
            if (\array_key_exists($post['user_id'], $cache)) {
                $postModel->setAutor($cache[$post['user_id']]['user']);
            } else {
                $userMapper = new UserMapper();
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

            $postEntry[] = $postModel;
        }

        return $postEntry;
    }

    /**
     * Get first post of topic.
     *
     * @param $topicId
     * @return PostModel
     * @throws \Ilch\Database\Exception
     */
    public function getFirstPostByTopicId($topicId)
    {
        $select = $this->db()->select('*')
            ->from('forum_posts')
            ->where(['topic_id' => $topicId])
            ->limit(1)
            ->execute()
            ->fetchAssoc();

        $postModel = new PostModel();
        $userMapper = new UserMapper();
        $postModel->setId($select['id']);
        $postModel->setText($select['text']);
        $postModel->setVotes($select['votes']);
        $postModel->setDateCreated($select['date_created']);
        $user = $userMapper->getUserById($select['user_id']);
        if ($user) {
            $postModel->setAutor($user);
        } else {
            $postModel->setAutor($userMapper->getDummyUser());
        }
        $postModel->setAutorAllPost($this->getAllPostsByUserId($select['user_id']));

        return $postModel;
    }

    /**
     * Get date of last post created by user.
     *
     * @param $userId
     * @return false|null|string
     */
    public function getDateOfLastPostByUserId($userId)
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
     * Get the votes/likes for a post.
     *
     * @param integer $id
     * @return false|null|string
     */
    public function getVotes($id)
    {
        return $this->db()->select('votes')
            ->from('forum_posts')
            ->where(['id' => $id])
            ->execute()
            ->fetchCell();
    }

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

    public function saveRead(PostModel $model)
    {
        if ($model->getId()) {
            $this->db()->update('forum_posts')
                ->values(['read' => $model->getRead()])
                ->where(['id' => $model->getId()])
                ->execute();
        }
    }

    public function saveVisits(PostModel $model)
    {
        if ($model->getVisits()) {
            $this->db()->update('forum_topics')
                    ->values(['visits' => $model->getVisits()])
                    ->where(['id' => $model->getFileId()])
                    ->execute();
        }
    }

    /**
     * Save post vote/like.
     *
     * @param integer $id
     * @param integer $userId
     */
    public function saveVotes($id, $userId)
    {
        $votes = $this->getVotes($id);

        $this->db()->update('forum_posts')
            ->values(['votes' => $votes.$userId.','])
            ->where(['id' => $id])
            ->execute();
    }

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
     * @return \Ilch\Database\Mysql\Result|int
     */
    public function deleteById($id)
    {
        $rememberMapper = new RememberMapper();
        $rememberMapper->deleteByPostId($id);

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
    public function isFirstPostOfTopic($topicId, $postId)
    {
        $row = $this->db()->select('id')
            ->from('forum_posts')
            ->where(['topic_id' => $topicId])
            ->execute()
            ->fetchAssoc();

        return ($row['id'] == $postId);
    }
}
