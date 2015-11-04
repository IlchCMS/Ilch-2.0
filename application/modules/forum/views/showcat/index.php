<link href="<?=$this->getModuleUrl('static/css/forum-style.css') ?>" rel="stylesheet">
<h3><?=$this->getTrans('forumOverview') ?></h3>
<?php
$forumMapper = $this->get('forumMapper');
$forumItems = $this->get('forumItems');
$cat = $this->get('cat');
$readAccess = $this->get('readAccess');


function rec($item, $forumMapper, $obj, $readAccess)
{
    $subItems = $forumMapper->getForumItemsByParent('1', $item->getId());
    $topics = $forumMapper->getCountTopicsById($item->getId());
    $lastPost = $forumMapper->getLastPostByTopicId($item->getId());
    $posts = $forumMapper->getCountPostsById($item->getId());
    $adminAccess = null;
    if($obj->getUser()){
        $adminAccess = $obj->getUser()->isAdmin();
    }
?>
    <?php if ($item->getType() === 0): ?>
        <ul class="topiclist">
            <li class="header">
                <dl class="icon">
                    <dt><a href="<?=$obj->getUrl(array('controller' => 'showcat', 'action' => 'index','id' => $item->getId())) ?>"><?=$item->getTitle() ?></a></dt>
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
                            <a href="<?=$obj->getUrl(array('controller' => 'showtopics', 'action' => 'index','forumid' => $item->getId())) ?>" class="forumtitle"><?=$item->getTitle() ?></a><br>
                            <?=$item->getDesc() ?>
                        </dt>
                        <dd class="topics"><?=$topics; ?> <dfn><?=$obj->getTrans('topics') ?></dfn></dd>
                        <dd class="posts"><?=$posts; ?> <dfn><?=$obj->getTrans('posts') ?></dfn></dd>
                        <dd class="lastpost">
                            <?php if($lastPost): ?>
                                <span>
                                    <img style="width:30px; padding-right: 5px;" src="<?=$obj->getBaseUrl($lastPost->getAutor()->getAvatar()) ?>"> <?=$obj->getTrans('by') ?>
                                    <a href="<?=$obj->getUrl(array('module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $lastPost->getAutor()->getId())) ?>"><?=$lastPost->getAutor()->getName() ?></a>
                                    <a href="<?=$obj->getUrl(array('controller' => 'showposts', 'action' => 'index','topicid' => $lastPost->getTopicId(), 'page' => $lastPost->getPage())) ?>#<?=$lastPost->getId()?>">
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
    if (!empty($subItems)) {
        foreach ($subItems as $subItem) {
            rec($subItem, $forumMapper, $obj, $readAccess);
        }
    }
}
?>
<div id="forum" class="col-lg-12">

    <?php
    $adminAccess = null;
    if($this->getUser()){
        $adminAccess = $this->getUser()->isAdmin();
    }
    $subItemsFalse = false;
        foreach ($forumItems as $subItem) {
            if (is_in_array($readAccess, explode(',', $subItem->getReadAccess())) || $adminAccess == true){
                $subItemsFalse = true;
            }
        }
    ?>
    <?php if (!empty($forumItems) && $subItemsFalse == true): ?>
    <div class="forabg">
        <ul class="topiclist">
            <li class="header">
                <dl class="icon">
                    <dt><a href="<?=$this->getUrl(array('controller' => 'showcat', 'action' => 'index','id' => $cat->getId())) ?>"><?=$cat->getTitle() ?></a></dt>
                    <dd class="topics"><?=$this->getTrans('topics') ?></dd>
                    <dd class="posts"><?=$this->getTrans('posts') ?></dd>
                    <dd class="lastpost"><span><?=$this->getTrans('lastPost') ?></span></dd>
                </dl>
            </li>
        </ul>
        <?php foreach ($forumItems as $item): ?>
            
                <?php rec($item, $forumMapper, $this, $readAccess) ?>
           
        <?php endforeach; ?>
        <?php else: ?>
            <?php header("location: ".$this->getUrl(array('controller' => 'index', 'action' => 'index')));
        exit; ?>
    <?php endif; ?>
    </div>
</div>
