<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Comment\Mappers;
use Comment\Models\Comment as CommentModel;

defined('ACCESS') or die('no direct access');

/**
 * @package ilch
 */
class Comment extends \Ilch\Mapper
{
    /**
     * @return CommentModel[]|null
     */
    public function getCommentsByKey($key)
    {
        $commentsArray = $this->db()->select('*')
			->from('comments')
			->where(array('key' => $key))
			->execute()
            ->fetchRows();

        $comments = array();

        foreach ($commentsArray as $commentRow) {
            $commentModel = new CommentModel();
            $commentModel->setId($commentRow['id']);
            $commentModel->setKey($commentRow['key']);
			$commentModel->setText($commentRow['text']);
			$commentModel->setUserId($commentRow['user_id']);
			$commentModel->setDateCreated($commentRow['date_created']);
            $comments[] = $commentModel;
        }

        return $comments;
    }

    /**
     * @param ReceiverModel $receiver
     */
    public function save(CommentModel $comment)
    {
        $this->db()->insert('comments')
            ->fields
            (
                array
                (
                    'key' => $comment->getKey(),
                    'text' => $comment->getText(),
                    'date_created' => $comment->getDateCreated(),
                    'user_id' => $comment->getUserId(),
                )
            )
            ->execute();
    }

    /**
     * @param integer $id
     */
    public function delete($id)
    {
        $this->db()->delete('comments')
            ->where(array('id' => $id))
            ->execute();
    }
}
