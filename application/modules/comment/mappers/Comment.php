<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Comment\Mappers;

use Modules\Comment\Models\Comment as CommentModel;
use Modules\Comment\Mappers\Comment as CommentMappers;


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
            ->order(array('id' => 'DESC'))
			->execute()
            ->fetchRows();

        $comments = array();

        foreach ($commentsArray as $commentRow) {
            $commentModel = new CommentModel();
            $commentModel->setId($commentRow['id']);
			$commentModel->setFKId($commentRow['fk_id']);
            $commentModel->setKey($commentRow['key']);
            $commentModel->setText($commentRow['text']);
            $commentModel->setUserId($commentRow['user_id']);
            $commentModel->setDateCreated($commentRow['date_created']);
            $comments[] = $commentModel;
        }

        return $comments;
    }
	
	public function getCommentsByFKid($key)
    {
        $commentsArray = $this->db()->select('*')
			->from('comments')
			->where(array('fk_id' => $key))
            ->order(array('id' => 'DESC'))
			->execute()
            ->fetchRows();

        $comments = array();

        foreach ($commentsArray as $commentRow) {
            $commentModel = new CommentModel();
            $commentModel->setId($commentRow['id']);
			$commentModel->setFKId($commentRow['fk_id']);
            $commentModel->setKey($commentRow['key']);
            $commentModel->setText($commentRow['text']);
            $commentModel->setUserId($commentRow['user_id']);
            $commentModel->setDateCreated($commentRow['date_created']);
            $comments[] = $commentModel;
        }

        return $comments;
    }

    /**
     * @return CommentModel[]|null
     */
    public function getComments($locale = '')
    {
        $commentsArray = $this->db()->select('*')
            ->from('comments')
            ->order(array('id' => 'DESC'))
            ->execute()
            ->fetchRows();

        if (empty($commentsArray)) {
            return NULL;
        }

        $comments = array();

        foreach ($commentsArray as $commentRow) {
            $commentModel = new CommentModel();
            $commentModel->setId($commentRow['id']);
			$commentModel->setFKId($commentRow['fk_id']);
            $commentModel->setKey($commentRow['key']);
            $commentModel->setText($commentRow['text']);
            $commentModel->setUserId($commentRow['user_id']);
            $commentModel->setDateCreated($commentRow['date_created']);
            $comments[] = $commentModel;
        }

        return $comments;
    }

    /**
     * @param CommentModel $comment
     */
    public function save(CommentModel $comment)
    {
        $this->db()->insert('comments')
            ->values
            (
                array
                (
                    'key' => $comment->getKey(),
                    'text' => $comment->getText(),
                    'date_created' => $comment->getDateCreated(),
                    'user_id' => $comment->getUserId(),
					'fk_id' => $comment->getFKId(),
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
	
	/**
     * @param integer $id
     */
    public function comments_comment($id, $uid, $dtm, $ebene){
        $CommentMappers = new CommentMappers();
        $userMapper = new \Modules\User\Mappers\User();
        $fk_comments = $CommentMappers->getCommentsByFKId($id);
        $user = $userMapper->getUserById($uid);
        $commentDate = new \Ilch\Date($dtm);

        foreach($fk_comments as $fk_comment){
                    $user = $userMapper->getUserById($fk_comment->getUserId());
                    $commentDate = new \Ilch\Date($fk_comment->getDateCreated());
                    echo '<section class="comment-list reply'.$ebene.'">';
                    echo '<article class="row" id="'.$fk_comment->getId().'">';
                    echo '<div class="col-md-2 col-sm-2 hidden-xs"><figure class="thumbnail">';
                    echo '<a href="#"><img src="http://lorempixel.com/70/70/" alt=""></a>';
                    echo '</figure></div><div class="col-md-10 col-sm-10"><div class="panel panel-default arrow left"><div class="panel-bodylist"><header class="text-left">';
                    // echo '<div class="comment-user"><i class="fa fa-user"></i> <a href="'.$base->getUrl(array('module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $user->getId())).'">'.$this->escape($user->getName()).'</a></div>';
                    echo '<div class="comment-user"><i class="fa fa-user"></i> <a href="link">'.$user->getName().'</a></div>';
                    // echo '<span><a href="'.$base->getUrl(array('module' => 'comment', 'action' => 'index', 'id' => $fk_comment->getId(), 'id_a' => $article->getId())).'"><i class="fa fa-comment-o"></i> Reply</a></span>';
                    echo '<span><a href="link"><i class="fa fa-comment-o"></i> Reply</a></span>';
                    echo '<time class="comment-date"><i class="fa fa-clock-o"></i> '.$commentDate->format("d.m.Y - H:i", true).'</time>';
                    // echo '</header><div class="comment-post"><p>'.nl2br($this->escape($fk_comment->getText())).'</p>';
                    echo '</header><div class="comment-post"><p>'.nl2br($fk_comment->getText()).'</p>';
                    echo '</div></div></div></div>';
                    echo '</article></section>';
                }
    }
}
