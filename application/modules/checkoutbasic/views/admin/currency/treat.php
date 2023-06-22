<?php

/** @var \Ilch\View $this */

/** @var Modules\Checkout\Models\Currency|null $currency */
$currency = $this->get('currency');
?>

<h1>
    <?=($currency) ? $this->getTrans('edit') : $this->getTrans('add') ?>
</h1>
<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <div class="form-group <?=$this->validation()->hasError('name') ? 'has-error' : '' ?>">
        <label for="name" class="col-lg-2 control-label">
            <?=$this->getTrans('name') ?>
        </label>
        <div class="col-lg-4">
            <input type="text"
                   class="form-control"
                   id="name"
                   name="name"
                   placeholder="<?=$this->getTrans('name') ?>"
                   value="<?=$this->escape($currency->getName()) ?>" />
        </div>
    </div>
    <?=$this->getSaveBar() ?>
</form>
