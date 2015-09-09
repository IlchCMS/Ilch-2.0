<link href="<?=$this->getModuleUrl('static/css/comments.css') ?>" rel="stylesheet">

<legend><?=$this->getTrans('settings') ?>
    <a class="badge" data-toggle="modal" data-target="#infoComment">
        <i class="fa fa-info" ></i>
    </a>
</legend>
<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
        <div class="form-group">
            <label for="allowedCommentCommentInput" class="col-lg-2 control-label">
                <?=$this->getTrans('allowedCommentComment') ?>:
            </label>
            <div class="col-lg-8">
                <input value="1" type="checkbox" name="check_answer" />
            </div>
        </div>
        <div class="form-group">
            <label for="countCommentInput" class="col-lg-2 control-label">
                <?=$this->getTrans('countComment') ?>:
            </label>
            <div class="col-lg-8">             
                <div class="container">
                    <div class="input-group spinner">
                        <input type="text" class="form-control" value="1">
                        <div class="input-group-btn-vertical">
                            <button class="btn btn-default" type="button"><i class="fa fa-caret-up"></i></button>
                            <button class="btn btn-default" type="button"><i class="fa fa-caret-down"></i></button>
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
                <input value="1" type="checkbox" name="check_avatar" />
            </div>
        </div>
        <div class="form-group">
            <label for="showDateInput" class="col-lg-2 control-label">
                <?=$this->getTrans('showDate') ?>:
            </label>
            <div class="col-lg-8">
                <input value="1" type="checkbox" name="check_date" />
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
    (function ($) {
      $('.spinner .btn:first-of-type').on('click', function() {
        $('.spinner input').val( parseInt($('.spinner input').val(), 10) + 1);
      });
      $('.spinner .btn:last-of-type').on('click', function() {
        $('.spinner input').val( parseInt($('.spinner input').val(), 10) - 1);
      });
    })(jQuery);
</script>
