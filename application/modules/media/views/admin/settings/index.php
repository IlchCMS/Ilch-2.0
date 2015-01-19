<form class="form-horizontal" method="POST" action="">
<?php echo $this->getTokenField(); ?>
<legend><?=$this->getTrans('settings'); ?>
    <a class="badge" data-toggle="modal" data-target="#infoModal">
        <i class="fa fa-info" ></i>
    </a>
</legend>
    <div class="form-group">
        <label for="allowedImagesInput" class="col-lg-2 control-label">
            <?php echo $this->getTrans('allowedImages'); ?>:
        </label>
        <div class="col-lg-8">
            <textarea class="form-control" 
                      id="allowedImagesInput" 
                      name="allowedImages"><?=$this->escape($this->get('media_ext_img')); ?></textarea>
        </div>
    </div>
    <div class="form-group">
        <label for="allowedVideosInput" class="col-lg-2 control-label">
            <?php echo $this->getTrans('allowedVideos'); ?>:
        </label>
        <div class="col-lg-8">
            <textarea class="form-control" 
                      id="allowedVideosInput" 
                      name="allowedVideos"><?=$this->escape($this->get('media_ext_video')); ?></textarea>
        </div>
    </div>
    <div class="form-group">
        <label for="allowedFilesInput" class="col-lg-2 control-label">
            <?php echo $this->getTrans('allowedFiles'); ?>:
        </label>
        <div class="col-lg-8">
            <textarea class="form-control" 
                      id="allowedFilesInput" 
                      name="allowedFiles"><?=$this->escape($this->get('media_ext_file')); ?></textarea>
        </div>
    </div>
    <?=$this->getSaveBar()?>
</form>
<div class="modal fade" id="infoModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button"
                        class="close"
                        data-dismiss="modal"
                        aria-hidden="true">&times;</button>
                <h4 class="modal-title"><?=$this->getTrans('allowedMediaInfo'); ?></h4>
            </div>
            <div class="modal-body">
                <p id="modalText"><?=$this->getTrans('allowedMediaInfoText'); ?></p>
            </div>
            <div class="modal-footer">
                <button type="button"
                        class="btn btn-primary"
                        data-dismiss="modal"><?=$this->getTrans('close'); ?></button>
            </div>
        </div>
    </div>
</div>