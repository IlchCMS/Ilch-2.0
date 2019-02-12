<?php
$forumMapper = $this->get('forumMapper');
$topicMapper = $this->get('topicMapper');
$groupIdsArray = $this->get('groupIdsArray');
$adminAccess = null;
if ($this->getUser()) {
    $adminAccess = $this->getUser()->isAdmin();
}
?>

<?php if (!empty($this->get('topics'))): ?>
    <ul class="list-unstyled">
        <?php foreach ($this->get('topics') as $topic): ?>
            <?php $forum = $forumMapper->getForumById($topic['forum_id']); ?>
            <?php if (is_in_array($groupIdsArray, explode(',', $forum->getReadAccess())) || $adminAccess == true): ?>
                <?php $lastPost = $topicMapper->getLastPostByTopicId($topic['topic_id']) ?>
                <?php $date = new \Ilch\Date($lastPost->getDateCreated()); ?>
                <li style="line-height: 15px;">
                    <?php if ($this->getUser()): ?>
                        <?php if (in_array($this->getUser()->getId(), explode(',', $lastPost->getRead()))): ?>
                            <img src="<?=$this->getStaticUrl('../application/modules/forum/static/img/topic_read.png') ?>" style="float: left; margin-top: 8px;" alt="<?=$this->getTrans('read') ?>">
                        <?php else: ?>
                            <img src="<?=$this->getStaticUrl('../application/modules/forum/static/img/topic_unread.png') ?>" style="float: left; margin-top: 8px;" alt="<?=$this->getTrans('unread') ?>">
                        <?php endif; ?>
                    <?php else: ?>
                        <img src="<?=$this->getStaticUrl('../application/modules/forum/static/img/topic_read.png') ?>" style="float: left; margin-top: 8px;" alt="<?=$this->getTrans('read') ?>">
                    <?php endif; ?>
                    <a href="<?=$this->getUrl(['module' => 'forum', 'controller' => 'showposts', 'action' => 'index', 'topicid' => $topic['topic_id']]) ?>">
                        <?=$topic['topic_title'] ?>
                    </a>
                    <br />
                    <small>
                        <?=$this->getTrans('by') ?> <a href="<?=$this->getUrl(['module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $lastPost->getAutor()->getId()]) ?>"><?=$this->escape($lastPost->getAutor()->getName()) ?></a><br>
                        <?=$date->format("d.m.y - H:i", true) ?> <?=$this->getTrans('clock') ?>
                    </small>
                </li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <?=$this->getTrans('noPosts') ?>
<?php endif; ?>
