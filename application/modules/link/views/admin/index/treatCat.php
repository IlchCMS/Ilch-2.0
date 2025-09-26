<?php

/** @var \Ilch\View $this */

/** @var Modules\Link\Models\Category $cat */
$cat = $this->get('category');
?>
<h1><?=$this->getTrans($cat->getId() ? 'menuActionEditCategory' : 'menuActionNewCategory') ?></h1>
<form method="POST" action="">
    <?=$this->getTokenField() ?>
    <div class="row mb-3<?=$this->validation()->hasError('name') ? ' has-error' : '' ?>">
        <label for="name" class="col-xl-2 col-form-label">
            <?=$this->getTrans('name') ?>:
        </label>
        <div class="col-xl-4">
            <input type="text"
                   class="form-control"
                   id="name"
                   name="name"
                   placeholder="Name"
                   value="<?=$this->originalInput('name', $cat->getName(), true) ?>" />
        </div>
    </div>
    <div class="row mb-3">
        <label for="desc" class="col-xl-2 col-form-label">
            <?=$this->getTrans('description') ?>:
        </label>
        <div class="col-xl-4">
            <textarea class="form-control"
                      id="desc"
                      name="desc"
                      cols="45"
                      rows="3"><?=$this->originalInput('desc', $cat->getDesc(), true) ?></textarea>
        </div>
    </div>
    <?=$this->getSaveBar($cat->getId() ? 'updateButton' : 'addButton') ?>
</form>
