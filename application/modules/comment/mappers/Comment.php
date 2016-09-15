<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Comment\Mappers;

use Modules\Comment\Models\Comment as CommentModel;

class Comment extends \Ilch\Mapper
{
    /**
     * @return CommentModel[]
     */
    public function getCommentsByKey($key)
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
            $comments[] = $commentModel;
        }

        return $comments;
    }
	
    public function getCommentsByFKid($key)
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
            $comments[] = $commentModel;
        }

        return $comments;
    }

    /**
     * @return CommentModel[]
     */
    public function getComments()
    {
        $commentsArray = $this->db()->select('*')
            ->from('comments')
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
            $comments[] = $commentModel;
        }

        return $comments;
    }
    
    /**
     * Gets the counter of all comments with given $key.
     *
     * @return integer
     */
    public function getCountComments($key)
    {
        $sql = 'SELECT COUNT(*)
                FROM `[prefix]_comments`
                WHERE `key` LIKE "'.$key.'%"';

        $count = $this->db()->queryCell($sql);

        return $count;
    }

    /**
     * Updates comment like with given id and key.
     *
     * @param integer $id
     * @param integer $key
     */
    public function updateLike($id, $key)
    {
        $sql = 'UPDATE `[prefix]_comments`
                SET `'.$key.'` = `'.$key.'` + 1
                WHERE `id` = '.$id;

        $like = $this->db()->queryCell($sql);

        return $like;
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
}
