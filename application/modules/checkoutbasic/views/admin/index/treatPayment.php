<h1><?=$this->getTrans('treatpayment') ?></h1>
<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <?php foreach ($this->get('checkout') as $checkout): ?>
    <div class="form-group <?=$this->validation()->hasError('name') ? 'has-error' : '' ?>">
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
        <div class="col-lg-4">
            <input type="text"
                   class="form-control"
                   id="usage"
                   name="usage"
                   placeholder="<?=$this->getTrans('usage') ?>"
                   value="<?=($this->originalInput('usage') != '') ? $this->escape($this->originalInput('usage')) : $this->escape($checkout->getUsage()) ?>" />
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
                   value="<?=($this->originalInput('amount') != '') ? $this->escape($this->originalInput('amount')) : $this->escape($checkout->getAmount()) ?>" />
        </div>
    </div>
    <?php endforeach; ?>
    <?=$this->getSaveBar('updateButton') ?>
</form>

<script>
$('#amount').popover();
</script>
