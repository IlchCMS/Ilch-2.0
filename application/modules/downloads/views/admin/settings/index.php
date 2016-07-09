<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <legend><?=$this->getTrans('settings') ?></legend>
    <div class="form-group">
        <label for="downloadsPerPageInput" class="col-lg-2 control-label">
            <?=$this->getTrans('downloadsPerPage') ?>:
        </label>
        <div class="col-lg-2">
            <input class="form-control"
                   id="downloadsPerPageInput"
                   name="downloadsPerPage"
                   type="number"
                   min="1"
                   value="<?=$this->escape($this->get('downloadsPerPage')) ?>" />
        </div>
    </div>
    <?=$this->getSaveBar() ?>
</form>
