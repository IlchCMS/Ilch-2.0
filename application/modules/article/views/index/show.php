<?php
$comments = $this->get('comments');
$article = $this->get('article');
$categoryMapper = $this->get('categoryMapper');
$commentMapper = $this->get('commentMapper');
$userMapper = $this->get('userMapper');
$articlesCats = $categoryMapper->getCategoryById($article->getCatId());
$content = str_replace('[PREVIEWSTOP]', '', $article->getContent());
$image = $article->getArticleImage();
$imageSource = $article->getArticleImageSource();
$preview = $this->getRequest()->getParam('preview');
$config = $this->get('config');
$date = new \Ilch\Date($article->getDateCreated());
$commentsCount = $commentMapper->getCountComments('article/index/show/id/'.$article->getId());

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
                                                          style="resize: vertical"
                                                          name="comment_text"
                                                          accesskey=""
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
.article_preview {
    position: fixed;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    z-index: 1000;
    cursor: not-allowed;
}
</style>

<?php if ($preview): ?>
    <div class="article_preview"></div>
<?php endif; ?>

<div class="col-lg-12 hidden-xs" style="padding-left: 0px;">
    <div class="col-lg-8" style="padding-left: 0px;">
        <h4><a href="<?=$this->getUrl(['controller' => 'cats', 'action' => 'show', 'id' => $article->getCatId()]) ?>"><?=$this->escape($articlesCats->getName()) ?></a></h4>
    </div>
    <div class="col-lg-4 text-right" style="padding-right: 0px;">
        <h4><a href="<?=$this->getUrl(['controller' => 'archive', 'action' => 'show', 'year' => $date->format("Y", true), 'month' => $date->format("m", true)]) ?>"><?=$date->format('d. F Y', true) ?></a></h4>
    </div>
</div>
<h3><a href="<?=$this->getUrl(['action' => 'show', 'id' => $article->getId()]) ?>"><?=$this->escape($article->getTitle()) ?></a></h3>
<?php if (!empty($image)): ?>
    <figure>
        <img class="article_image" src="<?=$this->getBaseUrl($image) ?>" />
        <?php if (!empty($imageSource)): ?>
            <figcaption class="article_image_source"><?=$this->getTrans('articleImageSource') ?>: <?=$this->escape($imageSource) ?></figcaption>
            <br />
        <?php endif; ?>
    <figure>
<?php endif; ?>
<?=$content ?>
<hr />
<div>
    <?php
    if ($article->getAuthorId() != ''):
        $user = $userMapper->getUserById($article->getAuthorId());
        if ($user != ''): ?>
            <i class="fa fa-user" title="<?=$this->getTrans('author') ?>"></i> <a href="<?=$this->getUrl(['module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $user->getId()]) ?>"><?=$this->escape($user->getName()) ?></a>&nbsp;&nbsp;
        <?php endif; ?>
    <?php endif; ?>
    <i class="fa fa-calendar" title="<?=$this->getTrans('date') ?>"></i> <a href="<?=$this->getUrl(['controller' => 'archive', 'action' => 'show', 'year' => $date->format("Y", true), 'month' => $date->format("m", true)]) ?>"><?=$date->format('d. F Y', true) ?></a>
    &nbsp;&nbsp;<i class="fa fa-clock-o" title="<?=$this->getTrans('time') ?>"></i> <?=$date->format('H:i', true) ?>
    &nbsp;&nbsp;<i class="fa fa-folder-open-o" title="<?=$this->getTrans('cats') ?>"></i> <a href="<?=$this->getUrl(['controller' => 'cats', 'action' => 'show', 'id' => $article->getCatId()]) ?>"><?=$this->escape($articlesCats->getName()) ?></a>
    &nbsp;&nbsp;<i class="fa fa-comment-o" title="<?=$this->getTrans('comments') ?>"></i> <a href="<?=$this->getUrl(['action' => 'show', 'id' => $article->getId().'#comment']) ?>"><?=$commentsCount ?></a>
    &nbsp;&nbsp;<i class="fa fa-eye" title="<?=$this->getTrans('hits') ?>"></i> <?=$article->getVisits() ?>
</div>

<?php if (empty($preview)): ?>
    <?php $nowDate = new \Ilch\Date(); ?>
    <?php $col = 10; ?>
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
                                                              style="resize: vertical"
                                                              name="comment_text"
                                                              accesskey=""
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
                                                                  style="resize: vertical"
                                                                  name="comment_text"
                                                                  accesskey=""
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
                    <?php rec($comment->getId(), $comment->getUserId(), 1, $this); ?>
                </section>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>

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
