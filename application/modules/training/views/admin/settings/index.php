<?php

/** @var \Ilch\View $this */
?>
<h1><?=$this->getTrans('settings') ?></h1>
<form method="POST">
    <?=$this->getTokenField() ?>

    <h1><?=$this->getTrans('boxSettings') ?></h1>
    <div class="row mb-3<?=$this->validation()->hasError('boxNexttrainingLimit') ? ' has-error' : '' ?>">
        <label for="limitNextTrainingInput" class="col-xl-2 col-form-label">
            <?=$this->getTrans('boxNexttrainingLimit') ?>:
        </label>
        <div class="col-xl-1">
            <input type="number"
                   class="form-control"
                   id="limitNextTrainingInput"
                   name="boxNexttrainingLimit"
                   min="1"
                   value="<?=$this->originalInput('boxNexttrainingLimit', $this->get('boxNexttrainingLimit'), true) ?>" />
        </div>
    </div>
    <?=$this->getSaveBar() ?>
</form>
