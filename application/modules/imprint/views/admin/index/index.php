<?php

/** @var \Ilch\View $this */

/** @var \Modules\Imprint\Models\Imprint $imprint */
$imprint = $this->get('imprint');
?>
<h1><?=$this->getTrans('manage') ?></h1>
<form method="POST">
    <?=$this->getTokenField() ?>
    <div class="row mb-3 <?=$this->validation()->hasError('imprint') ? 'has-error' : '' ?>">
        <label for="ck_2" class="col-xl-2 col-form-label">
            <?=$this->getTrans('imprint') ?>:
        </label>
        <div class="col-xl-12">
           <textarea class="form-control ckeditor"
                     id="ck_2"
                     name="imprint"
                     toolbar="ilch_html"
                     cols="60"
                     rows="5"><?=$this->escape($this->originalInput('imprint', $imprint->getImprint())) ?></textarea>
        </div>
    </div>
    <?=$this->getSaveBar('updateButton') ?>
</form>
<?=$this->getDialog('mediaModal', $this->getTrans('media'), '<iframe frameborder="0"></iframe>') ?>
