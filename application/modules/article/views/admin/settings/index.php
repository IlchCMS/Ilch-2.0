<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <legend><?=$this->getTrans('settings') ?></legend>
    <div class="form-group">
        <label for="articlesPerPageInput" class="col-lg-2 control-label">
            <?=$this->getTrans('articlePerPage') ?>:
        </label>
        <div class="col-lg-2">
            <input class="form-control"
                   id="articlesPerPageInput"
                   name="articlesPerPage"
                   type="number"
                   min="1"
                   value="<?=$this->escape($this->get('articlesPerPage')) ?>" />
        </div>
    </div>
    <?=$this->getSaveBar() ?>
</form>
