<?php

/** @var \Ilch\View $this */

/** @var array $topics */

use Ilch\Date;

/** @var \Modules\Forum\Models\ForumTopic[] $topics */
$topics = $this->get('topics');
/** @var int $postsPerPage */
$DESCPostorder = $this->get('DESCPostorder');
/** @var int $postsPerPage */
$postsPerPage = $this->get('postsPerPage');
?>

<link href="<?=$this->getModuleUrl('static/css/forum.css') ?>" rel="stylesheet">

<div id="forum">
    <h1>
        <a href="<?=$this->getUrl(['controller' => 'index', 'action' => 'index']) ?>"><?=$this->getTrans('forum') ?></a>
        <i class="fa-solid fa-chevron-right"></i> <?=$this->getTrans('showNewPosts') ?>
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
            <?php foreach ($topics as $topic) : ?>
                <li class="row ilch-border ilch-bg--hover">
                    <dl class="icon
                    <?php if ($this->getUser()) : ?>
                        <?php if ($topic['topic']->getStatus() == 1) : ?>
                            topic-unread-locked
                        <?php else : ?>
                            topic-unread
                        <?php endif; ?>
                    <?php elseif ($topic['topic']->getStatus() == 1) : ?>
                        topic-read-locked
                    <?php else : ?>
                        topic-read
                    <?php endif; ?>
                ">
                        <dt>
                            <?php
                            if ($topic['forumPrefix'] != '' && $topic['topic']->getTopicPrefix()->getId() > 0) {
                                $prefixIds = explode(',', $topic['forumPrefix']);
                                array_unshift($prefixIds, '');

                                foreach ($prefixIds as $prefixId) {
                                    if ($topic['topic']->getTopicPrefix()->getId() == $prefixId) {
                                        echo '<span class="label label-default">' . $this->escape($topic['topic']->getTopicPrefix()->getPrefix()) . '</span>';
                                        break;
                                    }
                                }
                            }
                            ?>
                            <a href="<?=$this->getUrl(['controller' => 'showposts', 'action' => 'index','topicid' => $topic['topic']->getId()]) ?>" class="topictitle">
                                <?=$topic['topic']->getTopicTitle() ?>
                            </a>
                            <?php if ($topic['topic']->getType() == '1') : ?>
                                <i class="fa-solid fa-thumbtack"></i>
                            <?php endif; ?>
                            <br>
                            <div class="small">
                                <?=$this->getTrans('by') ?>
                                <a href="<?=$this->getUrl(['module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $topic['topic']->getAuthor()->getId()]) ?>" class="ilch-link-red">
                                    <?=$this->escape($topic['topic']->getAuthor()->getName()) ?>
                                </a>
                                »
                                <?php $date = new Date($topic['topic']->getDateCreated()); ?>
                                <?=$date->format('d.m.y - H:i', true) ?>
                            </div>
                        </dt>
                        <dd class="posts small">
                            <div class="float-start text-nowrap stats">
                                <?=$this->getTrans('replies') ?>:
                                <br />
                                <?=$this->getTrans('views') ?>:
                            </div>
                            <div class="float-start">
                                <?=$topic['topic']->getCountPosts() - 1 ?>
                                <br />
                                <?=$topic['topic']->getVisits() ?>
                            </div>
                        </dd>
                        <dd class="lastpost small">
                            <div class="float-start">
                                <a href="<?=$this->getUrl(['module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $topic['lastPost']->getAutor()->getId()]) ?>" title="<?=$this->escape($topic['lastPost']->getAutor()->getName()) ?>">
                                    <img style="width:40px; padding-right: 5px;" src="<?=$this->getBaseUrl($topic['lastPost']->getAutor()->getAvatar()) ?>" alt="<?=$this->escape($topic['lastPost']->getAutor()->getName()) ?>">
                                </a>
                            </div>
                            <div class="float-start">
                                <?=$this->getTrans('by') ?>
                                <a href="<?=$this->getUrl(['module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $topic['lastPost']->getAutor()->getId()]) ?>" title="<?=$this->escape($topic['lastPost']->getAutor()->getName()) ?>">
                                    <?=$this->escape($topic['lastPost']->getAutor()->getName()) ?>
                                </a>
                                <a href="<?=$this->getUrl(['controller' => 'showposts', 'action' => 'index','topicid' => $topic['lastPost']->getTopicId(), 'page' => ($DESCPostorder ? 1 : ceil($topic['topic']->getCountPosts() / $postsPerPage))]) ?>#<?=$topic['lastPost']->getId() ?>">
                                    <img src="<?=$this->getModuleUrl('static/img/icon_topic_latest.png') ?>" alt="<?=$this->getTrans('viewLastPost') ?>" title="<?=$this->getTrans('viewLastPost') ?>" height="10" width="12">
                                </a>
                                <br>
                                <?php $date = new Date($topic['lastPost']->getDateCreated()); ?>
                                <?=$date->format('d.m.y - H:i', true) ?>
                            </div>
                        </dd>
                    </dl>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
