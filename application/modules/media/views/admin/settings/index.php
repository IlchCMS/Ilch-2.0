<legend><?=$this->getTrans('settings') ?>
    <a class="badge" data-toggle="modal" data-target="#infoModal">
        <i class="fa fa-info" ></i>
    </a>
</legend>
<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
        <div class="form-group">
            <label for="allowedImagesInput" class="col-lg-2 control-label">
                <?=$this->getTrans('allowedImages') ?>:
            </label>
            <div class="col-lg-8">
                <textarea class="form-control"
                          id="allowedImagesInput"
                          name="allowedImages"><?=$this->escape($this->get('media_ext_img')) ?></textarea>
            </div>
        </div>
        <div class="form-group">
            <label for="allowedVideosInput" class="col-lg-2 control-label">
                <?=$this->getTrans('allowedVideos') ?>:
            </label>
            <div class="col-lg-8">
                <textarea class="form-control"
                          id="allowedVideosInput"
                          name="allowedVideos"><?=$this->escape($this->get('media_ext_video')) ?></textarea>
            </div>
        </div>
        <div class="form-group">
            <label for="allowedFilesInput" class="col-lg-2 control-label">
                <?=$this->getTrans('allowedFiles') ?>:
            </label>
            <div class="col-lg-8">
                <textarea class="form-control"
                          id="allowedFilesInput"
                          name="allowedFiles"><?=$this->escape($this->get('media_ext_file')) ?></textarea>
            </div>
        </div>
    <?=$this->getSaveBar() ?>
</form>

<?=$this->getDialog('infoModal', $this->getTrans('info'), $this->getTrans('allowedMediaInfoText')); ?>
