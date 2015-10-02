<link href="<?=$this->getModuleUrl('static/css/comment.css') ?>" rel="stylesheet">

<legend><?=$this->getTrans('settings') ?>
    <a class="badge" data-toggle="modal" data-target="#infoComment">
        <i class="fa fa-info" ></i>
    </a>
</legend>
<form class="form-horizontal" method="POST" action="<?=$this->getUrl(array('action' => $this->getRequest()->getActionName())) ?>">
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
        <div class="col-lg-2">
            <div class="container">
                <div class="input-group spinner">
                    <input type="text" class="form-control" id="interleaving" name="interleaving" min="0" value="<?=$this->get('comment_interleaving') ?>">
                    <div class="input-group-btn-vertical">
                        <span class="btn btn-default"><i class="fa fa-caret-up"></i></span>
                        <span class="btn btn-default"><i class="fa fa-caret-down"></i></span>
                    </div>
                </div>
            </div>
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
<div class="modal fade" id="infoComment">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button"
                        class="close"
                        data-dismiss="modal"
                        aria-hidden="true">&times;</button>
                <h4 class="modal-title"><?=$this->getTrans('info') ?></h4>
            </div>
            <div class="modal-body">
                <p id="modalText"><?=$this->getTrans('CommentCommentInfoText') ?></p>
            </div>
            <div class="modal-footer">
                <button type="button"
                        class="btn btn-primary"
                        data-dismiss="modal"><?=$this->getTrans('close') ?></button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
$(function() {
    $('.spinner .btn:first-of-type').on('click', function() {
        var btn = $(this);
        var input = btn.closest('.spinner').find('input');
        if (input.attr('max') == undefined || parseInt(input.val()) < parseInt(input.attr('max'))) {
            input.val(parseInt(input.val(), 10) + 1);
        } else {
            btn.next("disabled", true);
        }
    });
    $('.spinner .btn:last-of-type').on('click', function() {
        var btn = $(this);
        var input = btn.closest('.spinner').find('input');
        if (input.attr('min') == undefined || parseInt(input.val()) > parseInt(input.attr('min'))) {
            input.val(parseInt(input.val(), 10) - 1);
        } else {
            btn.prev("disabled", true);
        }
    });
})
</script>
