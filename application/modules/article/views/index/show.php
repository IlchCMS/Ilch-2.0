<?php
$comments = $this->get('comments');
$article = $this->get('article');
$commentMapper = new \Modules\Comment\Mappers\Comment();
$categoryMapper = new \Modules\Article\Mappers\Category();
$articlesCats = $categoryMapper->getCategoryById($article->getCatId());
$content = str_replace('[PREVIEWSTOP]', '', $article->getContent());
$image = $article->getArticleImage();
$imageSource = $article->getArticleImageSource();
$preview = $this->getRequest()->getParam('preview');
$config = \Ilch\Registry::get('config');
$date = new \Ilch\Date($article->getDateCreated());
$commentsCount = $commentMapper->getCountComments('article/index/show/id/'.$article->getId());

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
            $req = $config->get('comment_interleaving');
        }

        $col = 10 - $req;
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
            echo '<time class="comment-date"><i class="fa fa-clock-o" title="'.$obj->getTrans('commentDateTime').'"></i> '.$commentDate->format("d.m.Y - H:i", true).'</time>';
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

<div class="col-lg-12 hidden-xs" style="padding-left: 0px;">
    <div class="col-lg-8" style="padding-left: 0px;">
        <h4><a href="<?=$this->getUrl(array('controller' => 'cats', 'action' => 'show', 'id' => $article->getCatId())) ?>"><?=$articlesCats->getName() ?></a></h4>
    </div>
    <div class="col-lg-4 text-right" style="padding-right: 0px;">
        <h4><a href="<?=$this->getUrl(array('controller' => 'archive', 'action' => 'show', 'year' => $date->format("Y", true), 'month' => $date->format("m", true))) ?>"><?=$date->format('d. F Y', true) ?></a></h4>
    </div>
</div>
<h3><a href="<?=$this->getUrl(array('action' => 'show', 'id' => $article->getId())) ?>"><?=$article->getTitle() ?></a></h3>
<?php if (!empty($image)): ?>
    <figure>
        <img class="article_image" src="<?=$this->getBaseUrl($image) ?>" />
        <?php if (!empty($imageSource)): ?>
            <figcaption class="article_image_source"><?=$this->getTrans('articleImageSource') ?>: <?=$imageSource ?></figcaption>
            <br />
        <?php endif; ?>
    <figure>
<?php endif; ?>
<?=$content ?>
<hr />
<div>
    <?php if ($article->getAuthorId() != ''): ?>
        <?php $userMapper = new \Modules\User\Mappers\User(); ?>
        <?php $user = $userMapper->getUserById($article->getAuthorId()); ?>
        <?php if ($user != ''): ?>
            <i class="fa fa-user" title="<?=$this->getTrans('author') ?>"></i> <a href="<?=$this->getUrl(array('module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $user->getId())) ?>"><?=$this->escape($user->getName()) ?></a>&nbsp;&nbsp;
        <?php endif; ?>
    <?php endif; ?>
    <i class="fa fa-calendar" title="<?=$this->getTrans('date') ?>"></i> <a href="<?=$this->getUrl(array('controller' => 'archive', 'action' => 'show', 'year' => $date->format("Y", true), 'month' => $date->format("m", true))) ?>"><?=$date->format('d. F Y', true) ?></a>
    &nbsp;&nbsp;<i class="fa fa-clock-o" title="<?=$this->getTrans('time') ?>"></i> <?=$date->format('H:i', true) ?>
    &nbsp;&nbsp;<i class="fa fa-folder-open-o" title="<?=$this->getTrans('cats') ?>"></i> <a href="<?=$this->getUrl(array('controller' => 'cats', 'action' => 'show', 'id' => $article->getCatId())) ?>"><?=$articlesCats->getName() ?></a>
    &nbsp;&nbsp;<i class="fa fa-comment-o" title="<?=$this->getTrans('comments') ?>"></i> <a href="<?=$this->getUrl(array('action' => 'show', 'id' => $article->getId().'#comment')) ?>"><?=$commentsCount ?></a>
    &nbsp;&nbsp;<i class="fa fa-eye" title="<?=$this->getTrans('hits') ?>"></i> <?=$article->getVisits() ?>
</div>

<link href="<?= $this->getModuleUrl('../comment/static/css/comment.css') ?>" rel="stylesheet">

<?php if(empty($preview)): ?>
    <?php $userMapper = new \Modules\User\Mappers\User(); ?>
    <?php $nowDate = new \Ilch\Date(); ?>
    <?php $col = 10; ?>
    <?php if ($config->get('comment_avatar') == 0) { $col = $col +2; }; ?>
    <div class="row">
        <div class="col-md-12">
            <legend class="page-header" id="comment"><?=$this->getTrans('comments') ?> (<?=$commentsCount ?>)</legend>
            <?php if($this->getUser()): ?>
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
                                                <i class="fa fa-user" title="<?=$this->getTrans('commentUser') ?>"></i> <a href="<?=$this->getUrl(array('module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $this->getUser()->getId())) ?>"><?=$this->getUser()->getName() ?></a>
                                            </div>
                                            <?php if ($config->get('comment_date') == 1): ?>
                                                <time class="comment-date"><i class="fa fa-clock-o" title="<?=$this->getTrans('commentDateTime') ?>"></i> <?=$nowDate->format("d.m.Y - H:i", true) ?></time>
                                            <?php endif; ?>
                                        </header>
                                        <div class="comment-post">
                                            <p>
                                                <textarea class="form-control"
                                                          accesskey=""
                                                          name="article_comment_text"
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
            <?php foreach ($comments as $comment): ?>
                <?php $user = $userMapper->getUserById($comment->getUserId()); ?>
                <?php $commentDate = new \Ilch\Date($comment->getDateCreated()); ?>
                <section class="comment-list">
                    <article class="row" id="<?=$comment->getId() ?>">
                        <?php if ($config->get('comment_avatar') == 1): ?>
                            <div class="col-md-2 col-sm-2 hidden-xs">
                                <figure class="thumbnail" title="<?=$this->getUser()->getName() ?>">
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
                                            <time class="comment-date"><i class="fa fa-clock-o" title="<?=$this->getTrans('commentDateTime') ?>"></i> <?=$commentDate->format("d.m.Y - H:i", true) ?></time>
                                        <?php endif; ?>
                                    </header>
                                    <div class="comment-post"><p><?=nl2br($this->escape($comment->getText())) ?></p></div>
                                    <?php if ($config->get('comment_reply') == 1): ?>
                                        <p class="text-right"><a href="<?=$this->getUrl(array('module' => 'comment', 'action' => 'index', 'id' => $comment->getId(), 'id_a' => $article->getId())) ?>" class="btn btn-default btn-sm"><i class="fa fa-reply"></i> <?=$this->getTrans('reply') ?></a></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </article>
                    <?php rec($comment->getId(), $comment->getUserId(), 1, $this) ?>
                </section>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>
