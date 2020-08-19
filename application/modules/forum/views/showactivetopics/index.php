<?php
$topics = $this->get('topics');
$forumMapper = $this->get('forumMapper');
$topicMapper = $this->get('topicMapper');
$postMapper = $this->get('postMapper');
$date = new \Ilch\Date();
$dateLessHours = new \Ilch\Date('-1 day');
$groupIdsArray = $this->get('groupIdsArray');
$adminAccess = null;
if ($this->getUser()) {
    $adminAccess = $this->getUser()->isAdmin();
}
$DESCPostorder = $this->get('DESCPostorder');
$postsPerPage = $this->get('postsPerPage');
?>

<link href="<?=$this->getModuleUrl('static/css/forum.css') ?>" rel="stylesheet">

<div id="forum">
    <h1>
        <a href="<?=$this->getUrl(['controller' => 'index', 'action' => 'index']) ?>"><?=$this->getTrans('forum') ?></a>
        <i class="fa fa-chevron-right"></i> <?=$this->getTrans('showActiveTopics') ?>
    </h1>
    <div class="forabg">
        <ul class="topiclist">
            <li class="header">
                <dl class="title ilch-head">
                    <dt><?=$this->getTrans('topics') ?></dt>
                    <dd class="posts"><?=$this->getTrans('replies') ?> / <?=$this->getTrans('views') ?></dd>
                    <dd class="lastpost"><span><?=$this->getTrans('lastPost') ?></span></dd>
                </dl>
            </li>
        </ul>
        <ul class="topiclist topics">
            <?php foreach ($topics as $topic): ?>
                <?php $forum = $forumMapper->getForumById($topic->getForumId()); ?>
                <?php $forumPrefix = $forumMapper->getForumByTopicId($topic->getId()) ?>
                <?php $lastPost = $topicMapper->getLastPostByTopicId($topic->getId()) ?>
                <?php if ($adminAccess == true || is_in_array($groupIdsArray, explode(',', $forum->getReadAccess()))): ?>
                    <?php $countPosts = $forumMapper->getCountPostsByTopicId($topic->getId()) ?>
                    <?php if ($lastPost->getDateCreated() < $date->format('Y-m-d H:i:s', true) && $lastPost->getDateCreated() > $dateLessHours->format('Y-m-d H:i:s', true)): ?>
                        <li class="row ilch-border ilch-bg--hover">
                            <dl class="icon 
                                <?php if ($this->getUser()): ?>
                                    <?php if ($topic->getStatus() == 0 && in_array($this->getUser()->getId(), explode(',', $lastPost->getRead()))): ?>
                                        topic-read
                                    <?php elseif ($topic->getStatus() == 1 && in_array($this->getUser()->getId(), explode(',', $lastPost->getRead()))): ?>
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
                                <dt>
                                    <?php
                                    if ($forumPrefix->getPrefix() != '' && $topic->getTopicPrefix() > 0) {
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
                                        <a href="<?=$this->getUrl(['module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $topic->getAuthor()->getId()]) ?>" class="ilch-link-red">
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
                                        <a href="<?=$this->getUrl(['controller' => 'showposts', 'action' => 'index','topicid' => $lastPost->getTopicId(), 'page' => ($DESCPostorder?1:ceil($countPosts/$postsPerPage))]) ?>#<?=$lastPost->getId() ?>">
                                            <img src="<?=$this->getModuleUrl('static/img/icon_topic_latest.png') ?>" alt="<?=$this->getTrans('viewLastPost') ?>" title="<?=$this->getTrans('viewLastPost') ?>" height="10" width="12">
                                        </a>
                                        <br>
                                        <?=$lastPost->getDateCreated() ?>
                                    </div>
                                </dd>
                            </dl>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
