<?php

use Ilch\Date;
use Modules\Forum\Mappers\Forum;
use Modules\Forum\Mappers\Topic;

/** @var Forum $forumMapper */
$forumMapper = $this->get('forumMapper');
/** @var Topic $topicMapper */
$topicMapper = $this->get('topicMapper');
$groupIdsArray = $this->get('groupIdsArray');
$adminAccess = null;
if ($this->getUser()) {
    $adminAccess = $this->getUser()->isAdmin();
}
$DESCPostorder = $this->get('DESCPostorder');
$postsPerPage = $this->get('postsPerPage');
?>

<?php if (!empty($this->get('topics'))): ?>
    <ul class="list-unstyled">
        <?php foreach ($this->get('topics') as $topic): ?>
            <?php $forum = $forumMapper->getForumById($topic['forum_id']); ?>
            <?php if ($adminAccess || is_in_array($groupIdsArray, explode(',', $forum->getReadAccess()))): ?>
                <?php $lastPost = ($this->getUser()) ? $topicMapper->getLastPostByTopicId($topic['topic_id'], $this->getUser()->getId()) : $topicMapper->getLastPostByTopicId($topic['topic_id']) ?>
                <?php $countPosts = $forumMapper->getCountPostsByTopicId($topic['topic_id']) ?>
                <li style="line-height: 15px;">
                    <?php if ($this->getUser()): ?>
                        <?php if ($lastPost->getRead()): ?>
                            <img src="<?=$this->getStaticUrl('../application/modules/forum/static/img/topic_read.png') ?>" style="float: left; margin-top: 8px;" alt="<?=$this->getTrans('read') ?>">
                        <?php else: ?>
                            <img src="<?=$this->getStaticUrl('../application/modules/forum/static/img/topic_unread.png') ?>" style="float: left; margin-top: 8px;" alt="<?=$this->getTrans('unread') ?>">
                        <?php endif; ?>
                    <?php else: ?>
                        <img src="<?=$this->getStaticUrl('../application/modules/forum/static/img/topic_read.png') ?>" style="float: left; margin-top: 8px;" alt="<?=$this->getTrans('read') ?>">
                    <?php endif; ?>
                    <a href="<?=$this->getUrl(['module' => 'forum', 'controller' => 'showposts', 'action' => 'index', 'topicid' => $topic['topic_id'], 'page' => ($DESCPostorder?1:ceil($countPosts/$postsPerPage))]) ?>#<?=$lastPost->getId() ?>">
                        <?=$this->escape($topic['topic_title']) ?>
                    </a>
                    <br />
                    <small>
                        <?php $date = new Date($lastPost->getDateCreated()); ?>
                        <?=$this->getTrans('by') ?> <a href="<?=$this->getUrl(['module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $lastPost->getAutor()->getId()]) ?>"><?=$this->escape($lastPost->getAutor()->getName()) ?></a><br>
                        <?=$date->format('d.m.y - H:i', true) ?> <?=$this->getTrans('clock') ?>
                    </small>
                </li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <?=$this->getTrans('noPosts') ?>
<?php endif; ?>
