<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Ilch;

use Modules\Comment\Models\Comment as CommentModel;
use Modules\User\Mappers\User as UserMapper;
use Modules\Comment\Mappers\Comment as CommentMapper;
use Modules\User\Models\User;

/**
 * Comments class to add comments to various modules by just calling getComments().
 */
class Comments
{
    private $userCache = [];

    /**
     * Get the user or the dummy user if not existing.
     * Either from cache or fetched from the database.
     *
     * @param int $userId
     * @return User|null
     */
    private function getUser(int $userId): ?User
    {
        if (!isset($this->userCache[$userId])) {
            $userMapper = new UserMapper();
            $this->userCache[$userId] = $userMapper->getUserById($userId);

            if (!isset($this->userCache[$userId])) {
                return $userMapper->getDummyUser();
            }
        }

        return $this->userCache[$userId];
    }

    /**
     * @param int $id
     * @param int $commentId
     * @param int $uid
     * @param int $req
     * @param $obj
     * @return string
     */
    private function rec(int $id, int $commentId, int $uid, int $req, $obj): string
    {
        $commentMappers = new CommentMapper();
        $fk_comments = $commentMappers->getCommentsByFKId($commentId);
        $user_rep = $this->getUser($uid);
        $config = Registry::get('config');
        $nowDate = new Date();
        $commentsHtml = '';

        foreach ($fk_comments as $fk_comment) {
            $commentDate = new Date($fk_comment->getDateCreated());
            $user = $this->getUser($fk_comment->getUserId());
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
                                        <i class="fa-regular fa-clock" title="'.$obj->getTrans('commentDateTime').'"></i> '.$commentDate->format('d.m.Y - H:i', true).'
                                    </p>
                                </div>
                                <div class="pull-right text-muted small">
                                    <i class="fa-solid fa-reply fa-flip-vertical"></i> '.$user_rep->getName().'
                                </div>
                            </div>
                            <p>'.nl2br($obj->escape($fk_comment->getText())).'</p>
                            <div>';
            if ($obj->getUser() && !in_array($obj->getUser()->getId(), $voted)) {
                $commentsHtml .= '
                                <div class="btn-group">
                                    <a class="btn btn-sm btn-outline-secondary btn-hover-success" href="'.$obj->getUrl(['id' => $id, 'commentId' => $fk_comment->getId(), 'key' => 'up']).'" title="'.$obj->getTrans('iLike').'">
                                        <i class="fa-solid fa-thumbs-up"></i> '.$obj->escape($fk_comment->getUp()).'
                                    </a>
                                    <a class="btn btn-sm btn-outline-secondary btn-hover-danger" href="'.$obj->getUrl(['id' => $id, 'commentId' => $fk_comment->getId(), 'key' => 'down']).'" title="'.$obj->getTrans('notLike').'">
                                        <i class="fa-solid fa-thumbs-down"></i> '.$obj->escape($fk_comment->getDown()).'
                                    </a>
                                </div>';
            } else {
                $commentsHtml .= '
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-outline-secondary btn-success">
                                        <i class="fa-solid fa-thumbs-up"></i> '.$obj->escape($fk_comment->getUp()).'
                                    </button>
                                    <button class="btn btn-sm btn-outline-secondary btn-danger">
                                        <i class="fa-solid fa-thumbs-down"></i> '.$obj->escape($fk_comment->getDown()).'
                                    </button>
                                </div>';
            }

            if ($obj->getUser() && $config->get('comment_reply') == 1 && $req < $config->get('comment_nesting')-1) {
                $commentsHtml .= '
                                <a href="javascript:slideReply(\'reply_'.$fk_comment->getId().'\');" class="btn btn-sm btn-outline-secondary btn-hover-primary">
                                    <i class="fa-solid fa-reply"></i> '.$obj->getTrans('reply').'
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
                                                        <i class="fa-regular fa-clock" title="'.$obj->getTrans('commentDateTime').'"></i> '.$nowDate->format('d.m.Y - H:i', true).'
                                                    </p>
                                                </div>
                                                <div class="pull-right text-muted small">
                                                    <i class="fa-solid fa-reply fa-flip-vertical"></i> '.$user->getName().'
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
                                                    <button type="submit" class="btn btn-outline-secondary btn-sm" name="saveComment" value="save">'.
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

            foreach ($fkk_comments as $ignored) {
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
     * @param mixed $object e.g. an article model
     * @param mixed $layout $this from within the view.
     * @return string the complete html for the comments
     * @throws Database\Exception
     * @since 2.1.37
     */
    public function getComments(string $key, $object, $layout): string
    {
        $commentMapper = new CommentMapper();
        $comments = $commentMapper->getCommentsByKey($key);
        $commentsCount = $commentMapper->getCountComments($key);
        $config = Registry::get('config');
        $nowDate = new Date();

        $commentsHtml = '
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header" id="comment">'.$layout->getTrans('comments').'('.$commentsCount.')</h1>';

        if ($layout->getUser()) {
            $commentsHtml .= '
        <div class="reply">
            <form class="form-horizontal" method="POST">'.
                $layout->getTokenField().'
                <section class="comment-list">
                    <div class="card">
                        <div class="card-body">
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
                                            <i class="fa-regular fa-clock" title="'.$layout->getTrans('commentDateTime').'"></i> '.$nowDate->format('d.m.Y - H:i', true).'
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
                                            <button type="submit" class="btn btn-outline-secondary btn-sm" name="saveComment" value="save">'.
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
        }

        foreach ($comments as $comment) {
            $user = $this->getUser($comment->getUserId());
            $commentDate = new Date($comment->getDateCreated());
            $voted = explode(',', $comment->getVoted());

            $commentsHtml .= '
        <section class="comment-list">
            <article id="comment_'.$comment->getId().'">
                <div class="card">
                    <div class="card-body">
                        <div class="media-block">
                            <a class="media-left hidden-xs" href="'.$layout->getUrl(['module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $user->getId()]).'" title="'.$layout->escape($user->getName()).'">
                                <img class="img-circle comment-img" alt="'.$layout->escape($user->getName()).'" src="'.$layout->getUrl().'/'.$user->getAvatar().'">
                            </a>
                            <div class="media-body">
                                <div>
                                    <a href="'.$layout->getUrl(['module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $user->getId()]).'" title="'.$layout->escape($user->getName()).'">'.$layout->escape($user->getName()).'</a>
                                    <p class="text-muted small">
                                        <i class="fa-regular fa-clock" title="'.$layout->getTrans('commentDateTime').'"></i> '.$commentDate->format('d.m.Y - H:i', true).'
                                    </p>
                                </div>
                                <p>'.nl2br($layout->escape($comment->getText())).'</p>
                                <div>';

            if ($layout->getUser() && !in_array($layout->getUser()->getId(), $voted)) {
                $commentsHtml .= '
                                    <div class="btn-group">
                                        <a class="btn btn-sm btn-outline-secondary btn-hover-success" href="'.$layout->getUrl(['id' => $object->getId(), 'commentId' => $comment->getId(), 'key' => 'up']).'" title="'.$layout->getTrans('iLike').'">
                                            <i class="fa-solid fa-thumbs-up"></i> '.$comment->getUp().'
                                        </a>
                                        <a class="btn btn-sm btn-outline-secondary btn-hover-danger" href="'.$layout->getUrl(['id' => $object->getId(), 'commentId' => $comment->getId(), 'key' => 'down']).'" title="'.$layout->getTrans('notLike').'">
                                            <i class="fa-solid fa-thumbs-down"></i> '.$comment->getDown().'
                                        </a>
                                    </div>';
            } else {
                $commentsHtml .= '
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-outline-secondary btn-success">
                                            <i class="fa-solid fa-thumbs-up"></i> '.$comment->getUp().'
                                        </button>
                                        <button class="btn btn-sm btn-outline-secondary btn-danger">
                                            <i class="fa-solid fa-thumbs-down"></i> '.$comment->getDown().'
                                        </button>
                                    </div>';
            }

            if ($layout->getUser() && $config->get('comment_reply') == 1 && $config->get('comment_nesting') > 0) {
                $commentsHtml .= '
                                    <a href="javascript:slideReply(\'reply_'.$comment->getId().'\');" class="btn btn-sm btn-outline-secondary btn-hover-primary">
                                        <i class="fa-solid fa-reply"></i> '.$layout->getTrans('reply').'
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
                                                                <i class="fa-regular fa-clock" title="'.$layout->getTrans('commentDateTime').'"></i> '.$nowDate->format('d.m.Y - H:i', true).'
                                                            </p>
                                                        </div>
                                                        <div class="pull-right text-muted small">
                                                            <i class="fa-solid fa-reply fa-flip-vertical"></i> '.$layout->escape($user->getName()).'
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
                                                            <button type="submit" class="btn btn-outline-secondary btn-sm" name="saveComment" value="save">'.
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

    /**
     * Save a comment.
     *
     * @param string $key key for the comment e.g. "article/index/show/id/1"
     * @param string $text text of the comment
     * @param int $userId id of the user.
     * @return bool true if sucessfully saved. currently false if it triggered the flooding protection.
     * @since 2.1.37
     * @since 2.1.50 boolean return value. true if sucessfully saved. false if it triggered the flooding protection.
     */
    public function saveComment(string $key, string $text, int $userId): bool
    {
        $commentMapper = new CommentMapper();

        $config = Registry::get('config');
        $dateCreated = $commentMapper->getDateOfLastCommentByUserId($userId);
        $isExcludedFromFloodProtection = is_in_array(array_keys($this->getUser($userId)->getGroups()), explode(',', $config->get('comment_excludeFloodProtection')));

        if ($config->get('comment_floodInterval') > 0 && !$isExcludedFromFloodProtection && ($dateCreated >= date('Y-m-d H:i:s', time()-$config->get('comment_floodInterval')))) {
            $translator = new \Ilch\Translator();
            $translator->load(APPLICATION_PATH.'/modules/comment/translations/');
            $_SESSION['messages'][] = ['text' => $translator->trans('floodError'), 'type' => 'danger'];
            return false;
        }

        $splittedKey = explode('/', $key);
        $fkId = null;

        foreach ($splittedKey as $x => $xValue) {
            if (($xValue === 'id_c') && isset($splittedKey[$x + 1])) {
                $fkId = $splittedKey[$x+1];
            }
        }

        $date = new Date();
        $commentModel = new CommentModel();
        $commentModel->setKey($key);
        if ($fkId) {
            $commentModel->setFKId($fkId);
        }
        $commentModel->setText($text);
        $commentModel->setDateCreated($date);
        $commentModel->setUserId($userId);
        $commentMapper->save($commentModel);

        return true;
    }

    /**
     * Save a vote for a comment.
     *
     * @param int $id id of the comment
     * @param int $userId id of the user
     * @param bool $upVote true if an upvote, false if downvote.
     * @since 2.1.37
     */
    public function saveVote(int $id, int $userId, bool $upVote)
    {
        $commentMapper = new CommentMapper();
        $oldComment = $commentMapper->getCommentById($id);

        $commentModel = new CommentModel();
        $commentModel->setId($id);
        if ($upVote) {
            $commentModel->setUp($oldComment->getUp()+1);
        } else {
            $commentModel->setDown($oldComment->getDown()+1);
        }
        $commentModel->setVoted($oldComment->getVoted().$userId.',');
        $commentMapper->saveLike($commentModel);
    }
}
