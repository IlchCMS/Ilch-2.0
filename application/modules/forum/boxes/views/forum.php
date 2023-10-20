<?php

/** @var \Ilch\View $this */

use Ilch\Date;

$DESCPostorder = $this->get('DESCPostorder');
$postsPerPage = $this->get('postsPerPage');
?>

<?php if (!empty($this->get('lastActiveTopicsToShow'))) : ?>
    <ul class="list-unstyled">
        <?php foreach ($this->get('lastActiveTopicsToShow') as $topic) : ?>
            <li style="line-height: 15px;">
                <?php if ($this->getUser()) : ?>
                    <?php if ($topic['lastPost']->getRead()) : ?>
                        <img src="<?=$this->getStaticUrl('../application/modules/forum/static/img/topic_read.png') ?>" style="float: left; margin-top: 8px;" alt="<?=$this->getTrans('read') ?>">
                    <?php else : ?>
                        <img src="<?=$this->getStaticUrl('../application/modules/forum/static/img/topic_unread.png') ?>" style="float: left; margin-top: 8px;" alt="<?=$this->getTrans('unread') ?>">
                    <?php endif; ?>
                <?php else : ?>
                    <img src="<?=$this->getStaticUrl('../application/modules/forum/static/img/topic_read.png') ?>" style="float: left; margin-top: 8px;" alt="<?=$this->getTrans('read') ?>">
                <?php endif; ?>
                <a href="<?=$this->getUrl(['module' => 'forum', 'controller' => 'showposts', 'action' => 'index', 'topicid' => $topic['topicId'], 'page' => ($DESCPostorder ? 1 : ceil($topic['countPosts'] / $postsPerPage))]) ?>#<?=$topic['lastPost']->getId() ?>">
                    <?=$this->escape($topic['topicTitle']) ?>
                </a>
                <br />
                <small>
                    <?php $date = new Date($topic['lastPost']->getDateCreated()); ?>
                    <?=$this->getTrans('by') ?> <a href="<?=$this->getUrl(['module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $topic['lastPost']->getAutor()->getId()]) ?>"><?=$this->escape($topic['lastPost']->getAutor()->getName()) ?></a><br>
                    <?=$date->format('d.m.y - H:i', true) ?> <?=$this->getTrans('clock') ?>
                </small>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else : ?>
    <?=$this->getTrans('noPosts') ?>
<?php endif; ?>
