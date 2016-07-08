<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <legend><?=$this->getTrans('settings') ?></legend>
    <div class="form-group">
        <label for="threadsPerPageInput" class="col-lg-2 control-label">
            <?=$this->getTrans('threadsPerPage') ?>:
        </label>
        <div class="col-lg-8">
            <input class="form-control"
                   id="threadsPerPageInput"
                   name="threadsPerPage"
                   type="number"
                   min="1"
                   value="<?=$this->escape($this->get('threadsPerPage')) ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="postsPerPageInput" class="col-lg-2 control-label">
            <?=$this->getTrans('postsPerPage') ?>:
        </label>
        <div class="col-lg-8">
            <input class="form-control"
                   id="postsPerPageInput"
                   name="postsPerPage"
                   type="number"
                   min="1"
                   value="<?=$this->escape($this->get('postsPerPage')) ?>" />
        </div>
    </div>
    <?=$this->getSaveBar() ?>
</form>
