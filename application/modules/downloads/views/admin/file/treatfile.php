<legend><?=$this->getTrans('treatFile') ?></legend>
<?php if ($this->get('file') != ''): ?>
    <?php $file = $this->get('file') ?>
    <?php $image = '' ?>
    <?php if($file->getFileImage() != ''): ?>
        <?php $image = $this->getBaseUrl($file->getFileImage()) ?>
    <?php else: ?>
        <?php $image = $this->getBaseUrl('application/modules/media/static/img/nomedia.png') ?>
    <?php endif; ?>
    <form class="form-horizontal" method="POST" action="">
        <div id="gallery">
            <div class="row">
                <div class="col-md-4">
                    <a href="<?=$this->getBaseUrl($file->getFileUrl()) ?>">
                        <img class="thumbnail"
                             src="<?=$image ?>"/>
                    </a>
                </div>
                <div class="col-md-8">
                    <?=$this->getTokenField() ?>
                    <div class="form-group">
                        <label for="fileTitleInput" class="col-lg-2 control-label">
                            <?=$this->getTrans('fileTitle') ?>:
                        </label>
                        <div class="col-lg-8">
                            <input class="form-control"
                                   type="text"
                                   name="fileTitle"
                                   id="fileTitleInput"
                                   value="<?=$file->getFileTitle() ?>" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="fileImage"
                                class="col-lg-2 control-label">
                            <?=$this->getTrans('fileImage'); ?>:
                        </label>
                        <div class="col-lg-8">
                            <div class="input-group">
                                <input class="form-control"
                                       type="text"
                                       name="fileImage"
                                       id="selectedImage"
                                       placeholder="<?=$this->getTrans('fileImageInfo'); ?>"
                                       value="<?=$file->getFileImage() ?>" />
                                <span class="input-group-addon"><a id="media" href="javascript:media()"><i class="fa fa-picture-o"></i></a></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="fileDescInput" class="col-lg-2 control-label">
                            <?=$this->getTrans('fileDesc') ?>:
                        </label>
                        <div class="col-lg-8">
                            <textarea class="form-control"
                                      id="fileDescInput"
                                      rows="8"
                                      name="fileDesc"><?=$file->getFileDesc() ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?=$this->getSaveBar('saveButton') ?>
    </form>
<?php else: ?>
    <?=$this->getTrans('noFile') ?>
<?php endif; ?>

<script>
<?=$this->getMedia()
        ->addMediaButton($this->getUrl('admin/media/iframe/index/type/single/'))
        ->addUploadController($this->getUrl('admin/media/index/upload'))
?>
</script>
