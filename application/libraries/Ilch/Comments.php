<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Ilch;

use Modules\User\Mappers\User as userMapper;
use Modules\Comment\Mappers\Comment as commentMapper;

class Comments
{
    /**
     * @param integer $id
     * @param integer $commentId
     * @param integer $uid
     * @param integer $req
     * @param $obj
     * @return string
     */
    private function rec($id, $commentId, $uid, $req, $obj): string
    {
        $commentMappers = $obj->get('commentMapper');
        $userMapper = $obj->get('userMapper');
        $fk_comments = $commentMappers->getCommentsByFKId($commentId);
        $user_rep = $userMapper->getUserById($uid);
        if (!$user_rep) {
            $user_rep = $userMapper->getDummyUser();
        }
        $config = $obj->get('config');
        $nowDate = new \Ilch\Date();
        $commentsHtml = '';

        foreach ($fk_comments as $fk_comment) {
            $commentDate = new \Ilch\Date($fk_comment->getDateCreated());
            $user = $userMapper->getUserById($fk_comment->getUserId());
            if (!$user) {
                $user = $userMapper->getDummyUser();
            }
            $voted = explode(',', $fk_comment->getVoted());
            if ($req >= $config->get('comment_nesting')) {
                $req = $config->get('comment_nesting');
            }

            $commentsHtml .= '
            <article id="comment_'.$fk_comment->getId().'">
                <div>
                    <div class="media-block">
                        <a class="media-left col-md-offset-<?=$req ?> col-sm-offset-'.$req.' hidden-xs" href="'.$obj->getUrl(['module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $user->getId()]).'" title="'.$obj->escape($user->getName()).'">
                            <img class="img-circle comment-img" alt="'.$obj->escape($user->getName()).'" src="'.$obj->getUrl().'/'.$user->getAvatar().'">
                        </a>
                        <div class="media-body">
                            <div class="clearfix">
                                <div class="pull-left">
                                    <a href="'.$obj->getUrl(['module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $user->getId()]).'" title="'.$obj->escape($user->getName()).'">'.
                                        $obj->escape($user->getName()).'
                                    </a>
                                    <p class="text-muted small">
                                        <i class="fa fa-clock-o" title="'.$obj->getTrans('commentDateTime').'"></i> '.$commentDate->format('d.m.Y - H:i', true).'
                                    </p>
                                </div>
                                <div class="pull-right text-muted small">
                                    <i class="fa fa-reply fa-flip-vertical"></i> '.$user_rep->getName().'
                                </div>
                            </div>
                            <p>'.nl2br($fk_comment->getText()).'</p>
                            <div>';
            if ($obj->getUser() && in_array($obj->getUser()->getId(), $voted) == false) {
                $commentsHtml .= '
                                <div class="btn-group">
                                    <a class="btn btn-sm btn-default btn-hover-success" href="'.$obj->getUrl(['id' => $id, 'commentId' => $fk_comment->getId(), 'key' => 'up']).'" title="'.$obj->getTrans('iLike').'">
                                        <i class="fa fa-thumbs-up"></i> '.$obj->escape($fk_comment->getUp()).'
                                    </a>
                                    <a class="btn btn-sm btn-default btn-hover-danger" href="'.$obj->getUrl(['id' => $id, 'commentId' => $fk_comment->getId(), 'key' => 'down']).'" title="'.$obj->getTrans('notLike').'">
                                        <i class="fa fa-thumbs-down"></i> '.$obj->escape($fk_comment->getDown()).'
                                    </a>
                                </div>';
            } else {
                $commentsHtml .= '
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-default btn-success">
                                        <i class="fa fa-thumbs-up"></i> '.$obj->escape($fk_comment->getUp()).'
                                    </button>
                                    <button class="btn btn-sm btn-default btn-danger">
                                        <i class="fa fa-thumbs-down"></i> '.$obj->escape($fk_comment->getDown()).'
                                    </button>
                                </div>';
            }

            if ($obj->getUser() && $config->get('comment_reply') == 1 && $req < $config->get('comment_nesting')-1) {
                $commentsHtml .= '
                                <a href="javascript:slideReply(\'reply_'.$fk_comment->getId().'\');" class="btn btn-sm btn-default btn-hover-primary">
                                    <i class="fa fa-reply"></i> '.$obj->getTrans('reply').'
                                </a>';
            }

            $commentsHtml .= '
                        </div>
                        <hr>
                    </div>';

            ++$req;

            if ($obj->getUser()) {
                $commentsHtml .= '
                        <div class="replyHidden" id="reply_'.$fk_comment->getId().'">
                            <form class="form-horizontal" method="POST">'.
                                $obj->getTokenField().'
                                <div>
                                    <div class="media-block">
                                        <a class="media-left col-md-offset-'.$req.' col-sm-offset-'.$req.' hidden-xs" href="'.$obj->getUrl(['module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $obj->getUser()->getId()]).'" title="'.$obj->escape($obj->getUser()->getName()).'">
                                            <img class="img-circle comment-img" alt="'.$obj->escape($obj->getUser()->getName()).'" src="'.$obj->getUrl().'/'.$obj->getUser()->getAvatar().'">
                                        </a>
                                        <div class="media-body">
                                            <div class="clearfix">
                                                <div class="pull-left">
                                                    <a href="'.$obj->getUrl(['module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $obj->getUser()->getId()]).'" title="'.$obj->escape($obj->getUser()->getName()).'">'.
                                                        $obj->escape($obj->getUser()->getName()).'
                                                    </a>
                                                    <p class="text-muted small">
                                                        <i class="fa fa-clock-o" title="'.$obj->getTrans('commentDateTime').'"></i> '.$nowDate->format('d.m.Y - H:i', true).'
                                                    </p>
                                                </div>
                                                <div class="pull-right text-muted small">
                                                    <i class="fa fa-reply fa-flip-vertical"></i> '.$user->getName().'
                                                </div>
                                            </div>
                                            <p>
                                                <textarea class="form-control"
                                                          style="resize: vertical"
                                                          name="comment_text"
                                                          required></textarea>
                                                <input type="hidden" name="fkId" value="'.$fk_comment->getId().'" />
                                            </p>
                                            <div>
                                                <div class="content_savebox">
                                                    <button type="submit" class="btn btn-default btn-sm" name="saveComment" value="save">'.
                                                        $obj->getTrans('submit').'
                                                    </button>
                                                </div>
                                            </div>
                                            <hr />
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>';
            }

            $commentsHtml .= '
                    </div>
                </div>
            </article>';

            --$req;
            $fkk_comments = $commentMappers->getCommentsByFKId($fk_comment->getId());
            if (count($fkk_comments) > 0) {
                $req++;
            }
            $i = 1;

            foreach ($fkk_comments as $fkk_comment) {
                if ($i == 1) {
                    $commentsHtml .= $this->rec($id, $fk_comment->getId(), $fk_comment->getUserId(), $req, $obj);
                    $i++;
                }
            }

            if (count($fkk_comments) > 0) {
                $req--;
            }
        }

        return $commentsHtml;
    }

