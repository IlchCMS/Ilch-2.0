<link href="<?=$this->getModuleUrl('static/css/shop_admin.css') ?>" rel="stylesheet">

<h1><?=$this->getTrans('menuSettings') ?></h1>

<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a href="<?=$this->getUrl(['controller' => 'settings', 'action' => 'index']) ?>" class="nav-link">
                <i class="fa-solid fa-store"></i> <?=$this->getTrans('menuSettingShop') ?>
            </a>
        </li>
        <li class="nav-item">
            <a href="<?=$this->getUrl(['controller' => 'settings', 'action' => 'bank']) ?>" class="nav-link active">
                <i class="fa-solid fa-university"></i> <b><?=$this->getTrans('menuSettingBank') ?></b>
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
            <a href="<?=$this->getUrl(['controller' => 'settings', 'action' => 'payment']) ?>" class="nav-link">
                <i class="fa-solid fa-money-bill"></i> <?=$this->getTrans('menuSettingPayment') ?>
            </a>
        </li>
    </ul>
    <br />
    <div class="row mb-3 <?=$this->validation()->hasError('bankName') ? 'has-error' : '' ?>">
        <label for="bankName" class="col-xl-2 control-label">
            <?=$this->getTrans('bankName') ?>:
        </label>
        <div class="col-xl-3">
            <input type="text"
                   class="form-control"
                   id="bankName"
                   name="bankName"
                   placeholder="<?=$this->getTrans('bankName') ?>"
                   value="<?=($this->escape($this->get('settings')->getBankName()) != '') ? $this->escape($this->get('settings')->getBankName()) : $this->escape($this->originalInput('bankName')) ?>" />
        </div>
    </div>
    <div class="row mb-3 <?=$this->validation()->hasError('bankOwner') ? 'has-error' : '' ?>">
        <label for="bankOwner" class="col-xl-2 control-label">
            <?=$this->getTrans('bankOwner') ?>:
        </label>
        <div class="col-xl-3">
            <input type="text"
                   class="form-control"
                   id="bankOwner"
                   name="bankOwner"
                   placeholder="<?=$this->getTrans('bankOwner') ?>"
                   value="<?=($this->escape($this->get('settings')->getBankOwner()) != '') ? $this->escape($this->get('settings')->getBankOwner()) : $this->escape($this->originalInput('bankOwner')) ?>" />
        </div>
    </div>
    <div class="row mb-3 <?=$this->validation()->hasError('bankIBAN') ? 'has-error' : '' ?>">
        <label for="bankIBAN" class="col-xl-2 control-label">
            <?=$this->getTrans('bankIBAN') ?>:
        </label>
        <div class="col-xl-3">
            <input type="text"
                   class="form-control"
                   id="bankIBAN"
                   name="bankIBAN"
                   placeholder="<?=$this->getTrans('bankIBAN') ?>"
                   value="<?=($this->escape($this->get('settings')->getBankIBAN()) != '') ? $this->escape($this->get('settings')->getBankIBAN()) : $this->escape($this->originalInput('bankIBAN')) ?>" />
        </div>
    </div>
    <div class="row mb-3 <?=$this->validation()->hasError('bankBIC') ? 'has-error' : '' ?>">
        <label for="bankBIC" class="col-xl-2 control-label">
            <?=$this->getTrans('bankBIC') ?>:
        </label>
        <div class="col-xl-3">
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
