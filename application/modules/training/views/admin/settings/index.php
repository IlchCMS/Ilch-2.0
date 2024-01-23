<?php

/** @var \Ilch\View $this */
?>
<h1><?=$this->getTrans('settings') ?></h1>
<form class="form-horizontal" method="POST">
    <?=$this->getTokenField() ?>

    <h1><?=$this->getTrans('boxSettings') ?></h1>
    <div class="form-group <?=$this->validation()->hasError('boxNexttrainingLimit') ? 'has-error' : '' ?>">
        <label for="limitNextTrainingInput" class="col-lg-2 control-label">
            <?=$this->getTrans('boxNexttrainingLimit') ?>:
        </label>
        <div class="col-lg-1">
            <input type="number"
                   class="form-control"
                   id="limitNextTrainingInput"
                   name="boxNexttrainingLimit"
                   min="1"
                   value="<?=$this->escape($this->originalInput('boxNexttrainingLimit', $this->get('boxNexttrainingLimit'))) ?>" />
        </div>
    </div>
    <?=$this->getSaveBar() ?>
</form>
