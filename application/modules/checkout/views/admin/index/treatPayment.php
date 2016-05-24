<form class="form-horizontal" method="POST" action="<?=$this->getUrl(['action' => $this->getRequest()->getActionName()]) ?>">
    <?=$this->getTokenField() ?>
    <legend><?=$this->getTrans('treatpayment') ?></legend>
    <?php foreach ($this->get('checkout') as $checkout): ?>
    <div class="form-group">
        <div class="col-lg-4">
            <input class="form-control"
                   type="text"
                   name="name"
                   id="name"
                   placeholder="<?=$this->getTrans('name') ?>"
                   value="<?=$this->escape($checkout->getName()) ?>" />
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-4">
            <input class="form-control"
                   type="text"
                   name="datetime"
                   id="datetime"
                   placeholder="<?=$this->getTrans('datetime') ?>"
                   value="<?=$this->escape($checkout->getDatetime()) ?>" />
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-4">
            <input class="form-control"
                   type="text"
                   name="usage"
                   id="usage"
                   placeholder="<?=$this->getTrans('usage') ?>"
                   value="<?=$this->escape($checkout->getUsage()) ?>" />
        </div>
    </div>
    <div class="form-group hidden">
        <div class="col-lg-4">
            <input class="form-control"
                   type="text"
                   name="id"
                   id="id"
                   value="<?=$this->escape($checkout->getId()) ?>" />
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
                   value="<?=$this->escape($checkout->getAmount()) ?>" />
        </div>
    </div>
    <?php endforeach; ?>
    <?=$this->getSaveBar('updateButton') ?>
</form>

<script>
$('#amount').popover();
</script>
