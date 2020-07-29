<link href="<?=$this->getModuleUrl('static/css/user.css') ?>" rel="stylesheet">

<div class="row">
    <div class="col-lg-12 profile">
        <?php include APPLICATION_PATH.'/modules/user/views/panel/navi.php'; ?>

        <div class="profile-content active">
            <div class="row">
                <h1><?=$this->getTrans('menuSettings') ?></h1>
                <div class="settings-stretch">
                    <div class="col-xs-12 col-md-4">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title"><a href="<?=$this->getUrl(['module' => 'user', 'controller' => 'panel', 'action' => 'profile']) ?>"><?=$this->getTrans('settingsProfile') ?></a></h3>
                            </div>
                            <div class="panel-body">
                                <?=$this->getTrans('settingsProfileInfo') ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-4">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title"><a href="<?=$this->getUrl(['module' => 'user', 'controller' => 'panel', 'action' => 'avatar']) ?>"><?=$this->getTrans('settingsAvatar') ?></a></h3>
                            </div>
                            <div class="panel-body">
                                <?=$this->getTrans('settingsAvatarInfo') ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-4">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title"><a href="<?=$this->getUrl(['module' => 'user', 'controller' => 'panel', 'action' => 'signature']) ?>"><?=$this->getTrans('settingsSignature') ?></a></h3>
                            </div>
                            <div class="panel-body">
                                <?=$this->getTrans('settingsSignatureInfo') ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="settings-stretch">
                    <div class="col-xs-12 col-md-4">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title"><a href="<?=$this->getUrl(['module' => 'user', 'controller' => 'panel', 'action' => 'password']) ?>"><?=$this->getTrans('settingsPassword') ?></a></h3>
                            </div>
                            <div class="panel-body">
                                <?=$this->getTrans('settingsPasswordInfo') ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-4">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title"><a href="<?=$this->getUrl(['module' => 'user', 'controller' => 'panel', 'action' => 'setting']) ?>"><?=$this->getTrans('settingsSetting') ?></a></h3>
                            </div>
                            <div class="panel-body">
                                <?=$this->getTrans('settingsSettingInfo') ?>
                            </div>
                        </div>
                    </div>
                    <?php if (count($this->get('providers')) > 0): ?>
                    <div class="col-xs-12 col-md-4">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title"><a href="<?=$this->getUrl(['module' => 'user', 'controller' => 'panel', 'action' => 'providers']) ?>"><?=$this->getTrans('socialMedia') ?></a></h3>
                            </div>
                            <div class="panel-body">
                                <?=$this->getTrans('socialMediaDesc') ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                <?php if ($this->get('usermenusettingslinks') != ''): ?>
                        <?php
                        $i = 0;
                        $countLinks = count($this->get('usermenusettingslinks'));
                        ?>
                        <?php foreach ($this->get('usermenusettingslinks') as $usermenulinks): ?>
                            <?php if ($i % 3 == 0): ?>
                                <div class="settings-stretch">
                            <?php endif; ?>
                            <div class="col-xs-12 col-md-4">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h3 class="panel-title"><a href="<?=$this->getUrl($usermenulinks->getKey()) ?>"><?=$usermenulinks->getName() ?></a></h3>
                                    </div>
                                    <div class="panel-body">
                                        <?=$usermenulinks->getDescription() ?>
                                    </div>
                                </div>
                            </div>
                            <?php $i++ ?>
                            <?php if ($i == 3 || $i == $countLinks): ?>
                                </div>
                                <?php $i = 0; ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                <?php endif; ?>
                <div class="col-xs-12 col-md-4">
                    <div class="panel panel-danger">
                        <div class="panel-heading">
                            <h3 class="panel-title"><a href="<?=$this->getUrl(['module' => 'user', 'controller' => 'panel', 'action' => 'deleteaccount']) ?>"><?=$this->getTrans('deleteaccount') ?></a></h3>
                        </div>
                        <div class="panel-body">
                            <?=$this->getTrans('deleteaccountDesc') ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
