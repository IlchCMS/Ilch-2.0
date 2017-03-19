<h1><?=$this->getTrans('settings') ?>
    <a class="badge" data-toggle="modal" data-target="#infoModal">
        <i class="fa fa-info" ></i>
    </a>
</h1>
<?php if (!empty($this->get('errors'))): ?>
    <div class="alert alert-danger" role="alert">
        <strong> <?=$this->getTrans('errorsOccured') ?>:</strong>
        <ul>
            <?php foreach ($this->get('errors') as $error): ?>
                <li><?= $error; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
<form class="form-horizontal" method="POST" action="<?=$this->getUrl(['action' => $this->getRequest()->getActionName()]) ?>">
    <?=$this->getTokenField() ?>
    <div class="form-group <?=in_array('reply', $this->get('errorFields')) ? 'has-error' : '' ?>">
        <div class="col-lg-2 control-label">
            <?=$this->getTrans('acceptReply') ?>:
        </div>
        <div class="col-lg-4">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" id="reply-yes" name="reply" value="1" <?php if ($this->get('comment_reply') == '1') { echo 'checked="checked"'; } ?> />
                <label for="reply-yes" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('yes') ?></label>
                <input type="radio" class="flipswitch-input" id="reply-no" name="reply" value="0" <?php if ($this->get('comment_reply') != '1') { echo 'checked="checked"'; } ?> />
                <label for="reply-no" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('no') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
         </div>
    </div>
    <div class="form-group <?=in_array('nesting', $this->get('errorFields')) ? 'has-error' : '' ?>">
        <label for="nesting" class="col-lg-2 control-label">
            <?=$this->getTrans('nesting') ?>:
        </label>
        <div class="col-lg-1">
            <input class="form-control"
                   type="number"
                   id="nesting"
                   name="nesting"
                   min="0"
                   value="<?=$this->get('comment_nesting') ?>">
        </div>
    </div>
    <?=$this->getSaveBar() ?>
</form>

<?=$this->getDialog('infoModal', $this->getTrans('info'), $this->getTrans('CommentCommentInfoText')); ?>
