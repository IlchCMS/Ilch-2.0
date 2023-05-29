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
        <li class="active">
            <a href="<?=$this->getUrl(['controller' => 'settings', 'action' => 'bank']) ?>">
                <i class="fa-solid fa-university"></i> <b><?=$this->getTrans('menuSettingBank') ?></b>
            </a>
        </li>
        <li>
            <a href="<?=$this->getUrl(['controller' => 'settings', 'action' => 'default']) ?>">
                <i class="fa-solid fa-tools"></i> <?=$this->getTrans('menuSettingDefault') ?>
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
    <div class="form-group <?=$this->validation()->hasError('bankName') ? 'has-error' : '' ?>">
        <label for="bankName" class="col-lg-2 control-label">
            <?=$this->getTrans('bankName') ?>:
        </label>
        <div class="col-lg-3">
            <input type="text"
                   class="form-control"
                   id="bankName"
                   name="bankName"
                   placeholder="<?=$this->getTrans('bankName') ?>"
                   value="<?=($this->escape($this->get('settings')->getBankName()) != '') ? $this->escape($this->get('settings')->getBankName()) : $this->escape($this->originalInput('bankName')) ?>" />
        </div>
    </div>
    <div class="form-group <?=$this->validation()->hasError('bankOwner') ? 'has-error' : '' ?>">
        <label for="bankOwner" class="col-lg-2 control-label">
            <?=$this->getTrans('bankOwner') ?>:
        </label>
        <div class="col-lg-3">
            <input type="text"
                   class="form-control"
                   id="bankOwner"
                   name="bankOwner"
                   placeholder="<?=$this->getTrans('bankOwner') ?>"
                   value="<?=($this->escape($this->get('settings')->getBankOwner()) != '') ? $this->escape($this->get('settings')->getBankOwner()) : $this->escape($this->originalInput('bankOwner')) ?>" />
        </div>
    </div>
    <div class="form-group <?=$this->validation()->hasError('bankIBAN') ? 'has-error' : '' ?>">
        <label for="bankIBAN" class="col-lg-2 control-label">
            <?=$this->getTrans('bankIBAN') ?>:
        </label>
        <div class="col-lg-3">
            <input type="text"
                   class="form-control"
                   id="bankIBAN"
                   name="bankIBAN"
                   placeholder="<?=$this->getTrans('bankIBAN') ?>"
                   value="<?=($this->escape($this->get('settings')->getBankIBAN()) != '') ? $this->escape($this->get('settings')->getBankIBAN()) : $this->escape($this->originalInput('bankIBAN')) ?>" />
        </div>
    </div>
    <div class="form-group <?=$this->validation()->hasError('bankBIC') ? 'has-error' : '' ?>">
        <label for="bankBIC" class="col-lg-2 control-label">
            <?=$this->getTrans('bankBIC') ?>:
        </label>
        <div class="col-lg-3">
            <input type="text"
                   class="form-control"
                   id="bankBIC"
                   name="bankBIC"
                   placeholder="<?=$this->getTrans('bankBIC') ?>"
                   value="<?=($this->escape($this->get('settings')->getBankBIC()) != '') ? $this->escape($this->get('settings')->getBankBIC()) : $this->escape($this->originalInput('bankBIC')) ?>" />
        </div>
    </div>
    <?=$this->getSaveBar('saveButton') ?>
</form>
