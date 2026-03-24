<?php

/** @var \Ilch\View $this */

/** @var \Modules\Media\Models\Media $cat */
$cat = $this->get('cat');
?>
<h1><?=$this->getTrans('treatCat') ?></h1>
<?php if ($cat): ?>
<form method="POST">
    <?=$this->getTokenField() ?>
    <div class="row mb-3">
        <div class="input-group-option col-xl-6 col-lg-6 col-12">
            <input type="text" class="form-control" name="title_treat" placeholder="<?=$this->escape($cat->getCatName()) ?>">
        </div>
    </div>
    <?=$this->getSaveBar('saveButton') ?>
</form>
<?php else: ?>
    <?=$this->getTrans('treatCatError') ?>
<?php endif; ?>
