<?php
$forumMapper = $this->get('forumMapper');
$rankMapper = $this->get('rankMapper');
$posts = $this->get('posts');
$cat = $this->get('cat');
$topicpost = $this->get('post');
$readAccess = $this->get('readAccess');
$forum = $this->get('forum');
$adminAccess = null;
if ($this->getUser()) {
    $adminAccess = $this->getUser()->isAdmin();
    $userAccess = $this->get('userAccess');
}

$forumPrefix = $forumMapper->getForumByTopicId($topicpost->getId());
$prefix = '';
if ($forumPrefix->getPrefix() != '' AND $topicpost->getTopicPrefix() > 0) {
    $prefix = explode(',', $forumPrefix->getPrefix());
    array_unshift($prefix, '');

    foreach ($prefix as $key => $value) {
        if ($topicpost->getTopicPrefix() == $key) {
            $value = trim($value);
            $prefix = '['.$value.'] ';
        }
    }
}
?>

<link href="<?=$this->getModuleUrl('static/css/forum.css') ?>" rel="stylesheet">

<?php if (is_in_array($readAccess, explode(',', $forum->getReadAccess())) || $adminAccess == true): ?>
    <div id="forum">
        <h1>
            <a href="<?=$this->getUrl(['controller' => 'index', 'action' => 'index']) ?>"><?=$this->getTrans('forum') ?></a>
            <i class="fa fa-chevron-right"></i> <a href="<?=$this->getUrl(['controller' => 'showcat', 'action' => 'index', 'id' => $cat->getId()]) ?>"><?=$cat->getTitle() ?></a>
            <i class="fa fa-chevron-right"></i> <a href="<?=$this->getUrl(['controller' => 'showtopics', 'action' => 'index', 'forumid' => $forum->getId()]) ?>"><?=$forum->getTitle() ?></a>
            <i class="fa fa-chevron-right"></i> <?=$prefix.$topicpost->getTopicTitle() ?>
        </h1>
        <div class="row">
            <div class="col-lg-12">
                <?php if ($topicpost->getStatus() == 0): ?>
                    <?php if ($this->getUser()): ?>
                        <?php if (is_in_array($readAccess, explode(',', $forum->getReplayAccess())) || $adminAccess == true): ?>
                            <a href="<?=$this->getUrl(['controller' => 'newpost', 'action' => 'index','topicid' => $this->getRequest()->getParam('topicid')]) ?>" class="btn btn-primary">
                                <span class="btn-label">
                                    <i class="fa fa-plus"></i>
                                </span><?=$this->getTrans('createNewPost') ?>
                            </a>
                        <?php endif; ?>
                    <?php else: ?>
                        <?php $_SESSION['redirect'] = $this->getRouter()->getQuery(); ?>
                        <a href="<?=$this->getUrl(['module' => 'user', 'controller' => 'login', 'action' => 'index']) ?>" class="btn btn-primary">
                            <span class="btn-label">
                                <i class="fa fa-user"></i>
                            </span><?=$this->getTrans('loginPost') ?>
                        </a>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="btn btn-primary">
                        <span class="btn-label">
                            <i class="fa fa-lock"></i>
                        </span><?=$this->getTrans('lockPost') ?>
                    </div>
                <?php endif; ?>
                <?=$this->get('pagination')->getHtml($this, ['action' => 'index', 'topicid' => $this->getRequest()->getParam('topicid')]); ?>
            </div>
            <div class="col-lg-12">
                <div class="posts-head ilch-head">
                    <?=$prefix.$topicpost->getTopicTitle() ?>
                </div>
            </div>
        </div>
        <?php foreach ($posts as $post): ?>
            <?php $date = new \Ilch\Date($post->getDateCreated()) ?>
            <div id="<?=$post->getId() ?>" class="post ilch-bg">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-lg-6">
                                <p class="author">
                                    <a href="#<?=$post->getId() ?>"><img src="<?=$this->getModuleUrl('static/img/icon_post_target.png') ?>" alt="Post" title="Post" height="9" width="11"></a>
                                    <?=$this->getTrans('by') ?>
                                    <strong>
                                        <a href="<?=$this->getUrl(['module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $post->getAutor()->getId()]) ?>" class="ilch-link-red"><?=$this->escape($post->getAutor()->getName()) ?></a>
                                    </strong>
                                    »
                                    <?=$date->format("d.m.Y H:i:s", true) ?>
                                </p>
                            </div>
                            <div class="col-lg-6 pull-right">
                                <div class="delete">
                                    <?php if ($this->getUser()): ?>
                                        <?php if ($this->getUser()->isAdmin()): ?>
                                            <p class="delete-post">
                                                <a href="<?=$this->getUrl(['controller' => 'showposts', 'action' => 'delete', 'id' => $post->getId(), 'topicid' => $this->getRequest()->getParam('topicid'), 'forumid' => $forum->getId()]) ?>" class="btn btn-primary btn-xs">
                                                    <span class="btn-label">
                                                        <i class="fa fa-trash"></i>
                                                    </span><?=$this->getTrans('delete') ?>
                                                </a>
                                            </p>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                                <div class="edit">
                                    <?php if ($this->getUser()): ?>
                                        <?php if ($this->getUser()->getId() == $post->getAutor()->getId() || $this->getUser()->isAdmin() || $this->getUser()->hasAccess('module_forum')): ?>
                                            <p class="edit-post">
                                                <a href="<?=$this->getUrl(['controller' => 'showposts', 'action' => 'edit', 'id' => $post->getId(), 'topicid' => $this->getRequest()->getParam('topicid')]) ?>" class="btn btn-primary btn-xs">
                                                    <span class="btn-label">
                                                        <i class="fa fa-pencil"></i>
                                                    </span><?=$this->getTrans('edit') ?>
                                                </a>
                                            </p>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="postbody">
                    <hr class="hr-top" />
                    <div class="content">
                        <?=nl2br($this->getHtmlFromBBCode($this->escape($post->getText()))) ?>
                    </div>

                    <?php if ($post->getAutor()->getSignature()): ?>
                        <hr />
                        <?=nl2br($this->getHtmlFromBBCode($this->escape($post->getAutor()->getSignature()))) ?>
                    <?php endif; ?>
                </div>
                <dl class="postprofile">
                    <dt>
                        <a href="<?=$this->getUrl(['module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $post->getAutor()->getId()]) ?>"><img src="<?=$this->getBaseUrl($post->getAutor()->getAvatar()) ?>" alt="User avatar" height="100" width="100"></a>
                        <br>
                        <a href="<?=$this->getUrl(['module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $post->getAutor()->getId()]) ?>" class="ilch-link-red">
                            <?=$this->escape($post->getAutor()->getName()) ?>
                        </a>
                    </dt>
                    <dd>
                        <?php foreach ($post->getAutor()->getGroups() as $group): ?>
                            <i class="forum appearance<?=$group->getId() ?>"><?=$group->getName() ?></i><br>
                        <?php endforeach; ?>
                        <i><?=$rankMapper->getRankByPosts($post->getAutorAllPost())->getTitle() ?></i>
                    </dd>
                    <dd>&nbsp;</dd>
                    <dd><b><?=$this->getTrans('posts') ?>:</b> <?=$post->getAutorAllPost() ?></dd>
                    <dd><b><?=$this->getTrans('joined') ?>:</b> <?=$post->getAutor()->getDateCreated() ?></dd>
                </dl>
            </div>
            <?php if ($this->get('postVoting')) : ?>
            <div class="post-footer ilch-bg">
                <?php
                $votes = explode(',', $post->getVotes());
                $countOfVotes = count($votes) - 1;
                ?>
                <?php if ($this->getUser() AND in_array($this->getUser()->getId(), $votes) == false) : ?>
                    <a class="btn btn-sm btn-default btn-hover-success" href="<?=$this->getUrl(['id' => $post->getId(), 'action' => 'vote', 'topicid' => $this->getRequest()->getParam('topicid')]) ?>" title="<?=$this->getTrans('iLike') ?>">
                        <i class="fa fa-thumbs-up"></i> <?=$countOfVotes ?>
                    </a>
                <?php else: ?>
                    <button class="btn btn-sm btn-default btn-success">
                        <i class="fa fa-thumbs-up"></i> <?=$countOfVotes ?>
                    </button>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        <?php endforeach; ?>
        <div class="topic-actions">
            <?php if ($topicpost->getStatus() == 0): ?>
                <?php if ($this->getUser()): ?>
                    <?php if (is_in_array($readAccess, explode(',', $forum->getReplayAccess())) || $adminAccess == true): ?>
                        <a href="<?=$this->getUrl(['controller' => 'newpost', 'action' => 'index','topicid' => $this->getRequest()->getParam('topicid')]) ?>" class="btn btn-primary">
                            <span class="btn-label">
                                <i class="fa fa-plus"></i>
                            </span><?=$this->getTrans('createNewPost') ?>
                        </a>
                    <?php endif; ?>
                <?php else: ?>
                    <?php $_SESSION['redirect'] = $this->getRouter()->getQuery(); ?>
                    <a href="<?=$this->getUrl(['module' => 'user', 'controller' => 'login', 'action' => 'index']) ?>" class="btn btn-primary">
                        <span class="btn-label">
                            <i class="fa fa-user"></i>
                        </span><?=$this->getTrans('loginPost') ?>
                    </a>
                <?php endif; ?>
            <?php else: ?>
                <div class="btn btn-primary">
                    <span class="btn-label">
                        <i class="fa fa-lock"></i>
                    </span><?=$this->getTrans('lockPost') ?>
                </div>
            <?php endif; ?>
            <?=$this->get('pagination')->getHtml($this, ['action' => 'index', 'topicid' => $this->getRequest()->getParam('topicid')]); ?>
        </div>
    </div>
<?php else: ?>
    <?php
    header("location: ".$this->getUrl(['controller' => 'index', 'action' => 'index', 'access' => 'noaccess']));
    exit;
    ?>
<?php endif; ?>

<script>
$(document).ready(function() {
    $('a[href^="#"]').on('click',function (e) {
        e.preventDefault();

        var target = this.hash;
        var $target = $(target);
    });
});
</script>
