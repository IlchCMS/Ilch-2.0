<?php

/** @var \Ilch\View $this */

/** @var \Modules\Events\Models\Currency|null $currency */
$currency = $this->get('currency');
?>
<h1><?=($this->getRequest()->getParam('id')) ? $this->getTrans('edit') : $this->getTrans('add') ?></h1>
<form method="POST" action="">
    <?=$this->getTokenField() ?>
    <div class="row mb-3<?=$this->validation()->hasError('name') ? ' has-error' : '' ?>">
        <div class="col-xl-4">
            <input type="text"
                   class="form-control"
                   id="name"
                   name="name"
                   placeholder="<?=$this->getTrans('name') ?>"
                   value="<?=($currency != '') ? $this->escape($currency->getName()) : $this->originalInput('name') ?>" />
        </div>
    </div>
    <?=$this->getSaveBar() ?>
</form>
