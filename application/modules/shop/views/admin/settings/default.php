<link href="<?=$this->getModuleUrl('static/css/shop_admin.css') ?>" rel="stylesheet">

<h1><?=$this->getTrans('menuSettings') ?></h1>

<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <ul class="nav nav-tabs">
        <li>
            <a href="<?=$this->getUrl(['controller' => 'settings', 'action' => 'index']) ?>">
                <i class="fa-solid fa-store"></i> <?=$this->getTrans('menuSettingShop') ?>
            </a>
        </li>
        <li>
            <a href="<?=$this->getUrl(['controller' => 'settings', 'action' => 'bank']) ?>">
                <i class="fa-solid fa-university"></i> <?=$this->getTrans('menuSettingBank') ?>
            </a>
        </li>
        <li class="active">
            <a href="<?=$this->getUrl(['controller' => 'settings', 'action' => 'default']) ?>">
                <i class="fa-solid fa-tools"></i> <b><?=$this->getTrans('menuSettingDefault') ?></b>
            </a>
        </li>
        <li>
            <a href="<?=$this->getUrl(['controller' => 'settings', 'action' => 'agb']) ?>">
                <i class="fa-solid fa-gavel"></i> <?=$this->getTrans('menuSettingAGB') ?>
            </a>
        </li>
        <li>
            <a href="<?=$this->getUrl(['controller' => 'settings', 'action' => 'payment']) ?>">
                <i class="fa-solid fa-money-bill"></i> <?=$this->getTrans('menuSettingPayment') ?>
            </a>
        </li>
    </ul>
    <br />
    <div class="form-group <?=$this->validation()->hasError('shopCurrency') ? 'has-error' : '' ?>">
        <label for="shopCurrency" class="col-lg-2 control-label">
            <?=$this->getTrans('shopCurrency') ?>:
        </label>
        <div class="col-lg-3">
            <select class="form-control"
                    name="shopCurrency"
                    id="shopCurrency">
                <?php
                foreach ($this->get('currencies') as $currency) {
                    if ($this->get('shopCurrency') != $currency->getId()) {
                        echo '<option value="'.$currency->getId().'">'.$this->escape($currency->getName()).'</option>';
                    } else {
                        echo '<option value="'.$currency->getId().'" selected>'.$this->escape($currency->getName()).'</option>';
                    }
                }
                ?>
            </select>
        </div>
    </div>
    <div class="form-group <?=$this->validation()->hasError('fixTax') ? 'has-error' : '' ?>">
        <label for="fixTax" class="col-lg-2 control-label">
            <?=$this->getTrans('fixTax') ?>:
        </label>
        <div class="col-lg-3">
            <div class="input-group">
                <input type="number"
                       class="form-control"
                       id="fixTax"
                       name="fixTax"
                       min="1"
                       placeholder="19"
                       value="<?=($this->escape($this->get('settings')->getFixTax()) != '') ? $this->escape($this->get('settings')->getFixTax()) : $this->escape($this->originalInput('fixTax')) ?>" />
                <span class="input-group-addon">
                    <b><?=$this->getTrans('percent') ?> (%)</b>
                </span>
            </div>
        </div>
    </div>
    <div class="form-group <?=$this->validation()->hasError('fixShippingCosts') ? 'has-error' : '' ?>">
        <label for="fixShippingCosts" class="col-lg-2 control-label">
            <?=$this->getTrans('fixShippingCosts') ?>:
        </label>
        <div class="col-lg-3">
            <div class="input-group">
                <span class="input-group-addon">
                    <span class="fa-solid fa-info" data-toggle="event-popover" title="<?=$this->getTrans('popoverInfo') ?>" data-content="<?=$this->getTrans('priceInfo') ?>"></span>
                </span>
                <input type="text"
                       class="form-control text-right"
                       id="fixShippingCosts"
                       name="fixShippingCosts"
                       pattern="^\d*(\.\d{2}$)?" 
                       placeholder="99.00"
                       value="<?=($this->escape($this->get('settings')->getFixShippingCosts()) != '') ? $this->escape($this->get('settings')->getFixShippingCosts()) : $this->escape($this->originalInput('fixShippingCosts')) ?>" />
                <span class="input-group-addon">
                    <b><?=$this->escape($this->get('currency')) ?></b>
                </span>
            </div>
        </div>
    </div>
    <div class="form-group <?=$this->validation()->hasError('fixShippingTime') ? 'has-error' : '' ?>">
        <label for="fixShippingTime" class="col-lg-2 control-label">
            <?=$this->getTrans('fixShippingTime') ?>:
        </label>
        <div class="col-lg-3">
            <div class="input-group">
                <input type="number"
                       class="form-control"
                       id="fixShippingTime"
                       name="fixShippingTime"
                       min="1"
                       placeholder="7"
                       value="<?=($this->escape($this->get('settings')->getFixShippingTime()) != '') ? $this->escape($this->get('settings')->getFixShippingTime()) : $this->escape($this->originalInput('fixShippingTime')) ?>" />
                <span class="input-group-addon">
                    <b><?=$this->getTrans('days') ?></b>
                </span>
            </div>
        </div>
    </div>
    <hr />
    <div class="form-group <?=$this->validation()->hasError('invoiceTextTop') ? 'has-error' : '' ?>">
        <label for="invoiceTextTop" class="col-lg-2 control-label">
            <?=$this->getTrans('invoiceTextTop') ?>:
        </label>
        <div class="col-lg-10">
            <div class="input-group">
                <span class="input-group-addon">
                    <span class="fa-solid fa-info" data-toggle="event-popover" title="<?=$this->getTrans('popoverInfo') ?>" data-content="<?=$this->getTrans('infoInvoiceTextTop') ?>"></span>
                </span>
                <input type="text"
                       class="form-control"
                       id="invoiceTextTop"
                       name="invoiceTextTop"
                       value="<?=($this->escape($this->get('settings')->getInvoiceTextTop()) != '') ? $this->escape($this->get('settings')->getInvoiceTextTop()) : $this->escape($this->originalInput('invoiceTextTop')) ?>" />
            </div>
        </div>
    </div>
    <div class="form-group <?=$this->validation()->hasError('invoiceTextBottom') ? 'has-error' : '' ?>">
        <label for="invoiceTextBottom" class="col-lg-2 control-label">
            <?=$this->getTrans('invoiceTextBottom') ?>:
        </label>
        <div class="col-lg-10">
            <div class="input-group">
                <span class="input-group-addon">
                    <span class="fa-solid fa-info" data-toggle="event-popover" title="<?=$this->getTrans('popoverInfo') ?>" data-content="<?=$this->getTrans('infoInvoiceTextBottom') ?>"></span>
                </span>
                <input type="text"
                       class="form-control"
                       id="invoiceTextBottom"
                       name="invoiceTextBottom"
                       value="<?=($this->escape($this->get('settings')->getInvoiceTextBottom()) != '') ? $this->escape($this->get('settings')->getInvoiceTextBottom()) : $this->escape($this->originalInput('invoiceTextBottom')) ?>" />
            </div>
        </div>
    </div>
    <div class="form-group <?=$this->validation()->hasError('deliveryTextTop') ? 'has-error' : '' ?>">
        <label for="deliveryTextTop" class="col-lg-2 control-label">
            <?=$this->getTrans('deliveryTextTop') ?>:
        </label>
        <div class="col-lg-10">
            <div class="input-group">
                <span class="input-group-addon">
                    <span class="fa-solid fa-info" data-toggle="event-popover" title="<?=$this->getTrans('popoverInfo') ?>" data-content="<?=$this->getTrans('infoDeliveryTextTop') ?>"></span>
                </span>
                <input type="text"
                       class="form-control"
                       id="deliveryTextTop"
                       name="deliveryTextTop"
                       value="<?=($this->escape($this->get('settings')->getDeliveryTextTop()) != '') ? $this->escape($this->get('settings')->getDeliveryTextTop()) : $this->escape($this->originalInput('deliveryTextTop')) ?>" />
            </div>
        </div>
    </div>
    <?=$this->getSaveBar('saveButton') ?>
</form>
<?=$this->getDialog('mediaModal', $this->getTrans('media'), '<iframe frameborder="0"></iframe>') ?>
<script>
$(function () {
    $('[data-toggle="event-popover"]').popover({
        container: 'body',
        trigger: 'hover',
        placement: 'top',
    });
});
</script>
