<?php
$currency = $this->escape($this->get('currency'));
$date;
?>

<h1><?=$this->getTrans('bookings') ?></h1>
<form class="form-horizontal" method="POST" action="<?=$this->getUrl(['action' => $this->getRequest()->getActionName()]) ?>">
    <?=$this->getTokenField() ?>
    <div class="form-group <?=$this->validation()->hasError('name') ? 'has-error' : '' ?>">
        <div class="col-lg-4">
            <input type="text"
                   class="form-control"
                   id="name"
                   name="name"
                   placeholder="<?=$this->getTrans('name') ?>"
                   value="<?=$this->escape($this->originalInput('name')) ?>" />
        </div>
    </div>
    <div class="form-group <?=$this->validation()->hasError('checkoutdate') ? 'has-error' : '' ?>">
        <div class="col-lg-4">
            <input type="text"
                   class="form-control"
                   id="datetime"
                   name="datetime"
                   placeholder="<?=$this->getTrans('datetime') ?>"
                   value="<?php if ($this->get('checkoutdate') != '') { echo $this->get('checkoutdate'); } ?>" />
        </div>
    </div>
    <div class="form-group <?=$this->validation()->hasError('usage') ? 'has-error' : '' ?>">
        <div class="col-lg-4">
            <input type="text"
                   class="form-control"
                   id="usage"
                   name="usage"
                   placeholder="<?=$this->getTrans('usage') ?>"
                   value="<?=$this->escape($this->originalInput('usage')) ?>" />
        </div>
    </div>
    <div class="form-group <?=$this->validation()->hasError('amount') ? 'has-error' : '' ?>">
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
                   value="<?=$this->escape($this->originalInput('amount')) ?>" />
        </div>
    </div>
    <br>
    <br>
    <div class="col-lg-4">
        <h1><?=$this->getTrans('bankbalance') ?></h1>
        <div class="panel panel-default">
            <div class="panel-body">
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
        </div>
    </div>
    <div class="col-lg-12">
        <h1><?=$this->getTrans('bookedpayments') ?></h1>
        <table class="table table-hover table-striped">
            <colgroup>
                <col class="icon_width">
                <col class="icon_width">
                <col class="col-lg-2">
                <col>
                <col class="col-lg-2">
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
            <?php foreach ($this->get('checkout') as $checkout): ?>
                <?php $date = new \Ilch\Date($checkout->getDatetime()); ?>
                <tr>
                    <td><?=$this->getEditIcon(['action' => 'treatPayment', 'id' => $this->escape($checkout->getId())]) ?></td>
                    <td><?=$this->getDeleteIcon(['action' => 'del', 'id' => $this->escape($checkout->getId())]) ?></td>
                    <td><?=$this->escape($date->format(null, true)) ?></td>
                    <td><?=$this->escape($checkout->getName()) ?></td>
                    <td><?=$this->escape(number_format($checkout->getAmount(), 2, '.', '')) ?> <?=$currency ?></td>
                    <td><?=$this->escape($checkout->getUsage()) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?=$this->getSaveBar($this->getTrans('book')) ?>
</form>

<script>
$('#amount').popover();
</script>
