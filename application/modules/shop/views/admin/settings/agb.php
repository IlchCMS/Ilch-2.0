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
        <li>
            <a href="<?=$this->getUrl(['controller' => 'settings', 'action' => 'default']) ?>">
                <i class="fa-solid fa-tools"></i> <?=$this->getTrans('menuSettingDefault') ?>
            </a>
        </li>
        <li class="active">
            <a href="<?=$this->getUrl(['controller' => 'settings', 'action' => 'agb']) ?>">
                <i class="fa-solid fa-gavel"></i> <b><?=$this->getTrans('menuSettingAGB') ?></b>
            </a>
        </li>
        <li>
            <a href="<?=$this->getUrl(['controller' => 'settings', 'action' => 'payment']) ?>">
                <i class="fa-solid fa-money-bill"></i> <?=$this->getTrans('menuSettingPayment') ?>
            </a>
        </li>
    </ul>
    <br />
    <div class="form-group <?=$this->validation()->hasError('settingsAGB') ? 'has-error' : '' ?>">
        <label for="settingsAGB" class="col-lg-2 control-label">
            <?=$this->getTrans('AGB') ?>:
        </label>
        <div class="col-lg-10">
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
