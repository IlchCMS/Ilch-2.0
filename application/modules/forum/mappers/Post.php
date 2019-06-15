<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Forum\Mappers;

use Modules\Forum\Models\ForumPost as PostModel;
use Modules\User\Mappers\User as UserMapper;
use Modules\Forum\Config\Config as ForumConfig;

class Post extends \Ilch\Mapper
{
    public function getPostById($id)
    {
        $fileRow = $this->db()->select('*')
            ->from('forum_posts')
            ->where(['id' => $id])
            ->execute()
            ->fetchAssoc();

        $postModel = new PostModel();
        $userMapper = new UserMapper();
        $postModel->setId($fileRow['id']);
        $postModel->setText($fileRow['text']);
        $postModel->setVotes($fileRow['votes']);
        $postModel->setDateCreated($fileRow['date_created']);
        $postModel->setForumId($fileRow['forum_id']);
        $user = $userMapper->getUserById($fileRow['user_id']);
        if ($user) {
            $postModel->setAutor($user);
        } else {
            $postModel->setAutor($userMapper->getDummyUser());
        }
        $postModel->setAutorAllPost($this->getAllPostsByUserId($fileRow['user_id']));

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
     * @param $topicId
     * @param null $pagination
     * @return array
     * @throws \Ilch\Database\Exception
     */
    public function getPostsByTopicId($topicId, $pagination = null)
    {
        $select = $this->db()->select('*')
            ->from('forum_posts')
            ->where(['topic_id' => $topicId]);

        if ($pagination !== null) {
            $select->limit($pagination->getLimit())
                ->useFoundRows();
            $result = $select->execute();
            $pagination->setRows($result->getFoundRows());
        } else {
            $result = $select->execute();
        }

        $fileArray = $result->fetchRows();
        $postEntry = [];
        $user = null;
        $dummyUser = null;
        $userCache = [];

        foreach ($fileArray as $entries) {
            $entryModel = new PostModel();
            $userMapper = new UserMapper();
            $entryModel->setId($entries['id']);
            $entryModel->setText($entries['text']);
            $entryModel->setVotes($entries['votes']);
            $entryModel->setDateCreated($entries['date_created']);
            if (!array_key_exists($entries['user_id'], $userCache)) {
                $user = $userMapper->getUserById($entries['user_id']);
                if ($user) {
                    $userCache[$entries['user_id']] = $user;
                    $entryModel->setAutor($user);
                } else {
                    if (!$dummyUser) {
                        $dummyUser = $userMapper->getDummyUser();
                    }
                    $entryModel->setAutor($dummyUser);
                }
            } else {
                $entryModel->setAutor($userCache[$entries['user_id']]);
            }
            $entryModel->setAutorAllPost($this->getAllPostsByUserId($entries['user_id']));
            $postEntry[] = $entryModel;
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

    public function deleteById($id)
    {
        $this->trigger(ForumConfig::EVENT_DELETEPOST_BEFORE, ['id' => $id]);
        $returnValue = $this->db()->delete('forum_posts')
            ->where(['id' => $id])
            ->execute();
        $this->trigger(ForumConfig::EVENT_DELETEPOST_AFTER, ['id' => $id]);
        return $returnValue;
    }

    /**
     * Check if a post is the first one of a topic.
     *
     * @param $topicId
     * @param $postId
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
