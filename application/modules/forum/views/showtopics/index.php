<?php

/** @var \Ilch\View $this */

/** @var \Modules\Forum\Models\ForumItem $forum */

use Ilch\Date;

$forum = $this->get('forum');
/** @var \Modules\Forum\Models\ForumItem $cat */
$cat = $this->get('cat');
/** @var bool $forumEdit */
$forumEdit = $this->get('forumEdit');
/** @var \Modules\Forum\Models\ForumTopic[]|null $topics */
$topics = $this->get('topics');
/** @var \Modules\Forum\Models\ForumPost[]|null $posts */
$posts = $this->get('posts');
/** @var array $postTopicRelation */
$postTopicRelation = $this->get('postTopicRelation');
$adminAccess = null;
if ($this->getUser()) {
    $adminAccess = $this->getUser()->isAdmin();
}
/** @var bool $DESCPostorder */
$DESCPostorder = $this->get('DESCPostorder');
/** @var int $postsPerPage */
$postsPerPage = $this->get('postsPerPage');

/** @var \Ilch\Pagination $pagination */
$pagination = $this->get('pagination');
?>
<link href="<?=$this->getModuleUrl('static/css/forum.css') ?>" rel="stylesheet">

<?php if ($adminAccess || $forum->getReadAccess()) : ?>
    <div id="forum">
        <h1>
            <a href="<?=$this->getUrl(['controller' => 'index', 'action' => 'index']) ?>"><?=$this->getTrans('forum') ?></a>
            <i class="fa-solid fa-chevron-right"></i> <a href="<?=$this->getUrl(['controller' => 'showcat', 'action' => 'index', 'id' => $cat->getId()]) ?>"><?=$cat->getTitle() ?></a>
            <i class="fa-solid fa-chevron-right"></i> <?=$forum->getTitle() ?>
        </h1>
        <div class="topic-actions">
            <?php if ($this->getUser()) : ?>
                <a href="<?=$this->getUrl(['controller' => 'newtopic', 'action' => 'index', 'id' => $forum->getId()]) ?>" class="btn btn-sm btn-primary">
                    <span class="badge">
                        <i class="fa-solid fa-plus"></i>
                    </span><?=$this->getTrans('createNewTopic') ?>
                </a>
            <?php else : ?>
                <?php $_SESSION['redirect'] = $this->getRouter()->getQuery(); ?>
                <a href="<?=$this->getUrl(['module' => 'user', 'controller' => 'login', 'action' => 'index']) ?>" class="btn btn-sm btn-primary">
                    <span class="btn-label">
                        <i class="fa-solid fa-user"></i>
                    </span><?=$this->getTrans('loginTopic') ?>
                </a>
            <?php endif; ?>
            <?=$pagination->getHtml($this, ['action' => 'index', 'forumid' => $this->getRequest()->getParam('forumid')]) ?>
        </div>
        <?php if ($forumEdit) : ?>
            <form name="editForm" method="POST">
                <?=$this->getTokenField() ?>
        <?php endif; ?>
        <div class="forabg">
            <ul class="topiclist">
                <li class="header">
                    <dl class="title ilch-head">
                        <dt><?=$this->getTrans('topics') ?></dt>
                        <dd class="posts"><?=$this->getTrans('replies') ?> / <?=$this->getTrans('views') ?></dd>
                        <dd class="lastpost"><span><?=$this->getTrans('lastPost') ?></span></dd>
                        <?php if ($forumEdit) : ?>
                            <dd class="forumEdit"><input type="checkbox" class="check_all" id="allTopics" data-childs="check_topics" /></dd>
                        <?php endif; ?>
                    </dl>
                </li>
            </ul>
            <ul class="topiclist topics">
                <?php if (!empty($topics)) : ?>
                    <?php foreach ($topics as $topic) : ?>
                        <?php $lastPost = $posts[$postTopicRelation[$topic->getId()]] ?>
                        <li class="row ilch-border ilch-bg--hover <?=($topic->getType() == '1') ? 'tack' : '' ?>">
                            <dl class="icon
                                <?php if ($this->getUser()) : ?>
                                    <?php if ($topic->getStatus() == 0 && $lastPost->getRead()) : ?>
                                        topic-read
                                    <?php elseif ($topic->getStatus() == 1 && $lastPost->getRead()) : ?>
                                        topic-read-locked
                                    <?php elseif ($topic->getStatus() == 1) : ?>
                                        topic-unread-locked
                                    <?php else : ?>
                                        topic-unread
                                    <?php endif; ?>
                                <?php elseif ($topic->getStatus() == 1) : ?>
                                    topic-read-locked
                                <?php else : ?>
                                    topic-read
                                <?php endif; ?>
                            ">
                                <dt>
                                    <?php
                                    if ($forum->getPrefixes() != '' && $topic->getTopicPrefix()->getId() > 0) {
                                        $prefixIds = explode(',', $forum->getPrefixes());
                                        array_unshift($prefixIds, '');

                                        foreach ($prefixIds as $prefixId) {
                                            if ($topic->getTopicPrefix()->getId() == $prefixId) {
                                                echo '<span class="label label-default">' . $this->escape($topic->getTopicPrefix()->getPrefix()) . '</span>';
                                                break;
                                            }
                                        }
                                    }
                                    ?>
                                    <a href="<?=$this->getUrl(['controller' => 'showposts', 'action' => 'index', 'topicid' => $topic->getId()]) ?>" class="topictitle">
                                        <?=$this->escape($topic->getTopicTitle()) ?>
                                    </a>
                                    <?php if ($topic->getType() == '1') : ?>
                                        <i class="fa-solid fa-thumbtack"></i>
                                    <?php endif; ?>
                                    <br>
                                    <div class="small">
                                        <?=$this->getTrans('by') ?>
                                        <a href="<?=$this->getUrl(['module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $topic->getAuthor()->getId()]) ?>" class="ilch-link-red">
                                            <?=$this->escape($topic->getAuthor()->getName()) ?>
                                        </a>
                                        »
                                        <?php $date = new Date($topic->getDateCreated()); ?>
                                        <?=$date->format('d.m.y - H:i', true) ?>
                                    </div>
                                </dt>
                                <dd class="posts small">
                                    <div class="float-start text-nowrap stats">
                                        <?=$this->getTrans('replies') ?>:
                                        <br />
                                        <?=$this->getTrans('views') ?>:
                                    </div>
                                    <div class="float-start">
                                        <?=$topic->getCountPosts() - 1 ?>
                                        <br />
                                        <?=$topic->getVisits() ?>
                                    </div>
                                </dd>
                                <dd class="lastpost small">
                                    <div class="float-start">
                                        <a href="<?=$this->getUrl(['module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $lastPost->getAutor()->getId()]) ?>" title="<?=$this->escape($lastPost->getAutor()->getName()) ?>">
                                            <img style="width:40px; padding-right: 5px;" src="<?=$this->getBaseUrl($lastPost->getAutor()->getAvatar()) ?>" alt="<?=$this->escape($lastPost->getAutor()->getName()) ?>">
                                        </a>
                                    </div>
                                    <div class="float-start">
                                        <?=$this->getTrans('by') ?>
                                        <a href="<?=$this->getUrl(['module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $lastPost->getAutor()->getId()]) ?>" title="<?=$this->escape($lastPost->getAutor()->getName()) ?>">
                                            <?=$this->escape($lastPost->getAutor()->getName()) ?>
                                        </a>
                                        <a href="<?=$this->getUrl(['controller' => 'showposts', 'action' => 'index','topicid' => $topic->getId(), 'page' => ($DESCPostorder ? 1 : ceil($topic->getCountPosts() / $postsPerPage))]) ?>#<?=$lastPost->getId() ?>">
                                            <img src="<?=$this->getModuleUrl('static/img/icon_topic_latest.png') ?>" alt="<?=$this->getTrans('viewLastPost') ?>" title="<?=$this->getTrans('viewLastPost') ?>" height="10" width="12">
                                        </a>
                                        <br>
                                        <?php $date = new Date($lastPost->getDateCreated()); ?>
                                        <?=$date->format('d.m.y - H:i', true) ?>
                                    </div>
                                </dd>
                                <?php if ($forumEdit) : ?>
                                    <dd class="forumEdit"><input type="checkbox" name="check_topics[]" value="<?=$topic->getId() ?>" /></dd>
                                <?php endif; ?>
                            </dl>
                        </li>
                    <?php endforeach; ?>
                <?php else : ?>
                    <li class="row ilch-border text-center"><?=$this->getTrans('noThreads') ?></li>
                <?php endif; ?>
            </ul>
        </div>
        <div class="topic-actions">
            <?php if ($this->getUser()) : ?>
                <a href="<?=$this->getUrl(['controller' => 'newtopic', 'action' => 'index','id' => $forum->getId()]) ?>" class="btn btn-sm btn-primary">
                    <span class="badge">
                        <i class="fa-solid fa-plus"></i>
                    </span><?=$this->getTrans('createNewTopic') ?>
                </a>
            <?php else : ?>
                <?php $_SESSION['redirect'] = $this->getRouter()->getQuery(); ?>
                <a href="<?=$this->getUrl(['module' => 'user', 'controller' => 'login', 'action' => 'index']) ?>" class="btn btn-primary">
                    <span class="btn-label">
                        <i class="fa-solid fa-user"></i>
                    </span><?=$this->getTrans('loginTopic') ?>
                </a>
            <?php endif; ?>
            <?=$pagination->getHtml($this, ['action' => 'index', 'forumid' => $this->getRequest()->getParam('forumid')]) ?>
        </div>
        <div class="topic-actions">
            <?php if ($adminAccess || ($this->getUser() && $this->getUser()->hasAccess('module_forum'))) : ?>
                <?php if (!$forumEdit) : ?>
                    <form method="post">
                        <?=$this->getTokenField() ?>
                        <button class="btn btn-outline-secondary" name="forumEdit" value="forumEdit"><?=$this->getTrans('forumEdit') ?></button>
                    </form>
                <?php else : ?>
                    <button class="btn btn-sm btn-primary" name="topicDelete" value="topicDelete" id="topicDelete" OnClick="SetAction1()" disabled><?=$this->getTrans('topicDelete') ?></button>
                    <button class="btn btn-sm btn-primary" name="topicMove" value="topicMove" id="topicMove" OnClick="SetAction2()" disabled><?=$this->getTrans('topicMove') ?></button>
                    <button class="btn btn-sm btn-primary" name="topicChangeStatus" value="topicChangeStatus" id="topicChangeStatus" OnClick="SetAction3()" disabled><?=$this->getTrans('topicChangeStatus') ?></button>
                    <button class="btn btn-sm btn-primary" name="topicChangeType" value="topicChangeType" id="topicChangeType" OnClick="SetAction4()" disabled><?=$this->getTrans('topicChangeType') ?></button>

                    <script>
                        function SetAction1() {
                            document.forms["editForm"].action = "<?=$this->getUrl(['controller' => 'showtopics', 'action' => 'delete', 'forumid' => $forum->getId()]) ?>";
                        }

                        function SetAction2() {
                            document.forms["editForm"].action = "<?=$this->getUrl(['controller' => 'edittopic', 'action' => 'index', 'forumid' => $forum->getId()]) ?>";
                        }

                        function SetAction3() {
                            document.forms["editForm"].action = "<?=$this->getUrl(['controller' => 'edittopic', 'action' => 'status', 'forumid' => $forum->getId()]) ?>";
                        }

                        function SetAction4() {
                            document.forms["editForm"].action = "<?=$this->getUrl(['controller' => 'edittopic', 'action' => 'type', 'forumid' => $forum->getId()]) ?>";
                        }
                    </script>
                <?php endif; ?>
            <?php endif; ?>
            <?php if ($this->getUser()) : ?>
                <div class="float-end foren-actions">
                    <a href="<?=$this->getUrl(['controller' => 'showtopics', 'action' => 'marktopicsasread', 'forumid' => $this->getRequest()->getParam('forumid')], null, true) ?>" class="ilch-link"><?=$this->getTrans('markTopicsAsRead') ?></a>
                </div>
            <?php endif; ?>
        </div>
        <?php if ($forumEdit) : ?>
            </form>
        <?php endif; ?>
    </div>
<?php else : ?>
    <?php
    header('location: ' . $this->getUrl(['controller' => 'index', 'action' => 'index', 'access' => 'noaccess']));
    exit;
    ?>
<?php endif; ?>

<script>
$('.row.tack').last().addClass('last');

$('input[type=checkbox]').change(function () {
    let checked = false,
        checkedBoxes = 0,
        checkboxes = document.getElementsByName('check_topics[]'),
        allCheckbox = document.getElementById('allTopics');

    if (allCheckbox.checked) {
        checked = true;
    }

    for (i = 0; i < checkboxes.length; i++) {
        if (checkboxes[i].checked) {
            checked = true;
            checkedBoxes++;
        }
    }

    if (checkedBoxes === 0) {
        checked = false;
    }

    if (!checked) {
        $('#topicDelete').prop('disabled', true);
        $('#topicMove').prop('disabled', true);
        $('#topicChangeStatus').prop('disabled', true);
        $('#topicChangeType').prop('disabled', true);
    } else {
        $('#topicDelete').prop('disabled', false);
        $('#topicMove').prop('disabled', false);
        $('#topicChangeStatus').prop('disabled', false);
        $('#topicChangeType').prop('disabled', false);
    }
});
</script>
