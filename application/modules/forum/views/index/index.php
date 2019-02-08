<?php
$forumItems = $this->get('forumItems');
$readAccess = $this->get('groupIdsArray');
$usersOnlineList = $this->get('usersOnlineList');
$usersWhoWasOnline = $this->get('usersWhoWasOnline');
$usersOnline = $this->get('usersOnline');
$guestOnline = $this->get('guestOnline');
$forumStatistics = $this->get('forumStatics');
$ins = $usersOnline+$guestOnline;

function rec($item, $obj, $readAccess, $i)
{
    $subItems = $item->getSubItems();
    $topics = $item->getTopics();
    $lastPost = $item->getLastPost();
    $posts = $item->getPosts();
    $adminAccess = null;
    if ($obj->getUser()) {
        $adminAccess = $obj->getUser()->isAdmin();
    }
    $subItemsFalse = false;
    if ($item->getType() === 0) {
        if (!empty($subItems)) {
            foreach ($subItems as $subItem) {
                if (is_in_array($readAccess, explode(',', $subItem->getReadAccess())) || $adminAccess == true) {
                     $subItemsFalse = true;
                }
            }
        }
    }
?>
    <?php if ($item->getType() === 0 && $subItemsFalse == true): ?>
        <ul class="forenlist">
            <li class="header">
                <dl class="title ilch-head">
                    <dt>
                        <a href="<?=$obj->getUrl(['controller' => 'showcat', 'action' => 'index','id' => $item->getId()]) ?>">
                            <?=$item->getTitle() ?>
                        </a>
                    </dt>
                </dl>
                <?php if ($item->getDesc() != ''): ?>
                    <dl class="desc small ilch-bg ilch-border">
                        <?=$item->getDesc() ?>
                    </dl>
                <?php endif; ?>
            </li>
        </ul>
    <?php endif; ?>

    <?php if (is_in_array($readAccess, explode(',', $item->getReadAccess())) || $adminAccess == true): ?>
        <?php if ($item->getType() != 0): ?>
            <ul class="forenlist forums">
                <li class="row ilch-border ilch-bg--hover">
                    <dl class="icon 
                        <?php if ($obj->getUser() && $lastPost): ?>
                            <?php if (in_array($obj->getUser()->getId(), explode(',', $lastPost->getRead()))): ?>
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
                                <div class="pull-left">
                                    <a href="<?=$obj->getUrl(['module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $lastPost->getAutor()->getId()]) ?>" title="<?=$obj->escape($lastPost->getAutor()->getName()) ?>">
                                        <img style="width:40px; padding-right: 5px;" src="<?=$obj->getBaseUrl($lastPost->getAutor()->getAvatar()) ?>">
                                    </a>
                                </div>
                                <div class="pull-left">
                                    <a href="<?=$obj->getUrl(['controller' => 'showposts', 'action' => 'index','topicid' => $lastPost->getTopicId(), 'page' => $lastPost->getPage()]) ?>#<?=$lastPost->getId() ?>">
                                        <?=$obj->escape($lastPost->getTopicTitle()) ?>
                                    </a>
                                    <br>
                                    <?=$obj->getTrans('by') ?>
                                    <a href="<?=$obj->getUrl(['module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $lastPost->getAutor()->getId()]) ?>" title="<?=$obj->escape($lastPost->getAutor()->getName()) ?>">
                                        <?=$obj->escape($lastPost->getAutor()->getName()) ?>
                                    </a>
                                    <a href="<?=$obj->getUrl(['controller' => 'showposts', 'action' => 'index','topicid' => $lastPost->getTopicId(), 'page' => $lastPost->getPage()]) ?>#<?=$lastPost->getId() ?>">
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
    if (!empty($subItems) && $i == 0) {
        $i++;
        foreach ($subItems as $subItem) {
            rec($subItem, $obj, $readAccess, $i);
            
        }
    }
}
?>

<link href="<?=$this->getModuleUrl('static/css/forum.css') ?>" rel="stylesheet">

