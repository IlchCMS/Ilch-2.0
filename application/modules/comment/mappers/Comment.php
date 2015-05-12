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
	 * @param integer $uid
	 * @param integer $req
     */
    public function comments_comment($id, $uid, $req){
        $CommentMappers = new CommentMappers();
        $userMapper = new \Modules\User\Mappers\User();
        $fk_comments = $CommentMappers->getCommentsByFKId($id);
		$user_rep = $userMapper->getUserById($uid);
		foreach($fk_comments as $fk_comment){
			$commentDate = new \Ilch\Date($fk_comment->getDateCreated());
			$user = $userMapper->getUserById($fk_comment->getUserId());
			echo '<section class="comment-list reply'.$req.'">';
			echo '<article class="row" id="'.$fk_comment->getId().'">';
			echo '<div class="col-md-2 col-sm-2 hidden-xs"><figure class="thumbnail">';
			echo '<a href="'.BASE_URL.'/index.php/user/profil/index/user/'.$user->getId().'"><img class="img-responsive" src="'.BASE_URL.'/'.$user->getAvatar().'" alt="'.$user->getName().'"></a>';
			echo '</figure></div><div class="col-md-10 col-sm-10"><div class="panel panel-default arrow left"><div class="panel-bodylist"><div class="panel-heading right">'.$user_rep->getName().'<i class="fa fa-reply"></i>Reply</div><header class="text-left">';
			echo '<div class="comment-user"><i class="fa fa-user"></i> <a href="'.BASE_URL.'/index.php/user/profil/index/user/'.$fk_comment->getUserId().'">'.$user->getName().'</a></div>';
			echo '<time class="comment-date"><i class="fa fa-clock-o"></i> '.$commentDate->format("d.m.Y - H:i", true).'</time>';
			echo '</header><div class="comment-post"><p>'.nl2br($fk_comment->getText()).'</p></div>'; 
			echo '<p class="text-right"><a href="'.BASE_URL.'/index.php/comment/index/index/id/'.$fk_comment->getId().'" class="btn btn-default btn-sm"><i class="fa fa-reply"></i> reply</a></p>';
			echo '</div></div></div>';
			echo '</article></section>';
			$fkk_comments = $CommentMappers->getCommentsByFKId($fk_comment->getId());
			if(count($fkk_comments) > 0){
				$req++;
			}
			$i=1;
			foreach($fkk_comments as $fkk_comment){
				if($i == 1){
					$CommentMappers->comments_comment($fk_comment->getId(), $fk_comment->getUserId(), $req);
					$i++;
				}	
			}
			if(count($fkk_comments) > 0){
				$req--;
			}
		}
    }
}
