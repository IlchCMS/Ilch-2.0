<?php

/** @var \Ilch\View $this */

/** @var \Modules\Checkoutbasic\Models\Entry $checkout */
$checkout = $this->get('checkout');
?>

<h1><?=$this->getTrans('treatpayment') ?></h1>
<form method="POST" action="">
    <?=$this->getTokenField() ?>
    <div class="row mb-3<?=$this->validation()->hasError('name') ? ' has-error' : '' ?>">
        <label for="name" class="col-xl-2 col-form-label">
            <?=$this->getTrans('name') ?>
        </label>
        <div class="col-xl-4">
            <input type="text"
                   class="form-control"
                   id="name"
                   name="name"
                   placeholder="<?=$this->getTrans('name') ?>"
                   value="<?=$this->originalInput('name', $checkout->getName(), true) ?>" />
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('datetime') ? ' has-error' : '' ?>">
        <label for="datetime" class="col-xl-2 col-form-label">
            <?=$this->getTrans('datetime') ?>
        </label>
        <div class="col-xl-4">
            <input type="text"
                   class="form-control"
                   id="datetime"
                   name="datetime"
                   placeholder="<?=$this->getTrans('datetime') ?>"
                   value="<?=$this->originalInput('datetime', $checkout->getDateTime(), true) ?>" />
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('usage') ? ' has-error' : '' ?>">
        <label for="usage" class="col-xl-2 col-form-label">
            <?=$this->getTrans('usage') ?>
        </label>
        <div class="col-xl-4">
            <input type="text"
                   class="form-control"
                   id="usage"
                   name="usage"
                   placeholder="<?=$this->getTrans('usage') ?>"
                   value="<?=$this->originalInput('usage', $checkout->getUsage(), true) ?>" />
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('amount') ? ' has-error' : '' ?>">
        <label for="amount" class="col-xl-2 col-form-label">
            <?=$this->getTrans('amount') ?>
        </label>
        <div class="col-xl-4">
            <input type="text"
                   class="form-control"
                   id="amount"
                   name="amount"
                   placeholder="<?=$this->getTrans('amount') ?>"
                   data-bs-toggle="tooltip"
                   data-bs-placement="bottom"
                   data-bs-custom-class="custom-tooltip"
                   data-bs-title="<?=$this->getTrans('amountinfo') ?>"
                   value="<?=$this->originalInput('amount', $checkout->getAmount(), true) ?>" />
        </div>
    </div>
    <?=$this->getSaveBar('updateButton') ?>
</form>
