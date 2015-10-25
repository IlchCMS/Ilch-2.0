<legend><?=$this->getTrans('bookings') ?></legend>
<form class="form-horizontal" method="POST" action="<?=$this->getUrl(array('action' => $this->getRequest()->getActionName())) ?>">
    <?=$this->getTokenField() ?>
    <div class="form-group">
        <div class="col-lg-4">
            <input class="form-control"
                   type="text"
                   name="name"
                   id="name"
                   placeholder="<?=$this->getTrans('name') ?>"
                   value="" />
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-4">
            <input class="form-control"
                   type="text"
                   name="datetime"
                   id="datetime"
                   placeholder="<?=$this->getTrans('datetime') ?>"
                   value="<?php if ($this->get('checkoutdate') != '') { echo $this->get('checkoutdate'); } ?>" />
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-4">
            <input class="form-control"
                   type="text"
                   name="usage"
                   id="usage"
                   placeholder="<?=$this->getTrans('usage') ?>"
                   value="" />
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-4">
            <input class="form-control"
                   type="text"
                   name="amount"
                   id="amount"
                   placeholder="<?=$this->getTrans('amount') ?>"
                   data-content="<?=$this->getTrans('amountinfo') ?>" 
                   rel="popover" 
                   data-placement="bottom" 
                   data-original-title="<?=$this->getTrans('amount') ?>" 
                   data-trigger="hover"
                   value="" />
        </div>
    </div>
    <br>
    <br>
    <div class="col-lg-4">
        <legend><?=$this->getTrans('bankbalance') ?></legend>
        <div class="panel panel-default">
            <div class="panel-body">
                <strong>
                    <?php if ($this->get('amount') != '') { echo $this->getTrans('balancetotal'),': ', $this->get('amount'), $this->getTrans('currency') ; } 
                    else { echo $this->getTrans('balancetotal'), ': 0', $this->getTrans('currency') ;}
                    ?>
                </strong>
                <br>
                <?php if ($this->get('amountplus') != '') { echo $this->getTrans('totalpaid'),': ', $this->get('amountplus'), $this->getTrans('currency') ; }
                else { echo $this->getTrans('totalpaid'), ': 0', $this->getTrans('currency') ;}
                ?>
                <br>
                <?php if ($this->get('amountminus') != '') { echo $this->getTrans('totalpaidout'),': ', $this->get('amountminus'), $this->getTrans('currency') ; }
                else { echo $this->getTrans('totalpaidout'), ': 0', $this->getTrans('currency') ;}
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
                            <?=$this->escape($checkout->getAmount()) ?> 
                            <?=$this->getTrans('currency') ?>
                        </strong> 
                        <?=$this->getTrans('for') ?>: 
                        <?=$this->escape($checkout->getUsage()) ?>
                        <?=$this->getEditIcon(array('action' => 'treatPayment', 'id' => $this->escape($checkout->getId()))) ?>
                        <?=$this->getDeleteIcon(array('action' => 'del', 'id' => $this->escape($checkout->getId()))) ?>
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
