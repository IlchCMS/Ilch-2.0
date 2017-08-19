<?php
$comments = $this->get('comments');
$article = $this->get('article');
$categoryMapper = $this->get('categoryMapper');
$commentMapper = $this->get('commentMapper');
$userMapper = $this->get('userMapper');
$content = str_replace('[PREVIEWSTOP]', '', $article->getContent());
$preview = $this->getRequest()->getParam('preview');
$config = $this->get('config');
$date = new \Ilch\Date($article->getDateCreated());
$commentsCount = $commentMapper->getCountComments(sprintf(Modules\Article\Config\Config::COMMENT_KEY_TPL, $article->getId()));

$catIds = explode(",", $article->getCatId());
$categories = '';
foreach ($catIds as $catId) {
    $articlesCats = $categoryMapper->getCategoryById($catId);
    $categories .= '<a href="'.$this->getUrl(['controller' => 'cats', 'action' => 'show', 'id' => $catId]).'">'.$articlesCats->getName().'</a>, ';
}
?>

<?php function rec($id, $commentId, $uid, $req, $obj)
{
    $commentMappers = $obj->get('commentMapper');
    $userMapper = $obj->get('userMapper');
    $fk_comments = $commentMappers->getCommentsByFKId($commentId);
    $user_rep = $userMapper->getUserById($uid);
    $config = $obj->get('config');
    $nowDate = new \Ilch\Date();

    foreach ($fk_comments as $fk_comment) {
        $commentDate = new \Ilch\Date($fk_comment->getDateCreated());
        $user = $userMapper->getUserById($fk_comment->getUserId());
        $voted = explode(',', $fk_comment->getVoted());
        if ($req >= $config->get('comment_nesting')) {
            $req = $config->get('comment_nesting');
        }
    ?>
        <article id="comment_<?=$fk_comment->getId() ?>">
            <div>
                <div class="media-block">
                    <a class="media-left col-md-offset-<?=$req ?> col-sm-offset-<?=$req ?> hidden-xs" href="<?=$obj->getUrl(['module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $user->getId()]) ?>" title="<?=$obj->escape($user->getName()) ?>">
                        <img class="img-circle comment-img" alt="<?=$obj->escape($user->getName()) ?>" src="<?=$obj->getUrl().'/'.$user->getAvatar() ?>">
                    </a>
                    <div class="media-body">
                        <div class="clearfix">
                            <div class="pull-left">
                                <a href="<?=$obj->getUrl(['module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $user->getId()]) ?>" title="<?=$obj->escape($user->getName()) ?>">
                                    <?=$obj->escape($user->getName()) ?>
                                </a>
                                <p class="text-muted small">
                                    <i class="fa fa-clock-o" title="<?=$obj->getTrans('commentDateTime') ?>"></i> <?=$commentDate->format("d.m.Y - H:i", true) ?>
                                </p>
                            </div>
                            <div class="pull-right text-muted small">
                                <i class="fa fa-reply fa-flip-vertical"></i> <?=$user_rep->getName() ?>
                            </div>
                        </div>
                        <p><?=nl2br($fk_comment->getText()) ?></p>
                        <div>
                            <?php if ($obj->getUser() AND in_array($obj->getUser()->getId(), $voted) == false): ?>
                                <div class="btn-group">
                                    <a class="btn btn-sm btn-default btn-hover-success" href="<?=$obj->getUrl(['id' => $id, 'commentId' => $fk_comment->getId(), 'key' => 'up']) ?>" title="<?=$obj->getTrans('iLike') ?>">
                                        <i class="fa fa-thumbs-up"></i> <?=$obj->escape($fk_comment->getUp()) ?>
                                    </a>
                                    <a class="btn btn-sm btn-default btn-hover-danger" href="<?=$obj->getUrl(['id' => $id, 'commentId' => $fk_comment->getId(), 'key' => 'down']) ?>" title="<?=$obj->getTrans('notLike') ?>">
                                        <i class="fa fa-thumbs-down"></i> <?=$obj->escape($fk_comment->getDown()) ?>
                                    </a>
                                </div>
                            <?php else: ?>
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-default btn-success">
                                        <i class="fa fa-thumbs-up"></i> <?=$obj->escape($fk_comment->getUp()) ?>
                                    </button>
                                    <button class="btn btn-sm btn-default btn-danger">
                                        <i class="fa fa-thumbs-down"></i> <?=$obj->escape($fk_comment->getDown()) ?>
                                    </button>
                                </div>
                            <?php endif; ?>
                            <?php if ($obj->getUser() AND $config->get('comment_reply') == 1 AND $req < $config->get('comment_nesting')-1): ?>
                                <a href="javascript:slideReply('reply_<?=$fk_comment->getId() ?>');" class="btn btn-sm btn-default btn-hover-primary">
                                    <i class="fa fa-reply"></i> <?=$obj->getTrans('reply') ?>
                                </a>
                            <?php endif; ?>
                        </div>
                        <hr>
                    </div>
                    <?php $req = $req + 1; ?>

                    <?php if ($obj->getUser()): ?>
                        <div class="replyHidden" id="reply_<?=$fk_comment->getId() ?>">
                            <form class="form-horizontal" action="" method="POST">
                                <?=$obj->getTokenField(); ?>
                                <div>
                                    <div class="media-block">
                                        <a class="media-left col-md-offset-<?=$req ?> col-sm-offset-<?=$req ?> hidden-xs" href="<?=$obj->getUrl(['module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $obj->getUser()->getId()]) ?>" title="<?=$obj->escape($obj->getUser()->getName()) ?>">
                                            <img class="img-circle comment-img" alt="<?=$obj->escape($obj->getUser()->getName()) ?>" src="<?=$obj->getUrl().'/'.$obj->getUser()->getAvatar() ?>">
                                        </a>
                                        <div class="media-body">
                                            <div class="clearfix">
                                                <div class="pull-left">
                                                    <a href="<?=$obj->getUrl(['module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $obj->getUser()->getId()]) ?>" title="<?=$obj->escape($obj->getUser()->getName()) ?>">
                                                        <?=$obj->escape($obj->getUser()->getName()) ?>
                                                    </a>
                                                    <p class="text-muted small">
                                                        <i class="fa fa-clock-o" title="<?=$obj->getTrans('commentDateTime') ?>"></i> <?=$nowDate->format("d.m.Y - H:i", true) ?>
                                                    </p>
                                                </div>
                                                <div class="pull-right text-muted small">
                                                    <i class="fa fa-reply fa-flip-vertical"></i> <?=$user->getName() ?>
                                                </div>
                                            </div>
                                            <p>
                                                <textarea class="form-control"
                                                          style="resize: vertical"
                                                          name="comment_text"
                                                          required></textarea>
                                                <input type="hidden" name="fkId" value="<?=$fk_comment->getId() ?>" />
                                            </p>
                                            <div>
                                                <div class="content_savebox">
                                                    <button type="submit" class="btn btn-default btn-sm" name="saveComment" value="save">
                                                        <?=$obj->getTrans('submit') ?>
                                                    </button>
                                                </div>
                                            </div>
                                            <hr />
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </article>

        <?php
        $req = $req-1;
        $fkk_comments = $commentMappers->getCommentsByFKId($fk_comment->getId());
        if (count($fkk_comments) > 0) {
            $req++;
        }
        $i = 1;

        foreach ($fkk_comments as $fkk_comment) {
            if ($i == 1) {
                rec($id, $fk_comment->getId(), $fk_comment->getUserId(), $req, $obj);
                $i++;
            }
        }

        if (count($fkk_comments) > 0) {
            $req--;
        }
    }
}
?>

