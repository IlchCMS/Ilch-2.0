<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <legend><?=$this->getTrans('settings') ?></legend>
    <div class="form-group">
        <label for="warsPerPageInput" class="col-lg-2 control-label">
            <?=$this->getTrans('warsPerPagePerPage') ?>:
        </label>
        <div class="col-lg-8">
            <input class="form-control"
                   id="warsPerPageInput"
                   name="warsPerPage"
                   type="number"
                   min="1"
                   value="<?=$this->escape($this->get('warsPerPage')) ?>" />
        </div>
    </div>
    <?=$this->getSaveBar() ?>
</form>
