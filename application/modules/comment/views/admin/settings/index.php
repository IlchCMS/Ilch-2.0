<link href="<?=$this->getModuleUrl('static/css/comment.css') ?>" rel="stylesheet">

<legend><?=$this->getTrans('settings') ?>
    <a class="badge" data-toggle="modal" data-target="#infoModal">
        <i class="fa fa-info" ></i>
    </a>
</legend>
<form class="form-horizontal" method="POST" action="<?=$this->getUrl(['action' => $this->getRequest()->getActionName()]) ?>">
    <?=$this->getTokenField() ?>
    <div class="form-group">
        <label for="reply" class="col-lg-2 control-label">
            <?=$this->getTrans('acceptReply') ?>:
        </label>
        <div class="col-lg-4">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" name="reply" value="1" id="reply-yes" <?php if ($this->get('comment_reply') == '1') { echo 'checked="checked"'; } ?> />
                <label for="reply-yes" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('yes') ?></label>
                <input type="radio" class="flipswitch-input" name="reply" value="0" id="reply-no" <?php if ($this->get('comment_reply') != '1') { echo 'checked="checked"'; } ?> />
                <label for="reply-no" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('no') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
         </div>
    </div>
    <div class="form-group">
        <label for="interleaving" class="col-lg-2 control-label">
            <?=$this->getTrans('interleaving') ?>:
        </label>
        <div class="col-lg-1">
            <input class="form-control" type="number" id="interleaving" name="interleaving" min="0" value="<?=$this->get('comment_interleaving') ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="showAvatarInput" class="col-lg-2 control-label">
            <?=$this->getTrans('showAvatar') ?>:
        </label>
        <div class="col-lg-8">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" name="check_avatar" value="1" id="avatar-yes" <?php if ($this->get('comment_avatar') == '1') { echo 'checked="checked"'; } ?> />
                <label for="avatar-yes" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('yes') ?></label>
                <input type="radio" class="flipswitch-input" name="check_avatar" value="" id="avatar-no" <?php if ($this->get('comment_avatar') != '1') { echo 'checked="checked"'; } ?> />
                <label for="avatar-no" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('no') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="showDateInput" class="col-lg-2 control-label">
            <?=$this->getTrans('showDateTime') ?>:
        </label>
        <div class="col-lg-8">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" name="check_date" value="1" id="date-yes" <?php if ($this->get('comment_date') == '1') { echo 'checked="checked"'; } ?> />
                <label for="date-yes" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('yes') ?></label>
                <input type="radio" class="flipswitch-input" name="check_date" value="" id="date-no" <?php if ($this->get('comment_date') != '1') { echo 'checked="checked"'; } ?> />
                <label for="date-no" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('no') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
        </div>
    </div>
    <?=$this->getSaveBar() ?>
</form>

<?=$this->getDialog('infoModal', $this->getTrans('info'), $this->getTrans('CommentCommentInfoText')); ?>
