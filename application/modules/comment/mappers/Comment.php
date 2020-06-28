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
     * @return CommentModel[]
     */
    public function getCommentsByKey($key): array
    {
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

    public function getCommentsLikeKey($key): array
    {
        $sql = 'SELECT *
                FROM `[prefix]_comments`
                WHERE `key` LIKE "'.$key.'%"
                ORDER BY `id` DESC';

        $commentsArray = $this->db()->queryArray($sql);

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
	
    public function getCommentsByFKid($key): array
    {
        $commentsArray = $this->db()->select('*')
            ->from('comments')
            ->where(['fk_id' => $key])
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
     * @param integer $limit
     *
     * @return CommentModel[]
     */
    public function getComments($limit = null): array
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
     * @return integer
     */
    public function getCountComments($key): int
    {
        return $this->db()->select('COUNT(*)')
            ->from('comments')
            ->where(['key' => $key])
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
     * @param CommentModel $comment
     */
    public function save(CommentModel $comment)
    {
        $this->db()->insert('comments')
            ->values
            (
                [
                    'key' => $comment->getKey(),
                    'text' => $comment->getText(),
                    'date_created' => $comment->getDateCreated(),
                    'user_id' => $comment->getUserId(),
                    'fk_id' => $comment->getFKId(),
                ]
            )
            ->execute();
    }

    /**
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
        $sql = 'DELETE FROM `[prefix]_comments`
                WHERE `key` LIKE "'.$key.'%"';

        $this->db()->query($sql);
    }

    /**
     * Get the id of a comment with a specific fk_id.
     *
     * @param integer $fk_id
     * @return integer $id
     */
    public function getCommentIdbyFKid($fk_id): int
    {
        return $this->db()->select('id', 'comments', ['fk_id' => $fk_id])
            ->execute()
            ->fetchCell();
    }
}
