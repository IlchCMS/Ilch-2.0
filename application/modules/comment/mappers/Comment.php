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
    public function getCommentsByKey(string $key): array
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
    public function getCommentsLikeKey(string $key): array
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
     * @param int $id
     * @return CommentModel|null
     */
    public function getCommentById(int $id): ?CommentModel
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
     * @param int $fkid
     * @return array
     */
    public function getCommentsByFKid(int $fkid): array
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
     * @param int|null $limit
     * @return CommentModel[]
     */
    public function getComments(int $limit = null): array
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
     * @return int
     */
    public function getCountComments(string $key): int
    {
        $key = $this->addMissingSlashIfNeeded($key);

        return $this->db()->select('COUNT(*)')
            ->from('comments')
            ->where(['key LIKE' => $key.'%'])
            ->execute()
            ->fetchCell();
    }

    /**
     * Get date of last comment of a user.
     *
     * @param int $userId
     * @return int|string
     * @since 2.1.50
     */
    public function getDateOfLastCommentByUserId(int $userId)
    {
        $select = $this->db()->select('date_created')
            ->from('comments')
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
     * @param int $id
     */
    public function delete(int $id)
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
    public function deleteByKey(string $key)
    {
        $this->db()->delete('comments')
            ->where(['key LIKE' => $key.'%'])
            ->execute();
    }

    /**
     * Get the id of a comment with a specific fk_id.
     *
     * @param int $fk_id
     * @return int $id
     */
    public function getCommentIdbyFKid(int $fk_id): int
    {
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
    private function addMissingSlashIfNeeded(string $key): string
    {
        if (!(strlen($key) - (strrpos($key, '/')) === 0)) {
            // Add missing slash at the end to usually terminate the id.
            // This is needed for example so that id 11 doesn't get counted as id 1.
            $key .= '/';
        }

        return $key;
    }
}
