<?php

use Modules\Forum\Mappers\Forum;

/** @var Forum $forumMapper */
$forumMapper = $this->get('forumMapper');
$forumItems = $this->get('forumItems');
$cat = $this->get('cat');
$readAccess = $this->get('readAccess');

function rec($item, $forumMapper, $obj, $readAccess)
{
    $DESCPostorder = $obj->get('DESCPostorder');
    $postsPerPage = $obj->get('postsPerPage');
    $subItems = $item->getSubItems();
    $topics = $item->getTopics();
    $lastPost = $item->getLastPost();
    $posts = $item->getPosts();
    $adminAccess = null;
    if ($obj->getUser()) {
        $adminAccess = $obj->getUser()->isAdmin();
    }
?>
    <?php if ($item->getType() === 0): ?>
        <ul class="forenlist">
            <li class="header">
                <dl class="title ilch-head">
                    <dt>
                        <a href="<?=$obj->getUrl(['controller' => 'showcat', 'action' => 'index','id' => $item->getId()]) ?>">
                            <?=$obj->escape($item->getTitle()) ?>
                        </a>
                    </dt>
                </dl>
                <?php if ($item->getDesc() != ''): ?>
                    <dl class="desc small">
                        <?=$obj->escape($item->getDesc()) ?>
                    </dl>
                <?php endif; ?>
            </li>
        </ul>
    <?php endif; ?>

    <?php if ($adminAccess || is_in_array($readAccess, explode(',', $item->getReadAccess()))): ?>
        <?php if ($item->getType() != 0): ?>
            <ul class="forenlist forums">
                <li class="row ilch-border ilch-bg--hover">
                    <dl class="icon 
                        <?php if ($obj->getUser()): ?>
                            <?php if (!in_array($item->getId(), $obj->get('containsUnreadTopics'))): ?>
                                topic-read
                            <?php else: ?>
                                topic-unread
                            <?php endif; ?>
                        <?php else: ?>
                            topic-read
                        <?php endif; ?>
                    ">
                        <dt>
                            <a href="<?=$obj->getUrl(['controller' => 'showtopics', 'action' => 'index','forumid' => $item->getId()]) ?>">
                                <?=$item->getTitle() ?>
                            </a>
                            <br>
                            <div class="small">
                                <?=$item->getDesc() ?>
                            </div>
                        </dt>
                        <dd class="posts small">
                            <div class="pull-left text-nowrap stats">
                                <?=$obj->getTrans('topics') ?>:
                                <br />
                                <?=$obj->getTrans('posts') ?>:
                            </div>
                            <div class="pull-left text-justify">
                                <?=$topics ?>
                                <br />
                                <?=$posts ?>
                            </div>
                        </dd>
                        <dd class="lastpost small">
                            <?php if ($lastPost): ?>
                                <?php $countPosts = $forumMapper->getCountPostsByTopicId($lastPost->getTopicId()) ?>
                                <div class="pull-left">
                                    <a href="<?=$obj->getUrl(['module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $lastPost->getAutor()->getId()]) ?>" title="<?=$obj->escape($lastPost->getAutor()->getName()) ?>">
                                        <img style="width:40px; padding-right: 5px;" src="<?=$obj->getBaseUrl($lastPost->getAutor()->getAvatar()) ?>" alt="<?=$obj->escape($lastPost->getAutor()->getName()) ?>">
                                    </a>
                                </div>
                                <div class="pull-left">
                                    <a href="<?=$obj->getUrl(['controller' => 'showposts', 'action' => 'index','topicid' => $lastPost->getTopicId()]) ?>">
                                        <?=$obj->escape($lastPost->getTopicTitle()) ?>
                                    </a>
                                    <br>
                                    <?=$obj->getTrans('by') ?>
                                    <a href="<?=$obj->getUrl(['module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $lastPost->getAutor()->getId()]) ?>" title="<?=$obj->escape($lastPost->getAutor()->getName()) ?>">
                                        <?=$obj->escape($lastPost->getAutor()->getName()) ?>
                                    </a>
                                    <a href="<?=$obj->getUrl(['controller' => 'showposts', 'action' => 'index','topicid' => $lastPost->getTopicId(), 'page' => ($DESCPostorder ? 1 : ceil($countPosts / $postsPerPage))]) ?>#<?=$lastPost->getId() ?>">
                                        <img src="<?=$obj->getModuleUrl('static/img/icon_topic_latest.png') ?>" alt="<?=$obj->getTrans('viewLastPost') ?>" title="<?=$obj->getTrans('viewLastPost') ?>" height="10" width="12">
                                    </a>
                                    <br>
                                    <?=$lastPost->getDateCreated() ?>
                                </div>
                            <?php endif; ?>
                        </dd>
                    </dl>
                </li>
            </ul>
        <?php endif; ?>
    <?php endif; ?>
    <?php
    if (!empty($subItems)) {
        foreach ($subItems as $subItem) {
            rec($subItem, $forumMapper, $obj, $readAccess);
        }
    }
}
?>

<link href="<?=$this->getModuleUrl('static/css/forum.css') ?>" rel="stylesheet">

<div id="forum">
    <h1>
        <a href="<?=$this->getUrl(['controller' => 'index', 'action' => 'index']) ?>"><?=$this->getTrans('forum') ?></a>
        <i class="fa-solid fa-chevron-right"></i> <?=$cat->getTitle() ?>
    </h1>
    <?php
    $adminAccess = null;
    if ($this->getUser()) {
        $adminAccess = $this->getUser()->isAdmin();
    }
    $subItemsFalse = false;
        foreach ($forumItems as $subItem) {
            if ($adminAccess || is_in_array($readAccess, explode(',', $subItem->getReadAccess()))) {
                $subItemsFalse = true;
            }
        }
    ?>
    <?php if (!empty($forumItems) && $subItemsFalse): ?>
        <div class="forabg">
            <ul class="forenlist">
                <li class="header">
                    <dl class="title ilch-head">
                        <dt>
                            <a href="<?=$this->getUrl(['controller' => 'showcat', 'action' => 'index', 'id' => $cat->getId()]) ?>">
                                <?=$cat->getTitle() ?>
                            </a>
                        </dt>
                    </dl>
                    <?php if ($cat->getDesc() != ''): ?>
                        <dl class="desc small ilch-bg ilch-border">
                            <?=$cat->getDesc() ?>
                        </dl>
                    <?php endif; ?>
                </li>
            </ul>
            <?php
            foreach ($forumItems as $item) {
                rec($item, $forumMapper, $this, $readAccess);
            }
            ?>
        </div>
    <?php else: ?>
        <?php header('location: ' .$this->getUrl(['controller' => 'index', 'action' => 'index']));
        exit; ?>
    <?php endif; ?>
    <div class="topic-actions">
    <?php if ($this->getUser()): ?>
        <div class="pull-right">
            <a href="<?=$this->getUrl(['controller' => 'showcat', 'action' => 'markallasread', 'id' => $this->getRequest()->getParam('id')], null, true) ?>" class="ilch-link"><?=$this->getTrans('markAllAsRead') ?></a>
        </div>
    <?php endif; ?>
    </div>
</div>
