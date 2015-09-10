<?php $image = $this->get('image'); ?>

<div id="gallery">
    <div class="row">
        <div class="col-md-6">
            <a href="<?=$this->getUrl().'/'.$image->getImageUrl() ?>">
                <img class="thumbnail" src="<?=$this->getUrl().'/'.$image->getImageUrl() ?>" alt="<?=$image->getImageTitle() ?>"/>
            </a>
        </div>
        <div class="col-md-6">
            <h3><?=$image->getImageTitle() ?></h3>
            <p><?=$image->getImageDesc() ?></p>
        </div>
    </div>
</div>

<?php if($this->getUser()): ?>
    <hr />
    <h5><?=$this->getTrans('commentPost') ?></h5>
    <form action="" class="form-horizontal" method="POST">
        <?=$this->getTokenField(); ?>
        <div class="form-group">
            <div class="col-lg-12">
                <textarea class="form-control"
                        name="gallery_comment_text"
                          required></textarea>
            </div>
        </div>
        <div class="form-group">
            <div class="col-lg-12">
                <input type="submit"
                       name="saveEntry"
                       class="pull-right btn" 
                       value="<?=$this->getTrans('submit'); ?>" />
            </div>
        </div>
    </form>
<?php endif; ?>

<?php $comments = $this->get('comments'); ?>
<div class="comments-gallery">
    <h4><?=$this->getTrans('comments') ?> (<?=count($comments) ?>)</h4>
    <?php $userMapper = new \Modules\User\Mappers\User(); ?>
    <?php foreach($comments as $comment): ?>
        <?php $user = $userMapper->getUserById($comment->getUserId()); ?>
        <div class="comment-heading">
            <span><?=$this->getTrans('from')?>: </span>
            <a href="<?=$this->getUrl(array('module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $user->getId()))?>"><?=$this->escape($user->getName())?></a>
            <span class="pull-right"><?=$this->getTrans('on') ?>: <?=$comment->getDateCreated() ?></span>
        </div>
        <hr />
        <?=nl2br($this->escape($comment->getText())) ?>
        <br /><br />
    <?php endforeach; ?>
</div>

<style>
hr {
    margin-top: 0px !important;
}
</style>
