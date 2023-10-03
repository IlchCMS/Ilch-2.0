<link href="<?=$this->getModuleUrl('static/css/user.css') ?>" rel="stylesheet">

<div class="row">
    <div class="col-lg-12 profile">
        <?php include APPLICATION_PATH.'/modules/user/views/panel/navi.php'; ?>

        <div class="profile-content active">
                <h1><?=$this->getTrans('menuSettings') ?></h1>
                <div class="settings-stretch row">
                    <div class="col-xs-12 col-md-4">
                        <div class="card panel-default">
                            <div class="card-header">
                                <h6 class="card-title"><a href="<?=$this->getUrl(['module' => 'user', 'controller' => 'panel', 'action' => 'profile']) ?>"><?=$this->getTrans('settingsProfile') ?></a></h6>
                            </div>
                            <div class="card-body">
                                <?=$this->getTrans('settingsProfileInfo') ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-4">
                        <div class="card panel-default">
                            <div class="card-header">
                                <h6 class="card-title"><a href="<?=$this->getUrl(['module' => 'user', 'controller' => 'panel', 'action' => 'avatar']) ?>"><?=$this->getTrans('settingsAvatar') ?></a></h6>
                            </div>
                            <div class="card-body">
                                <?=$this->getTrans('settingsAvatarInfo') ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-4">
                        <div class="card panel-default">
                            <div class="card-header">
                                <h6 class="card-title"><a href="<?=$this->getUrl(['module' => 'user', 'controller' => 'panel', 'action' => 'signature']) ?>"><?=$this->getTrans('settingsSignature') ?></a></h6>
                            </div>
                            <div class="card-body">
                                <?=$this->getTrans('settingsSignatureInfo') ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="settings-stretch row">
                    <div class="col-xs-12 col-md-4">
                        <div class="card panel-default">
                            <div class="card-header">
                                <h6 class="card-title"><a href="<?=$this->getUrl(['module' => 'user', 'controller' => 'panel', 'action' => 'password']) ?>"><?=$this->getTrans('settingsPassword') ?></a></h6>
                            </div>
                            <div class="card-body">
                                <?=$this->getTrans('settingsPasswordInfo') ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-4">
                        <div class="card panel-default">
                            <div class="card-header">
                                <h6 class="card-title"><a href="<?=$this->getUrl(['module' => 'user', 'controller' => 'panel', 'action' => 'setting']) ?>"><?=$this->getTrans('settingsSetting') ?></a></h6>
                            </div>
                            <div class="card-body">
                                <?=$this->getTrans('settingsSettingInfo') ?>
                            </div>
                        </div>
                    </div>
                    <?php if (count($this->get('providers')) > 0): ?>
                    <div class="col-xs-12 col-md-4">
                        <div class="card panel-default">
                            <div class="card-header">
                                <h6 class="card-title"><a href="<?=$this->getUrl(['module' => 'user', 'controller' => 'panel', 'action' => 'providers']) ?>"><?=$this->getTrans('socialMedia') ?></a></h6>
                            </div>
                            <div class="card-body">
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
                                <div class="settings-stretch row">
                            <?php endif; ?>
                            <div class="col-xs-12 col-md-4">
                                <div class="card panel-default">
                                    <div class="card-header">
                                        <h6 class="card-title"><a href="<?=$this->getUrl($usermenulinks->getKey()) ?>"><?=$usermenulinks->getName() ?></a></h6>
                                    </div>
                                    <div class="card-body">
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
                <div class="settings-stretch row">
                    <div class="col-xs-12 col-md-4">
                        <div class="card panel-default">
                            <div class="card-header">
                                <h6 class="card-title"><a href="<?=$this->getUrl(['module' => 'user', 'controller' => 'panel', 'action' => 'notifications']) ?>"><?=$this->getTrans('settingsNotifications') ?></a></h6>
                            </div>
                            <div class="card-body">
                                <?=$this->getTrans('settingsNotificationsDesc') ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-4">
                        <div class="card border-danger">
                            <div class="card-header">
                                <h6 class="card-title"><a href="<?=$this->getUrl(['module' => 'user', 'controller' => 'panel', 'action' => 'deleteaccount']) ?>" class="text-danger"><?=$this->getTrans('deleteaccount') ?></a></h6>
                            </div>
                            <div class="card-body">
                                <?=$this->getTrans('deleteaccountDesc') ?>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</div>
