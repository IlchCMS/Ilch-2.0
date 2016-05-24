<!DOCTYPE html>
<html lang="de">
    <head>
        <meta charset="utf-8">
        <title>Ilch - Installation</title>
        <meta name="description" content="Ilch - Installation">
        <link rel="shortcut icon" type="image/x-icon" href="<?=$this->getStaticUrl('img/favicon.ico') ?>">
        <link href="<?=$this->getStaticUrl('css/bootstrap.css') ?>" rel="stylesheet">
        <link href="<?=$this->getStaticUrl('css/font-awesome.css') ?>" rel="stylesheet">
        <link href="<?=$this->getStaticUrl('css/ilch.css') ?>" rel="stylesheet">
        <link href="<?=$this->getStaticUrl('../application/modules/install/static/css/install.css') ?>" rel="stylesheet">
        <link href="<?=$this->getStaticUrl('css/ui-lightness/jquery-ui.css') ?>" rel="stylesheet">
        <script src="<?=$this->getStaticUrl('js/jquery.js') ?>"></script>
        <script src="<?=$this->getStaticUrl('js/jquery-ui.js') ?>"></script>        
        <script src="<?=$this->getStaticUrl('js/bootstrap.js') ?>"></script>
    </head>
    <body>
        <form autocomplete="off" class="form-horizontal" method="POST" action="<?=$this->getUrl(['action' => $this->getRequest()->getActionName()]) ?>">
            <?=$this->getTokenField() ?>
            <div class="container install_container">
                <img class="logo" src="<?=$this->getStaticUrl('img/ilch_logo.png') ?>" />
                <ul class="nav nav-tabs" id="install_steps">
                    <?php $done = 1; ?>
                    <?php $menuCounter = count($this->get('menu')); ?>
                    <?php $lastAction = ''; ?>

                    <?php foreach ($this->get('menu') as $key => $values): ?>
                        <?php if (isset($values['done'])): ?>
                            <?php $done++; ?>
                            <?php $lastAction = $key; ?>
                        <?php endif; ?>
                        <li class="<?=$this->getRequest()->getActionName() == $key ? 'active': '' ?>">
                            <a data-toggle="tab">
                                <?=$this->getTrans($values['langKey']) ?>
                            </a>
                        </li>
                    <?php endforeach; ?>

                    <?php $progress = 100 / $menuCounter * $done; ?>
                </ul>
                <br />
                <div class="progress  progress-striped">
                    <div class="progress-bar progress-bar-success active"
                        role="progressbar"
                        aria-valuenow="<?=$progress ?>"
                        aria-valuemin="0"
                        aria-valuemax="100"
                        style="width: <?=$progress ?>%;">
                    </div>
                </div>
                <div class="install_content">
                    <?=$this->getContent() ?>
                </div>
            </div>
            <div class="container save_box">
                <?php if (!in_array($this->getRequest()->getActionName(), ['index', 'finish'])): ?>
                    <a href="<?=$this->getUrl(['action' => $lastAction]) ?>" class="btn pull-left">
                        <?=$this->getTrans('backButton') ?>
                    </a>
                <?php endif; ?>

                <?php if ($this->getRequest()->getActionName() != 'finish'): ?>
                    <button type="submit" name="save" class="btn pull-right">
                        <?php $buttonTrans = 'nextButton'; ?>

                        <?php if ($this->getRequest()->getActionName() == 'config'): ?>
                            <?php $buttonTrans = 'installButton'; ?>
                        <?php endif; ?>

                        <?=$this->getTrans($buttonTrans) ?>
                    </button>
                <?php endif; ?>
            </div>
        </form>
    </body>
</html>
