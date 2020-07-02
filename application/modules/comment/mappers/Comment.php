<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Comment\Mappers;

use Modules\Comment\Models\Comment as CommentModel;

class Comment extends \Ilch\Mapper
{
    /**
     * Get comments with specific key.
     *
     * @param string $key
     * @return CommentModel[]|array
     */
    public function getCommentsByKey($key)
    {
        $key = $this->addMissingSlashIfNeeded($key);

        $commentsArray = $this->db()->select('*')
            ->from('comments')
            ->where(['key' => $key])
            ->order(['id' => 'DESC'])
            ->execute()
            ->fetchRows();

        $comments = [];
        foreach ($commentsArray as $commentRow) {
            $commentModel = new CommentModel();
            $commentModel->setId($commentRow['id']);
            $commentModel->setFKId($commentRow['fk_id']);
            $commentModel->setKey($commentRow['key']);
            $commentModel->setText($commentRow['text']);
            $commentModel->setUserId($commentRow['user_id']);
            $commentModel->setDateCreated($commentRow['date_created']);
            $commentModel->setUp($commentRow['up']);
            $commentModel->setDown($commentRow['down']);
            $commentModel->setVoted($commentRow['voted']);
            $comments[] = $commentModel;
        }

        return $comments;
    }

    /**
     * Get comments with key like ...
     *
     * @param string $key
     * @return CommentModel[]|array
     */
    public function getCommentsLikeKey($key)
    {
        $key = $this->addMissingSlashIfNeeded($key);

        $commentsArray = $this->db()->select('*')
            ->from('comments')
            ->where(['key LIKE' => $key.'%'])
            ->execute()
            ->fetchRows();

        $comments = [];
        foreach ($commentsArray as $commentRow) {
            $commentModel = new CommentModel();
            $commentModel->setId($commentRow['id']);
            $commentModel->setFKId($commentRow['fk_id']);
            $commentModel->setKey($commentRow['key']);
            $commentModel->setText($commentRow['text']);
            $commentModel->setUserId($commentRow['user_id']);
            $commentModel->setDateCreated($commentRow['date_created']);
            $commentModel->setUp($commentRow['up']);
            $commentModel->setDown($commentRow['down']);
            $commentModel->setVoted($commentRow['voted']);
            $comments[] = $commentModel;
        }

        return $comments;
    }

    /**
     * Get comments by specific id.
     *
     * @param integer $id
     * @return CommentModel|null
     */
    public function getCommentById($id)
    {
        $commentRow = $this->db()->select('*')
            ->from('comments')
            ->where(['id' => $id])
            ->execute()
            ->fetchAssoc();

        if (empty($commentRow)) {
            return null;
        }

        $commentModel = new CommentModel();
        $commentModel->setId($commentRow['id']);
        $commentModel->setFKId($commentRow['fk_id']);
        $commentModel->setKey($commentRow['key']);
        $commentModel->setText($commentRow['text']);
        $commentModel->setUserId($commentRow['user_id']);
        $commentModel->setDateCreated($commentRow['date_created']);
        $commentModel->setUp($commentRow['up']);
        $commentModel->setDown($commentRow['down']);
        $commentModel->setVoted($commentRow['voted']);

        return $commentModel;
    }

    /**
     * Get comments by fkid
     *
     * @param integer $fkid
     * @return array
     */
    public function getCommentsByFKid($fkid)
    {
        $commentsArray = $this->db()->select('*')
            ->from('comments')
            ->where(['fk_id' => $fkid])
            ->order(['id' => 'DESC'])
            ->execute()
            ->fetchRows();

        $comments = [];
        foreach ($commentsArray as $commentRow) {
            $commentModel = new CommentModel();
            $commentModel->setId($commentRow['id']);
            $commentModel->setFKId($commentRow['fk_id']);
            $commentModel->setKey($commentRow['key']);
            $commentModel->setText($commentRow['text']);
            $commentModel->setUserId($commentRow['user_id']);
            $commentModel->setDateCreated($commentRow['date_created']);
            $commentModel->setUp($commentRow['up']);
            $commentModel->setDown($commentRow['down']);
            $commentModel->setVoted($commentRow['voted']);
            $comments[] = $commentModel;
        }

        return $comments;
    }

