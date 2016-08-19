<legend><?=$this->getTrans('settings') ?></legend>
<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <div class="form-group<?=in_array('articlesPerPage', $this->get('errorFields')) ? ' has-error' : '' ?>">
        <label for="articlesPerPageInput" class="col-lg-2 control-label">
            <?=$this->getTrans('articlePerPage') ?>:
        </label>
        <div class="col-lg-1">
            <input type="number"
                   class="form-control"
                   id="articlesPerPageInput"
                   name="articlesPerPage"
                   min="1"
                   value="<?=$this->escape($this->get('articlesPerPage')) ?>" />
        </div>
    </div>
    <?=$this->getSaveBar() ?>
</form>
