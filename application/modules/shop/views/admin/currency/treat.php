<?php $currency = $this->get('currency'); ?>

<h1>
    <?=($this->getRequest()->getParam('id')) ? $this->getTrans('edit') : $this->getTrans('add'); ?>
</h1>

<?php if ($this->get('currencyInUse')) : ?>
<div class="alert alert-danger">
    <b><?=$this->getTrans('currencyInUseWarning') ?></b>
</div>
<?php endif; ?>
<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <div class="row mb-3 <?=$this->validation()->hasError('name') ? 'has-error' : '' ?>">
        <label for="name" class="col-lg-2 control-label"><?=$this->getTrans('name') ?>:</label>
        <div class="col-lg-4">
            <input type="text"
                   class="form-control"
                   id="name"
                   name="name"
                   placeholder="<?=$this->getTrans('name') ?>"
                   value="<?=$this->escape($currency->getName()) ?>" />
        </div>
    </div>
    <div class="row mb-3 <?=$this->validation()->hasError('code') ? 'has-error' : '' ?>">
        <label for="code" class="col-lg-2 control-label"><?=$this->getTrans('currencyCode') ?>:</label>
        <div class="col-lg-4">
            <div class="input-group">
                <span class="input-group-text">
                    <span class="fa-solid fa-info" data-toggle="event-popover" title="<?=$this->getTrans('popoverInfo') ?>" data-content="<?=$this->getTrans('currencyCodeInfo') ?>"></span>
                </span>
                <input type="text"
                       class="form-control"
                       id="code"
                       name="code"
                       placeholder="<?=$this->getTrans('currencyCode') ?>"
                       value="<?=$this->escape($currency->getCode()) ?>"
                       required
                       pattern="[A-Z]{3,3}" />
            </div>
        </div>
    </div>
    <div class="row mb-3 d-none">
        <div class="col-lg-4">
            <input type="text"
                   class="form-control"
                   id="id"
                   name="id"
                   value="<?=(empty($currency->getId())) ? '' : $this->escape($currency->getId()) ?>" />
        </div>
    </div>
    <?=$this->getSaveBar() ?>
</form>

<script>
    $(function () {
        $('[data-toggle="event-popover"]').popover({
            container: 'body',
            trigger: 'hover',
            placement: 'top',
        });
    });
</script>
