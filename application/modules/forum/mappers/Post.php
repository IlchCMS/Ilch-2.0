<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Forum\Mappers;

use Modules\Forum\Models\ForumPost as PostModel;
use Modules\User\Mappers\User as UserMapper;

class Post extends \Ilch\Mapper
{
    public function getPostsByTopicId($id)
    {
        $sql = 'SELECT *
                FROM `[prefix]_forum_topics`
                WHERE id = '.$id;
        $fileRow = $this->db()->queryRow($sql);

        $entryModel = new TopicModel();
        $entryModel->setId($fileRow['id']);
        $entryModel->setTopicTitle($fileRow['topic_title']);
        $entryModel->setText($fileRow['text']);
        $entryModel->setCreatorId($fileRow['creator_id']);
        $entryModel->setCreatorName($fileRow['creator_name']);
        $entryModel->setDateCreated($fileRow['date_created']);

        return $entryModel;
    }

    public function getPostById($id)
    {
        $sql = 'SELECT *
                FROM `[prefix]_forum_posts`
                WHERE id = '.$id;
        $fileRow = $this->db()->queryRow($sql);

        $entryModel = new PostModel();
        $entryModel->setId($fileRow['id']);
        $entryModel->setTopicId($fileRow['topic_id']);
        $entryModel->setText($fileRow['text']);

        return $entryModel;
    }

    public function getAllPostsByUderId($userId)
    {
        $sql = 'SELECT COUNT(id)
                FROM [prefix]_forum_posts
                WHERE user_id ='.$userId;
        $topics = $this->db()->queryCell($sql);

        if (empty($topics)) {
            return '0';
        }

        return $topics;
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

    public function getPostByTopicId($topicId, $pagination = null)
    {
        $sql = 'SELECT SQL_CALC_FOUND_ROWS *
                FROM `[prefix]_forum_posts`
                WHERE topic_id = '.$topicId.'
                LIMIT '.implode(',',$pagination->getLimit());

        $fileArray = $this->db()->queryArray($sql);
        $pagination->setRows($this->db()->querycell('SELECT FOUND_ROWS()'));

        $postEntry = [];
        $userMapper = new UserMapper();

        foreach ($fileArray as $entries) {
            $entryModel = new PostModel();
            $entryModel->setId($entries['id']);
            $entryModel->setText($entries['text']);
            $entryModel->setDateCreated($entries['date_created']);
            if ($userMapper->getUserById($entries['user_id'])) {
                $entryModel->setAutor($userMapper->getUserById($entries['user_id']));
            } else {
                $entryModel->setAutor($userMapper->getDummyUser());
            }
            $entryModel->setAutorAllPost($this->getAllPostsByUderId($entries['user_id']));
            $postEntry[] = $entryModel;
        }
        
        return $postEntry;
    }

    public function deleteById($id)
    {
            return $this->db()->delete('forum_posts')
            ->where(['id' => $id])
            ->execute();
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
}
