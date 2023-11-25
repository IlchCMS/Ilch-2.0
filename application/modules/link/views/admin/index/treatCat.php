<?php

/** @var \Ilch\View $this */

/** @var Modules\Link\Models\Category $cat */
$cat = $this->get('category');
?>
<h1><?=($cat->getId()) ? $this->getTrans('menuActionEditCategory') : $this->getTrans('menuActionNewCategory') ?></h1>
<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <div class="form-group <?=$this->validation()->hasError('name') ? 'has-error' : '' ?>">
        <label for="name" class="col-lg-2 control-label">
            <?=$this->getTrans('name') ?>:
        </label>
        <div class="col-lg-4">
            <input type="text"
                   class="form-control"
                   id="name"
                   name="name"
                   placeholder="Name"
                   value="<?=$this->escape($this->originalInput('name', $cat->getName())) ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="desc" class="col-lg-2 control-label">
            <?=$this->getTrans('description') ?>:
        </label>
        <div class="col-lg-4">
            <textarea class="form-control"
                      id="desc"
                      name="desc"
                      cols="45"
                      rows="3"><?=$this->escape($this->originalInput('desc', $cat->getDesc())) ?></textarea>
        </div>
    </div>
    <?=$this->getSaveBar($cat->getId() ? 'updateButton' : 'addButton') ?>
</form>