    /**
     * Get comments.
     *
     * @param integer $limit
     * @return CommentModel[]
     */
    public function getComments($limit = null)
    {
        $select = $this->db()->select('*')
            ->from('comments')
            ->order(['id' => 'DESC']);

        if ($limit !== null) {
            $select->limit($limit);
        }

        $result = $select->execute();
        $commentsArray = $result->fetchRows();

        $comments = [];
        foreach ($commentsArray as $commentRow) {
            $commentModel = new CommentModel();
            $commentModel->setId($commentRow['id']);
            $commentModel->setFKId($commentRow['fk_id']);
            $commentModel->setKey($commentRow['key']);
            $commentModel->setText($commentRow['text']);
            $commentModel->setUserId($commentRow['user_id']);
            $commentModel->setDateCreated($commentRow['date_created']);
            $commentModel->setUp($commentRow['up']);
            $commentModel->setDown($commentRow['down']);
            $commentModel->setVoted($commentRow['voted']);
            $comments[] = $commentModel;
        }

        return $comments;
    }
    
    /**
     * Gets the count of all comments with given $key.
     *
     * @param string $key
     * @return integer
     */
    public function getCountComments($key)
    {
        $key = $this->addMissingSlashIfNeeded($key);

        return $this->db()->select('COUNT(*)')
            ->from('comments')
            ->where(['key LIKE' => $key.'%'])
            ->execute()
            ->fetchCell();
    }

    /**
     * Save comment like.
     *
     * @param CommentModel $comment
     */
    public function saveLike(CommentModel $comment)
    {
        $fields = [
            'down' => $comment->getDown(),
            'up' => $comment->getUp(),
            'voted' => $comment->getVoted()
        ];

        $this->db()->update('comments')
            ->values($fields)
            ->where(['id' => $comment->getId()])
            ->execute();
    }

    /**
     * Save comment.
     *
     * @param CommentModel $comment
     */
    public function save(CommentModel $comment)
    {
        $this->db()->insert('comments')
            ->values
            (
                [
                    'key' => $this->addMissingSlashIfNeeded($comment->getKey()),
                    'text' => $comment->getText(),
                    'date_created' => $comment->getDateCreated(),
                    'user_id' => $comment->getUserId(),
                    'fk_id' => $comment->getFKId(),
                ]
            )
            ->execute();
    }

    /**
     * Delete comment.
     *
     * @param integer $id
     */
    public function delete($id)
    {
        do {
            $this->db()->delete('comments')
                ->where(['id' => $id])
                ->execute();
            $id = $this->getCommentIdbyFKid($id);
        } while($id);
    }

    /**
     * Delete comment with given $key
     *
     * @param string $key
     */
    public function deleteByKey($key)
    {
        $this->db()->delete('comments')
            ->where(['key LIKE' => $key.'%'])
            ->execute();
    }

    /**
     * Get the id of a comment with a specific fk_id.
     *
     * @param integer $fk_id
     * @return integer $id
     */
    public function getCommentIdbyFKid($fk_id) {
        return $this->db()->select('id', 'comments', ['fk_id' => $fk_id])
            ->execute()
            ->fetchCell();
    }

    /**
     * Check if the key ends on a slash and adds one if not.
     *
     * @param string $key
     * @return string key with slash at the end
     */
    private function addMissingSlashIfNeeded($key)
    {
        if (!(strlen($key) - (strrpos($key, '/')) === 0)) {
            // Add missing slash at the end to usually terminate the id.
            // This is needed for example so that id 11 doesn't get counted as id 1.
            $key .= '/';
        }

        return $key;
    }
}
