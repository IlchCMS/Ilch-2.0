<link href="<?=$this->getModuleUrl('static/css/user.css') ?>" rel="stylesheet">

<div id="panel">
    <div class="row">
        <div class="col-sm-3 col-md-2 col-lg-2">
            <?php include APPLICATION_PATH.'/modules/user/views/panel/navi.php'; ?>
        </div>
        <div class=" col-sm-9 col-md-10 col-lg-10">
            <legend><?=$this->getTrans('menuSettings'); ?></legend>
            <div class="col-sm-6 col-md-4">
                <div class="thumbnail media">
                    <div class="media-body">
                        <h4 class="media-heading"><a href="<?=$this->getUrl(array('module' => 'user', 'controller' => 'panel', 'action' => 'profile')) ?>"><?=$this->getTrans('settingsProfile') ?></a></h4>
                        <hr>
                        <p><?=$this->getTrans('settingsProfileInfo') ?></p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-4">
                <div class="thumbnail media">
                    <div class="media-body">
                        <h4 class="media-heading"><a href="<?=$this->getUrl(array('module' => 'user', 'controller' => 'panel', 'action' => 'avatar')) ?>"><?=$this->getTrans('settingsAvatar') ?></a></h4>
                        <hr>
                        <p><?=$this->getTrans('settingsAvatarInfo') ?></p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-4">
                <div class="thumbnail media">
                    <div class="media-body">
                        <h4 class="media-heading"><a href="<?=$this->getUrl(array('module' => 'user', 'controller' => 'panel', 'action' => 'signature')) ?>"><?=$this->getTrans('settingsSignature') ?></a></h4>
                        <hr>
                        <p><?=$this->getTrans('settingsSignatureInfo') ?></p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-4">
                <div class="thumbnail media">
                    <div class="media-body">
                        <h4 class="media-heading"><a href="<?=$this->getUrl(array('module' => 'user', 'controller' => 'panel', 'action' => 'password')) ?>"><?=$this->getTrans('settingsPassword') ?></a></h4>
                        <hr>
                        <p><?=$this->getTrans('settingsPasswordInfo') ?></p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-4">
                <div class="thumbnail media">
                    <div class="media-body">
                        <h4 class="media-heading"><a href="<?=$this->getUrl(array('module' => 'user', 'controller' => 'panel', 'action' => 'setting')) ?>"><?=$this->getTrans('settingsSetting') ?></a></h4>
                        <hr>
                        <p><?=$this->getTrans('settingsSettingInfo') ?></p>
                    </div>
                </div>
            </div>
            <?php if ($this->get('usermenusettingslinks') != ''): ?>
                <?php foreach ($this->get('usermenusettingslinks') as $usermenulinks): ?>
                    <div class="col-sm-6 col-md-4">
                        <div class="thumbnail media">
                            <div class="media-body">
                                <h4 class="media-heading"><a href="<?=$this->getUrl($usermenulinks->getKey()) ?>"><?=$usermenulinks->getName() ?></a></h4>
                                <hr>
                                <p><?=$usermenulinks->getDescription() ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
