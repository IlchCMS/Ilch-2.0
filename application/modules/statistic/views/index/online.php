<?php if($this->getUser()): ?>
    <?php $userMapper = new \Modules\User\Mappers\User() ?>
    <?php $userCheck = $userMapper->getUserById($this->getUser()->getId()) ?>
<?php endif; ?>

<legend><?=$this->getTrans('menuOnlineStatistic') ?></legend>
<div class="table-responsive">
    <table class="table table-hover table-striped">
        <colgroup>
            <col class="col-lg-2">
            <col class="col-lg-2">
            <?php if($this->getUser()): ?>
                <?php if($userCheck->isAdmin()): ?>
                    <col class="col-lg-2">
                    <col class="col-lg-3">
                <?php endif; ?>
            <?php endif; ?>
            <col />
        </colgroup>
        <thead>
            <tr>
                <th><?=$this->getTrans('user') ?></th>
                <th><?=$this->getTrans('lastHere') ?></th>
                <?php if($this->getUser()): ?>
                    <?php if($userCheck->isAdmin()): ?>
                        <th><?=$this->getTrans('ipAdress') ?></th>
                        <th><?=$this->getTrans('osBrowser') ?></th>
                    <?php endif; ?>
                <?php endif; ?>
                <th><?=$this->getTrans('findOnSite') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($this->get('userOnlineList') as $userOnlineList): ?>
                <?php $userMapper = new \Modules\User\Mappers\User(); ?>
                <?php $user = $userMapper->getUserById($userOnlineList->getUserId()); ?>
                <?php $moduleKey = implode('/',array_slice(explode('/',$userOnlineList->getSite()),1,1)); ?>
                <?php if($moduleKey != ''): ?>
                    <?php $modulesMapper = new \Modules\Admin\Mappers\Module(); ?>
                    <?php $module = $modulesMapper->getModulesByKey($moduleKey, $this->getTranslator()->getLocale()); ?>
                    <?php $moduleName = $module->getName() ?>
                <?php else: ?>
                    <?php $moduleName = '' ?>
                <?php endif; ?>
                <tr>
                    <td>
                        <?php if ($userOnlineList->getUserId() == 0): ?>
                            Gast
                        <?php else: ?>
                            <a href="<?=$this->getUrl('user/profil/index/user/'.$user->getId()) ?>"><?=$this->escape($user->getName()) ?></a>
                        <?php endif; ?>
                    </td>
                    <td><?=$userOnlineList->getDateLastActivity() ?></td>
                    <?php if($this->getUser()): ?>
                        <?php if($userCheck->isAdmin()): ?>
                            <td><?=$userOnlineList->getIPAdress() ?></td>
                            <td><?=$userOnlineList->getOS() ?> <?=$userOnlineList->getOSVersion() ?> / <?=$userOnlineList->getBrowser() ?> <?=$userOnlineList->getBrowserVersion() ?></td>
                        <?php endif; ?>
                    <?php endif; ?>
                    <td><a href="<?=$this->getUrl(substr($userOnlineList->getSite(),1)) ?>"><?=$moduleName ?></a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