    /**
     * Get the comments for a specific key.
     * This function returns the complete html for the comments.
     *
     * @param string $key comment key e.g. "article/index/show/id/1"
     * @param $object e.g. an article model
     * @param $layout $this from within the view.
     * @return string the complete html for the comments
     * @throws Database\Exception
     * @since 2.1.37
     */
    public function getComments($key, $object, $layout): string
    {
        $userMapper = new userMapper();
        $commentMapper = new commentMapper();
        $comments = $commentMapper->getCommentsByKey($key);
        $commentsCount = $commentMapper->getCountComments($key);
        $config = $layout->get('config');
        $nowDate = new \Ilch\Date();

        $commentsHtml = '
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header" id="comment">'.$layout->getTrans('comments').'('.$commentsCount.')</h1>
        <div class="reply">
            <form class="form-horizontal" method="POST">'.
                $layout->getTokenField().'
                <section class="comment-list">
                    <div class="panel">
                        <div class="panel-body">
                            <div class="media-block">
                                <a class="media-left hidden-xs" href="'.$layout->getUrl(['module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $layout->getUser()->getId()]).'" title="'.$layout->escape($layout->getUser()->getName()).'">
                                    <img class="img-circle comment-img" alt="'.$layout->escape($layout->getUser()->getName()).'" src="'.$layout->getUrl().'/'.$layout->getUser()->getAvatar().'">
                                </a>
                                <div class="media-body">
                                    <div>
                                        <a href="'.$layout->getUrl(['module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $layout->getUser()->getId()]).'" title="'.$layout->escape($layout->getUser()->getName()).'">'.
                                            $layout->escape($layout->getUser()->getName()).'
                                        </a>
                                        <p class="text-muted small">
                                            <i class="fa fa-clock-o" title="'.$layout->getTrans('commentDateTime').'"></i> '.$nowDate->format('d.m.Y - H:i', true).'
                                        </p>
                                    </div>
                                    <p>
                                        <textarea class="form-control"
                                                  style="resize: vertical"
                                                  name="comment_text"
                                                  required></textarea>
                                    </p>
                                    <div>
                                        <div class="content_savebox">
                                            <button type="submit" class="btn btn-default btn-sm" name="saveComment" value="save">'.
                                                $layout->getTrans('submit').'
                                            </button>
                                        </div>
                                    </div>
                                    <hr />
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </form>
        </div>';

        foreach($comments as $comment) {
            $user = $userMapper->getUserById($comment->getUserId());
            if (!$user) {
                $user = $userMapper->getDummyUser();
            }
            $commentDate = new \Ilch\Date($comment->getDateCreated());
            $voted = explode(',', $comment->getVoted());

            $commentsHtml .= '
        <section class="comment-list">
            <article id="comment_'.$comment->getId().'">
                <div class="panel">
                    <div class="panel-body">
                        <div class="media-block">
                            <a class="media-left hidden-xs" href="'.$layout->getUrl(['module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $user->getId()]).'" title="'.$layout->escape($user->getName()).'">
                                <img class="img-circle comment-img" alt="'.$layout->escape($user->getName()).'" src="'.$layout->getUrl().'/'.$user->getAvatar().'">
                            </a>
                            <div class="media-body">
                                <div>
                                    <a href="'.$layout->getUrl(['module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $user->getId()]).'" title="'.$layout->escape($user->getName()).'">'.$layout->escape($user->getName()).'</a>
                                    <p class="text-muted small">
                                        <i class="fa fa-clock-o" title="'.$layout->getTrans('commentDateTime').'"></i> '.$commentDate->format('d.m.Y - H:i', true).'
                                    </p>
                                </div>
                                <p>'.nl2br($layout->escape($comment->getText())).'</p>
                                <div>';

//            $commentsHtml .= '<div>';

            if ($layout->getUser() && in_array($layout->getUser()->getId(), $voted) == false) {
                $commentsHtml .= '
                                    <div class="btn-group">
                                        <a class="btn btn-sm btn-default btn-hover-success" href="'.$layout->getUrl(['id' => $object->getId(), 'commentId' => $comment->getId(), 'key' => 'up']).'" title="'.$layout->getTrans('iLike').'">
                                            <i class="fa fa-thumbs-up"></i> '.$comment->getUp().'
                                        </a>
                                        <a class="btn btn-sm btn-default btn-hover-danger" href="'.$layout->getUrl(['id' => $object->getId(), 'commentId' => $comment->getId(), 'key' => 'down']).'" title="'.$layout->getTrans('notLike').'">
                                            <i class="fa fa-thumbs-down"></i> '.$comment->getDown().'
                                        </a>
                                    </div>';
            } else {
                $commentsHtml .= '
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-default btn-success">
                                            <i class="fa fa-thumbs-up"></i> '.$comment->getUp().'
                                        </button>
                                        <button class="btn btn-sm btn-default btn-danger">
                                            <i class="fa fa-thumbs-down"></i> '.$comment->getDown().'
                                        </button>
                                    </div>';
            }

            if ($layout->getUser() && $config->get('comment_reply') == 1 && $config->get('comment_nesting') > 0) {
                $commentsHtml .= '
                                    <a href="javascript:slideReply(\'reply_'.$comment->getId().'\');" class="btn btn-sm btn-default btn-hover-primary">
                                        <i class="fa fa-reply"></i> '.$layout->getTrans('reply').'
                                    </a>';
            }
            $commentsHtml .= '
                                </div>
                                <hr />';

            if ($layout->getUser()) {
                $commentsHtml .= '
                                <div class="replyHidden" id="reply_'.$comment->getId().'">
                                    <form class="form-horizontal" method="POST">'.
                                        $layout->getTokenField().'
                                        <div>
                                            <div class="media-block">
                                                <a class="media-left hidden-xs" href="'.$layout->getUrl(['module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $layout->getUser()->getId()]).'" title="'.$layout->escape($layout->getUser()->getName()).'">
                                                    <img class="img-circle comment-img" alt="'.$layout->escape($layout->getUser()->getName()).'" src="'.$layout->getUrl().'/'.$layout->getUser()->getAvatar().'">
                                                </a>
                                                <div class="media-body">
                                                    <div class="clearfix">
                                                        <div class="pull-left">
                                                            <a href="'.$layout->getUrl(['module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $layout->getUser()->getId()]).'" title="'.$layout->escape($layout->getUser()->getName()).'">'.
                                                                $layout->escape($layout->getUser()->getName()).'
                                                            </a>
                                                            <p class="text-muted small">
                                                                <i class="fa fa-clock-o" title="'.$layout->getTrans('commentDateTime').'"></i> '.$nowDate->format('d.m.Y - H:i', true).'
                                                            </p>
                                                        </div>
                                                        <div class="pull-right text-muted small">
                                                            <i class="fa fa-reply fa-flip-vertical"></i> '.$layout->escape($user->getName()).'
                                                        </div>
                                                    </div>
                                                    <p>
                                                        <textarea class="form-control"
                                                                  style="resize: vertical"
                                                                  name="comment_text"
                                                                  required></textarea>
                                                        <input type="hidden" name="fkId" value="'.$comment->getId().'" />
                                                    </p>
                                                    <div>
                                                        <div class="content_savebox">
                                                            <button type="submit" class="btn btn-default btn-sm" name="saveComment" value="save">'.
                                                                $layout->getTrans('submit').'
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <hr />
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>';
            }

            $commentsHtml .= $this->rec($object->getId(), $comment->getId(), $comment->getUserId(), 0, $layout);
            $commentsHtml .= '
                            </div>
                        </div>
                    </div>
                </div>
            </article>
        </section>';
        }

        $commentsHtml .= '
    </div>
</div>

<script>
function slideReply(thechosenone) {
    $(\'.replyHidden\').each(function(index) {
        if ($(this).attr("id") == thechosenone) {
            $(this).slideToggle(400);
        } else {
            $(this).slideUp(200);
        }
    });
}
</script>';

        return $commentsHtml;
    }
}
