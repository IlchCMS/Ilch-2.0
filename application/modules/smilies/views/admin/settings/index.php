<h1><?=$this->getTrans('settings') ?></h1>
<form method="POST" class="form-horizontal">
    <?=$this->getTokenField() ?>
    <div class="form-group">
        <label for="smiley_filetypes" class="col-lg-2 control-label">
            <?=$this->getTrans('allowedFileExtensions') ?>:
        </label>
        <div class="col-lg-2">
            <input type="text"
                   class="form-control required"
                   id="smiley_filetypes"
                   name="smiley_filetypes"
                   value="<?=$this->get('smiley_filetypes') ?>" />
        </div>
    </div>
    <?=$this->getSaveBar(); ?>
</form>
