<h1><?=$this->getTrans('settings') ?></h1>
<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <div class="form-group">
        <label for="downloadsPerPageInput" class="col-lg-2 control-label">
            <?=$this->getTrans('downloadsPerPage') ?>:
        </label>
        <div class="col-lg-1">
            <input type="number"
                   class="form-control"
                   id="downloadsPerPageInput"
                   name="downloadsPerPage"
                   min="1"
                   value="<?=$this->escape($this->get('downloadsPerPage')) ?>" />
        </div>
    </div>
    <?=$this->getSaveBar() ?>
</form>
