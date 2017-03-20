<?php
$forumMapper = $this->get('forumMapper');
$cat = $this->get('cat');
$topicpost = $this->get('post');
$forum = $this->get('forum');

$forumPrefix = $forumMapper->getForumByTopicId($topicpost->getId());
$prefix = '';
if ($forumPrefix->getPrefix() != '' AND $topicpost->getTopicPrefix() > 0) {
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

<h1>
    <a href="<?=$this->getUrl(['controller' => 'index', 'action' => 'index']) ?>"><?=$this->getTrans('forum') ?></a>
    <i class="forum fa fa-chevron-right"></i> <a href="<?=$this->getUrl(['controller' => 'showcat', 'action' => 'index', 'id' => $cat->getId()]) ?>"><?=$cat->getTitle() ?></a>
    <i class="forum fa fa-chevron-right"></i> <a href="<?=$this->getUrl(['controller' => 'showtopics', 'action' => 'index', 'forumid' => $forum->getId()]) ?>"><?=$forum->getTitle() ?></a>
    <i class="forum fa fa-chevron-right"></i> <a href="<?=$this->getUrl(['controller' => 'showposts', 'action' => 'index', 'topicid' => $this->getRequest()->getParam('topicid')]) ?>"><?=$prefix.$topicpost->getTopicTitle() ?></a>
    <i class="forum fa fa-chevron-right"></i> <?=$this->getTrans('newPost') ?>
</h1>
<h3 class="blue-header ilch-head"><?=$this->getTrans('newPost') ?></h3>
<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group <?=$this->validation()->hasError('text') ? 'has-error' : '' ?>">
                <div class="col-lg-12">
                    <textarea class="form-control ckeditor"
                              id="ck_1"
                              name="text"
                              toolbar="ilch_bbcode"></textarea>
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-12">
                    <input type="submit"
                           class="btn btn-primary"
                           name="saveNewPost"
                           value="<?php echo $this->getTrans('add'); ?>" />
                </div>
            </div>
        </div>
    </div>
</form>

<?=$this->getDialog('smiliesModal', $this->getTrans('smilies'), '<iframe frameborder="0"></iframe>'); ?>
