<?php

/** @var \Ilch\View $this */

/** @var \Modules\Imprint\Models\Imprint $imprint */
$imprint = $this->get('imprint');
?>
<h1><?=$this->getTrans('manage') ?></h1>
<form class="form-horizontal" method="POST">
    <?=$this->getTokenField() ?>
    <div class="form-group <?=$this->validation()->hasError('imprint') ? 'has-error' : '' ?>">
        <label for="ck_2" class="col-lg-2 control-label">
            <?=$this->getTrans('imprint') ?>:
        </label>
        <div class="col-lg-12">
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
