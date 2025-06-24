<?php

/** @var \Ilch\View $this */

/** @var \Modules\Article\Models\Category $cat */
$cat = $this->get('cat');
?>
<h1><?=($cat != '') ? $this->getTrans('edit') : $this->getTrans('add') ?></h1>
<form method="POST">
    <?=$this->getTokenField() ?>
    <div class="row mb-3<?=$this->validation()->hasError('name') ? ' has-error' : '' ?>">
        <label for="name" class="col-xl-2 col-form-label">
            <?=$this->getTrans('name') ?>:
        </label>
        <div class="col-xl-3">
            <input type="text"
                   class="form-control"
                   id="name"
                   name="name"
                   value="<?=($cat != '') ? $this->escape($cat->getName()) : $this->originalInput('name') ?>" />
        </div>
    </div>
    <?=($cat != '') ? $this->getSaveBar('updateButton') : $this->getSaveBar('addButton') ?>
</form>
