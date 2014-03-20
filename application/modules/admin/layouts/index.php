<!DOCTYPE html>
<html lang="de">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
        <title>Ilch - Admincenter</title>
        <meta name="description" content="Ilch - Login">
        <link rel="shortcut icon" type="image/x-icon" href="<?php echo $this->getStaticUrl('img/favicon.ico'); ?>">
        <link href="<?php echo $this->getStaticUrl('css/bootstrap.css'); ?>" rel="stylesheet">
        <link href="<?php echo $this->getStaticUrl('css/font-awesome.css'); ?>" rel="stylesheet">
        <link href="<?php echo $this->getStaticUrl('css/ilch.css'); ?>" rel="stylesheet">
        <link href="<?php echo $this->getStaticUrl('../application/modules/admin/static/css/main.css'); ?>" rel="stylesheet">
        <link href="<?php echo $this->getStaticUrl('css/ui-lightness/jquery-ui.css'); ?>" rel="stylesheet">
        <link href="<?php echo $this->getStaticUrl('css/chosen/bootstrap-chosen.css') ?>" rel="stylesheet">
        <link href="<?php echo $this->getStaticUrl('css/chosen/chosen.css') ?>" rel="stylesheet">

        <script src="<?php echo $this->getStaticUrl('js/jquery.js'); ?>"></script>
        <script src="<?php echo $this->getStaticUrl('js/jquery-ui.js'); ?>"></script>
        <script src="<?php echo $this->getStaticUrl('js/jquery.mjs.nestedSortable.js'); ?>"></script>
        <script src="<?php echo $this->getStaticUrl('js/bootstrap.js'); ?>"></script>
        <script src="<?php echo $this->getStaticUrl('../application/modules/admin/static/js/functions.js'); ?>"></script>
        <script src="<?php echo $this->getStaticUrl('js/chosen/chosen.jquery.min.js') ?>"></script>
        <script src="<?php echo $this->getStaticUrl('js/validate/jquery.validate.min.js'); ?>"></script>
        <script src="<?php echo $this->getStaticUrl('js/validate/additional-methods.min.js'); ?>"></script>
        <script src="<?php echo $this->getStaticUrl('js/validate/ilch-validate.js'); ?>"></script>
        <script src="<?php echo $this->getStaticUrl('js/ckeditor/ckeditor.js'); ?>"></script>
        <script src="<?php echo $this->getStaticUrl('js/ilch.js'); ?>"></script>
        <style>
            .btn {
                background-image: none;
                border: 1px solid silver;
                border-color: silver;
                -webkit-border-radius: 0px;
                   -moz-border-radius: 0px;
                        border-radius: 0px;
            }

            .form-horizontal .form-group .control-label {
                text-align: left;
            }

            label {
                font-weight: normal;
            }

            legend {
                font-size: 18px;
            }

            i {
                color: black;
            }

            hr {
                margin: 10px 0;
            }

            .clickable {
                cursor: pointer;
            }

            @font-face {
                font-family: 'Glyphicons Halflings';
                src: url('../fonts/glyphicons-halflings-regular.eot');
                src: url('../font/sglyphicons-halflings-regular.eot?#iefix') format('embedded-opentype'), url('../fonts/glyphicons-halflings-regular.woff') format('woff'), url('../fonts/glyphicons-halflings-regular.ttf') format('truetype'), url('../fonts/glyphicons-halflings-regular.svg#glyphicons-halflingsregular') format('svg');
            }

            .cke_button__ilchmedia_label{
               display: inline !important;
            }

            #MediaModal{
                z-index: 100000 !important;
                visibility: visible;
            }
            #MediaModal .modal-dialog {
                width: 100%;
                height: 100%;
                padding: 0;
                margin: auto !important;
            }

            #MediaModal .modal-content {
                height: 96%;
                border-radius: 0;
                margin: 1%;
            }

            #MediaModal .modal-body {
                height: 75%;
                border-radius: 0;
                padding: 0px !important;
            }

            iframe {
                width: 100%;
                min-height: 100%;
            }
        </style>
    </head>
    <body>
        <script>
            /*
             * Custom validate messages.
             */
            jQuery.extend(jQuery.validator.messages, {
                required: <?php echo json_encode($this->getTrans('validateRequired')); ?>,
                email: <?php echo json_encode($this->getTrans('validateEmail')); ?>,
            });
        </script>
        <nav class="navbar navbar-default navbar-fixed-top topnavbar">
            <div class="navbar-header leftbar">
                <img title="Version <?=VERSION?>" class="pull-left" src="<?php echo $this->getStaticUrl('img/ilch_logo_2.png'); ?>" />
                <div class="mobile hidden-md hidden-lg">
                    <button type="button" class="pull-right navbar-toggle" data-toggle="collapse" data-target="#rightbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="pull-right" href="<?php echo $this->getUrl(array('module' => 'admin', 'controller' => 'settings', 'action' => 'index')); ?>">
                        <i class="fa fa-2x fa-cogs"></i>
                    </a>
                    <a class="pull-right" title="<?php echo $this->getTrans('openFrontend'); ?>"
                           target="_blank"
                           href="<?php echo $this->getUrl(); ?>">
                        <i class="fa fa-2x fa-share"></i>
                    </a>
                    <a class="pull-right" href="<?php echo $this->getUrl(array('module' => 'admin', 'controller' => 'index', 'action' => 'index')); ?>">
                        <i class="fa fa-2x fa-home"></i>
                    </a>
                </div>
                <ul class="nav navbar-nav hidden-xs hidden-sm navbar-right">
                    <li>
                        <a href="#" id="search">
                            <i class="fa fa-search"></i> <?php echo $this->getTrans('search'); ?> <b class="caret"></b>
                        </a>
                    </li>
                </ul>
            </div>
            <div id="rightbar" class="rightbar navbar-collapse collapse">
                <ul class="nav navbar-nav">
                    <li class="<?php if($this->getRequest()->getModuleName() == 'admin' && $this->getRequest()->getControllerName() == 'index') {
                                    echo 'active';
                                }?> visible-md visible-lg">
                        <a href="<?php echo $this->getUrl(array('module' => 'admin', 'controller' => 'index', 'action' => 'index')); ?>">
                            <i class="fa fa-home"></i>
                        </a>
                    </li>
                    <li <?php if($this->getRequest()->getModuleName() == 'admin' && $this->getRequest()->getControllerName() == 'menu') {
                                    echo 'class="active"';
                                }?>>
                        <a href="<?php echo $this->getUrl(array('module' => 'admin', 'controller' => 'menu', 'action' => 'index')); ?>">
                            <i class="fa fa-list-ol"></i> <?php echo $this->getTrans('navigation'); ?>
                        </a>
                    </li>
                    <li class="dropdown <?php if($this->getRequest()->getModuleName() !== 'admin') {
                                    echo 'active';
                                }?>">
                        <a data-toggle="dropdown" class="dropdown-toggle"
                               target="_blank"
                               href="<?php echo $this->getUrl(); ?> ">
                            <i class="fa fa-puzzle-piece"></i> <?php echo $this->getTrans('modules'); ?>
                            <b class="caret"></b>
                        </a>
                        <ul role="menu" class="dropdown-menu">
                            <li>
                                <a href="<?=$this->getUrl(array('module' => 'admin', 'controller' => 'modules', 'action' => 'index'))?>">
                                    <i class="fa fa-list-ol"></i> <?php echo $this->getTrans('overview'); ?>
                                </a>
                            </li>
                            <li class="divider"></li>
                            <?php
                                $user = \Ilch\Registry::get('user');

                                foreach ($this->get('modules') as $module) {
                                    if($user->hasAccess('module_'.$module->getId())) {
                                        echo '<li>
                                                <a href="'.$this->getUrl(array('module' => $module->getKey(), 'controller' => 'index', 'action' => 'index')).'">
                                                    <img style="padding-right: 5px;" src="'.$this->getStaticUrl('../application/modules/'.$module->getKey().'/config/'.$module->getIconSmall()).'" />'
                                                    .$module->getName($this->getTranslator()->getLocale()).'</a>
                                            </li>';
                                    }
                                }
                            ?>
                        </ul>
                    </li>
                    <li <?php if($this->getRequest()->getModuleName() == 'admin' && $this->getRequest()->getControllerName() == 'boxes') {
                                    echo 'class="active"';
                                }?>>
                        <a href="<?php echo $this->getUrl(array('module' => 'admin', 'controller' => 'boxes', 'action' => 'index')); ?>">
                            <i class="fa fa-inbox"></i> <?php echo $this->getTrans('boxes'); ?>
                        </a>
                    </li>
                    <li <?php if($this->getRequest()->getModuleName() == 'admin' && $this->getRequest()->getControllerName() == 'layouts') {
                                    echo 'class="active"';
                                }?>>
                        <a href="<?php echo $this->getUrl(array('module' => 'admin', 'controller' => 'layouts', 'action' => 'index')); ?>">
                            <i class="fa fa-picture-o"></i> <?php echo $this->getTrans('layouts'); ?>
                        </a>
                    </li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li class="<?php if($this->getRequest()->getModuleName() == 'admin' && $this->getRequest()->getControllerName() == 'settings') {
                                    echo 'active';
                                }?> visible-md visible-lg">
                        <a href="<?php echo $this->getUrl(array('module' => 'admin', 'controller' => 'settings', 'action' => 'index')); ?>">
                            <i class="fa fa-cogs"></i>
                        </a>
                    </li>
                    <li class="visible-md visible-lg">
                        <a title="<?php echo $this->getTrans('openFrontend'); ?>"
                           target="_blank"
                           href="<?php echo $this->getUrl(); ?>">
                            <i class="fa fa-share"></i>
                        </a>
                    </li>
                    <li class="dropdown">
                        <a data-toggle="dropdown" class="dropdown-toggle"
                               target="_blank"
                               href="<?php echo $this->getUrl(); ?> ">
                            <i class="fa fa-question-circle"></i> <b class="caret"></b>
                        </a>
                        <ul role="menu" class="dropdown-menu">
                            <li>
                                <a href="http://www.ilch.de" target="_blank">
                                    <i class="fa fa-home"></i>
                                    <?php echo $this->getTrans('officalSite'); ?>
                                </a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="http://www.ilch.de/forum.html" target="_blank">
                                    <i class="fa fa-comments-o"></i>
                                    <?php echo $this->getTrans('officalSupportForum'); ?>
                                </a>
                            </li>
                            <li>
                                <a href="http://www.ilch.de/redmine/projects/dev2-doku/wiki" target="_blank">
                                    <i class="fa fa-book"></i>
                                    <?php echo $this->getTrans('documentationFAQ'); ?>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="dropdown dropdown-user">
                        <a data-toggle="dropdown" class="dropdown-toggle"
                               target="_blank"
                               href="<?php echo $this->getUrl(); ?> ">
                            <i class="fa fa-user"></i> <?php echo $this->getUser()->getName(); ?>
                            <b class="caret"></b>
                        </a>
                        <ul role="menu" class="dropdown-menu">
                            <li>
                                <a href="<?php echo $this->getUrl(array('module' => 'admin', 'controller' => 'login', 'action' => 'logout'))?>">
                                    <i class="fa fa-power-off"></i> <?php echo $this->getTrans('logout'); ?>
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
                                        echo '<li class="heading">'.$this->getTrans($key).'</li>';

                                        foreach ($items as $key) {
                                            $class = '';

                                            if ($key['active']) {
                                                $class = ' class="active"';
                                            }

                                            echo '<li'.$class.'>
                                                      <a href="'.$key['url'].'"><i class="'.$key['icon'].'"></i> '.$this->getTrans($key['name']).'</a>
                                                  </li>';
                                        }
                                    }

                                    $actions = $this->getMenuAction();

                                    if (!empty($actions)) {
                                        echo '<li class="divider"></li>';

                                        foreach ($actions as $action) {
                                            echo '<li>
                                                      <a href="'.$action['url'].'"><i class="'.$action['icon'].'"></i> '.$this->getTrans($action['name']).'</a>
                                                  </li>';
                                        }
                                    }
                                ?>
                            </ul>
                            <img class="watermark" src="<?php echo $this->getStaticUrl('img/ilch_logo_sw.png'); ?>" />
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
                                <h4 class="modal-title"><?php echo $this->getTrans('needAcknowledgement'); ?></h4>
                            </div>
                            <div class="modal-body">
                                <p id="modalText"></p>
                            </div>
                            <div class="modal-footer">
                                <button type="button"
                                        class="btn btn-primary"
                                        id="modalButton"><?php echo $this->getTrans('ack'); ?></button>
                                <button type="button"
                                        class="btn btn-primary"
                                        data-dismiss="modal"><?php echo $this->getTrans('cancel'); ?></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" 
                     id="MediaModal" 
                     tabindex="-1" 
                     role="dialog"  
                     aria-labelledby="MediaModalLabel" 
                     aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" 
                                        class="close" 
                                        data-dismiss="modal" 
                                        aria-hidden="true">&times;
                                </button>
                                <h4 class="modal-title" 
                                    id="MediaModalLabel">Media
                                </h4>
                            </div>
                            <div class="modal-body">
                                <iframe frameborder="0"></iframe>
                            </div>
                            <div class="modal-footer">
                                <button type="button" 
                                        class="btn btn-default" 
                                        data-dismiss="modal">Close
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php echo $this->getContent(); ?>
            </div>
        </div>
        <script>
            $('.toggleSidebar').on('click', toggleSidebar);
            var iframeUrlImage = "<?=$this->getUrl('admin/media/iframe/index/type/image/');?>";
            var iframeUrlFile = "<?=$this->getUrl('admin/media/iframe/index/type/file/');?>";
            var iframeUrlMedia = "<?=$this->getUrl('admin/media/iframe/index/type/media/');?>";
            var iframeSingleUrlImage = "<?=$this->getUrl('admin/media/iframe/index/type/single/');?>";
            var ilchMediaPlugin = "<?=$this->getStaticUrl('../application/modules/media/static/js/ilchmedia/');?>";
        </script>
    </body>
</html>
