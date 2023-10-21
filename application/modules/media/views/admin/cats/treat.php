<h1><?=$this->getTrans('treatCat') ?></h1>
<?php if ($this->get('cat')): ?>
<form class="form-horizontal" method="POST">
    <?=$this->getTokenField() ?>
    <div class="row mb-3">
        <div class="input-group-option col-xl-6 col-lg-6 col-12">
            <input type="text" class="form-control" name="title_treat" placeholder="<?=$this->escape($this->get('cat')->getCatName()) ?>">
        </div>
    </div>
    <?=$this->getSaveBar('saveButton') ?>
</form>
<?php else: ?>
    <?=$this->getTrans('treatCatError') ?>
<?php endif; ?>
