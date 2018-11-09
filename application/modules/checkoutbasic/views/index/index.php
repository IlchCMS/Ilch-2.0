<?php
$currency = $this->escape($this->get('currency'));
$date;
?>

<h1><?=$this->getTrans('accountdata') ?></h1>
<?php if ($this->get('checkout_contact') != '') { echo $this->get('checkout_contact') ; } ?>
<br>
<br>
<h1><?=$this->getTrans('bankbalance') ?></h1>
<div>
    <strong>
        <?php if ($this->get('amount') != '') { echo $this->getTrans('balancetotal'),': ', $this->get('amount'), ' '.$currency ; } 
        else { echo $this->getTrans('balancetotal'), ': 0 ', $currency ;}
        ?>
    </strong>
    <br>
    <?php if ($this->get('amountplus') != '') { echo $this->getTrans('totalpaid'),': ', $this->get('amountplus'), ' '.$currency ; } 
    else { echo $this->getTrans('totalpaid'), ': 0 ', $currency ;}
    ?>
    <br>
    <?php if ($this->get('amountminus') != '') { echo $this->getTrans('totalpaidout'),': ', $this->get('amountminus'), ' '.$currency ; }
    else { echo $this->getTrans('totalpaidout'), ': 0 ', $currency ;}
    ?>
</div>
<br>
<br>
<h1><?=$this->getTrans('bookedpayments') ?></h1>
<table class="table table-hover table-striped">
    <colgroup>
        <col class="col-lg-2">
        <col>
        <col class="col-lg-2">
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
    <?php foreach ($this->get('checkout') as $checkout): ?>
        <?php $date = new \Ilch\Date($checkout->getDatetime()); ?>
        <tr>
            <td><?=$this->escape($date->format(null, true)) ?></td>
            <td><?=$this->escape($checkout->getName()) ?></td>
            <td><?=$this->escape(number_format($checkout->getAmount(), 2, '.', '')) ?> <?=$currency ?></td>
            <td><?=$this->escape($checkout->getUsage()) ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
