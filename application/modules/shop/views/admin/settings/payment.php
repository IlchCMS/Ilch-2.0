<link href="<?=$this->getModuleUrl('static/css/shop_admin.css') ?>" rel="stylesheet">

<h1><?=$this->getTrans('menuSettings') ?></h1>

<ul class="nav nav-tabs">
    <li class="nav-item">
        <a href="<?=$this->getUrl(['controller' => 'settings', 'action' => 'index']) ?>" class="nav-link">
            <i class="fa-solid fa-store"></i> <?=$this->getTrans('menuSettingShop') ?>
        </a>
    </li>
    <li class="nav-item">
        <a href="<?=$this->getUrl(['controller' => 'settings', 'action' => 'bank']) ?>" class="nav-link">
            <i class="fa-solid fa-university"></i> <?=$this->getTrans('menuSettingBank') ?>
        </a>
    </li>
    <li class="nav-item">
        <a href="<?=$this->getUrl(['controller' => 'settings', 'action' => 'default']) ?>" class="nav-link">
            <i class="fa-solid fa-tools"></i> <?=$this->getTrans('menuSettingDefault') ?>
        </a>
    </li>
    <li class="nav-item">
        <a href="<?=$this->getUrl(['controller' => 'settings', 'action' => 'agb']) ?>" class="nav-link">
            <i class="fa-solid fa-gavel"></i> <?=$this->getTrans('menuSettingAGB') ?>
        </a>
    </li>
    <li class="nav-item">
        <a href="<?=$this->getUrl(['controller' => 'settings', 'action' => 'payment']) ?>" class="nav-link active">
            <i class="fa-solid fa-money-bill"></i> <b><?=$this->getTrans('menuSettingPayment') ?></b>
        </a>
    </li>
</ul>
<br />

<div class="alert alert-info"><?=$this->getTrans('infoPayPalBusiness') ?></div>

<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>

    <div class="row mb-3">
        <label for="clientID" class="col-xl-2 control-label">
            <?=$this->getTrans('clientID') ?>:
        </label>
        <div class="col-xl-4">
            <div class="input-group">
                <span class="input-group-text">
                    <span class="fa-solid fa-info" data-toggle="event-popover" title="<?=$this->getTrans('popoverInfo') ?>" data-content="<?=$this->getTrans('clientIDInfo') ?>"></span>
                </span>
                <input type="text"
                       class="form-control"
                       id="clientID"
                       name="clientID"
                       placeholder="<?=$this->getTrans('clientID') ?>"
                       value="<?=($this->escape($this->get('settings')->getClientID()) != '') ? $this->escape($this->get('settings')->getClientID()) : $this->escape($this->originalInput('clientID')) ?>" />
            </div>
        </div>
    </div>
    <hr>
    <p><?=$this->getTrans('paypalMeDesc') ?></p>
    <div class="row mb-3">
        <label for="paypalMe" class="col-xl-2 control-label">
            <?=$this->getTrans('paypalMe') ?>:
        </label>
        <div class="col-xl-4">
            <div class="input-group">
                <span class="input-group-text">
                    <span class="fa-solid fa-info" data-toggle="event-popover" title="<?=$this->getTrans('popoverInfo') ?>" data-content="<?=$this->getTrans('paypalMeInfo') ?>"></span>
                </span>
                <input type="text"
                       class="form-control"
                       id="paypalMe"
                       name="paypalMe"
                       placeholder="<?=$this->getTrans('paypalMeName') ?>"
                       value="<?=($this->escape($this->get('settings')->getPayPalMe()) != '') ? $this->escape($this->get('settings')->getPayPalMe()) : $this->escape($this->originalInput('paypalMe')) ?>" />
            </div>
        </div>
    </div>
    <div class="row mb-3 <?=$this->validation()->hasError('paypalMePresetAmount') ? 'has-error' : '' ?>">
        <label for="paypalMePresetAmount" class="col-xl-2 control-label">
            <?=$this->getTrans('paypalMePresetAmount') ?>:
        </label>
        <div class="col-xl-4">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" id="paypalMePresetAmount-on" name="paypalMePresetAmount" value="1" <?=($this->get('settings') && $this->get('settings')->isPayPalMePresetAmount() == '1') ? 'checked="checked"' : '' ?> />
                <label for="paypalMePresetAmount-on" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('on') ?></label>
                <input type="radio" class="flipswitch-input" id="paypalMePresetAmount-off" name="paypalMePresetAmount" value="0" <?=(empty($this->get('settings')) || $this->get('settings')->isPayPalMePresetAmount() != '1') ? 'checked="checked"' : '' ?> />
                <label for="paypalMePresetAmount-off" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('off') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
        </div>
    </div>
    <?=$this->getSaveBar('saveButton') ?>
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
