<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <legend><?=$this->getTrans('settings') ?></legend>
    <div class="form-group">
        <label for="picturesPerPageInput" class="col-lg-2 control-label">
            <?=$this->getTrans('picturesPerPage') ?>:
        </label>
        <div class="col-lg-2">
            <input class="form-control"
                   id="picturesPerPageInput"
                   name="picturesPerPage"
                   type="number"
                   min="1"
                   value="<?=$this->escape($this->get('picturesPerPage')) ?>" />
        </div>
    </div>
    <?=$this->getSaveBar() ?>
</form>
