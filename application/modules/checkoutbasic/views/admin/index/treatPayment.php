<?php

/** @var \Ilch\View $this */

/** @var \Modules\Checkoutbasic\Models\Entry $checkout */
$checkout = $this->get('checkout');
?>

<h1><?=$this->getTrans('treatpayment') ?></h1>
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
                   value="<?=($this->originalInput('name') != '') ? $this->escape($this->originalInput('name')) : $this->escape($checkout->getName()) ?>" />
        </div>
    </div>
    <div class="form-group <?=$this->validation()->hasError('datetime') ? 'has-error' : '' ?>">
        <label for="datetime" class="col-lg-2 control-label">
            <?=$this->getTrans('datetime') ?>
        </label>
        <div class="col-lg-4">
            <input type="text"
                   class="form-control"
                   id="datetime"
                   name="datetime"
                   placeholder="<?=$this->getTrans('datetime') ?>"
                   value="<?=($this->originalInput('datetime') != '') ? $this->escape($this->originalInput('datetime')) : $this->escape($checkout->getDateTime()) ?>" />
        </div>
    </div>
    <div class="form-group <?=$this->validation()->hasError('usage') ? 'has-error' : '' ?>">
        <label for="usage" class="col-lg-2 control-label">
            <?=$this->getTrans('usage') ?>
        </label>
        <div class="col-lg-4">
            <input type="text"
                   class="form-control"
                   id="usage"
                   name="usage"
                   placeholder="<?=$this->getTrans('usage') ?>"
                   value="<?=($this->originalInput('usage') != '') ? $this->escape($this->originalInput('usage')) : $this->escape($checkout->getUsage()) ?>" />
        </div>
    </div>
    <div class="form-group <?=$this->validation()->hasError('amount') ? 'has-error' : '' ?>">
        <label for="amount" class="col-lg-2 control-label">
            <?=$this->getTrans('amount') ?>
        </label>
        <div class="col-lg-4">
            <input type="text"
                   class="form-control"
                   id="amount"
                   name="amount"
                   placeholder="<?=$this->getTrans('amount') ?>"
                   data-content="<?=$this->getTrans('amountinfo') ?>" 
                   rel="popover" 
                   data-placement="bottom" 
                   data-original-title="<?=$this->getTrans('amount') ?>" 
                   data-trigger="hover"
                   value="<?=($this->originalInput('amount') != '') ? $this->escape($this->originalInput('amount')) : $this->escape($checkout->getAmount()) ?>" />
        </div>
    </div>
    <?=$this->getSaveBar('updateButton') ?>
</form>

<script>
$('#amount').popover();
</script>
