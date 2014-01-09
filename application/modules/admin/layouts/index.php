<!DOCTYPE html>
<html lang="de">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
        <title>Ilch <?php echo VERSION; ?> - Admincenter</title>
        <meta name="description" content="Ilch - Login">
        <link rel="shortcut icon" type="image/x-icon" href="<?php echo $this->staticUrl('img/favicon.ico'); ?>">
        <link href="<?php echo $this->staticUrl('css/bootstrap.css'); ?>" rel="stylesheet">
        <link href="<?php echo $this->staticUrl('css/font-awesome.css'); ?>" rel="stylesheet">
        <link href="<?php echo $this->staticUrl('css/global.css'); ?>" rel="stylesheet">
        <link href="<?php echo $this->staticUrl('../application/modules/admin/static/css/main.css'); ?>" rel="stylesheet">
        <link href="<?php echo $this->staticUrl('css/ui-lightness/jquery-ui.css'); ?>" rel="stylesheet">
        <link href="<?php echo $this->staticUrl('css/chosen/bootstrap-chosen.css') ?>" rel="stylesheet">
        <link href="<?php echo $this->staticUrl('css/chosen/chosen.css') ?>" rel="stylesheet">

        <script src="<?php echo $this->staticUrl('js/jquery.js'); ?>"></script>
        <script src="<?php echo $this->staticUrl('js/jquery-ui.js'); ?>"></script>
        <script src="<?php echo $this->staticUrl('js/jquery.mjs.nestedSortable.js'); ?>"></script>
        <script src="<?php echo $this->staticUrl('js/bootstrap.js'); ?>"></script>
        <script src="<?php echo $this->staticUrl('../application/modules/admin/static/js/functions.js'); ?>"></script>
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
                <img class="pull-left" src="<?php echo $this->staticUrl('img/ilch_logo_2.png'); ?>" />
                <div class="mobile hidden-md hidden-lg">
                    <button type="button" class="pull-right navbar-toggle" data-toggle="collapse" data-target="#rightbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="pull-right" href="<?php echo $this->url(array('module' => 'admin', 'controller' => 'settings', 'action' => 'index')); ?>">
                        <i class="fa fa-2x fa-cogs"></i>
                    </a>
                    <a class="pull-right" title="<?php echo $this->trans('openFrontend'); ?>"
                           target="_blank"
                           href="<?php echo $this->url(); ?>">
                        <i class="fa fa-2x fa-share"></i>
                    </a>
                    <a class="pull-right" href="<?php echo $this->url(array('module' => 'admin', 'controller' => 'index', 'action' => 'index')); ?>">
                        <i class="fa fa-2x fa-home"></i>
                    </a>
                </div>
                <ul class="nav navbar-nav hidden-xs hidden-sm navbar-right">
                    <li>
                        <a href="#" id="search">
                            <i class="fa fa-search"></i> <?php echo $this->trans('search'); ?> <b class="caret"></b>
                        </a>
                    </li>
                </ul>
            </div>
            <div id="rightbar" class="rightbar navbar-collapse collapse">
                <ul class="nav navbar-nav">
                    <li class="<?php if($this->getRequest()->getModuleName() == 'admin' && $this->getRequest()->getControllerName() == 'index') {
                                    echo 'active';
                                }?> visible-md visible-lg">
                        <a href="<?php echo $this->url(array('module' => 'admin', 'controller' => 'index', 'action' => 'index')); ?>">
                            <i class="fa fa-home"></i>
                        </a>
                    </li>
                    <li <?php if($this->getRequest()->getModuleName() == 'admin' && $this->getRequest()->getControllerName() == 'menu') {
                                    echo 'class="active"';
                                }?>>
                        <a href="<?php echo $this->url(array('module' => 'admin', 'controller' => 'menu', 'action' => 'index')); ?>">
                            <i class="fa fa-list-ol"></i> <?php echo $this->trans('navigation'); ?>
                        </a>
                    </li>
                    <li class="dropdown <?php if($this->getRequest()->getModuleName() !== 'admin') {
                                    echo 'active';
                                }?>">
                        <a data-toggle="dropdown" class="dropdown-toggle"
                               target="_blank"
                               href="<?php echo $this->url(); ?> ">
                            <i class="fa fa-puzzle-piece"></i> <?php echo $this->trans('modules'); ?>
                            <b class="caret"></b>
                        </a>
                        <ul role="menu" class="dropdown-menu">
                            <li>
                                <a href="#">
                                    <i class="fa fa-list-ol"></i> <?php echo $this->trans('overview'); ?>
                                </a>
                            </li>
                            <li class="divider"></li>
                            <?php
                                foreach ($this->get('modules') as $module) {
                                    echo '<li>
                                            <a href="'.$this->url(array('module' => $module->getKey(), 'controller' => 'index', 'action' => 'index')).'">
                                                <img style="padding-right: 5px;" src="'.$this->staticUrl('../application/modules/'.$module->getKey().'/config/'.$module->getIconSmall()).'" />'
                                                .$module->getName($this->getTranslator()->getLocale()).'</a>
                                        </li>';
                                }
                            ?>
                        </ul>
                    </li>
                    <li <?php if($this->getRequest()->getModuleName() == 'admin' && $this->getRequest()->getControllerName() == 'boxes') {
                                    echo 'class="active"';
                                }?>>
                        <a href="<?php echo $this->url(array('module' => 'admin', 'controller' => 'boxes', 'action' => 'index')); ?>">
                            <i class="fa fa-inbox"></i> <?php echo $this->trans('boxes'); ?>
                        </a>
                    </li>
                    <li <?php if($this->getRequest()->getModuleName() == 'admin' && $this->getRequest()->getControllerName() == 'layouts') {
                                    echo 'class="active"';
                                }?>>
                        <a href="<?php echo $this->url(array('module' => 'admin', 'controller' => 'layouts', 'action' => 'index')); ?>">
                            <i class="fa fa-picture-o"></i> <?php echo $this->trans('layouts'); ?>
                        </a>
                    </li>
                    <li class="dropdown <?php if($this->getRequest()->getModuleName() !== 'admin') {
                                    echo 'active';
                                }?>">
                        <a data-toggle="dropdown" class="dropdown-toggle"
                               target="_blank"
                               href="<?php echo $this->url(); ?> ">
                            <i class="fa fa-question-circle"></i> <?php echo $this->trans('help'); ?>
                            <b class="caret"></b>
                        </a>
                        <ul role="menu" class="dropdown-menu">
                            <li>
                                <a href="http://www.ilch.de" target="_blank">
                                    <i class="fa fa-home"></i>
                                    <?php echo $this->trans('officalSite'); ?></a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="http://www.ilch.de/forum.html" target="_blank">
                                    <i class="fa fa-comments-o"></i>
                                    <?php echo $this->trans('officalSupportForum'); ?></a>
                            </li>
                            <li>
                                <a href="http://www.ilch.de/doku.html" target="_blank">
                                    <i class="fa fa-book"></i>
                                    <?php echo $this->trans('documentationFAQ'); ?></a>
                            </li>
                        </ul>
                    </li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li class="<?php if($this->getRequest()->getModuleName() == 'admin' && $this->getRequest()->getControllerName() == 'settings') {
                                    echo 'active';
                                }?> visible-md visible-lg">
                        <a href="<?php echo $this->url(array('module' => 'admin', 'controller' => 'settings', 'action' => 'index')); ?>">
                            <i class="fa fa-cogs"></i>
                        </a>
                    </li>
                    <li class="visible-md visible-lg">
                        <a title="<?php echo $this->trans('openFrontend'); ?>"
                           target="_blank"
                           href="<?php echo $this->url(); ?>">
                            <i class="fa fa-share"></i>
                        </a>
                    </li>
                    <li class="dropdown">
                        <a data-toggle="dropdown" class="dropdown-toggle"
                               target="_blank"
                               href="<?php echo $this->url(); ?> ">
                            <i class="fa fa-user"></i> <?php echo $this->getUser()->getName(); ?>
                            <b class="caret"></b>
                        </a>
                        <ul role="menu" class="dropdown-menu">
                            <li>
                                <a href="<?php echo $this->url(array('module' => 'admin', 'controller' => 'login', 'action' => 'logout'))?>">
                                    <i class="fa fa-power-off"></i> <?php echo $this->trans('logout'); ?>
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

                    if ($this->hasSidebar()) {
                        $contentFullClass = '';
                ?>
                    <div class="app_left">
                        <i class="fa fa-angle-left toggleSidebar slideLeft"></i>
                        <div id="sidebar_content">
                            <ul class="nav">
                                <?php
                                    foreach ($this->getMenus() as $key => $items) {
                                        echo '<li class="heading">'.$this->trans($key).'</li>';

                                        foreach ($items as $key) {
                                            $class = '';

                                            if ($key['active']) {
                                                $class = ' class="active"';
                                            }

                                            echo '<li'.$class.'>
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
                <i class="toggleSidebar slideRight"></i>
                <div class="modal fade"
                     id="deleteModal">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button"
                                        class="close"
                                        data-dismiss="modal"
                                        aria-hidden="true">&times;</button>
                                <h4 class="modal-title"><?php echo $this->trans('needAcknowledgement'); ?></h4>
                            </div>
                            <div class="modal-body">
                                <p id="modalText"></p>
                            </div>
                            <div class="modal-footer">
                                <button type="button"
                                        class="btn btn-primary"
                                        id="modalButton"><?php echo $this->trans('ack'); ?></button>
                                <button type="button"
                                        class="btn btn-primary"
                                        data-dismiss="modal"><?php echo $this->trans('cancel'); ?></button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php echo $this->getContent(); ?>
            </div>
        </div>
        <script>
            $('.toggleSidebar').on('click', toggleSidebar);
        </script>
    </body>
</html>
