<?php
$forumMapper = $this->get('forumMapper');
$cat = $this->get('cat');
$topicpost = $this->get('topicPost');
$postTextAsQuote = $this->get('postTextAsQuote');
$forum = $this->get('forum');

$forumPrefix = $forumMapper->getForumByTopicId($topicpost->getId());
$prefix = '';
if ($forumPrefix->getPrefix() != '' && $topicpost->getTopicPrefix() > 0) {
    $prefix = explode(',', $forumPrefix->getPrefix());
    array_unshift($prefix, '');

    foreach ($prefix as $key => $value) {
        if ($topicpost->getTopicPrefix() == $key) {
            $value = trim($value);
            $prefix = '['.$value.'] ';
        }
    }
}
?>

<link href="<?=$this->getModuleUrl('static/css/forum.css') ?>" rel="stylesheet">

<div id="forum">
    <h1>
        <a href="<?=$this->getUrl(['controller' => 'index', 'action' => 'index']) ?>"><?=$this->getTrans('forum') ?></a>
        <i class="fa-solid fa-chevron-right"></i> <a href="<?=$this->getUrl(['controller' => 'showcat', 'action' => 'index', 'id' => $cat->getId()]) ?>"><?=$cat->getTitle() ?></a>
        <i class="fa-solid fa-chevron-right"></i> <a href="<?=$this->getUrl(['controller' => 'showtopics', 'action' => 'index', 'forumid' => $forum->getId()]) ?>"><?=$forum->getTitle() ?></a>
        <i class="fa-solid fa-chevron-right"></i> <a href="<?=$this->getUrl(['controller' => 'showposts', 'action' => 'index', 'topicid' => $this->getRequest()->getParam('topicid')]) ?>"><?=$prefix.$topicpost->getTopicTitle() ?></a>
        <i class="fa-solid fa-chevron-right"></i> <?=$this->getTrans('newPost') ?>
    </h1>
    <div class="row">
        <div class="col-lg-12">
            <div class="new-post-head ilch-head">
                <?=$this->getTrans('newPost') ?>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="new-topic ilch-bg ilch-border">
                <form class="form-horizontal" method="POST">
                    <?=$this->getTokenField() ?>
                    <div class="form-group <?=$this->validation()->hasError('text') ? 'has-error' : '' ?>">
                        <div class="col-lg-12">
                            <textarea class="form-control ckeditor"
                                      id="ck_1"
                                      name="text"
                                      toolbar="ilch_html_frontend"><?=(!empty($postTextAsQuote)) ? $this->escape($postTextAsQuote) : $this->originalInput('text') ?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-12">
                            <input type="submit"
                                   class="btn btn-primary"
                                   name="saveNewPost"
                                   value="<?=$this->getTrans('add') ?>" />
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
