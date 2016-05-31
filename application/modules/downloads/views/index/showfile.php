<?php
$userMapper = $this->get('userMapper');
$commentMapper = $this->get('commentMapper');
$file = $this->get('file');
$comments = $this->get('comments');
$commentsCount = $commentMapper->getCountComments('downloads/index/showfile/downloads/'.$this->getRequest()->getParam('downloads').'/id/'.$this->getRequest()->getParam('id'));
$nowDate = new \Ilch\Date();
$config = $this->get('config');
$col = 10;
$image = '';
if ($file->getFileImage() != '') {
    $image = $this->getBaseUrl($file->getFileImage());
} else {
    $image = $this->getBaseUrl('application/modules/media/static/img/nomedia.png');
}

function rec($id, $uid, $req, $obj)
{
    $commentMappers = $obj->get('commentMapper');
    $userMapper = $obj->get('userMapper');
    $fk_comments = $commentMappers->getCommentsByFKId($id);
    $user_rep = $userMapper->getUserById($uid);
    $config = $obj->get('config');
    $nowDate = new \Ilch\Date();

    foreach ($fk_comments as $fk_comment) {
        $commentDate = new \Ilch\Date($fk_comment->getDateCreated());
        $user = $userMapper->getUserById($fk_comment->getUserId());
        if ($req > $config->get('comment_interleaving')) {
            $req = $config->get('comment_interleaving');
        }

        $col = 10 - $req;
        ?>
        <article class="row" id="comment_<?=$fk_comment->getId() ?>">
            <?php if ($config->get('comment_avatar') == 1): ?>
                <div class="col-md-2 col-sm-2 col-md-offset-<?=$req ?> col-sm-offset-<?=$req ?> hidden-xs">
                    <figure class="thumbnail" title="<?=$user->getName() ?>">
                        <a href="<?=$obj->getUrl(['module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $user->getId()]) ?>"><img class="img-responsive" src="<?=$obj->getBaseUrl($user->getAvatar()) ?>" alt="<?=$user->getName() ?>"></a>
                    </figure>
                </div>
                <div class="col-md-<?=$col ?> col-sm-<?=$col ?>">
            <?php else: ?>
                <?php $col = $col + 2; ?>
                <div class="col-md-<?=$col ?> col-sm-<?=$col ?> col-md-offset-<?=$req ?> col-sm-offset-<?=$req ?>">
            <?php endif; ?>

                <div class="panel panel-default">
                    <div class="panel-bodylist">
                        <div class="panel-heading right">
                            <i class="fa fa-reply"></i> <?=$user_rep->getName() ?>
                        </div>
                        <header class="text-left">
                            <div class="comment-user">
                                <i class="fa fa-user" title="<?=$obj->getTrans('commentUser') ?>"></i> <a href="<?=$obj->getUrl(['module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $fk_comment->getUserId()]) ?>"><?=$user->getName() ?></a>
                            </div>
                            <?php if ($config->get('comment_date') == 1): ?>
                                <time class="comment-date"><i class="fa fa-clock-o" title="<?=$obj->getTrans('commentDateTime') ?>"></i> <?=$commentDate->format("d.m.Y - H:i", true) ?></time>
                            <?php endif; ?>
                        </header>
                        <div class="comment-post"><p><?=nl2br($fk_comment->getText()) ?></p></div>
                        <?php if ($obj->getUser() AND $config->get('comment_reply') == 1): ?>
                            <p class="text-right">
                                <a href="javascript:slideReply('reply_<?=$fk_comment->getId() ?>');" class="btn btn-default btn-sm">
                                    <i class="fa fa-reply"></i> <?=$obj->getTrans('reply') ?>
                                </a>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </article>

        <?php if ($obj->getUser()): ?>
            <div class="replyHidden" id="reply_<?=$fk_comment->getId() ?>">
                <form class="form-horizontal" action="" method="POST">
                    <?=$obj->getTokenField(); ?>
                    <section class="comment-list">
                        <article class="row">
                            <?php
                            $col = $col - 1;
                            $req = $req + 1;
                            if ($config->get('comment_avatar') == 1): ?>
                                <div class="col-md-2 col-sm-2 col-md-offset-<?=$req ?> col-sm-offset-<?=$req ?> hidden-xs">
                                    <figure class="thumbnail" title="<?=$obj->getUser()->getName() ?>">
                                        <a href="<?=$obj->getUrl('user/profil/index/user/'.$obj->getUser()->getId()) ?>">
                                            <img class="img-responsive" src="<?=$obj->getUrl().'/'.$obj->getUser()->getAvatar() ?>" alt="<?=$obj->getUser()->getName() ?>">
                                        </a>
                                    </figure>
                                </div>
                            <?php endif; ?>
                            <div class="col-md-<?=$col ?> col-sm-<?=$col ?>">
                                <div class="panel panel-default">
                                    <div class="panel-body">
                                        <div class="panel-heading right"><i class="fa fa-reply"></i> <?=$user->getName() ?></div>
                                        <header class="text-left">
                                            <div class="comment-user">
                                                <i class="fa fa-user" title="<?=$obj->getTrans('commentUser') ?>"></i> <a href="<?=$obj->getUrl(['module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $obj->getUser()->getId()]) ?>"><?=$obj->getUser()->getName() ?></a>
                                            </div>
                                            <?php if ($config->get('comment_date') == 1): ?>
                                                <time class="comment-date"><i class="fa fa-clock-o" title="<?=$obj->getTrans('commentDateTime') ?>"></i> <?=$nowDate->format("d.m.Y - H:i", true) ?></time>
                                            <?php endif; ?>
                                        </header>
                                        <div class="comment-post">
                                            <p>
                                                <textarea class="form-control"
                                                          accesskey=""
                                                          name="comment_text"
                                                          style="resize: vertical"
                                                          required></textarea>
                                            </p>
                                        </div>
                                        <input type="hidden" name="fkId" value="<?=$fk_comment->getId() ?>" />
                                        <p class="text-right submit">
                                            <?=$obj->getSaveBar('submit', 'Comment') ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </article>
                    </section>
                </form>
            </div>
        <?php endif;

        $fkk_comments = $commentMappers->getCommentsByFKId($fk_comment->getId());
        $req = $req -1;
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
<style>
.replyHidden {
    display: none;
}
</style>

<div id="downloads">
    <div class="row">
        <div class="col-md-6">
            <a href="<?=$this->getUrl().'/'.$file->getFileUrl() ?>">
                <img class="thumbnail" src="<?=$image ?>" alt="<?=$file->getFileTitle() ?>"/>
            </a>
        </div>
        <div class="col-md-6">
            <h3><?=$file->getFileTitle() ?></h3>
            <p><?=$file->getFileDesc() ?></p>
            <a href="<?=$this->getUrl().'/'.$file->getFileUrl() ?>" class="btn btn-primary pull-right"><?=$this->getTrans('download') ?></a>
        </div>
    </div>
</div>

<?php if ($config->get('comment_avatar') == 0) { $col = $col +2; }; ?>
<div class="row">
    <div class="col-md-12">
        <legend class="page-header" id="comment"><?=$this->getTrans('comments') ?> (<?=$commentsCount ?>)</legend>
        <?php if ($this->getUser()): ?>
            <div class="reply">
                <form action="" class="form-horizontal" method="POST">
                    <?=$this->getTokenField() ?>
                    <section class="comment-list">
                        <article class="row">
                            <?php if ($config->get('comment_avatar') == 1): ?>
                                <div class="col-md-2 col-sm-2 hidden-xs">
                                    <figure class="thumbnail" title="<?=$this->getUser()->getName() ?>">
                                        <a href="<?=$this->getUrl('user/profil/index/user/'.$this->getUser()->getId()) ?>"><img class="img-responsive" src="<?=$this->getUrl().'/'.$this->getUser()->getAvatar() ?>" alt="<?=$this->getUser()->getName() ?>"></a>
                                    </figure>
                                </div>
                            <?php endif; ?>
                            <div class="col-md-<?=$col ?> col-sm-<?=$col ?>">
                                <div class="panel panel-default">
                                    <div class="panel-body">
                                        <header class="text-left">
                                            <div class="comment-user">
                                                <i class="fa fa-user" title="<?=$this->getTrans('commentUser') ?>"></i> <a href="<?=$this->getUrl(['module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $this->getUser()->getId()]) ?>"><?=$this->getUser()->getName() ?></a>
                                            </div>
                                            <?php if ($config->get('comment_date') == 1): ?>
                                                <time class="comment-date"><i class="fa fa-clock-o" title="<?=$this->getTrans('commentDateTime') ?>"></i> <?=$nowDate->format("d.m.Y - H:i", true) ?></time>
                                            <?php endif; ?>
                                        </header>
                                        <div class="comment-post">
                                            <p>
                                                <textarea class="form-control"
                                                          accesskey=""
                                                          name="comment_text"
                                                          style="resize: vertical"
                                                          required></textarea>
                                            </p>
                                        </div>
                                        <p class="text-right submit">
                                            <?=$this->getSaveBar('submit', 'Comment') ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </article>
                    </section>
                </form>
            </div>
        <?php endif; ?>

        <?php foreach ($comments as $comment): ?>
            <?php $user = $userMapper->getUserById($comment->getUserId()); ?>
            <?php $commentDate = new \Ilch\Date($comment->getDateCreated()); ?>
            <section class="comment-list">
                <article class="row" id="comment_<?=$comment->getId() ?>">
                    <?php if ($config->get('comment_avatar') == 1): ?>
                        <div class="col-md-2 col-sm-2 hidden-xs">
                            <figure class="thumbnail" title="<?=$user->getName() ?>">
                                <a href="<?=$this->getUrl('user/profil/index/user/'.$user->getId()) ?>"><img class="img-responsive" src="<?=$this->getUrl().'/'.$user->getAvatar() ?>" alt="<?=$this->escape($user->getName()) ?>"></a>
                            </figure>
                        </div>
                    <?php endif; ?>
                    <div class="col-md-<?=$col ?> col-sm-<?=$col ?>">
                        <div class="panel panel-default">
                            <div class="panel-bodylist">
                                <header class="text-left">
                                    <div class="comment-user">
                                        <i class="fa fa-user" title="<?=$this->getTrans('commentUser') ?>"></i> <a href="<?=$this->getUrl(['module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $user->getId()]) ?>"><?=$this->escape($user->getName()) ?></a>
                                    </div>
                                    <?php if ($config->get('comment_date') == 1): ?>
                                        <time class="comment-date"><i class="fa fa-clock-o" title="<?=$this->getTrans('commentDateTime') ?>"></i> <?=$commentDate->format("d.m.Y - H:i", true) ?></time>
                                    <?php endif; ?>
                                </header>
                                <div class="comment-post"><p><?=nl2br($this->escape($comment->getText())) ?></p></div>
                                <?php if ($this->getUser() AND $config->get('comment_reply') == 1): ?>
                                    <p class="text-right"><a href="javascript:slideReply('reply_<?=$comment->getId() ?>');" class="btn btn-default btn-sm"><i class="fa fa-reply"></i> <?=$this->getTrans('reply') ?></a></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </article>

                <?php if ($this->getUser()): ?>
                    <div class="replyHidden" id="reply_<?=$comment->getId() ?>">
                        <form action="" class="form-horizontal" method="POST">
                            <?=$this->getTokenField() ?>
                            <article class="row">
                                <?php if ($config->get('comment_avatar') == 1): ?>
                                    <div class="col-md-2 col-sm-2 col-md-offset-1 col-sm-offset-1 hidden-xs">
                                        <figure class="thumbnail" title="<?=$this->getUser()->getName() ?>">
                                            <a href="<?=$this->getUrl('user/profil/index/user/'.$this->getUser()->getId()) ?>"><img class="img-responsive" src="<?=$this->getUrl().'/'.$this->getUser()->getAvatar() ?>" alt="<?=$this->getUser()->getName() ?>"></a>
                                        </figure>
                                    </div>
                                <?php endif; ?>
                                <div class="col-md-<?=$col+-1 ?> col-sm-<?=$col-1 ?>">
                                    <div class="panel panel-default">
                                        <div class="panel-body">
                                            <div class="panel-heading right"><i class="fa fa-reply"></i> <?=$this->escape($user->getName()) ?></div>
                                            <header class="text-left">
                                                <div class="comment-user">
                                                    <i class="fa fa-user" title="<?=$this->getTrans('commentUser') ?>"></i> <a href="<?=$this->getUrl(['module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $this->getUser()->getId()]) ?>"><?=$this->getUser()->getName() ?></a>
                                                </div>
                                                <?php if ($config->get('comment_date') == 1): ?>
                                                    <time class="comment-date"><i class="fa fa-clock-o" title="<?=$this->getTrans('commentDateTime') ?>"></i> <?=$nowDate->format("d.m.Y - H:i", true) ?></time>
                                                <?php endif; ?>
                                            </header>
                                            <div class="comment-post">
                                                <p>
                                                    <textarea class="form-control"
                                                              accesskey=""
                                                              name="comment_text"
                                                              style="resize: vertical"
                                                              required></textarea>
                                                </p>
                                            </div>
                                            <input type="hidden" name="fkId" value="<?=$comment->getId() ?>" />
                                            <p class="text-right submit">
                                                <?=$this->getSaveBar('submit', 'Comment') ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        </form>
                    </div>
                <?php endif; ?>
                <?php rec($comment->getId(), $comment->getUserId(), 1, $this) ?>
            </section>
        <?php endforeach; ?>
    </div>
</div>

<script>
function slideReply(thechosenone) {
    $('.replyHidden').each(function(index) {
        if ($(this).attr("id") == thechosenone) {
            $(this).slideDown(400);
        } else {
            $(this).slideUp(200);

            $('.reply').slideUp(200);
        }
    });
}
</script>