<?php if ($preview): ?>
    <div class="article_preview"></div>
<?php endif; ?>

<?php if ($article->getTeaser()): ?>
    <h3><?=$this->escape($article->getTeaser()) ?></h3>
<?php endif; ?>
<h2><a href="<?=$this->getUrl(['action' => 'show', 'id' => $article->getId()]) ?>"><?=$this->escape($article->getTitle()) ?></a></h2>
<?php if (!empty($article->getImage())): ?>
    <figure>
        <img class="article_image" src="<?=$this->getBaseUrl($article->getImage()) ?>" />
        <?php if (!empty($article->getImageSource())): ?>
            <figcaption class="article_image_source"><?=$this->getTrans('imageSource') ?>: <?=$this->escape($article->getImageSource()) ?></figcaption>
        <?php endif; ?>
    </figure>
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
    <i class="fa fa-calendar" title="<?=$this->getTrans('date') ?>"></i> <a href="<?=$this->getUrl(['controller' => 'archive', 'action' => 'show', 'year' => $date->format("Y", true), 'month' => $date->format("m", true)]) ?>"><?=$date->format('d.', true) ?> <?=$this->getTrans($date->format('F', true)) ?> <?=$date->format('Y', true) ?></a>
    &nbsp;&nbsp;<i class="fa fa-clock-o" title="<?=$this->getTrans('time') ?>"></i> <?=$date->format('H:i', true) ?>
    &nbsp;&nbsp;<i class="fa fa-folder-open-o" title="<?=$this->getTrans('cats') ?>"></i> <?=rtrim($categories, ', '); ?>
    &nbsp;&nbsp;<i class="fa fa-comment-o" title="<?=$this->getTrans('comments') ?>"></i> <a href="<?=$this->getUrl(['action' => 'show', 'id' => $article->getId().'#comment']) ?>"><?=$commentsCount ?></a>
    &nbsp;&nbsp;<i class="fa fa-eye" title="<?=$this->getTrans('hits') ?>"></i> <?=$article->getVisits() ?>
    <?php if ($article->getKeywords() != ''): ?>
        <br />
        <i class="fa fa-hashtag"></i> <?=$article->getKeywords() ?>
    <?php endif; ?>
