<legend><?=$this->getTrans('settings') ?></legend>
<form method="POST" class="form-horizontal" action="">
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
