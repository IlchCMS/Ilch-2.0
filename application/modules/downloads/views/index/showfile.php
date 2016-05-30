<?php
$userMapper = $this->get('userMapper');
$file = $this->get('file');
$comments = $this->get('comments');
$image = '';
if ($file->getFileImage() != '') {
    $image = $this->getBaseUrl($file->getFileImage());
} else {
    $image = $this->getBaseUrl('application/modules/media/static/img/nomedia.png');
}
?>

<style>
hr{
    margin-top: 0px !important;
}
</style>

<div id="gallery">
    <div class="row">
        <div class="col-md-6">
            <a href="<?=$this->getUrl().'/'.$file->getFileUrl() ?>">
                <img class="thumbnail" src="<?=$image ?>" alt="<?=$file->getFileTitle() ?>"/>
            </a>
        </div>
        <div class="col-md-6">
            <h3><?=$file->getFileTitle() ?></h3>
            <p><?=$file->getFileDesc() ?></p>
            <a href="<?=$this->getUrl().'/'.$file->getFileUrl() ?>" class="btn btn-primary pull-right"><?=$this->getTrans('download') ?></a>
        </div>
    </div>
</div>
<?php if ($this->getUser()): ?>
    <hr />
    <h5><?=$this->getTrans('commentPost') ?></h5>
    <form action="" class="form-horizontal" method="POST">
        <?=$this->getTokenField() ?>
        <div class="form-group">
            <div class="col-lg-12">
                <textarea class="form-control"
                        name="downloads_comment_text"
                          required></textarea>
            </div>
        </div>
        <div class="form-group">
            <div class="col-lg-12">
                <input type="submit"
                       name="saveEntry"
                       class="pull-right btn" 
                       value="<?=$this->getTrans('submit') ?>" />
            </div>
        </div>
    </form>
<?php endif; ?>
<div class="comments-gallery">
    <h4><?=$this->getTrans('comments')?><?='('.count($comments).')'?></h4>
    <?php foreach ($comments as $comment): ?>
        <?php $user = $userMapper->getUserById($comment->getUserId()); ?>
        <div class="comment-heading">
            <span><?=$this->getTrans('from') ?>: </span>
            <a href="<?=$this->getUrl(['module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $user->getId()]) ?>"><?=$this->escape($user->getName()) ?></a>
            <span class="pull-right"><?=$this->getTrans('on') ?>: <?=$comment->getDateCreated() ?></span>
        </div>
        <hr />
        <?=nl2br($this->escape($comment->getText())) ?>
        <br /><br />
    <?php endforeach; ?>
</div>
