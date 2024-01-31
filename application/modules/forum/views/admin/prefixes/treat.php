<?php

/** @var \Ilch\View $this */
?>
<h1><?=($this->get('prefix')) ? $this->getTrans('edit') : $this->getTrans('add') ?></h1>
<form class="form-horizontal" method="POST">
    <?=$this->getTokenField() ?>
    <div class="row mb-3 <?=$this->validation()->hasError('prefix') ? 'has-error' : '' ?>">
        <label for="prefix" class="col-xl-2 control-label">
            <?=$this->getTrans('prefix') ?>:
        </label>
        <div class="col-xl-2">
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
