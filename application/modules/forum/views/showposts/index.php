<?php

/** @var \Ilch\View $this */

use Ilch\Date;

/** @var \Modules\Forum\Mappers\Rank $rankMapper */
$rankMapper = $this->get('rankMapper');
/** @var \Modules\Forum\Models\ForumPost[] $posts */
$posts = $this->get('posts');
/** @var \Modules\Forum\Models\ForumItem $cat */
$cat = $this->get('cat');
/** @var \Modules\Forum\Models\ForumTopic|null $topicpost */
$topicpost = $this->get('post');
/** @var \Modules\Forum\Models\ForumItem $forum */
$forum = $this->get('forum');
/** @var string $prefix */
$prefix = $this->get('prefix');
/** @var array $reportedPostsIds */
$reportedPostsIds = $this->get('reportedPostsIds');
/** @var array $rememberedPostIds */
$rememberedPostIds = $this->get('rememberedPostIds');
$adminAccess = null;
if ($this->getUser()) {
    $adminAccess = $this->getUser()->isAdmin();
}
?>

<link href="<?=$this->getModuleUrl('static/css/forum.css') ?>" rel="stylesheet">

<?php if ($adminAccess || $forum->getReadAccess()) : ?>
    <div id="forum">
        <h1>
            <a href="<?=$this->getUrl(['controller' => 'index', 'action' => 'index']) ?>"><?=$this->getTrans('forum') ?></a>
            <i class="fa-solid fa-chevron-right"></i> <a href="<?=$this->getUrl(['controller' => 'showcat', 'action' => 'index', 'id' => $cat->getId()]) ?>"><?=$this->escape($cat->getTitle()) ?></a>
            <i class="fa-solid fa-chevron-right"></i> <a href="<?=$this->getUrl(['controller' => 'showtopics', 'action' => 'index', 'forumid' => $forum->getId()]) ?>"><?=$this->escape($forum->getTitle()) ?></a>
            <i class="fa-solid fa-chevron-right"></i> <?=$this->escape($prefix . $topicpost->getTopicTitle()) ?>
        </h1>
        <div class="row">
            <div class="col-xl-12">
                <?php if ($topicpost->getStatus() == 0) : ?>
                    <?php if ($this->getUser()): ?>
                        <?php if ($adminAccess || is_in_array($readAccess, explode(',', $forum->getReplyAccess()))): ?>
                            <a href="<?=$this->getUrl(['controller' => 'newpost', 'action' => 'index','topicid' => $this->getRequest()->getParam('topicid')]) ?>" class="btn btn-sm btn-primary">
                                <span class="badge">
                                    <i class="fa-solid fa-plus"></i>
                                </span><?=$this->getTrans('createNewPost') ?>
                            </a>
                            <?php if ($this->get('topicSubscription')) : ?>
                                <?php if ($this->get('isSubscribed')) : ?>
                                    <a href="<?=$this->getUrl(['action' => 'subscribe','topicid' => $this->getRequest()->getParam('topicid')]) ?>" class="btn btn-sm btn-primary">
                                    <span class="badge">
                                        <i class="fa-solid fa-bell-slash"></i>
                                    </span><?=$this->getTrans('unsubscribe') ?>
                                    </a>
                                <?php else : ?>
                                    <a href="<?=$this->getUrl(['action' => 'subscribe','topicid' => $this->getRequest()->getParam('topicid')]) ?>" class="btn btn-sm btn-primary">
                                    <span class="badge">
                                        <i class="fa-solid fa-bell"></i>
                                    </span><?=$this->getTrans('subscribe') ?>
                                    </a>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php else : ?>
                        <?php $_SESSION['redirect'] = $this->getRouter()->getQuery(); ?>
                        <a href="<?=$this->getUrl(['module' => 'user', 'controller' => 'login', 'action' => 'index']) ?>" class="btn btn-sm btn-primary">
                            <span class="badge">
                                <i class="fa-solid fa-user"></i>
                            </span><?=$this->getTrans('loginPost') ?>
                        </a>
                    <?php endif; ?>
                <?php else : ?>
                    <div class="btn btn-sm btn-primary">
                        <span class="badge">
                            <i class="fa-solid fa-lock"></i>
                        </span><?=$this->getTrans('lockPost') ?>
                    </div>
                <?php endif; ?>
                <?=$this->get('pagination')->getHtml($this, ['action' => 'index', 'topicid' => $this->getRequest()->getParam('topicid')]) ?>
            </div>
            <div class="col-xl-12">
                <div class="posts-head ilch-head">
                    <?=$this->escape($prefix . $topicpost->getTopicTitle()) ?>
                </div>
            </div>
        </div>
        <?php foreach ($posts as $post) : ?>
            <?php
            $date = new Date($post->getDateCreated());
            $reported = in_array($post->getId(), $reportedPostsIds);
            $remembered = in_array($post->getId(), $rememberedPostIds);
            ?>
            <div id="<?=$post->getId() ?>" class="post ilch-bg <?=($reported) ? 'reported' : '' ?>">
                <div class="row">
                    <div class="col-lxlg-12">
                        <div class="row">
                            <div class="col-xl-6">
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
                            <div class="col-xl-6 pull-right">
                                <div class="delete">
                                    <?php if ($this->getUser()) : ?>
                                        <?php if ($this->getUser()->isAdmin()) : ?>
                                            <p class="delete-post">
                                                <a href="<?=$this->getUrl(['controller' => 'showposts', 'action' => 'delete', 'id' => $post->getId(), 'topicid' => $this->getRequest()->getParam('topicid'), 'forumid' => $forum->getId()], null, true) ?>" id="delete" class="btn btn-primary btn-sm p-0 pe-1">
                                                    <span class="badge">
                                                        <i class="fa-regular fa-trash-can"></i>
                                                    </span><?=$this->getTrans('delete') ?>
                                                </a>
                                            </p>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                                <div class="edit">
                                    <?php if ($this->getUser()) : ?>
                                        <?php if ($this->getUser()->isAdmin() || $this->getUser()->hasAccess('module_forum') || $this->getUser()->getId() == $post->getAutor()->getId()) : ?>
                                            <p class="edit-post">
                                                <a href="<?=$this->getUrl(['controller' => 'showposts', 'action' => 'edit', 'id' => $post->getId(), 'topicid' => $this->getRequest()->getParam('topicid')]) ?>" class="btn btn-primary btn-sm p-0 pe-1">
                                                    <span class="badge">
                                                        <i class="fa-solid fa-pencil"></i>
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
                        <?=$this->alwaysPurify($post->getText()) ?>
                    </div>

                    <?php if ($post->getAutor()->getSignature()) : ?>
                        <hr />
                        <?=$this->alwaysPurify($post->getAutor()->getSignature()) ?>
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
                        <?php foreach ($post->getAutor()->getGroups() as $group) : ?>
                            <i class="forum appearance<?=$group->getId() ?>"><?=$this->escape($group->getName()) ?></i><br>
                        <?php endforeach; ?>
                        <i><?=($post->getAutorAllPost()) ? $this->escape($rankMapper->getRankByPosts($post->getAutorAllPost())->getTitle()) : '' ?></i>
                    </dd>
                    <dd>&nbsp;</dd>
                    <dd><b><?=$this->getTrans('posts') ?>:</b> <?=$post->getAutorAllPost() ?></dd>
                    <dd><b><?=$this->getTrans('joined') ?>:</b> <?=$post->getAutor()->getDateCreated() ?></dd>
                </dl>
            </div>
            <?php if ($this->getUser() || $this->get('postVoting')) : ?>
                <div class="post-footer ilch-bg">
                    <?php if ($this->get('postVoting')) : ?>
                        <?php if ($this->getUser() && !$post->isUserHasVoted()) : ?>
                            <a class="btn btn-sm btn-outline-secondary btn-hover-success text-white" href="<?=$this->getUrl(['id' => $post->getId(), 'action' => 'vote', 'topicid' => $this->getRequest()->getParam('topicid')]) ?>" title="<?=$this->getTrans('iLike') ?>">
                                <i class="fa-solid fa-thumbs-up"></i> <?=$post->getCountOfVotes() ?>
                            </a>
                        <?php elseif ($this->getUser() && $post->isUserHasVoted()) : ?>
                            <button class="btn btn-sm btn-outline-secondary btn-success text-white">
                                <i class="fa-solid fa-thumbs-up"></i> <?=$post->getCountOfVotes() ?>
                            </button>
                        <?php else : ?>
                            <button class="btn btn-sm btn-outline-secondary text-white">
                                <i class="fa-solid fa-thumbs-up"></i> <?=$post->getCountOfVotes() ?>
                            </button>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php if ($this->getUser()) : ?>
                        <div class="quote">
                            <?php if ($adminAccess || $forum->getReplyAccess()) : ?>
                                <p class="quote-post">
                                    <a href="<?=$this->getUrl(['controller' => 'newpost', 'action' => 'index','topicid' => $this->getRequest()->getParam('topicid'), 'quote' => $post->getId()]) ?>" class="btn btn-primary btn-sm p-0 pe-1">
                        <span class="badge">
                            <i class="fas fa-quote-left"></i>
                        </span><?=$this->getTrans('quote') ?>
                                    </a>
                                </p>
                            <?php endif; ?>
                        </div>
                        <?php if (!$reported && $this->get('reportingPosts')) : ?>
                            <div class="report">
                                <p class="report-post">
                                    <a href="<?=$this->getUrl(['action' => 'report','topicid' => $this->getRequest()->getParam('topicid'), 'postid' => $post->getId()]) ?>" class="btn btn-primary btn-sm p-0 pe-1">
                        <span class="badge">
                            <i class="fa-solid fa-flag"></i>
                        </span><?=$this->getTrans('report') ?>
                                    </a>
                                </p>
                            </div>
                        <?php endif; ?>
                        <?php if (!$remembered) : ?>
                            <div class="remember">
                                <p class="remember-post">
                                    <button class="btn btn-primary btn-sm p-0 pe-1" data-bs-toggle="modal" data-bs-target="#rememberDialog" data-post-id="<?=$post->getId() ?>">
                            <span class="badge">
                                <i class="fa-solid fa-bookmark"></i>
                            </span><?=$this->getTrans('remember') ?>
                                    </button>
                                </p>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
        <div class="topic-actions">
            <?php if ($topicpost->getStatus() == 0) : ?>
                <?php if ($this->getUser()) : ?>
                    <?php if ($adminAccess || is_in_array($readAccess, explode(',', $forum->getReplyAccess()))) : ?>
                        <a href="<?=$this->getUrl(['controller' => 'newpost', 'action' => 'index','topicid' => $this->getRequest()->getParam('topicid')]) ?>" class="btn btn-sm btn-primary">
                            <span class="badge">
                                <i class="fa-solid fa-plus"></i>
                            </span><?=$this->getTrans('createNewPost') ?>
                        </a>
                        <?php if ($this->get('topicSubscription')) : ?>
                            <?php if ($this->get('isSubscribed')) : ?>
                                <a href="<?=$this->getUrl(['action' => 'subscribe','topicid' => $this->getRequest()->getParam('topicid')]) ?>" class="btn btn-sm btn-primary">
                                    <span class="badge">
                                        <i class="fa-solid fa-bell-slash"></i>
                                    </span><?=$this->getTrans('unsubscribe') ?>
                                </a>
                            <?php else : ?>
                                <a href="<?=$this->getUrl(['action' => 'subscribe','topicid' => $this->getRequest()->getParam('topicid')]) ?>" class="btn btn-sm btn-primary">
                                    <span class="badge">
                                        <i class="fa-solid fa-bell"></i>
                                    </span><?=$this->getTrans('subscribe') ?>
                                </a>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php else : ?>
                    <?php $_SESSION['redirect'] = $this->getRouter()->getQuery(); ?>
                    <a href="<?=$this->getUrl(['module' => 'user', 'controller' => 'login', 'action' => 'index']) ?>" class="btn btn-primary">
                        <span class="badge">
                            <i class="fa-solid fa-user"></i>
                        </span><?=$this->getTrans('loginPost') ?>
                    </a>
                <?php endif; ?>
            <?php else : ?>
                <div class="btn btn-sm btn-primary">
                    <span class="badge">
                        <i class="fa-solid fa-lock"></i>
                    </span><?=$this->getTrans('lockPost') ?>
                </div>
            <?php endif; ?>
            <?=$this->get('pagination')->getHtml($this, ['action' => 'index', 'topicid' => $this->getRequest()->getParam('topicid')]) ?>
        </div>
    </div>

    <form id="rememberDialog_form" class="form-horizontal" method="POST" action="<?=$this->getUrl(['controller' => 'rememberedposts', 'action' => 'remember']) ?>">
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
                        <button type="submit" class="btn btn-sm btn-primary" id="submitRememberDialog" tabindex="0"><?=$this->getTrans('rememberPost') ?></button>
                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal"><?=$this->getTrans('close') ?></button>
                    </div>
                </div>
            </div>
        </div>
    </form>

<?php else : ?>
    <?php
    header('location: ' . $this->getUrl(['controller' => 'index', 'action' => 'index', 'access' => 'noaccess']));
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

    $("#rememberDialog").on('hidden.bs.modal', function () {
        $('#rememberPostStatus').empty();
        $('#rememberPostStatus').removeAttr("class");
        $('#note').val("");
    });

    $("a[id='delete']").click(function(){
        return confirm('<?=$this->getTrans('confirmDeletePost') ?>');
    });
});
</script>
