<?php
$currency = $this->escape($this->get('currency'));
?>

<legend><?=$this->getTrans('bookings') ?></legend>
<?php if ($this->validation()->hasErrors()): ?>
    <div class="alert alert-danger" role="alert">
        <strong> <?=$this->getTrans('errorsOccured') ?>:</strong>
        <ul>
            <?php foreach ($this->validation()->getErrorMessages() as $error): ?>
                <li><?= $error; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
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
        <legend><?=$this->getTrans('bankbalance') ?></legend>
        <div class="panel panel-default">
            <div class="panel-body">
                <strong>
                    <?php if ($this->get('amount') != '') { echo $this->getTrans('balancetotal'),': ', $this->escape($this->getFormattedCurrency($this->get('amount'), $currency)); } 
                    else { echo $this->getTrans('balancetotal'), ': ', $this->escape($this->getFormattedCurrency(0, $currency)) ;}
                    ?>
                </strong>
                <br>
                <?php if ($this->get('amountplus') != '') { echo $this->getTrans('totalpaid'),': ', $this->escape($this->getFormattedCurrency($this->get('amountplus'), $currency)); } 
                else { echo $this->getTrans('totalpaid'), ': ', $this->escape($this->getFormattedCurrency(0, $currency)) ;}
                ?>
                <br>
                <?php if ($this->get('amountminus') != '') { echo $this->getTrans('totalpaidout'),': ', $this->escape($this->getFormattedCurrency($this->get('amountminus'), $currency)); } 
                else { echo $this->getTrans('totalpaidout'), ': ', $this->escape($this->getFormattedCurrency(0, $currency)) ;}
                ?>
            </div>
        </div>

        <legend> <?=$this->getTrans('bookedpayments') ?></legend>
        <div class="panel-default">
            <ul class="list-group">
                <?php foreach ($this->get('checkout') as $checkout): ?>
                    <li class="list-group-item">
                        <?=$this->escape($checkout->getName()) ?>: 
                        <strong>
                            <?=$this->escape($this->getFormattedCurrency($checkout->getAmount(), $currency)) ?>
                        </strong> 
                        <?=$this->getTrans('for') ?>: 
                        <?=$this->escape($checkout->getUsage()) ?>
                        <?=$this->getEditIcon(['action' => 'treatPayment', 'id' => $this->escape($checkout->getId())]) ?>
                        <?=$this->getDeleteIcon(['action' => 'del', 'id' => $this->escape($checkout->getId())]) ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <br>
        <br>
    </div>
    <?=$this->getSaveBar($this->getTrans('book')) ?>
</form>

<script>
$('#amount').popover();
</script>
