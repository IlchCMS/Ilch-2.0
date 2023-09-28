<?php

/** @var $this \Ilch\Layout\Frontend */
?>
<!DOCTYPE html>
<html lang="de">
    <head>
        <meta charset="utf-8">
        <title>Ilch <?=VERSION ?> - Installation</title>
        <meta name="description" content="Ilch <?=VERSION ?> - Installation">
        <link rel="shortcut icon" type="image/x-icon" href="<?=$this->getStaticUrl('img/favicon.ico') ?>">
        <link href="<?=$this->getVendorUrl('twbs/bootstrap/dist/css/bootstrap.min.css') ?>" rel="stylesheet">
        <link href="<?=$this->getVendorUrl('fortawesome/font-awesome/css/all.min.css') ?>" rel="stylesheet">
        <link href="<?=$this->getVendorUrl('fortawesome/font-awesome/css/v4-shims.min.css') ?>" rel="stylesheet">
        <link href="<?=$this->getStaticUrl('css/ilch.css') ?>" rel="stylesheet">
        <link href="<?=$this->getStaticUrl('../application/modules/install/static/css/install.css') ?>" rel="stylesheet">
        <link href="<?=$this->getVendorUrl('npm-asset/jquery-ui/dist/themes/ui-lightness/jquery-ui.min.css') ?>" rel="stylesheet">
        <script src="<?=$this->getVendorUrl('npm-asset/jquery/dist/jquery.min.js') ?>"></script>
        <script src="<?=$this->getVendorUrl('npm-asset/jquery-ui/dist/jquery-ui.min.js') ?>"></script>
        <script src="<?=$this->getVendorUrl('twbs/bootstrap/dist/js/bootstrap.bundle.min.js') ?>"></script>
    </head>
    <body>
        <div class="container d-flex align-items-center justify-content-center">
            <div class="col-xl-offset-2 col-xl-8 col-lg-12 col-md-12 col-sm-12 install_container">
                <div class="logo" title="<?=$this->getTrans('ilchInstall', VERSION) ?>"></div>
                <div class="installVersion" title="<?=$this->getTrans('ilchInstall', VERSION) ?>">
                    <?=$this->getTrans('ilchInstallVersion', VERSION) ?>
                </div>
                <form autocomplete="off" class="row needs-validation" method="POST" action="<?=$this->getUrl(['action' => urlencode($this->getRequest()->getActionName())]) ?>">
                    <?=$this->getTokenField() ?>
                    <div class="d-none d-md-block col-xl-3 col-lg-3 col-md-3 verticalLine install_step">
                        <?php
                        $done = 1;
                        /** @var array $menu */
                        $menu = $this->get('menu');
                        $menuCounter = count($menu);
                        $lastAction = '';
                        ?>
                        <?php foreach ($menu as $key => $values) : ?>
                            <?php if (isset($values['done'])) : ?>
                                <?php $done++; ?>
                                <?php $lastAction = $key; ?>
                            <?php endif; ?>
                            <div class="step-item <?=isset($values['done']) ? 'done' : '' ?><?=$this->getRequest()->getActionName() == $key ? 'active' : '' ?>">
                                <div class="step-content">
                                    <?=$this->getTrans($values['langKey']) ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="col-xl-9 col-lg-9 col-md-9 col-sm-12">
                        <?php foreach ($menu as $key => $values) : ?>
                            <?php if ($this->getRequest()->getActionName() == $key) : ?>
                                <h2><?=$this->getTrans($values['langKey']) ?></h2>
                            <?php endif; ?>
                        <?php endforeach; ?>

                        <?=$this->getContent() ?>
                    </div>


                    <div class="save_box">
                        <?php if (!in_array($this->getRequest()->getActionName(), ['index', 'finish'])) : ?>
                            <a href="<?=$this->getUrl(['action' => $lastAction]) ?>" class="btn btn-outline-secondary">
                                <?=$this->getTrans('backButton') ?>
                            </a>
                        <?php endif; ?>

                        <?php if ($this->getRequest()->getActionName() !== 'finish' && !$this->get('mysqliExtensionMissing')) : ?>
                            <button type="submit" class="btn btn-primary float-end" name="save">
                                <?php $buttonTrans = 'nextButton'; ?>

                                <?php if ($this->getRequest()->getActionName() === 'configuration') : ?>
                                    <?php $buttonTrans = 'installButton'; ?>
                                <?php endif; ?>

                                <?=$this->getTrans($buttonTrans) ?>
                            </button>
                        <?php endif; ?>

                        <?php if ($this->getRequest()->getActionName() === 'finish') : ?>
                            <div class="float-end">
                                <a target="_blank" href="<?=$this->getUrl() ?>" class="btn btn-success">
                                    Frontend
                                </a>
                                <a target="_blank" href="<?=$this->getUrl(['module' => 'admin', 'controller' => 'admin', 'action' => 'index']) ?>" class="btn btn-primary">
                                    Administration
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>
