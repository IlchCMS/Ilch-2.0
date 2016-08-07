<legend><?=$this->getTrans('settings') ?></legend>
<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <div class="form-group">
        <label for="picturesPerPageInput" class="col-lg-2 control-label">
            <?=$this->getTrans('picturesPerPage') ?>:
        </label>
        <div class="col-lg-1">
            <input type="number"
                   class="form-control"
                   id="picturesPerPageInput"
                   name="picturesPerPage"
                   min="1"
                   value="<?=$this->escape($this->get('picturesPerPage')) ?>" />
        </div>
    </div>
    <?=$this->getSaveBar() ?>
</form>
