<?php $profil = $this->get('profil'); ?>

<link href="<?=$this->getModuleUrl('static/css/user.css') ?>" rel="stylesheet">

<div class="row">
    <div class="col-xl-12 profile">
        <?php include APPLICATION_PATH.'/modules/user/views/panel/navi.php'; ?>

        <div class="profile-content active">
            <h1><?=$this->getTrans('providers.title') ?></h1>
            <?php foreach ($this->get('providers') as $provider): ?>
                <h4><i class="fa <?=$provider->getIcon() ?> fa-fw"></i> <?=$provider->getName() ?></h4>
                <ul class="list-group">
                    <?php if ($this->get('authProvider')->hasProviderLinked($provider->getKey(), currentUser()->getId())): ?>
                        <?php $accountDetails = $this->get('authProvider')->getLinkedProviderDetails($provider->getKey(), currentUser()->getId()); ?>
                        <li class="list-group-item">
                            <?=$this->getTrans(
    'providers.linkedToAccount',
    '<b>'.$accountDetails->getScreenName().'</b>',
    '<i>'.$accountDetails->getCreatedAt().'</i>'
) ?>
                        </li>
                        <li class="list-group-item">
                            <form method="POST"
                                action="<?= $this->getUrl([
                                    'module' => $provider->getModule(),
                                    'controller' => $provider->getUnlinkController(),
                                    'action' => $provider->getUnlinkAction()
                                ]) ?>"
                            >
                                <?=$this->getTokenField() ?>
                                <button type="submit" class="btn btn-sm btn-secondary">
                                    <i class="fa-solid fa-xmark fa-fw text-danger"></i> <?=$this->getTrans('providers.unlink') ?>
                                </button>
                            </form>
                        </li>
                    <?php else: ?>
                        <li class="list-group-item">
                            <?=$this->getTrans('providers.notLinked') ?>
                        </li>
                        <li class="list-group-item">
                            <a class="btn btn-sm btn-secondary" href="<?=$this->getUrl([
                                'module' => $provider->getModule(),
                                'controller' => $provider->getAuthController(),
                                'action' => $provider->getAuthAction()
                            ]) ?>">
                                <i class="fa-solid fa-check fa-fw text-success"></i> <?=$this->getTrans('providers.link') ?>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            <?php endforeach; ?>
        </div>
    </div>
</div>
