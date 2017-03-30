<h1><?=$this->getTrans('settings') ?></h1>
<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <div class="form-group <?=$this->validation()->hasError('articlesPerPage') ? 'has-error' : '' ?>">
        <label for="articlesPerPageInput" class="col-lg-2 control-label">
            <?=$this->getTrans('articlesPerPage') ?>:
        </label>
        <div class="col-lg-1">
            <input type="number"
                   class="form-control"
                   id="articlesPerPageInput"
                   name="articlesPerPage"
                   min="1"
                   value="<?=($this->get('articlesPerPage') != '') ? $this->escape($this->get('articlesPerPage')) : $this->originalInput('articlesPerPage') ?>" />
        </div>
    </div>
    <?=$this->getSaveBar() ?>
</form>
