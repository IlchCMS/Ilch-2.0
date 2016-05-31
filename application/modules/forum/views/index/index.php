<link href="<?=$this->getModuleUrl('static/css/forum-style.css') ?>" rel="stylesheet">
<?php
$forumItems = $this->get('forumItems');
$readAccess = $this->get('groupIdsArray');
$usersOnline = $this->get('usersOnline');
$guestOnline = $this->get('guestOnline');
$forumStatistics = $this->get('forumStatics');
$ins = $usersOnline + $guestOnline;

function rec($item, $obj, $readAccess, $i)
{
    //dumpVar($item->getSubItems());
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
        <ul class="topiclist">
            <li class="header">
                <dl class="icon">
                    <dt><a href="<?=$obj->getUrl(['controller' => 'showcat', 'action' => 'index','id' => $item->getId()]) ?>"><?=$item->getTitle() ?></a></dt>
                    <dd class="topics"><?=$obj->getTrans('topics') ?></dd>
                    <dd class="posts"><?=$obj->getTrans('posts') ?></dd>
                    <dd class="lastpost"><span><?=$obj->getTrans('lastPost') ?></span></dd>
                </dl>
            </li>
        </ul>
    <?php endif; ?>

    <?php if (is_in_array($readAccess, explode(',', $item->getReadAccess())) || $adminAccess == true): ?>
        <?php if ($item->getType() != 0): ?>
            <ul class="topiclist forums">
                <li class="row">
                    <dl class="icon" style="
                        <?php if ($obj->getUser() && $lastPost): ?>
                            <?php if (in_array($obj->getUser()->getId(), explode(',', $lastPost->getRead()))): ?>
                                background-image: url(<?=$obj->getModuleUrl('static/img/forum_read.png') ?>);
                            <?php else: ?>
                                background-image: url(<?=$obj->getModuleUrl('static/img/topic_unread.png') ?>);
                            <?php endif; ?>
                        <?php else: ?>
                            background-image: url(<?=$obj->getModuleUrl('static/img/forum_read.png') ?>);
                        <?php endif; ?>
                            background-repeat: no-repeat;">
                        <dt>
                            <a href="<?=$obj->getUrl(['controller' => 'showtopics', 'action' => 'index','forumid' => $item->getId()]) ?>" class="forumtitle"><?=$item->getTitle() ?></a><br>
                            <?=$item->getDesc() ?>
                        </dt>
                        <dd class="topics"><?=$topics; ?> <dfn><?=$obj->getTrans('topics') ?></dfn></dd>
                        <dd class="posts"><?=$posts; ?> <dfn><?=$obj->getTrans('posts') ?></dfn></dd>
                        <dd class="lastpost">
                            <?php if ($lastPost): ?>
                                <span>
                                    <img style="width:30px; padding-right: 5px;" src="<?=$obj->getBaseUrl($lastPost->getAutor()->getAvatar()) ?>"> <?=$obj->getTrans('by') ?>
                                    <a href="<?=$obj->getUrl(['module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $lastPost->getAutor()->getId()]) ?>"><?=$lastPost->getAutor()->getName() ?></a>
                                    <a href="<?=$obj->getUrl(['controller' => 'showposts', 'action' => 'index','topicid' => $lastPost->getTopicId(), 'page' => $lastPost->getPage()]) ?>#<?=$lastPost->getId()?>">
                                        <img src="<?=$obj->getModuleUrl('static/img/icon_topic_latest.png') ?>" alt="<?=$obj->getTrans('viewLastPost') ?>" title="<?=$obj->getTrans('viewLastPost') ?>" height="10" width="12">
                                    </a>
                                    <br><?=$lastPost->getDateCreated() ?>
                                </span>
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
<div id="forum" class="col-lg-12">
    <h3><?=$this->getTrans('forumOverview') ?></h3>
    <div class="topic-actions">
        <?php if ($this->getUser()): ?>
            <div class="buttons">
                <a href="<?=$this->getUrl(['controller' => 'showunread', 'action' => 'index']) ?>" class="btn btn-labeled bgblue">
                    <span class="btn-label">
                        <i class="fa fa-eye"></i>
                    </span><?=$this->getTrans('showNewPosts') ?>
                </a>
            </div>
        <?php endif; ?>
    </div>
    <?php if (!empty($forumItems)): ?>
        <?php foreach ($forumItems as $item): ?>
            <div class="forabg">
                <?php rec($item, $this, $readAccess, $i = null) ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
    <h3 class="dark-header"><?=$this->getTrans('whoOnline') ?></h3>
    <div class="dark-header-content">
        Insgesamt sind <strong><?=$guestOnline+$usersOnline?></strong> Besucher online: <strong><?=$usersOnline ?></strong> registrierte <strong><?=$guestOnline ?></strong> Gäste (basierend auf den aktiven Besuchern der letzten 5 Minuten)<br>
        Der Besucherrekord liegt bei 1767 Besuchern, die am 27.09.2014 online waren.<br> <br>
        <br><em><?=$this->getTrans('legend') ?>:
            <?php foreach ($this->get('listGroups') as $group) : ?>
                <?php if ($group->getName() == 'Guest'): ?>
                <?php else: ?>
                    <?=$group->getName() ?>,
                <?php endif; ?>
            <?php endforeach; ?>
        </em>
    </div>
    <h3 class="dark-header"><?=$this->getTrans('statistics') ?></h3>
    <div class="dark-header-content"><?=$this->getTrans('totalPosts') ?> <strong><?=$forumStatistics->getCountPosts() ?></strong> • <?=$this->getTrans('totalTopics') ?> <strong><?=$forumStatistics->getCountTopics() ?></strong> • <?=$this->getTrans('totalMembers') ?> <strong><?=$forumStatistics->getCountUsers() ?></strong> • <?=$this->getTrans('newMember') ?> <strong><?=$this->get('registNewUser')->getName() ?></strong></div>
</div>
