<h1><?=$this->getTrans('settings') ?>
    <a class="badge rounded-pill bg-secondary" data-bs-toggle="modal" data-bs-target="#infoModal">
        <i class="fa fa-info" ></i>
    </a>
</h1>
<form class="form-horizontal" method="POST">
    <?=$this->getTokenField() ?>
    <div class="row mb-3">
        <label for="allowedImagesInput" class="col-xl-2 control-label">
            <?=$this->getTrans('allowedImages') ?>:
        </label>
        <div class="col-xl-8">
            <textarea class="form-control"
                      id="allowedImagesInput"
                      name="allowedImages"><?=$this->escape($this->get('media_ext_img')) ?></textarea>
        </div>
    </div>
    <div class="row mb-3">
        <label for="allowedVideosInput" class="col-xl-2 control-label">
            <?=$this->getTrans('allowedVideos') ?>:
        </label>
        <div class="col-xl-8">
            <textarea class="form-control"
                      id="allowedVideosInput"
                      name="allowedVideos"><?=$this->escape($this->get('media_ext_video')) ?></textarea>
        </div>
    </div>
    <div class="row mb-3">
        <label for="allowedFilesInput" class="col-xl-2 control-label">
            <?=$this->getTrans('allowedFiles') ?>:
        </label>
        <div class="col-xl-8">
            <textarea class="form-control"
                      id="allowedFilesInput"
                      name="allowedFiles"><?=$this->escape($this->get('media_ext_file')) ?></textarea>
        </div>
    </div>
    <div class="row mb-3">
        <label for="mediaPerPageInput" class="col-xl-2 control-label">
            <?=$this->getTrans('mediaPerPage') ?>:
        </label>
        <div class="col-xl-1">
            <input type="number"
                   class="form-control"
                   id="mediaPerPageInput"
                   name="mediaPerPage"
                   min="1"
                   value="<?=$this->escape($this->get('mediaPerPage')) ?>" />
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-xl-2 control-label">
            <?=$this->getTrans('directoriesAsCategories') ?>
        </div>
        <div class="col-xl-4">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" id="directoriesAsCategories-on" name="directoriesAsCategories" value="1" <?php if ($this->get('directoriesAsCategories') == '1') { echo 'checked="checked"'; } ?> />
                <label for="directoriesAsCategories-on" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('on') ?></label>
                <input type="radio" class="flipswitch-input" id="directoriesAsCategories-off" name="directoriesAsCategories" value="0" <?php if ($this->get('directoriesAsCategories') != '1') { echo 'checked="checked"'; } ?> />
                <label for="directoriesAsCategories-off" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('off') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
        </div>
    </div>
    <?=$this->getSaveBar() ?>
</form>

<?=$this->getDialog('infoModal', $this->getTrans('info'), $this->getTrans('allowedMediaInfoText')) ?>
