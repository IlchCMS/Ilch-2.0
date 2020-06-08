<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Forum\Mappers;

use Modules\Forum\Models\Remember as RememberModel;

class Remember extends \Ilch\Mapper
{
    /**
     * @param array $where
     * @return array|RememberModel[]
     */
    private function getBy($where = [])
    {
        $remembersArray = $this->db()->select('*')
            ->fields(['r.id', 'r.date', 'r.post_id', 'r.note', 'r.user_id'])
            ->from(['r' => 'forum_remember'])
            ->join(['p' => 'forum_posts'], 'p.id = r.post_id', 'LEFT', ['p.forum_id', 'p.topic_id'])
            ->join(['t' => 'forum_topics'], 'p.topic_id = t.id', 'LEFT', ['t.topic_title'])
            ->where($where)
            ->execute()
            ->fetchRows();

        $remembers = [];

        foreach ($remembersArray as $rememberRow) {
            $rememberModel = new RememberModel();
            $rememberModel->setId($rememberRow['id']);
            $rememberModel->setDate($rememberRow['date']);
            $rememberModel->setForumId($rememberRow['forum_id']);
            $rememberModel->setTopicId($rememberRow['topic_id']);
            $rememberModel->setPostId($rememberRow['post_id']);
            $rememberModel->setTopicTitle($rememberRow['topic_title']);
            $rememberModel->setNote($rememberRow['note']);
            $rememberModel->setUserId($rememberRow['user_id']);
            $remembers[] = $rememberModel;
        }

        return $remembers;
    }

    /**
     * Get all remembers.
     *
     * @param int $userId
     * @return array|RememberModel[]
     */
    public function getRememberedPostsByUserId($userId)
    {
        return $this->getBy(['r.user_id' => $userId]);
    }

    /**
     * Get remember by id.
     *
     * @param int $id
     * @return null|RememberModel
     */
    public function getRememberById($id)
    {
        $remember = $this->getBy(['r.id' => $id]);

        if (!empty($remember)) {
            return reset($remember);
        }

        return null;
    }

    /**
     * Get all remembered posts of a topic.
     *
     * @param int $topicId
     * @return array|RememberModel[]
     */
    public function getRememberedPostsByTopicId($topicId)
    {
        return $this->getBy(['p.topic_id' => $topicId]);
    }

    /**
     * Check if there is an specific entry.
     *
     * @param array $where
     * @return bool
     */
    private function hasEntryWith($where = [])
    {
        return (bool) $this->db()->select('id')
            ->from('forum_remember')
            ->where($where)
            ->execute()
            ->fetchCell();
    }

    /**
     * Check if there is an entry for a specific post.
     *
     * @param int $id
     * @return bool
     */
    public function hasRememberedPostWithPostId($postId)
    {
        return $this->hasEntryWith(['post_id' => $postId]);
    }

    /**
     * Save a remember model.
     *
     * @param RememberModel $remember
     */
    public function save($remember)
    {
        if ($this->hasEntryWith(['id' => $remember->getId()])) {
            $this->db()->update('forum_remember')
                ->values([
                    'note' => $remember->getNote()
                ])
                ->where(['id' => $remember->getId()])
                ->execute();
        } else {
            $this->db()->insert('forum_remember')
                ->values([
                    'post_id' => $remember->getPostId(),
                    'note' => $remember->getNote(),
                    'user_id' => $remember->getUserId()
                ])
                ->execute();
        }
    }

    /**
     * Delete remember by id.
     *
     * @param $id
     * @return \Ilch\Database\Mysql\Result|int
     */
    public function delete($id, $userId)
    {
        return $this->db()->delete('forum_remember')
            ->where(['id' => $id, 'user_id' => $userId])
            ->execute();
    }
}
