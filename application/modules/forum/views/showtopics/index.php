<link href="<?=$this->getModuleUrl('static/css/forum-style.css') ?>" rel="stylesheet">
<?php
$forum = $this->get('forum');
$forumEdit = $this->get('forumEdit');
$topics = $this->get('topics');
$topicMapper = $this->get('topicMapper');
$forumMapper = $this->get('forumMapper');
$groupIdsArray = $this->get('groupIdsArray');
$adminAccess = null;
if ($this->getUser()) {
    $adminAccess = $this->getUser()->isAdmin();
}
?>

<?php if (is_in_array($groupIdsArray, explode(',', $forum->getReadAccess())) || $adminAccess == true): ?>
    <div id="forum">
        <h3><?=$forum->getTitle() ?></h3>
        <div class="topic-actions">
            <?php if($this->getUser()): ?>
                <div class="buttons">
                    <a href="<?=$this->getUrl(array('controller' => 'newtopic', 'action' => 'index','id' => $forum->getId())) ?>" class="btn btn-labeled bgblue">
                        <span class="btn-label">
                            <i class="fa fa-plus"></i>
                        </span><?=$this->getTrans('createNewTopic') ?>
                    </a>
                </div>
            <?php else: ?>
                <?php $_SESSION['redirect'] = $this->getRouter()->getQuery(); ?>
                <div class="buttons">
                    <a href="<?=$this->getUrl(array('module' => 'user', 'controller' => 'login', 'action' => 'index')) ?>" class="btn btn-labeled bgblue">
                        <span class="btn-label">
                            <i class="fa fa-user"></i>
                        </span><?=$this->getTrans('loginTopic') ?>
                    </a>
                </div>
            <?php endif; ?>
            <?=$this->get('pagination')->getHtml($this, array('action' => 'index', 'forumid' => $this->getRequest()->getParam('forumid'))); ?>
        </div>
        <?php if ($forumEdit): ?>
            <form class="form-horizontal" method="POST" action="">
                <?php echo $this->getTokenField(); ?>
        <?php endif; ?>
        <div class="forabg">
            <ul class="topiclist">
                <li class="header">
                    <dl class="icon">
                        <dt><span class="forum-name"><?=$this->getTrans('topics') ?><span></span></span></dt>
                        <dd class="posts"><?=$this->getTrans('replies') ?></dd>
                        <dd class="views"><?=$this->getTrans('views') ?></dd>
                        <dd class="lastpost"><span><?=$this->getTrans('lastPost') ?></span></dd>
                        <dd class="forumEdit"></dd>
                    </dl>
                </li>
            </ul>
            <ul class="topiclist topics">
                <?php foreach ($topics as $topic): ?>
                    <?php $lastPost = $topicMapper->getLastPostByTopicId($topic->getId()) ?>
                    <?php $countPosts = $forumMapper->getCountPostsByTopicId($topic->getId()) ?>
                    <li class="row bg1">
                        <dl class="icon" style="
                            <?php if ($this->getUser()): ?>
                                <?php if (in_array($this->getUser()->getId(), explode(',', $lastPost->getRead()))): ?>
                                    background-image: url(<?=$this->getModuleUrl('static/img/forum_read.png') ?>);
                                <?php else: ?>
                                    background-image: url(<?=$this->getModuleUrl('static/img/topic_unread.png') ?>);
                                <?php endif; ?>
                            <?php else: ?>
                                background-image: url(<?=$this->getModuleUrl('static/img/forum_read.png') ?>);
                            <?php endif; ?>
                                background-repeat: no-repeat;">
                            <dt>
                                <a href="<?=$this->getUrl(array('controller' => 'showposts', 'action' => 'index','topicid' => $topic->getId())) ?>" class="topictitle">
                                    <?=$topic->getTopicTitle() ?>
                                </a>
                                <?php if ($topic->getType() == '1'): ?>
                                    <i class="fa fa-thumb-tack"></i>
                                <?php endif; ?>
                                <br>
                                <?=$this->getTrans('by') ?>
                                <a href="<?=$this->getUrl(array('controller' => 'showposts', 'action' => 'index','topicid' => $topic->getId())) ?>" style="color: #AA0000;" class="username-coloured">
                                    <?=$topic->getAuthor()->getName() ?>
                                </a>
                                »
                                <?=$topic->getDateCreated() ?>
                            </dt>
                            <dd class="posts"><?=$countPosts -1 ?></dd>
                            <dd class="views"><?=$topic->getVisits() ?></dd>
                            <dd class="lastpost">
                                <span>
                                    <img style="width:30px; padding-right: 5px;" src="<?=$this->getBaseUrl($lastPost->getAutor()->getAvatar()) ?>">
                                    <?=$this->getTrans('by') ?>
                                    <a href="<?=$this->getUrl(array('module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $lastPost->getAutor()->getId())) ?>">
                                        <?=$lastPost->getAutor()->getName() ?>
                                    </a>
                                    <a href="<?=$this->getUrl(array('controller' => 'showposts', 'action' => 'index','topicid' => $lastPost->getTopicId(), 'page' => $lastPost->getPage())) ?>#<?=$lastPost->getId()?>">
                                        <img src="<?=$this->getModuleUrl('static/img/icon_topic_latest.png') ?>" alt="<?=$this->getTrans('viewLastPost') ?>" title="<?=$this->getTrans('viewLastPost') ?>" height="10" width="12">
                                    </a>
                                    <br>
                                    <?=$lastPost->getDateCreated() ?>
                                </span>
                            </dd>
                            <?php if ($forumEdit): ?>
                                <dd><input value="<?=$topic->getId() ?>" type="checkbox" name="check_comments[]" /></dd>
                            <?php endif; ?>
                        </dl>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="topic-actions">
            <?php if($adminAccess): ?>
                <?php if (!$forumEdit): ?>
                    <form action="" method="post">
                        <?php echo $this->getTokenField(); ?>
                        <button name="forumEdit" value="forumEdit" class="btn btn-default"><?=$this->getTrans('forumEdit') ?></button>
                    </form>
                <?php else: ?>
                    <button class="btn btn-labeled bgblue" name="topicDelete"><?=$this->getTrans('topicDelete') ?></button>
                    <button class="btn btn-labeled bgblue" name="topicMove"><?=$this->getTrans('topicMove') ?></button>
                    <button class="btn btn-labeled bgblue" name="topicChangeStatus"><?=$this->getTrans('topicChangeStatus') ?></button>
                <?php endif; ?>
            <?php endif; ?>
            <?=$this->get('pagination')->getHtml($this, array('action' => 'index', 'forumid' => $this->getRequest()->getParam('forumid'))) ?>
        </div>
        <?php if ($forumEdit): ?>
            </form>
        <?php endif; ?>
    </div>
<?php else: ?>
    <?php
    header("location: ".$this->getUrl(array('controller' => 'index', 'action' => 'index', 'access' => 'noaccess')));
    exit;
    ?>
<?php endif; ?>