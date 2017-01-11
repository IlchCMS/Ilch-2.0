<legend><?=$this->getTrans('settings') ?></legend>
<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <div class="form-group">
        <label for="image_height" class="col-lg-2 control-label">
            <?=$this->getTrans('imageHeight') ?>
        </label>
        <div class="col-lg-2">
            <input type="number"
                   class="form-control required"
                   id="image_height"
                   name="image_height"
                   value="<?=$this->get('teams_height') ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="image_width" class="col-lg-2 control-label">
            <?=$this->getTrans('imageWidth') ?>
        </label>
        <div class="col-lg-2">
            <input type="number"
                   class="form-control required"
                   id="image_width"
                   name="image_width"
                   value="<?=$this->get('teams_width') ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="image_filetypes" class="col-lg-2 control-label">
            <?=$this->getTrans('allowedFileExtensions') ?>
        </label>
        <div class="col-lg-2">
            <input type="text"
                   class="form-control required"
                   id="image_filetypes"
                   name="image_filetypes"
                   value="<?=$this->get('teams_filetypes') ?>" />
        </div>
    </div>
    <?=$this->getSaveBar() ?>
</form>
