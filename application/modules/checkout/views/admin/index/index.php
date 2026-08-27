<?php

/** @var \Ilch\View $this */

$currency = $this->escape($this->get('currency'));

/** @var \Modules\Checkout\Models\Entry[]|null $checkouts */
$checkouts = $this->get('checkout');
?>

<h1><?=$this->getTrans('bookings') ?></h1>
<form method="POST" action="<?=$this->getUrl(['action' => $this->getRequest()->getActionName()]) ?>">
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
                   value="<?=$this->escape($this->originalInput('name')) ?>" />
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('checkoutdate') ? ' has-error' : '' ?>">
        <label for="datetime" class="col-xl-2 col-form-label">
            <?=$this->getTrans('datetime') ?>
        </label>
        <div class="col-xl-4">
            <input type="text"
                   class="form-control"
                   id="datetime"
                   name="datetime"
                   placeholder="<?=$this->getTrans('datetime') ?>"
                   value="<?=($this->get('checkoutdate') != '') ? $this->get('checkoutdate') : '' ?>" />
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
                   value="<?=$this->escape($this->originalInput('usage')) ?>" />
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
                   value="<?=$this->escape($this->originalInput('amount')) ?>" />
        </div>
    </div>
    <br>
    <br>
    <div class="col-xl-4">
        <h1><?=$this->getTrans('bankbalance') ?></h1>
        <div class="card card-default">
            <div class="card-body">
                <strong>
                    <?php
                    if ($this->get('amount') != '') {
                        echo $this->getTrans('balancetotal'),': ', $this->escape($this->getFormattedCurrency($this->get('amount'), $currency));
                    } else {
                        echo $this->getTrans('balancetotal'), ': ', $this->escape($this->getFormattedCurrency(0, $currency)) ;
                    }
                    ?>
                </strong>
                <br>
                    <?php
                    if ($this->get('amountplus') != '') {
                        echo $this->getTrans('totalpaid'), ': ', $this->escape($this->getFormattedCurrency($this->get('amountplus'), $currency));
                    } else {
                        echo $this->getTrans('totalpaid'), ': ', $this->escape($this->getFormattedCurrency(0, $currency)) ;
                    }
                    ?>
                <br>
                <?php
                if ($this->get('amountminus') != '') {
                    echo $this->getTrans('totalpaidout'),': ', $this->escape($this->getFormattedCurrency($this->get('amountminus'), $currency));
                } else {
                    echo $this->getTrans('totalpaidout'), ': ', $this->escape($this->getFormattedCurrency(0, $currency)) ;
                }
                ?>
            </div>
        </div>
    </div>
    <br>
    <br>
    <div class="col-xl-12">
        <h1><?=$this->getTrans('bookedpayments') ?></h1>
        <table class="table table-hover table-striped">
            <colgroup>
                <col class="icon_width">
                <col class="icon_width">
                <col class="col-xl-2">
                <col>
                <col class="col-xl-2">
                <col>
            </colgroup>
            <thead>
                <tr>
                    <th></th>
                    <th></th>
                    <th><?=$this->getTrans('datetime') ?></th>
                    <th><?=$this->getTrans('name') ?></th>
                    <th><?=$this->getTrans('amount') ?></th>
                    <th><?=$this->getTrans('usage') ?></th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($checkouts ?? [] as $checkout) : ?>
                <?php $date = new \Ilch\Date($checkout->getDatetime()); ?>
                <tr>
                    <td><?=$this->getEditIcon(['action' => 'treatPayment', 'id' => $this->escape($checkout->getId())]) ?></td>
                    <td><?=$this->getDeleteIcon(['action' => 'del', 'id' => $this->escape($checkout->getId())]) ?></td>
                    <td><?=$this->escape($date->format(null, true)) ?></td>
                    <td><?=$this->escape($checkout->getName()) ?></td>
                    <td><?=$this->escape($this->getFormattedCurrency($checkout->getAmount(), $currency)) ?></td>
                    <td><?=$this->escape($checkout->getUsage()) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?=$this->getSaveBar($this->getTrans('book')) ?>
</form>
