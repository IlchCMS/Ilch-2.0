<?php
$forumMapper = $this->get('forumMapper');
$rankMapper = $this->get('rankMapper');
$posts = $this->get('posts');
$cat = $this->get('cat');
$topicpost = $this->get('post');
$readAccess = $this->get('readAccess');
$forum = $this->get('forum');
$reportedPostsIds = $this->get('reportedPostsIds');
$rememberedPostIds = $this->get('rememberedPostIds');
$adminAccess = null;
if ($this->getUser()) {
    $adminAccess = $this->getUser()->isAdmin();
    $userAccess = $this->get('userAccess');
}

$forumPrefix = $forumMapper->getForumByTopicId($topicpost->getId());
$prefix = '';
if ($forumPrefix->getPrefix() != '' && $topicpost->getTopicPrefix() > 0) {
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

<?php if ($adminAccess == true || is_in_array($readAccess, explode(',', $forum->getReadAccess()))): ?>
    <div id="forum">
        <h1>
            <a href="<?=$this->getUrl(['controller' => 'index', 'action' => 'index']) ?>"><?=$this->getTrans('forum') ?></a>
            <i class="fa fa-chevron-right"></i> <a href="<?=$this->getUrl(['controller' => 'showcat', 'action' => 'index', 'id' => $cat->getId()]) ?>"><?=$this->escape($cat->getTitle()) ?></a>
            <i class="fa fa-chevron-right"></i> <a href="<?=$this->getUrl(['controller' => 'showtopics', 'action' => 'index', 'forumid' => $forum->getId()]) ?>"><?=$this->escape($forum->getTitle()) ?></a>
            <i class="fa fa-chevron-right"></i> <?=$this->escape($prefix.$topicpost->getTopicTitle()) ?>
        </h1>
        <div class="row">
            <div class="col-lg-12">
                <?php if ($topicpost->getStatus() == 0): ?>
                    <?php if ($this->getUser()): ?>
                        <?php if ($adminAccess == true || is_in_array($readAccess, explode(',', $forum->getReplayAccess()))): ?>
                            <a href="<?=$this->getUrl(['controller' => 'newpost', 'action' => 'index','topicid' => $this->getRequest()->getParam('topicid')]) ?>" class="btn btn-primary">
                                <span class="btn-label">
                                    <i class="fa fa-plus"></i>
                                </span><?=$this->getTrans('createNewPost') ?>
                            </a>
                            <?php if ($this->get('topicSubscription')) : ?>
                                <?php if ($this->get('isSubscribed')) : ?>
                                    <a href="<?=$this->getUrl(['action' => 'subscribe','topicid' => $this->getRequest()->getParam('topicid')]) ?>" class="btn btn-primary">
                                    <span class="btn-label">
                                        <i class="fas fa-bell-slash"></i>
                                    </span><?=$this->getTrans('unsubscribe') ?>
                                    </a>
                                <?php else : ?>
                                    <a href="<?=$this->getUrl(['action' => 'subscribe','topicid' => $this->getRequest()->getParam('topicid')]) ?>" class="btn btn-primary">
                                    <span class="btn-label">
                                        <i class="fas fa-bell"></i>
                                    </span><?=$this->getTrans('subscribe') ?>
                                    </a>
                                <?php endif; ?>
                            <?php endif; ?>
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
                <?=$this->get('pagination')->getHtml($this, ['action' => 'index', 'topicid' => $this->getRequest()->getParam('topicid')]) ?>
            </div>
            <div class="col-lg-12">
                <div class="posts-head ilch-head">
                    <?=$this->escape($prefix.$topicpost->getTopicTitle()) ?>
                </div>
            </div>
        </div>
        <?php foreach ($posts as $post): ?>
            <?php
            $date = new \Ilch\Date($post->getDateCreated());
            $reported = in_array($post->getId(), $reportedPostsIds);
            $remembered = in_array($post->getId(), $rememberedPostIds);
            ?>
            <div id="<?=$post->getId() ?>" class="post ilch-bg <?=($reported) ? 'reported' : '' ?>">
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
                                    <?=$date->format('d.m.Y H:i:s', true) ?>
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
                                        <?php if ($this->getUser()->isAdmin() || $this->getUser()->hasAccess('module_forum') || $this->getUser()->getId() == $post->getAutor()->getId()): ?>
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
                            <i class="forum appearance<?=$group->getId() ?>"><?=$this->escape($group->getName()) ?></i><br>
                        <?php endforeach; ?>
                        <i><?=$this->escape($rankMapper->getRankByPosts($post->getAutorAllPost())->getTitle()) ?></i>
                    </dd>
                    <dd>&nbsp;</dd>
                    <dd><b><?=$this->getTrans('posts') ?>:</b> <?=$post->getAutorAllPost() ?></dd>
                    <dd><b><?=$this->getTrans('joined') ?>:</b> <?=$post->getAutor()->getDateCreated() ?></dd>
                </dl>
            </div>
            <div class="post-footer ilch-bg">
                <?php if ($this->get('postVoting')) : ?>
                    <?php
                    $votes = explode(',', $post->getVotes());
                    $countOfVotes = count($votes) - 1;
                    ?>
                    <?php if ($this->getUser() && in_array($this->getUser()->getId(), $votes) == false) : ?>
                        <a class="btn btn-sm btn-default btn-hover-success" href="<?=$this->getUrl(['id' => $post->getId(), 'action' => 'vote', 'topicid' => $this->getRequest()->getParam('topicid')]) ?>" title="<?=$this->getTrans('iLike') ?>">
                            <i class="fa fa-thumbs-up"></i> <?=$countOfVotes ?>
                        </a>
                    <?php else: ?>
                        <button class="btn btn-sm btn-default btn-success">
                            <i class="fa fa-thumbs-up"></i> <?=$countOfVotes ?>
                        </button>
                    <?php endif; ?>
                <?php endif; ?>
                <?php if ($this->getUser()): ?>
                    <div class="quote">
                        <?php if ($adminAccess == true || is_in_array($readAccess, explode(',', $forum->getReplayAccess()))): ?>
                            <p class="quote-post">
                                <a href="<?=$this->getUrl(['controller' => 'newpost', 'action' => 'index','topicid' => $this->getRequest()->getParam('topicid'), 'quote' => $post->getId()]) ?>" class="btn btn-primary btn-xs">
                        <span class="btn-label">
                            <i class="fas fa-quote-left"></i>
                        </span><?=$this->getTrans('quote') ?>
                                </a>
                            </p>
                        <?php endif; ?>
                    </div>
                    <?php if (!$reported && $this->get('reportingPosts')) : ?>
                        <div class="report">
                                <p class="report-post">
                                    <a href="<?=$this->getUrl(['action' => 'report','topicid' => $this->getRequest()->getParam('topicid'), 'postid' => $post->getId()]) ?>" class="btn btn-primary btn-xs">
                        <span class="btn-label">
                            <i class="fas fa-flag"></i>
                        </span><?=$this->getTrans('report') ?>
                                    </a>
                                </p>
                        </div>
                    <?php endif; ?>
                    <?php if (!$remembered) : ?>
                        <div class="remember">
                            <p class="remember-post">
                                <button class="btn btn-primary btn-xs" data-toggle="modal" data-target="#rememberDialog" data-post-id="<?=$post->getId() ?>">
                            <span class="btn-label">
                                <i class="fas fa-bookmark"></i>
                            </span><?=$this->getTrans('remember') ?>
                                </button>
                            </p>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
        <div class="topic-actions">
            <?php if ($topicpost->getStatus() == 0): ?>
                <?php if ($this->getUser()): ?>
                    <?php if ($adminAccess == true || is_in_array($readAccess, explode(',', $forum->getReplayAccess()))): ?>
                        <a href="<?=$this->getUrl(['controller' => 'newpost', 'action' => 'index','topicid' => $this->getRequest()->getParam('topicid')]) ?>" class="btn btn-primary">
                            <span class="btn-label">
                                <i class="fa fa-plus"></i>
                            </span><?=$this->getTrans('createNewPost') ?>
                        </a>
                        <?php if ($this->get('topicSubscription')) : ?>
                            <?php if ($this->get('isSubscribed')) : ?>
                                <a href="<?=$this->getUrl(['action' => 'subscribe','topicid' => $this->getRequest()->getParam('topicid')]) ?>" class="btn btn-primary">
                                    <span class="btn-label">
                                        <i class="fas fa-bell-slash"></i>
                                    </span><?=$this->getTrans('unsubscribe') ?>
                                </a>
                            <?php else : ?>
                                <a href="<?=$this->getUrl(['action' => 'subscribe','topicid' => $this->getRequest()->getParam('topicid')]) ?>" class="btn btn-primary">
                                    <span class="btn-label">
                                        <i class="fas fa-bell"></i>
                                    </span><?=$this->getTrans('subscribe') ?>
                                </a>
                            <?php endif; ?>
                        <?php endif; ?>
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
            <?=$this->get('pagination')->getHtml($this, ['action' => 'index', 'topicid' => $this->getRequest()->getParam('topicid')]) ?>
        </div>
    </div>

    <form id="rememberDialog_form" class="form-horizontal" method="POST" action="<?=$this->getUrl(['controller' => 'rememberedPosts', 'action' => 'remember']) ?>">
        <?=$this->getTokenField() ?>
        <div class="modal fade" id="rememberDialog" tabindex="-1" role="dialog" aria-labelledby="rememberDialogTitle" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="rememberDialogTitle"><?=$this->getTrans('rememberDialogTitle') ?></h5>
                    </div>
                    <div class="modal-body">
                        <a href="<?=$this->getUrl(['controller' => 'rememberedposts', 'action' => 'index']) ?>"><?=$this->getTrans('viewRememberedPosts') ?></a>
                        <hr>
                        <p><?=$this->getTrans('noteRememberedPost') ?>:</p>
                        <input type="text"
                               class="form-control"
                               id="note"
                               name="note"
                               maxlength="255" />
                        <input type="hidden"
                               id="postId"
                               name="postId"
                               value="" />
                    </div>
                    <div class="modal-footer">
                        <div id="rememberPostStatus"></div>
                        <button type="submit" class="btn btn-primary" id="submitRememberDialog" tabindex="0"><?=$this->getTrans('rememberPost') ?></button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><?=$this->getTrans('close') ?></button>
                    </div>
                </div>
            </div>
        </div>
    </form>

<?php else: ?>
    <?php
    header('location: ' .$this->getUrl(['controller' => 'index', 'action' => 'index', 'access' => 'noaccess']));
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

    $("#rememberDialog").on('shown.bs.modal', function (e) {
        let postId = $(e.relatedTarget).data('post-id');

        $(e.currentTarget).find('input[name="postId"]').val(postId);

        $('#rememberDialog_form').submit(function (e) {
            // prevent form submit
            e.preventDefault();

            let formData = $(this).serializeArray();
            let action   = $(this).attr('action');
            let rememberPostStatus = $('#rememberPostStatus');

            $.post(action, {ilch_token: formData[0]['value'], note: formData[1]['value'], postId: formData[2]['value']}, function() {
                rememberPostStatus.append("<?=$this->getTrans('rememberPostSuccess') ?>");
                rememberPostStatus.addClass("alert alert-success");
            }).fail(function() {
                rememberPostStatus.append("<?=$this->getTrans('rememberPostFail') ?>");
                rememberPostStatus.addClass("alert alert-danger");
            });
        });

        $('#submitRememberDialog').click(function(){
            $('#rememberDialog').delay(1000).fadeOut(450);

            setTimeout(function(){
                $('#rememberDialog').modal("hide");
            }, 1500);
        });
    });

    $("#rememberDialog").on('hidden.bs.modal', function (e) {
        $('#rememberPostStatus').empty();
        $('#rememberPostStatus').removeAttr("class");
        $('#note').val("");
    });
});
</script>
