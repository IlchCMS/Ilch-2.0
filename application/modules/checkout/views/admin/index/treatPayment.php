<form class="form-horizontal" method="POST" action="<?php echo $this->getUrl(array('action' => $this->getRequest()->getActionName())); ?>">
    <?php echo $this->getTokenField(); ?>
    <legend>
        <?php echo $this->getTrans('treatpayment'); ?>
    </legend>
    <?php
        foreach ($this->get('checkout') as $checkout) :
    ?>
    <div class="form-group">
        <div class="col-lg-4">
            <input class="form-control"
                   type="text"
                   name="name"
                   id="name"
                   placeholder="<?php echo $this->getTrans('name'); ?>"
                   value="<?php echo $this->escape($checkout->getName()); ?>" />
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-4">
            <input class="form-control"
                   type="text"
                   name="datetime"
                   id="datetime"
                   placeholder="<?php echo $this->getTrans('datetime'); ?>"
                   value="<?php echo $this->escape($checkout->getDatetime()); ?>" />
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-4">
            <input class="form-control"
                   type="text"
                   name="usage"
                   id="usage"
                   placeholder="<?php echo $this->getTrans('usage'); ?>"
                   value="<?php echo $this->escape($checkout->getUsage()); ?>" />
        </div>
    </div>
    <div class="form-group hidden">
        <div class="col-lg-4">
            <input class="form-control"
                   type="text"
                   name="id"
                   id="id"
                   value="<?php echo $this->escape($checkout->getId()); ?>" />
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-4">
            <input class="form-control"
                   type="text"
                   name="amount"
                   id="amount"
                   placeholder="<?php echo $this->getTrans('amount'); ?>"
                   data-content="<?php echo $this->getTrans('amountinfo'); ?>" 
                   rel="popover" 
                   data-placement="bottom" 
                   data-original-title="<?php echo $this->getTrans('amount'); ?>" 
                   data-trigger="hover"
                   value="<?php echo $this->escape($checkout->getAmount()); ?>" />
        </div>
    </div>
    <?php
        endforeach;
    ?>
    <?=$this->getSaveBar('editButton')?>
</form>
<script>
    $('#amount').popover();
</script>