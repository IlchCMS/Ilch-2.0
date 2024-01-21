<?php

/** @var \Ilch\View $this */
?>
<h1><?=($this->get('prefix')) ? $this->getTrans('edit') : $this->getTrans('add') ?></h1>
<form class="form-horizontal" method="POST">
    <?=$this->getTokenField() ?>
    <div class="form-group <?=$this->validation()->hasError('prefix') ? 'has-error' : '' ?>">
        <label for="prefix" class="col-lg-2 control-label">
            <?=$this->getTrans('prefix') ?>:
        </label>
        <div class="col-lg-2">
            <input type="text"
                   class="form-control"
                   id="prefix"
                   name="prefix"
                   value="<?=($this->get('prefix')) ? $this->escape($this->get('prefix')->getPrefix()) : '' ?>"
                   required />
        </div>
    </div>
    <?=$this->getSaveBar() ?>
</form>