<div id="forum">
    <h1><?=$this->getTrans('forum') ?></h1>
    <?php foreach ($forumItems as $item): ?>
        <div class="forabg">
            <?php rec($item, $this, $readAccess, $i = null) ?>
        </div>
    <?php endforeach; ?>
    <div class="foren-actions clearfix">
        <ul class="pull-left">
            <li><a href="<?=$this->getUrl(['controller' => 'showunansweredtopics', 'action' => 'index']) ?>" class="ilch-link"><?=$this->getTrans('showUnansweredTopics') ?></a></li>
            <?php if ($this->getUser()): ?>
                <li><a href="<?=$this->getUrl(['controller' => 'shownewposts', 'action' => 'index']) ?>" class="ilch-link"><?=$this->getTrans('showNewPosts') ?></a></li>
            <?php endif; ?>
            <li><a href="<?=$this->getUrl(['controller' => 'showactivetopics', 'action' => 'index']) ?>" class="ilch-link"><?=$this->getTrans('showActiveTopics') ?></a></li>
        </ul>
        <?php if ($this->getUser()): ?>
            <!--            
                <div class="pull-right">
                    <a href="<?=$this->getUrl(['controller' => 'markallread', 'action' => 'index']) ?>" class="ilch-link"><?=$this->getTrans('markAllAsRead') ?></a>
                </div>
            -->
        <?php endif; ?>
    </div>

    <div class="statistic">
        <div class="header ilch-head-dark"><?=$this->getTrans('currentInfo') ?></div>
        <div class="content ilch-border">
            <h5><i class="fa fa-user"></i> <?=$this->getTrans('activeUser') ?></h5>
            <div class="statistics">
                <a href="<?=$this->getUrl(['module' => 'statistic', 'controller' => 'index', 'action' => 'online']) ?>" class="ilch-link"><?=$usersOnline+$guestOnline ?> Benutzer online</a>. Registrierte Benutzer: <?=$usersOnline ?>, Gäste: <?=$guestOnline ?><br />
                <ul class="user-list">
                    <?php foreach ($usersOnlineList as $user): ?>
                        <?php
                        $groups = [];
                        foreach ($user->getGroups() as $group) {
                            $groups[] = $group->getName();
                        }
                        ?>

                        <?php if ((in_array('Administrator', $groups))): ?>
                            <li><strong><a href="<?=$this->getUrl(['module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $user->getId()]) ?>" class="ilch-link"><?=$this->escape($user->getName()) ?></a></strong></li>
                        <?php else: ?>
                            <li><a href="<?=$this->getUrl(['module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $user->getId()]) ?>" class="ilch-link"><?=$this->escape($user->getName()) ?></a></li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
                <br />
                <div class="small">
                    <ul class="group-legend">
                        <li><?=$this->getTrans('legend') ?>:</li>
                        <?php foreach ($this->get('listGroups') as $group): ?>
                            <?php if ($group->getName() != 'Guest'): ?>
                                <?php if ($group->getName() == 'Administrator'): ?>
                                    <li><strong><?=$group->getName() ?></strong></li>
                                <?php else: ?>
                                    <li><?=$group->getName() ?></li>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <hr />
            <h5><i class="fa fa-users"></i> <?=$this->getTrans('whoWasHere') ?></h5>
            <div class="statistics">
                <ul class="user-list">
                    <?php foreach ($usersWhoWasOnline as $user): ?>
                        <li><a href="<?=$this->getUrl(['module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $user->getId()]) ?>" class="ilch-link"><?=$this->escape($user->getName()) ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <hr />
            <div class="stats">
                <h5><i class="fa fa-pie-chart"></i> <?=$this->getTrans('statistics') ?></h5>
                <ul class="statistics">
                    <li><?=$this->getTrans('totalPosts') ?>: <?=$forumStatistics->getCountPosts() ?></li>
                    <li><?=$this->getTrans('totalTopics') ?>: <?=$forumStatistics->getCountTopics() ?></li>
                    <li><?=$this->getTrans('totalMembers') ?>: <?=$forumStatistics->getCountUsers() ?></li>
                    <li><?=$this->getTrans('newMember') ?> <a href="<?=$this->getUrl(['module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $this->get('registNewUser')->getId()]) ?>" class="ilch-link" title="<?=$this->escape($this->get('registNewUser')->getName()) ?>"><?=$this->escape($this->get('registNewUser')->getName()) ?></a></li>
                </ul>
            </div>
            <hr />
            <div class="legend">
                <h5><i class="fa fa-bars"></i> <?=$this->getTrans('legend') ?></h5>
                <ul class="statistics">
                    <li><img src="<?=$this->getModuleUrl('static/img/topic_unread.png') ?>" class="legendIcon"> <?=$this->getTrans('legendNewPost') ?></li>
                    <li><img src="<?=$this->getModuleUrl('static/img/topic_read.png') ?>" class="legendIcon"> <?=$this->getTrans('legendReadPost') ?></li>
                    <li><img src="<?=$this->getModuleUrl('static/img/topic_read_locked.png') ?>" class="legendIcon"> <?=$this->getTrans('legendThreadLocked') ?></li>
                </ul>
            </div>
        </div>
    </div>
</div>
