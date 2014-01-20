<!DOCTYPE html>
<html lang="de">
    <head>
        <meta charset="utf-8">
        <title>Ilch - Installation</title>
        <meta name="description" content="Ilch - Installation">
        <link rel="shortcut icon" type="image/x-icon" href="<?php echo $this->getStaticUrl('img/favicon.ico'); ?>">
        <link href="<?php echo $this->getStaticUrl('css/bootstrap.css'); ?>" rel="stylesheet">
        <link href="<?php echo $this->getStaticUrl('css/font-awesome.css'); ?>" rel="stylesheet">
        <link href="<?php echo $this->getStaticUrl('css/global.css'); ?>" rel="stylesheet">
        <link href="<?php echo $this->getStaticUrl('../application/modules/install/static/css/install.css'); ?>" rel="stylesheet">
        <link href="<?php echo $this->getStaticUrl('css/ui-lightness/jquery-ui.css'); ?>" rel="stylesheet">
        <script src="<?php echo $this->getStaticUrl('js/jquery.js'); ?>"></script>
        <script src="<?php echo $this->getStaticUrl('js/jquery-ui.js'); ?>"></script>        
        <script src="<?php echo $this->getStaticUrl('js/bootstrap.js'); ?>"></script>
    </head>
    <body>
        <form class="form-horizontal" method="POST" action="<?php echo $this->getUrl(array('action' => $this->getRequest()->getActionName())); ?>">
            <?php echo $this->getTokenField(); ?>
            <div class="container install_container">
                <img class="logo" src="<?php echo $this->getStaticUrl('img/ilch_logo.png'); ?>" />
                <ul class="nav nav-tabs" id="install_steps">
                    <?php
                        $done = 1;
                        $menuCounter = count($this->get('menu'));
                        $lastAction = '';

                        foreach ($this->get('menu') as $key => $values) {
                            if (isset($values['done'])) {
                                $done++;
                                $lastAction = $key;
                            }
                            ?>
                                <li class="<?php echo $this->getRequest()->getActionName() == $key ? 'active': ''; ?>">
                                    <a data-toggle="tab">
                                        <?php echo $this->getTrans($values['langKey']); ?>
                                    </a>
                                </li>
                        <?php
                        }

                        $progress = 100 / $menuCounter * $done;
                    ?>
                </ul>
                <br />
                <div class="progress  progress-striped">
                    <div class="progress-bar progress-bar-success"
                        role="progressbar"
                        aria-valuenow="<?php echo $progress; ?>"
                        aria-valuemin="0"
                        aria-valuemax="100"
                        style="width: <?php echo $progress; ?>%;">
                    </div>
                </div>
                <div class="install_content">
                    <?php echo $this->getContent(); ?>
                </div>
            </div>
            <div class="container save_box">
                <?php
                    if (!in_array($this->getRequest()->getActionName(), array('index', 'finish'))) {
                ?>
                        <a href="<?php echo $this->getUrl(array('action' => $lastAction)); ?>" class="btn pull-left">
                            <?php echo $this->getTrans('backButton'); ?>
                        </a>
                <?php
                    }
                ?>

                <?php
                    if ($this->getRequest()->getActionName() != 'finish') {
                ?>
                        <button type="submit" name="save" class="btn pull-right">
                            <?php
                                $buttonTrans = 'nextButton';

                                if ($this->getRequest()->getActionName() == 'config') {
                                    $buttonTrans = 'installButton';
                                }

                                echo $this->getTrans($buttonTrans);
                            ?>
                        </button>
                <?php
                    }
                ?>
            </div>
        </form>
    </body>
</html>
