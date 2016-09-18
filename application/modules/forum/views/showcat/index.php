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
    if ($obj->getUser()) {
        $adminAccess = $obj->getUser()->isAdmin();
    }
?>
    <?php if ($item->getType() === 0): ?>
        <ul class="topiclist">
            <li class="header">
                <dl class="icon">
                    <dt>
                        <a href="<?=$obj->getUrl(['controller' => 'showcat', 'action' => 'index','id' => $item->getId()]) ?>">
                            <?=$item->getTitle() ?>
                        </a>
                    </dt>
                </dl>
                <?php if ($item->getDesc() != ''): ?>
                    <dl class="desc small">
                        <dt>
                            <?=$item->getDesc() ?>
                        </dt>
                    </dl>
                <?php endif; ?>
            </li>
        </ul>
    <?php endif; ?>

    <?php if (is_in_array($readAccess, explode(',', $item->getReadAccess())) || $adminAccess == true): ?>
        <?php if ($item->getType() != 0): ?>
            <ul class="topiclist forums">
                <li class="row">
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
                                <span>
                                    <div class="pull-left">
                                        <a href="<?=$obj->getUrl(['module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $lastPost->getAutor()->getId()]) ?>" title="<?=$obj->escape($lastPost->getAutor()->getName()) ?>">
                                            <img style="width:40px; padding-right: 5px;" src="<?=$obj->getBaseUrl($lastPost->getAutor()->getAvatar()) ?>">
                                        </a>
                                    </div>
                                    <div class="pull-left">
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

<link href="<?=$this->getModuleUrl('static/css/forum.css') ?>" rel="stylesheet">

<legend><a href="<?=$this->getUrl(['controller' => 'index', 'action' => 'index']) ?>"><?=$this->getTrans('forum') ?></a> <i class="forum fa fa-chevron-right"></i> <?=$cat->getTitle() ?></legend>
<div id="forum" class="col-lg-12">
    <?php
    $adminAccess = null;
    if ($this->getUser()) {
        $adminAccess = $this->getUser()->isAdmin();
    }
    $subItemsFalse = false;
        foreach ($forumItems as $subItem) {
            if (is_in_array($readAccess, explode(',', $subItem->getReadAccess())) || $adminAccess == true) {
                $subItemsFalse = true;
            }
        }
    ?>
    <?php if (!empty($forumItems) && $subItemsFalse == true): ?>
        <div class="forabg">
            <ul class="forenlist">
                <li class="header">
                    <dl class="icon">
                        <dt>
                            <a href="<?=$this->getUrl(['controller' => 'showcat', 'action' => 'index', 'id' => $cat->getId()]) ?>">
                                <?=$cat->getTitle() ?>
                            </a>
                        </dt>
                    </dl>
                    <?php if ($cat->getDesc() != ''): ?>
                        <dl class="desc small">
                            <dt>
                                <?=$cat->getDesc() ?>
                            </dt>
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
        <?php header("location: ".$this->getUrl(['controller' => 'index', 'action' => 'index']));
        exit; ?>
    <?php endif; ?>
</div>
