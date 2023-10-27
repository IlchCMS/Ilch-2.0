<link href="<?=$this->getModuleUrl('static/css/shop_admin.css') ?>" rel="stylesheet">

<h1><?=$this->getTrans('menuSettings') ?></h1>

<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a href="<?=$this->getUrl(['controller' => 'settings', 'action' => 'index']) ?>" class="nav-link active">
                <i class="fa-solid fa-store"></i> <b><?=$this->getTrans('menuSettingShop') ?></b>
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
            <a href="<?=$this->getUrl(['controller' => 'settings', 'action' => 'payment']) ?>" class="nav-link">
                <i class="fa-solid fa-money-bill"></i> <?=$this->getTrans('menuSettingPayment') ?>
            </a>
        </li>
    </ul>
    <br />
    <div class="row mb-3 <?=$this->validation()->hasError('shopName') ? 'has-error' : '' ?>">
        <label for="shopName" class="col-xl-2 control-label">
            <?=$this->getTrans('shopName') ?>:
        </label>
        <div class="col-xl-3">
            <input type="text"
                   class="form-control"
                   id="shopName"
                   name="shopName"
                   placeholder="<?=$this->getTrans('shopName') ?>"
                   value="<?=($this->escape($this->get('settings')->getShopName()) != '') ? $this->escape($this->get('settings')->getShopName()) : $this->escape($this->originalInput('shopName')) ?>" />
        </div>
    </div>
    <div class="row mb-3 <?=$this->validation()->hasError('shopStreet') ? 'has-error' : '' ?>">
        <label for="shopStreet" class="col-xl-2 control-label">
            <?=$this->getTrans('shopStreet') ?>:
        </label>
        <div class="col-xl-3">
            <input type="text"
                   class="form-control"
                   id="shopStreet"
                   name="shopStreet"
                   placeholder="<?=$this->getTrans('shopStreet') ?>"
                   value="<?=($this->escape($this->get('settings')->getShopStreet()) != '') ? $this->escape($this->get('settings')->getShopStreet()) : $this->escape($this->originalInput('shopStreet')) ?>" />
        </div>
    </div>
    <div class="row mb-3 <?=$this->validation()->hasError('shopPlz') ? 'has-error' : '' ?>">
        <label for="shopPlz" class="col-xl-2 control-label">
            <?=$this->getTrans('shopPlz') ?>:
        </label>
        <div class="col-xl-3">
            <input type="text"
                   class="form-control"
                   id="shopPlz"
                   name="shopPlz"
                   placeholder="<?=$this->getTrans('shopPlz') ?>"
                   value="<?=($this->escape($this->get('settings')->getShopPlz()) != '') ? $this->escape($this->get('settings')->getShopPlz()) : $this->escape($this->originalInput('shopPlz')) ?>" />
        </div>
    </div>
    <div class="row mb-3 <?=$this->validation()->hasError('shopCity') ? 'has-error' : '' ?>">
        <label for="shopCity" class="col-xl-2 control-label">
            <?=$this->getTrans('shopCity') ?>:
        </label>
        <div class="col-xl-3">
            <input type="text"
                   class="form-control"
                   id="shopCity"
                   name="shopCity"
                   placeholder="<?=$this->getTrans('shopCity') ?>"
                   value="<?=($this->escape($this->get('settings')->getShopCity()) != '') ? $this->escape($this->get('settings')->getShopCity()) : $this->escape($this->originalInput('shopCity')) ?>" />
        </div>
    </div>
    <hr />
    <div class="row mb-3 <?=$this->validation()->hasError('shopLogo') ? 'has-error' : '' ?>">
        <label for="selectedImage_shopLogo" class="col-xl-2 control-label">
            <?=$this->getTrans('shopLogo') ?>:
        </label>
        <div class="col-xl-3">
            <div class="input-group">
                <span class="input-group-text">
                    <?php $shopImgPath = '/application/modules/shop/static/img/';
                    if ($this->escape($this->get('settings')->getShopLogo() && file_exists(ROOT_PATH.'/'.$this->get('settings')->getShopLogo()))) {
                        $img = BASE_URL.'/'.$this->get('settings')->getShopLogo();
                    } else {
                        $img = BASE_URL.$shopImgPath.'ilchShop_logo.jpg';
                    } ?>
                    <span class="fa-solid fa-eye" data-toggle="event-image" data-img="<?=$img ?>"></span>
                </span>
                <input type="text"
                       class="form-control"
                       id="selectedImage_shopLogo"
                       name="shopLogo"
                       placeholder="<?=$this->getTrans('choosePic') ?>"
                       value="<?=($this->escape($this->get('settings')->getShopLogo()) != '') ? $this->escape($this->get('settings')->getShopLogo()) : $this->escape($this->originalInput('shopLogo')) ?>" />
                <span class="input-group-text">
                    <span class="fa-solid fa-xmark"></span>
                </span>
                <span class="input-group-text">
                    <a id="media_shopLogo" href="javascript:media_shopLogo()"><i class="fa-regular fa-image"></i></a>
                    <script>
                        <?=$this->getMedia()
                            ->addMediaButton($this->getUrl('admin/media/iframe/index/type/single/input/_shopLogo/'))
                            ->addInputId('_shopLogo')
                            ->addUploadController($this->getUrl('admin/media/index/upload')) ?>
                    </script>
                </span>
            </div>
        </div>
    </div>
    <hr />
    <div class="row mb-3 <?=$this->validation()->hasError('shopTel') ? 'has-error' : '' ?>">
        <label for="shopTel" class="col-xl-2 control-label">
            <?=$this->getTrans('shopTel') ?>:
        </label>
        <div class="col-xl-3">
            <input type="text"
                   class="form-control"
                   id="shopTel"
                   name="shopTel"
                   placeholder="<?=$this->getTrans('shopTel') ?>"
                   value="<?=($this->escape($this->get('settings')->getShopTel()) != '') ? $this->escape($this->get('settings')->getShopTel()) : $this->escape($this->originalInput('shopTel')) ?>" />
        </div>
    </div>
    <div class="row mb-3 <?=$this->validation()->hasError('shopFax') ? 'has-error' : '' ?>">
        <label for="shopFax" class="col-xl-2 control-label">
            <?=$this->getTrans('shopFax') ?>:
        </label>
        <div class="col-xl-3">
            <input type="text"
                   class="form-control"
                   id="shopFax"
                   name="shopFax"
                   placeholder="<?=$this->getTrans('shopFax') ?>"
                   value="<?=($this->escape($this->get('settings')->getShopFax()) != '') ? $this->escape($this->get('settings')->getShopFax()) : $this->escape($this->originalInput('shopFax')) ?>" />
        </div>
    </div>
    <div class="row mb-3 <?=$this->validation()->hasError('shopMail') ? 'has-error' : '' ?>">
        <label for="shopMail" class="col-xl-2 control-label">
            <?=$this->getTrans('shopMail') ?>:
        </label>
        <div class="col-xl-3">
            <input type="text"
                   class="form-control"
                   id="shopMail"
                   name="shopMail"
                   placeholder="<?=$this->getTrans('shopMail') ?>"
                   value="<?=($this->escape($this->get('settings')->getShopMail()) != '') ? $this->escape($this->get('settings')->getShopMail()) : $this->escape($this->originalInput('shopMail')) ?>" />
        </div>
    </div>
    <div class="row mb-3 <?=$this->validation()->hasError('shopWeb') ? 'has-error' : '' ?>">
        <label for="shopWeb" class="col-xl-2 control-label">
            <?=$this->getTrans('shopWeb') ?>:
        </label>
        <div class="col-xl-3">
            <input type="text"
                   class="form-control"
                   id="shopWeb"
                   name="shopWeb"
                   placeholder="<?=$this->getTrans('shopWeb') ?>"
                   value="<?=($this->escape($this->get('settings')->getShopWeb()) != '') ? $this->escape($this->get('settings')->getShopWeb()) : $this->escape($this->originalInput('shopWeb')) ?>" />
        </div>
    </div>
    <hr />
        <div class="row mb-3 <?=$this->validation()->hasError('shopStNr') ? 'has-error' : '' ?>">
        <label for="shopStNr" class="col-xl-2 control-label">
            <?=$this->getTrans('shopStNr') ?>:
        </label>
        <div class="col-xl-3">
            <input type="text"
                   class="form-control"
                   id="shopStNr"
                   name="shopStNr"
                   placeholder="<?=$this->getTrans('shopStNr') ?>"
                   value="<?=($this->escape($this->get('settings')->getShopStNr()) != '') ? $this->escape($this->get('settings')->getShopStNr()) : $this->escape($this->originalInput('shopStNr')) ?>" />
        </div>
    </div>
    <?=$this->getSaveBar('saveButton') ?>
</form>
<?=$this->getDialog('mediaModal', $this->getTrans('media'), '<iframe frameborder="0"></iframe>') ?>
<script>
$(function () {
    $('[data-toggle="event-image"]').popover({
        html: true,
        trigger: 'hover',
        placement: 'top',
        content: function () { return '<img src="' + $(this).data('img') + '" width="150" />'; }
    });
});
</script>
