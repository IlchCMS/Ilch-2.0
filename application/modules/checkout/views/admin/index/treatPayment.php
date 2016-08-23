<legend><?=$this->getTrans('treatpayment') ?></legend>
<?php if (!empty($this->get('errors'))): ?>
    <div class="alert alert-danger" role="alert">
        <strong> <?=$this->getTrans('errorsOccured') ?>:</strong>
        <ul>
            <?php foreach ($this->get('errors') as $error): ?>
                <li><?= $error; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <?php foreach ($this->get('checkout') as $checkout): ?>
    <div class="form-group <?=in_array('name', $this->get('errorFields')) ? 'has-error' : '' ?>">
        <div class="col-lg-4">
            <input type="text"
                   class="form-control"
                   id="name"
                   name="name"
                   placeholder="<?=$this->getTrans('name') ?>"
                   value="<?php if (empty($this->get('errors'))) { echo $this->escape($checkout->getName()); } else { echo $this->get('post')['name']; } ?>" />
        </div>
    </div>
    <div class="form-group <?=in_array('datetime', $this->get('errorFields')) ? 'has-error' : '' ?>">
        <div class="col-lg-4">
            <input type="text"
                   class="form-control"
                   id="datetime"
                   name="datetime"
                   placeholder="<?=$this->getTrans('datetime') ?>"
                   value="<?php if (empty($this->get('errors'))) { echo $this->escape($checkout->getDateTime()); } else { echo $this->get('post')['datetime']; } ?>" />
        </div>
    </div>
    <div class="form-group <?=in_array('usage', $this->get('errorFields')) ? 'has-error' : '' ?>">
        <div class="col-lg-4">
            <input type="text"
                   class="form-control"
                   id="usage"
                   name="usage"
                   placeholder="<?=$this->getTrans('usage') ?>"
                   value="<?php if (empty($this->get('errors'))) { echo $this->escape($checkout->getUsage()); } else { echo $this->get('post')['usage']; } ?>" />
        </div>
    </div>
    <div class="form-group hidden">
        <div class="col-lg-4">
            <input type="text"
                   class="form-control"
                   id="id"
                   name="id"
                   value="<?=$this->escape($checkout->getId()) ?>" />
        </div>
    </div>
    <div class="form-group <?=in_array('amount', $this->get('errorFields')) ? 'has-error' : '' ?>">
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
                   value="<?php if (empty($this->get('errors'))) { echo $this->escape($checkout->getAmount()); } else { echo $this->get('post')['amount']; } ?>" />
        </div>
    </div>
    <?php endforeach; ?>
    <?=$this->getSaveBar('updateButton') ?>
</form>

<script>
$('#amount').popover();
</script>
