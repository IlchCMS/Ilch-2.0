<?php
$comments = $this->get('comments');
$commentMapper = new \Modules\Comment\Mappers\Comment();
$config = \Ilch\Registry::get('config');
$col = 10;

if ($config->get('comment_avatar') == 0) {
    $col = $col +2;
};

function rec($id, $uid, $req, $obj)
{
    $CommentMappers = new \Modules\Comment\Mappers\Comment();
    $userMapper = new \Modules\User\Mappers\User();
    $fk_comments = $CommentMappers->getCommentsByFKId($id);
    $user_rep = $userMapper->getUserById($uid);
    $config = \Ilch\Registry::get('config');

    foreach ($fk_comments as $fk_comment) {
        $commentDate = new \Ilch\Date($fk_comment->getDateCreated());
        $user = $userMapper->getUserById($fk_comment->getUserId());

        if ($req > $config->get('comment_interleaving')) {
            $req = $config->get('comment_interleaving') ;
        }

        $col = 9 - $req;
        $req = $req + 1;
        echo '<article class="row" id="'.$fk_comment->getId().'">';
        if ($config->get('comment_avatar') == 1) {
            echo '<div class="col-md-2 col-sm-2 col-md-offset-'.$req.' col-sm-offset-'.$req.' hidden-xs">';
            echo '<figure class="thumbnail" title="'.$user->getName().'">';
            echo '<a href="'.$obj->getUrl(array('module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $user->getId())).'"><img class="img-responsive" src="'.$obj->getBaseUrl($user->getAvatar()).'" alt="'.$user->getName().'"></a>';
            echo '</figure>';
            echo '</div>';
            echo '<div class="col-md-'.$col.' col-sm-'.$col.'">';
        } else {
            $col = $col + 2;
            echo '<div class="col-md-'.$col.' col-sm-'.$col.' col-md-offset-'.$req.' col-sm-offset-'.$req.'">';
        }
        echo '<div class="panel panel-default">';
        echo '<div class="panel-bodylist">';
        echo '<div class="panel-heading right"><i class="fa fa-reply"></i> '.$user_rep->getName().'</div>';
        echo '<header class="text-left">';
        echo '<div class="comment-user">';
        echo '<i class="fa fa-user" title="'.$obj->getTrans('commentUser').'"></i> <a href="'.$obj->getUrl(array('module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $fk_comment->getUserId())).'">'.$user->getName().'</a>';
        echo '</div>';
        if ($config->get('comment_date') == 1) {
            echo '<time class="comment-date"><i class="fa fa-clock-o" title="'.$obj->getTrans('dateTime').'"></i> '.$commentDate->format("d.m.Y - H:i", true).'</time>';
        }
        echo '</header>';
        echo '<div class="comment-post"><p>'.nl2br($fk_comment->getText()).'</p></div>';

        if ($config->get('comment_reply') == 1) {
            echo '<p class="text-right"><a href="'.$obj->getUrl(array('module' => 'comment', 'controller' => 'index', 'action' => 'index', 'id' => $fk_comment->getId(), 'id_a' => $obj->getRequest()->getParam('id'))).'" class="btn btn-default btn-sm"><i class="fa fa-reply"></i> '.$obj->getTrans('reply').'</a></p>';
        }

        echo '</div>';
        echo '</div>';
        echo '</div>';
        echo '</article>';

        $fkk_comments = $CommentMappers->getCommentsByFKId($fk_comment->getId());

        if (count($fkk_comments) > 0) {
            $req++;
        }
        $i=1;

        foreach ($fkk_comments as $fkk_comment) {
            if ($i == 1) {
                rec($fk_comment->getId(), $fk_comment->getUserId(), $req, $obj);
                $i++;
            }
        }

        if (count($fkk_comments) > 0) {
            $req--;
        }
    }
}
?>

<link href="<?=$this->getModuleUrl('../comment/static/css/comment.css') ?>" rel="stylesheet">

<legend><?=$this->getTrans('menuComments') ?></legend>
<?php if(!empty($comments)): ?>
    <section class="comment-list">
        <?php foreach($comments as $comment): ?>
            <?php if($this->getRequest()->getParam('id') == $comment->getId()): ?>
                <?php $fk_comments = $commentMapper->getCommentsByFKId($comment->getId()); ?>
                <?php $date = new \Ilch\Date($comment->getDateCreated()); ?>
                <?php $userMapper = new \Modules\User\Mappers\User(); ?>
                <?php $user = $userMapper->getUserById($comment->getUserId()); ?>
                <?php $commentDate = new \Ilch\Date($comment->getDateCreated()); ?>
                <article class="row" id="<?=$comment->getId() ?>">
                    <?php if ($config->get('comment_avatar') == 1): ?>
                        <div class="col-md-2 col-sm-2 hidden-xs">
                            <figure class="thumbnail" title="<?=$this->escape($user->getName()) ?>">
                                <a href="<?=$this->getUrl('user/profil/index/user/'.$user->getId()) ?>"><img class="img-responsive" src="<?=$this->getUrl().'/'.$user->getAvatar() ?>" alt="<?=$this->escape($user->getName()) ?>"></a>
                            </figure>
                        </div>
                    <?php endif; ?>
                    <div class="col-md-<?=$col ?> col-sm-<?=$col ?>">
                        <div class="panel panel-default">
                            <div class="panel-bodylist">
                                <header class="text-left">
                                    <div class="comment-user">
                                        <i class="fa fa-user" title="<?=$this->getTrans('commentUser') ?>"></i> <a href="<?=$this->getUrl(array('module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $user->getId())) ?>"><?=$this->escape($user->getName()) ?></a>
                                    </div>
                                    <?php if ($config->get('comment_date') == 1): ?>
                                        <time class="comment-date"><i class="fa fa-clock-o" title="<?=$this->getTrans('dateTime') ?>"></i> <?=$commentDate->format("d.m.Y - H:i", true) ?></time>
                                    <?php endif; ?>
                                </header>
                                <div class="comment-post"><p><?=nl2br($this->escape($comment->getText())) ?></p></div>
                            </div>
                        </div>
                    </div>
                </article>
            <?php endif; ?>
        <?php endforeach; ?>
    </section>
    <br />

    <?php $userMapper = new \Modules\User\Mappers\User(); ?>
    <?php $nowDate = new \Ilch\Date(); ?>
    <div class="row">
        <div class="col-md-12">
            <?php if($this->getUser() AND $config->get('comment_reply') == 1): ?>
                <form action="" class="form-horizontal" method="POST">
                    <?=$this->getTokenField() ?>
                    <section class="comment-list">
                        <article class="row">
                            <?php if ($config->get('comment_avatar') == 1): ?>
                                <div class="col-md-2 col-sm-2 col-md-offset-1 col-sm-offset-1 hidden-xs">
                                    <figure class="thumbnail" title="<?=$this->escape($user->getName()) ?>">
                                        <a href="<?=$this->getUrl('user/profil/index/user/'.$this->getUser()->getId()) ?>"><img class="img-responsive" src="<?=$this->getUrl().'/'.$this->getUser()->getAvatar() ?>" alt="<?=$this->getUser()->getName() ?>"></a>
                                    </figure>
                                </div>
                            <?php endif; ?>
                            <div class="col-md-<?=$col - 1 ?> col-sm-<?=$col - 1 ?>">
                                <div class="panel panel-default">
                                    <div class="panel-body">
                                        <header class="text-left">
                                            <div class="comment-user">
                                                <i class="fa fa-user" title="<?=$this->getTrans('commentUser') ?>"></i> <a href="<?=$this->getUrl(array('module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $this->getUser()->getId())) ?>"><?=$this->getUser()->getName() ?></a>
                                            </div>
                                            <?php if ($config->get('comment_date') == 1): ?>
                                                <time class="comment-date"><i class="fa fa-clock-o" title="<?=$this->getTrans('dateTime') ?>"></i> <?=$nowDate->format("d.m.Y - H:i", true) ?></time>
                                            <?php endif; ?>
                                        </header>
                                        <div class="comment-post">
                                            <p>
                                                <textarea class="form-control"
                                                          accesskey=""
                                                          name="comment_comment_text"
                                                          style="resize: vertical"
                                                          required></textarea>
                                            </p>
                                        </div>
                                        <p class="text-right submit">
                                            <input type="submit"
                                                   name="saveEntry"
                                                   class="btn" 
                                                   value="<?=$this->getTrans('submit') ?>" />
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </article>
                    </section>
                </form>
            <?php endif; ?>

            <section class="comment-list">
                <?php foreach($comments as $comment): ?>
                    <?php if($this->getRequest()->getParam('id') == $comment->getFKId()): ?>
                        <?php $date = new \Ilch\Date($comment->getDateCreated()); ?>
                        <?php $userMapper = new \Modules\User\Mappers\User(); ?>
                        <?php $user = $userMapper->getUserById($comment->getUserId()); ?>
                        <?php $commentDate = new \Ilch\Date($comment->getDateCreated()); ?>
                        <article class="row" id="<?=$comment->getId() ?>">
                            <?php if ($config->get('comment_avatar') == 1): ?>
                                <div class="col-md-2 col-sm-2 col-md-offset-1 col-sm-offset-1 hidden-xs">
                                    <figure class="thumbnail" title="<?=$this->escape($user->getName()) ?>">
                                        <a href="<?=$this->getUrl('user/profil/index/user/'.$user->getId()) ?>"><img class="img-responsive" src="<?=$this->getUrl().'/'.$user->getAvatar() ?>" alt="<?=$this->escape($user->getName()) ?>"></a>
                                    </figure>
                                </div>                            
                                <div class="col-md-<?=$col - 1 ?> col-sm-<?=$col - 1 ?>">
                            <?php else: ?>
                                <div class="col-md-<?=$col - 1 ?> col-sm-<?=$col - 1 ?> col-md-offset-1 col-sm-offset-1">
                            <?php endif; ?>
                                <div class="panel panel-default">
                                    <div class="panel-bodylist">
                                        <header class="text-left">
                                            <div class="comment-user">
                                                <i class="fa fa-user" title="<?=$this->getTrans('commentUser') ?>"></i> <a href="<?=$this->getUrl(array('module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $user->getId())) ?>"><?=$this->escape($user->getName()) ?></a>
                                            </div>
                                            <?php if ($config->get('comment_date') == 1): ?>
                                                <time class="comment-date"><i class="fa fa-clock-o" title="<?=$this->getTrans('dateTime') ?>"></i> <?=$commentDate->format("d.m.Y - H:i", true) ?></time>
                                            <?php endif; ?>
                                        </header>
                                        <div class="comment-post"><p><?=nl2br($this->escape($comment->getText())) ?></p></div>
                                        <?php if ($config->get('comment_reply') == 1): ?>
                                            <p class="text-right"><a href="<?=$this->getUrl(array('module' => 'comment', 'action' => 'index', 'id' => $comment->getId(), 'id_a' => $this->getRequest()->getParam('id_a'))) ?>" class="btn btn-default btn-sm"><i class="fa fa-reply"></i> <?=$this->getTrans('reply') ?></a></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </article>
                        <?php rec($comment->getId(), $comment->getUserId(), 1, $this) ?>
                    <?php endif; ?>
                <?php endforeach; ?>
            </section>
        </div>
    </div>
<?php endif; ?>
