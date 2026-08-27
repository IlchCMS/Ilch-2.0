<?php

/** @var \Ilch\View $this */

$currency = $this->escape($this->get('currency'));

/** @var \Modules\Checkout\Models\Entry[]|null $checkouts */
$checkouts = $this->get('checkout');
?>

<h1><?=$this->getTrans('accountdata') ?></h1>
<?=($this->get('checkout_contact') != '') ? '<div class="ck-content">' . $this->purify($this->get('checkout_contact')) . '</div>' : '' ?>
<br>
<br>
<h1><?=$this->getTrans('bankbalance') ?></h1>
<div>
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
            echo $this->getTrans('totalpaid'), ': ', $this->escape($this->getFormattedCurrency(0, $currency));
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
<br>
<br>
<h1><?=$this->getTrans('bookedpayments') ?></h1>
<table class="table table-hover table-striped">
    <colgroup>
        <col class="col-xl-3">
        <col>
        <col class="col-xl-3">
        <col>
    </colgroup>
    <thead>
        <tr>
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
            <td><?=$this->escape($date->format(null, true)) ?></td>
            <td><?=$this->escape($checkout->getName()) ?></td>
            <td><?=$this->escape($this->getFormattedCurrency($checkout->getAmount(), $currency)) ?></td>
            <td><?=$this->escape($checkout->getUsage()) ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
