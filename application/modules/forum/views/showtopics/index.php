<?php
$forum = $this->get('forum');
$cat = $this->get('cat');
$forumEdit = $this->get('forumEdit');
$topics = $this->get('topics');
$topicMapper = $this->get('topicMapper');
$forumMapper = $this->get('forumMapper');
$postMapper = $this->get('postMapper');
$groupIdsArray = $this->get('groupIdsArray');
$adminAccess = null;
if ($this->getUser()) {
    $adminAccess = $this->getUser()->isAdmin();
    $userAccess = $this->get('userAccess');
}
?>

<link href="<?=$this->getModuleUrl('static/css/forum.css') ?>" rel="stylesheet">

<h1>
    <a href="<?=$this->getUrl(['controller' => 'index', 'action' => 'index']) ?>"><?=$this->getTrans('forum') ?></a>
    <i class="forum fa fa-chevron-right"></i> <a href="<?=$this->getUrl(['controller' => 'showcat', 'action' => 'index', 'id' => $cat->getId()]) ?>"><?=$cat->getTitle() ?></a>
    <i class="forum fa fa-chevron-right"></i> <?=$forum->getTitle() ?>
</h1>
<?php if (is_in_array($groupIdsArray, explode(',', $forum->getReadAccess())) || $adminAccess == true): ?>
    <div id="forum">
        <div class="topic-actions">
            <?php if ($this->getUser()): ?>
                <div class="buttons">
                    <a href="<?=$this->getUrl(['controller' => 'newtopic', 'action' => 'index','id' => $forum->getId()]) ?>" class="btn btn-primary btn-labeled">
                        <span class="btn-label">
                            <i class="fa fa-plus"></i>
                        </span><?=$this->getTrans('createNewTopic') ?>
                    </a>
                </div>
            <?php else: ?>
                <?php $_SESSION['redirect'] = $this->getRouter()->getQuery(); ?>
                <div class="buttons">
                    <a href="<?=$this->getUrl(['module' => 'user', 'controller' => 'login', 'action' => 'index']) ?>" class="btn btn-primary btn-labeled">
                        <span class="btn-label">
                            <i class="fa fa-user"></i>
                        </span><?=$this->getTrans('loginTopic') ?>
                    </a>
                </div>
            <?php endif; ?>
            <?=$this->get('pagination')->getHtml($this, ['action' => 'index', 'forumid' => $this->getRequest()->getParam('forumid')]); ?>
        </div>
        <?php if ($forumEdit): ?>
            <form class="form-horizontal" name="editForm" method="POST" action="">
                <?=$this->getTokenField(); ?>
        <?php endif; ?>
        <div class="forabg">
            <ul class="topiclist">
                <li class="header">
                    <dl class="title ilch-head">
                        <dt><?=$this->getTrans('topics') ?></dt>
                        <dd class="posts"><?=$this->getTrans('replies') ?> / <?=$this->getTrans('views') ?></dd>
                        <dd class="lastpost"><span><?=$this->getTrans('lastPost') ?></span></dd>
                        <?php if ($forumEdit): ?>
                            <dd class="forumEdit"><?=$this->getCheckAllCheckbox('check_topics') ?></dd>
                        <?php endif; ?>
                    </dl>
                </li>
            </ul>
            <ul class="topiclist topics">
                <?php if (!empty($topics)): ?>
                    <?php foreach ($topics as $topic): ?>
                        <?php $firstPost = $postMapper->getPostByTopicId($topic->getId()) ?>
                        <?php $lastPost = $topicMapper->getLastPostByTopicId($topic->getId()) ?>
                        <?php $countPosts = $forumMapper->getCountPostsByTopicId($topic->getId()) ?>
                        <?php $forumPrefix = $forumMapper->getForumByTopicId($topic->getId()) ?>
                        <li class="row ilch-border ilch-bg--hover <?php if ($topic->getType() == '1') { echo 'tack'; } ?>">
                            <dl class="icon
                                <?php if ($this->getUser()): ?>
                                    <?php if (in_array($this->getUser()->getId(), explode(',', $lastPost->getRead())) AND $topic->getStatus() == 0): ?>
                                        topic-read
                                    <?php elseif (in_array($this->getUser()->getId(), explode(',', $lastPost->getRead())) AND $topic->getStatus() == 1): ?>
                                        topic-read-locked
                                    <?php elseif ($topic->getStatus() == 1): ?>
                                        topic-unread-locked
                                    <?php else: ?>
                                        topic-unread
                                    <?php endif; ?>
                                <?php elseif ($topic->getStatus() == 1): ?>
                                    topic-read-locked
                                <?php else: ?>
                                    topic-read
                                <?php endif; ?>
                            ">
                                <dt title="<?=$firstPost[0]->getText() ?>">
                                    <?php
                                    if ($forumPrefix->getPrefix() != '' AND $topic->getTopicPrefix() > 0) {
                                        $prefix = explode(',', $forumPrefix->getPrefix());
                                        array_unshift($prefix, '');

                                        foreach ($prefix as $key => $value) {
                                            if ($topic->getTopicPrefix() == $key) {
                                                echo '<span class="label label-default">'.$value.'</span>';
                                            }
                                        }
                                    }
                                    ?>
                                    <a href="<?=$this->getUrl(['controller' => 'showposts', 'action' => 'index','topicid' => $topic->getId()]) ?>" class="topictitle">
                                        <?=$topic->getTopicTitle() ?>
                                    </a>
                                    <?php if ($topic->getType() == '1'): ?>
                                        <i class="fa fa-thumb-tack"></i>
                                    <?php endif; ?>
                                    <br>
                                    <div class="small">
                                        <?=$this->getTrans('by') ?>
                                        <a href="<?=$this->getUrl(['controller' => 'showposts', 'action' => 'index','topicid' => $topic->getId()]) ?>" class="ilch-link-red">
                                            <?=$this->escape($topic->getAuthor()->getName()) ?>
                                        </a>
                                        »
                                        <?=$topic->getDateCreated() ?>
                                    </div>
                                </dt>
                                <dd class="posts small">
                                    <div class="pull-left text-nowrap stats">
                                        <?=$this->getTrans('replies') ?>:
                                        <br />
                                        <?=$this->getTrans('views') ?>:
                                    </div>
                                    <div class="pull-left text-justify">
                                        <?=$countPosts -1 ?>
                                        <br />
                                        <?=$topic->getVisits() ?>
                                    </div>
                                </dd>
                                <dd class="lastpost small">
                                    <div class="pull-left">
                                        <a href="<?=$this->getUrl(['module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $lastPost->getAutor()->getId()]) ?>" title="<?=$this->escape($lastPost->getAutor()->getName()) ?>">
                                            <img style="width:40px; padding-right: 5px;" src="<?=$this->getBaseUrl($lastPost->getAutor()->getAvatar()) ?>">
                                        </a>
                                    </div>
                                    <div class="pull-left">
                                        <?=$this->getTrans('by') ?>
                                        <a href="<?=$this->getUrl(['module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $lastPost->getAutor()->getId()]) ?>" title="<?=$this->escape($lastPost->getAutor()->getName()) ?>">
                                            <?=$this->escape($lastPost->getAutor()->getName()) ?>
                                        </a>
                                        <a href="<?=$this->getUrl(['controller' => 'showposts', 'action' => 'index','topicid' => $lastPost->getTopicId(), 'page' => $lastPost->getPage()]) ?>#<?=$lastPost->getId() ?>">
                                            <img src="<?=$this->getModuleUrl('static/img/icon_topic_latest.png') ?>" alt="<?=$this->getTrans('viewLastPost') ?>" title="<?=$this->getTrans('viewLastPost') ?>" height="10" width="12">
                                        </a>
                                        <br>
                                        <?=$lastPost->getDateCreated() ?>
                                    </div>
                                </dd>
                                <?php if ($forumEdit): ?>
                                    <dd class="forumEdit"><?=$this->getDeleteCheckbox('check_topics', $topic->getId()) ?></dd>
                                <?php endif; ?>
                            </dl>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li class="row text-center"><?=$this->getTrans('noThreads') ?></li>
                <?php endif; ?>
            </ul>
        </div>
        <div class="topic-actions">
            <?php if ($this->getUser()): ?>
                <div class="buttons">
                    <a href="<?=$this->getUrl(['controller' => 'newtopic', 'action' => 'index','id' => $forum->getId()]) ?>" class="btn btn-primary btn-labeled">
                        <span class="btn-label">
                            <i class="fa fa-plus"></i>
                        </span><?=$this->getTrans('createNewTopic') ?>
                    </a>
                </div>
            <?php else: ?>
                <?php $_SESSION['redirect'] = $this->getRouter()->getQuery(); ?>
                <div class="buttons">
                    <a href="<?=$this->getUrl(['module' => 'user', 'controller' => 'login', 'action' => 'index']) ?>" class="btn btn-primary btn-labeled">
                        <span class="btn-label">
                            <i class="fa fa-user"></i>
                        </span><?=$this->getTrans('loginTopic') ?>
                    </a>
                </div>
            <?php endif; ?>
            <?=$this->get('pagination')->getHtml($this, ['action' => 'index', 'forumid' => $this->getRequest()->getParam('forumid')]) ?>
        </div>
        <div class="topic-actions">
            <?php if ($adminAccess || (!empty($userAccess) AND $userAccess->hasAccess('forum'))): ?>
                <?php if (!$forumEdit): ?>
                    <form action="" method="post">
                        <?=$this->getTokenField() ?>
                        <button class="btn btn-default" name="forumEdit" value="forumEdit"><?=$this->getTrans('forumEdit') ?></button>
                    </form>
                <?php else: ?>
                    <button class="btn btn-primary" name="topicDelete" value="topicDelete" OnClick="SetAction1()"><?=$this->getTrans('topicDelete') ?></button>
                    <button class="btn btn-primary" name="topicMove" value="topicMove" OnClick="SetAction2()"><?=$this->getTrans('topicMove') ?></button>
                    <button class="btn btn-primary" name="topicChangeStatus" value="topicChangeStatus" OnClick="SetAction3()"><?=$this->getTrans('topicChangeStatus') ?></button>

                    <script type="text/javascript">
                        function SetAction1() {
                            document.forms["editForm"].action = "<?=$this->getUrl(['controller' => 'showtopics', 'action' => 'delete', 'forumid' => $forum->getId()]) ?>";
                        }

                        function SetAction2() {
                            document.forms["editForm"].action = "<?=$this->getUrl(['controller' => 'edittopic', 'action' => 'index', 'forumid' => $forum->getId()]) ?>";
                        }

                        function SetAction3() {
                            document.forms["editForm"].action = "<?=$this->getUrl(['controller' => 'edittopic', 'action' => 'status', 'forumid' => $forum->getId()]) ?>";
                        }
                    </script>
                <?php endif; ?>
            <?php endif; ?>
        </div>
        <?php if ($forumEdit): ?>
            </form>
        <?php endif; ?>
    </div>
<?php else: ?>
    <?php
    header("location: ".$this->getUrl(['controller' => 'index', 'action' => 'index', 'access' => 'noaccess']));
    exit;
    ?>
<?php endif; ?>

<script>
$('.row.tack').last().addClass('last');
</script>
