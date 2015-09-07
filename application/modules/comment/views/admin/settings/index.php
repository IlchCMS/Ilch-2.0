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
                checkbox-platzhalter
            </div>
        </div>
        <div class="form-group">
            <label for="countCommentInput" class="col-lg-2 control-label">
                <?=$this->getTrans('countComment') ?>:
            </label>
            <div class="col-lg-8">
                input-platzhalter
            </div>
        </div>
        <div class="form-group">
            <label for="showAvatarInput" class="col-lg-2 control-label">
                <?=$this->getTrans('showAvatar') ?>:
            </label>
            <div class="col-lg-8">
                checkbox
            </div>
        </div>
        <div class="form-group">
            <label for="showDateInput" class="col-lg-2 control-label">
                <?=$this->getTrans('showDate') ?>:
            </label>
            <div class="col-lg-8">
                checkbox
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
