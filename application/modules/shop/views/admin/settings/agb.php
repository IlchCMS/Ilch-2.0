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
            <a href="<?=$this->getUrl(['controller' => 'settings', 'action' => 'agb']) ?>" class="nav-link active">
                <i class="fa-solid fa-gavel"></i> <b><?=$this->getTrans('menuSettingAGB') ?></b>
            </a>
        </li>
        <li class="nav-item">
            <a href="<?=$this->getUrl(['controller' => 'settings', 'action' => 'payment']) ?>" class="nav-link">
                <i class="fa-solid fa-money-bill"></i> <?=$this->getTrans('menuSettingPayment') ?>
            </a>
        </li>
    </ul>
    <br />
    <div class="row mb-3 <?=$this->validation()->hasError('settingsAGB') ? 'has-error' : '' ?>">
        <label for="settingsAGB" class="col-xl-2 control-label">
            <?=$this->getTrans('AGB') ?>:
        </label>
        <div class="col-xl-10">
            <textarea class="form-control ckeditor"
                id="settingsAGB"
                name="settingsAGB"
                toolbar="ilch_html">
                <?=($this->get('settings')->getAGB() != '') ? $this->get('settings')->getAGB() : '' ?>
            </textarea>
        </div>
    </div>
    <?=$this->getSaveBar('saveButton') ?>
</form>