</div>

<?php if (empty($preview)): ?>
    <?php $nowDate = new \Ilch\Date(); ?>
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header" id="comment"><?=$this->getTrans('comments') ?> (<?=$commentsCount ?>)</h1>
            <?php if ($this->getUser()): ?>
                <div class="reply">
                    <form action="" class="form-horizontal" method="POST">
                        <?=$this->getTokenField() ?>
                        <section class="comment-list">
                            <div class="panel">
                                <div class="panel-body">
                                    <div class="media-block">
                                        <a class="media-left hidden-xs" href="<?=$this->getUrl(['module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $this->getUser()->getId()]) ?>" title="<?=$this->escape($this->getUser()->getName()) ?>">
                                            <img class="img-circle comment-img" alt="<?=$this->escape($this->getUser()->getName()) ?>" src="<?=$this->getUrl().'/'.$this->getUser()->getAvatar() ?>">
                                        </a>
                                        <div class="media-body">
                                            <div>
                                                <a href="<?=$this->getUrl(['module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $this->getUser()->getId()]) ?>" title="<?=$this->escape($this->getUser()->getName()) ?>">
                                                    <?=$this->escape($this->getUser()->getName()) ?>
                                                </a>
                                                <p class="text-muted small">
                                                    <i class="fa fa-clock-o" title="<?=$this->getTrans('commentDateTime') ?>"></i> <?=$nowDate->format("d.m.Y - H:i", true) ?>
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
                                                    <button type="submit" class="btn btn-default btn-sm" name="saveComment" value="save">
                                                        <?=$this->getTrans('submit') ?>
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
                </div>
            <?php endif; ?>
            <?php foreach ($comments as $comment): ?>
                <?php $user = $userMapper->getUserById($comment->getUserId()); ?>
                <?php $commentDate = new \Ilch\Date($comment->getDateCreated()); ?>
                <?php $voted = explode(',', $comment->getVoted()); ?>
                <section class="comment-list">
                    <article id="comment_<?=$comment->getId() ?>">
                        <div class="panel">
                            <div class="panel-body">
                                <div class="media-block">
                                    <a class="media-left hidden-xs" href="<?=$this->getUrl(['module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $user->getId()]) ?>" title="<?=$this->escape($user->getName()) ?>">
                                        <img class="img-circle comment-img" alt="<?=$this->escape($user->getName()) ?>" src="<?=$this->getUrl().'/'.$user->getAvatar() ?>">
                                    </a>
                                    <div class="media-body">
                                        <div>
                                            <a href="<?=$this->getUrl(['module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $user->getId()]) ?>" title="<?=$this->escape($user->getName()) ?>">
                                                <?=$this->escape($user->getName()) ?>
                                            </a>
                                            <p class="text-muted small">
                                                <i class="fa fa-clock-o" title="<?=$this->getTrans('commentDateTime') ?>"></i> <?=$commentDate->format("d.m.Y - H:i", true) ?>
                                            </p>
                                        </div>
                                        <p><?=nl2br($this->escape($comment->getText())) ?></p>
                                        <div>
                                            <?php if ($this->getUser() AND in_array($this->getUser()->getId(), $voted) == false): ?>
                                                <div class="btn-group">
                                                    <a class="btn btn-sm btn-default btn-hover-success" href="<?=$this->getUrl(['id' => $article->getId(), 'commentId' => $comment->getId(), 'key' => 'up']) ?>" title="<?=$this->getTrans('iLike') ?>">
                                                        <i class="fa fa-thumbs-up"></i> <?=$this->escape($comment->getUp()) ?>
                                                    </a>
                                                    <a class="btn btn-sm btn-default btn-hover-danger" href="<?=$this->getUrl(['id' => $article->getId(), 'commentId' => $comment->getId(), 'key' => 'down']) ?>" title="<?=$this->getTrans('notLike') ?>">
                                                        <i class="fa fa-thumbs-down"></i> <?=$this->escape($comment->getDown()) ?>
                                                    </a>
                                                </div>
                                            <?php else: ?>
                                                <div class="btn-group">
                                                    <button class="btn btn-sm btn-default btn-success">
                                                        <i class="fa fa-thumbs-up"></i> <?=$this->escape($comment->getUp()) ?>
                                                    </button>
                                                    <button class="btn btn-sm btn-default btn-danger">
                                                        <i class="fa fa-thumbs-down"></i> <?=$this->escape($comment->getDown()) ?>
                                                    </button>
                                                </div>
                                            <?php endif; ?>
                                            <?php if ($this->getUser() AND $config->get('comment_reply') == 1 AND $config->get('comment_nesting') > 0): ?>
                                                <a href="javascript:slideReply('reply_<?=$comment->getId() ?>');" class="btn btn-sm btn-default btn-hover-primary">
                                                    <i class="fa fa-reply"></i> <?=$this->getTrans('reply') ?>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                        <hr />

                                        <?php if ($this->getUser()): ?>
                                            <div class="replyHidden" id="reply_<?=$comment->getId() ?>">
                                                <form action="" class="form-horizontal" method="POST">
                                                    <?=$this->getTokenField() ?>
                                                    <div>
                                                        <div class="media-block">
                                                            <a class="media-left hidden-xs" href="<?=$this->getUrl(['module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $this->getUser()->getId()]) ?>" title="<?=$this->escape($this->getUser()->getName()) ?>">
                                                                <img class="img-circle comment-img" alt="<?=$this->escape($this->getUser()->getName()) ?>" src="<?=$this->getUrl().'/'.$this->getUser()->getAvatar() ?>">
                                                            </a>
                                                            <div class="media-body">
                                                                <div class="clearfix">
                                                                    <div class="pull-left">
                                                                        <a href="<?=$this->getUrl(['module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $this->getUser()->getId()]) ?>" title="<?=$this->escape($this->getUser()->getName()) ?>">
                                                                            <?=$this->escape($this->getUser()->getName()) ?>
                                                                        </a>
                                                                        <p class="text-muted small">
                                                                            <i class="fa fa-clock-o" title="<?=$this->getTrans('commentDateTime') ?>"></i> <?=$nowDate->format("d.m.Y - H:i", true) ?>
                                                                        </p>
                                                                    </div>
                                                                    <div class="pull-right text-muted small">
                                                                        <i class="fa fa-reply fa-flip-vertical"></i> <?=$this->escape($user->getName()) ?>
                                                                    </div>
                                                                </div>
                                                                <p>
                                                                    <textarea class="form-control"
                                                                              style="resize: vertical"
                                                                              name="comment_text"
                                                                              required></textarea>
                                                                    <input type="hidden" name="fkId" value="<?=$comment->getId() ?>" />
                                                                </p>
                                                                <div>
                                                                    <div class="content_savebox">
                                                                        <button type="submit" class="btn btn-default btn-sm" name="saveComment" value="save">
                                                                            <?=$this->getTrans('submit') ?>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                                <hr />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        <?php endif; ?>
                                        <?php rec($article->getId(), $comment->getId(), $comment->getUserId(), 0, $this); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </article>
                </section>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>

<script>
function slideReply(thechosenone) {
    $('.replyHidden').each(function(index) {
        if ($(this).attr("id") == thechosenone) {
            $(this).slideToggle(400);
        } else {
            $(this).slideUp(200);
        }
    });
}
</script>
