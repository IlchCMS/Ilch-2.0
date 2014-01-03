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
                <img class="brand" src="<?php echo $this->staticUrl('img/ilch_logo_brand.png'); ?>" />
                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <div>
                            <a class="btn btn-default" href="#" id="search">
                                <i class="fa fa-search"></i> <?php echo $this->trans('search'); ?> <b class="caret"></b>
                            </a>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="collapse navbar-collapse navbar-ex1-collapse">
                <ul class="nav navbar-nav">
                    <li>
                        <div>
                            <a class="btn btn-default" href="<?php echo $this->url(array('module' => 'admin', 'controller' => 'index', 'action' => 'index')); ?>">
                                <i class="fa fa-home"></i> <?php echo $this->trans('home'); ?>
                            </a>
                        </div>
                    </li>
                    <li>
                        <div>
                            <a class="btn btn-default" href="<?php echo $this->url(array('module' => 'admin', 'controller' => 'menu', 'action' => 'index')); ?>">
                                <i class="fa fa-list-ol"></i> <?php echo $this->trans('navigation'); ?>
                            </a>
                        </div>
                    </li>
                    <li>
                        <div class="btn-group dropdown-toggle">
                            <a class="btn btn-default" href="<?php echo $this->url(array('controller' => 'modules', 'controller' => 'index', 'action' => 'index')); ?>" >
                                <i class="fa fa-puzzle-piece"></i> <?php echo $this->trans('modules'); ?>
                            </a>
                            <button data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button">
                                <span class="caret"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul role="menu" class="dropdown-menu">
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
                        </div>
                    </li>
                    <li>
                        <div>
                            <a class="btn btn-default" href="<?php echo $this->url(array('module' => 'admin', 'controller' => 'layouts', 'action' => 'index')); ?>">
                                <i class="fa fa-picture-o"></i> <?php echo $this->trans('layouts'); ?>
                            </a>
                        </div>
                    </li>
                    <li>
                        <div>
                            <a class="btn btn-default" href="<?php echo $this->url(array('module' => 'admin', 'controller' => 'settings', 'action' => 'index')); ?>">
                                <i class="fa fa-cogs"></i> <?php echo $this->trans('system'); ?>
                            </a>
                        </div>
                    </li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <div>
                            <a class="btn btn-default" target="_blank" href="<?php echo $this->url(); ?> ">
                                <i class="fa fa-share"></i> <?php echo $this->trans('frontend'); ?>
                            </a>
                        </div>
                    </li>
                    <li>
                        <div class="btn-group dropdown-toggle">
                            <a class="btn btn-default" href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-user"></i> <?php echo $this->getUser()->getName(); ?>
                                <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="<?php echo $this->url(array('module' => 'admin', 'controller' => 'login', 'action' => 'logout'))?>">
                                        <i class="fa fa-power-off"></i> <?php echo $this->trans('logout'); ?>
                                    </a>
                                </li>
                            </ul>
                        </div>
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
