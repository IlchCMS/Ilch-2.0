<!DOCTYPE html>
<html lang="de">
    <head>
        <meta charset="utf-8">
        <title>Ilch <?php echo VERSION; ?> - Admincenter</title>
        <meta name="description" content="Ilch - Login">
        <link rel="shortcut icon" type="image/x-icon" href="<?php echo $this->staticUrl('img/favicon.ico'); ?>">
        <link href="<?php echo $this->staticUrl('css/bootstrap.css'); ?>" rel="stylesheet">
        <link href="<?php echo $this->staticUrl('css/font-awesome.css'); ?>" rel="stylesheet">
        <link href="<?php echo $this->staticUrl('css/global.css'); ?>" rel="stylesheet">
        <link href="<?php echo $this->staticUrl('css/modules/admin/main.css'); ?>" rel="stylesheet">
        <link href="<?php echo $this->staticUrl('css/ui-lightness/jquery-ui.css'); ?>" rel="stylesheet">
        <link href="<?php echo $this->staticUrl('css/chosen/bootstrap-chosen.css') ?>" rel="stylesheet">
        <link href="<?php echo $this->staticUrl('css/chosen/chosen.css') ?>" rel="stylesheet">

        <script src="<?php echo $this->staticUrl('js/jquery.js'); ?>"></script>
        <script src="<?php echo $this->staticUrl('js/jquery-ui.js'); ?>"></script>
        <script src="<?php echo $this->staticUrl('js/jquery.mjs.nestedSortable.js'); ?>"></script>
        <script src="<?php echo $this->staticUrl('js/bootstrap.js'); ?>"></script>
        <script src="<?php echo $this->staticUrl('js/modules/admin/functions.js'); ?>"></script>
        <script src="<?php echo $this->staticUrl('js/chosen/chosen.jquery.min.js') ?>"></script>
        <script src="<?php echo $this->staticUrl('js/validate/jquery.validate.min.js'); ?>"></script>
        <script src="<?php echo $this->staticUrl('js/validate/additional-methods.min.js'); ?>"></script>
        <script src="<?php echo $this->staticUrl('js/validate/ilch-validate.js'); ?>"></script>

    </head>
    <body>
        <script>
            /*
             * Custom validate messages.
             */
            jQuery.extend(jQuery.validator.messages, {
                required: <?php echo json_encode($this->trans('validateRequired')); ?>,
                email: <?php echo json_encode($this->trans('validateEmail')); ?>,
            });
        </script>
        <nav class="navbar navbar-default navbar-fixed-top topnavbar">
            <div class="navbar-header leftbar">
                <img class="brand" src="<?php echo $this->staticUrl('img/ilch_logo_brand.png'); ?>" />
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                         <a href="#" id="search" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-search"></i> <?php echo $this->trans('search'); ?><b class="caret"></b></a>
                    </li>
                </ul>
            </div>
            <div class="collapse navbar-collapse navbar-ex1-collapse">
                <ul class="nav navbar-nav">
                    <li>
                        <a href="<?php echo $this->url(array('module' => 'admin', 'controller' => 'index', 'action' => 'index')); ?>">
                            <i class="fa fa-home"></i> <?php echo $this->trans('home'); ?>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo $this->url(array('module' => 'admin', 'controller' => 'menu', 'action' => 'index')); ?>">
                            <i class="fa fa-list-ol"></i> <?php echo $this->trans('navigation'); ?>
                        </a>
                    </li>
                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="<?php echo $this->url(array('controller' => 'modules', 'controller' => 'index', 'action' => 'index')); ?>">
                            <i class="fa fa-puzzle-piece"></i> <?php echo $this->trans('modules'); ?>
                            <b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu">
                            <?php
                                foreach ($this->get('modules') as $module) {
                                    echo '<li>
                                            <a href="'.$this->url(array('module' => $module->getKey(), 'controller' => 'index', 'action' => 'index')).'">
                                                <img style="padding-right: 5px;" src="'.$this->staticUrl('img/modules/'.$module->getKey().'/'.$module->getIconSmall()).'" />'
                                                .$module->getName($this->getTranslator()->getLocale()).'</a>
                                        </li>';
                                }
                            ?>
                        </ul>
                    </li>
                    <li>
                        <a href="#<?php echo $this->url(array('module' => 'admin', 'controller' => 'layouts', 'action' => 'index')); ?>">
                            <i class="fa fa-picture-o"></i> <?php echo $this->trans('layouts'); ?>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo $this->url(array('module' => 'admin', 'controller' => 'settings', 'action' => 'index')); ?>">
                            <i class="fa fa-cogs"></i> <?php echo $this->trans('system'); ?>
                        </a>
                    </li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-user"></i> <?php echo $this->getUser()->getName(); ?>
                        <span class="caret"></span>
                    </a>
                        <ul class="dropdown-menu">
                            <li class="divider"></li>
                            <li>
                                <a href="<?php echo $this->url(array('module' => 'admin', 'controller' => 'login', 'action' => 'logout'))?>">
                                    <i class="fa fa-power-off"></i> <?php echo $this->trans('logout');?>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
        <div id="app">
                <?php
                    $contentFullClass = 'app_right_full';

                    if (($this->getRequest()->getControllerName() !== 'index' && $this->getRequest()->getModuleName() == 'admin') || $this->getRequest()->getModuleName() !== 'admin') {
                        $contentFullClass = '';
                ?>
                    <div class="app_left">
                        <i class="fa fa-angle-left toggleSidebar slideLeft"></i>
                        <div id="sidebar_content">
                            <ul class="nav">
                                <?php
                                    foreach ($this->getMenus() as $key => $items) {
                                        echo '<li>'.$this->trans($key).'</li>';

                                        foreach ($items as $key) {
                                            $class = '';

                                            if ($key['active']) {
                                                $class = 'active';
                                            }

                                            echo '<li class="'.$class.'">
                                                      <a href="'.$key['url'].'"><i class="'.$key['icon'].'"></i> '.$this->trans($key['name']).'</a>
                                                  </li>';
                                        }
                                    }

                                    $actions = $this->getMenuAction();

                                    if (!empty($actions)) {
                                        echo '<li class="divider"></li>';

                                        foreach ($actions as $action) {
                                            echo '<li>
                                                      <a href="'.$action['url'].'"><i class="'.$action['icon'].'"></i> '.$this->trans($action['name']).'</a>
                                                  </li>';
                                        }
                                    }
                                ?>
                            </ul>
                            <img class="watermark" src="<?php echo $this->staticUrl('img/ilch_logo_sw.png'); ?>" />
                        </div>
                    </div>
                <?php
                    }
                ?>

            <div class="app_right <?php echo $contentFullClass?>">
                <?php
                    foreach ($this->get('messages') as $key => $message) {
                ?>
                    <div class="alert alert-<?php echo $message['type']; ?> alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <?php echo $this->escape($message['text']); ?>
                    </div>
                <?php
                        unset($_SESSION['messages'][$key]);
                    }
                ?>
                <i class="toggleSidebar slideRight"></i>
                <?php echo $this->getContent(); ?>
            </div>
        </div>

        <script>
            $('.toggleSidebar').on('click', toggleSidebar);
        </script>
    </body>
</html>
