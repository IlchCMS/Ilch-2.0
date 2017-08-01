<h1><?=$this->getTrans('settings') ?></h1>
<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <div class="form-group <?=$this->validation()->hasError('headername') ? 'has-error' : '' ?>">
        <label for="headername" class="col-lg-2 control-label">
            <?=$this->getTrans('headername') ?>
        </label>
        <div class="col-lg-4">
            <input type="text"
                   class="form-control"
                   id="headername"
                   name="headername"
                   value="<?=$this->escape($this->get('headername')->getValue()) ?>"
                   required />
        </div>
    </div>
    <?=$this->getSaveBar() ?>
</form>
