<!DOCTYPE html>
<html lang="de">
    <head>
        <title>Ilch - Admincenter</title>
        
        <!-- META -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
        <meta name="description" content="Ilch - Admincenter">
        
        <!-- FAVICON -->
        <link rel="shortcut icon" type="image/x-icon" href="<?php echo $this->getStaticUrl('img/favicon.ico'); ?>">
        
        <!-- STYLES -->
        <link href="<?php echo $this->getStaticUrl('css/bootstrap.css'); ?>" rel="stylesheet">
        <link href="<?php echo $this->getStaticUrl('css/font-awesome.css'); ?>" rel="stylesheet">
        <link href="<?php echo $this->getStaticUrl('css/ilch.css'); ?>" rel="stylesheet">
        <link href="<?php echo $this->getStaticUrl('../application/modules/admin/static/css/main.css'); ?>" rel="stylesheet">
        <link href="<?php echo $this->getStaticUrl('css/ui-lightness/jquery-ui.css'); ?>" rel="stylesheet">
        <link href="<?php echo $this->getStaticUrl('css/chosen/bootstrap-chosen.css') ?>" rel="stylesheet">
        <link href="<?php echo $this->getStaticUrl('css/chosen/chosen.css') ?>" rel="stylesheet">
        <link href="<?php echo $this->getStaticUrl('../application/modules/admin/static/css/admin.css'); ?>" rel="stylesheet">

        <!-- SCRIPTS -->
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
    </head>
    <body id="body" class="">
        <script>
            /*
             * Custom validate messages.
             */
            jQuery.extend(jQuery.validator.messages, {
                required: <?php echo json_encode($this->getTrans('validateRequired')); ?>,
                email: <?php echo json_encode($this->getTrans('validateEmail')); ?>,
            });
        </script>

        <!-- HEADER -->
        <header id="header">
            <!-- TOP NAVBAR -->
            <nav class="navbar navbar-default topnavbar">
                <!-- TOP NAVBAR LEFT -->
                <div class="navbar-header leftbar">
		<?php if ($this->hasSidebar()) { ?>
                    <div id="hide-menu" class="btn-header pull-left">
			<a href="#" id="toggleLeftMenu" title="Collapse Menu">
                            <i class="fa fa-outdent"></i>
                        </a>
                    </div>
                <?php } ?>
                    <img title="Version <?=VERSION?>" class="pull-left logo" src="<?php echo $this->getStaticUrl('img/ilch_logo_2.png'); ?>" />
                    <div class="hidden-md hidden-lg">
                        <a class="<?php if($this->getRequest()->getModuleName() == 'admin' && $this->getRequest()->getControllerName() == 'index') {
                                    echo 'active';
                                    }?> home" href="<?php echo $this->getUrl(array('module' => 'admin', 'controller' => 'index', 'action' => 'index')); ?>">
                            <i class="fa fa-home"></i>
                        </a>
			<button type="button" class="pull-right navbar-toggle" data-toggle="collapse" data-target="#rightbar">
                            <i class="fa fa-th"></i>
                        </button>
                    </div>
                </div>
                <!-- TOP NAVBAR LEFT END -->
                <!-- TOP NAVBAR RIGHT -->
		<nav id="rightbar" class="rightbar navbar-collapse collapse">
                    <ul class="nav navbar-nav">
                        <li class="<?php if($this->getRequest()->getModuleName() == 'admin' && $this->getRequest()->getControllerName() == 'index') {
                                    echo 'active';
                                    }?> visible-md visible-lg">
                            <a href="<?php echo $this->getUrl(array('module' => 'admin', 'controller' => 'index', 'action' => 'index')); ?>">
                                <i class="fa fa-home"></i>
                            </a>
                        </li>
                        <?php
                            if($this->getUser()->isAdmin()) {
                        ?>
                        <li <?php if($this->getRequest()->getModuleName() == 'admin' && $this->getRequest()->getControllerName() == 'menu') {
                                        echo 'class="active"';
                                    }?>>
                            <a href="<?php echo $this->getUrl(array('module' => 'admin', 'controller' => 'menu', 'action' => 'index')); ?>">
                                <i class="fa fa-list-ol"></i> <?php echo $this->getTrans('navigation'); ?>
                            </a>
                        </li>
                            <?php
                                }
                        
                            $user = \Ilch\Registry::get('user');
                            $modulesHtml = $systemModuleHtml = '';

                            foreach ($this->get('modules') as $module) {
                                if($user->hasAccess('module_'.$module->getKey())) {
                                    $content = $module->getContentForLocale($this->getTranslator()->getLocale());

                                    if ($module->getSystemModule()) {
                                        $systemModuleHtml .= '<li>
                                                <a href="'.$this->getUrl(array('module' => $module->getKey(), 'controller' => 'index', 'action' => 'index')).'">
                                                    <img style="padding-right: 5px;" src="'.$this->getStaticUrl('../application/modules/'.$module->getKey().'/config/'.$module->getIconSmall()).'" />'
                                                    .$content['name'].'</a>
                                            </li>';
                                    } else {
                                        $modulesHtml .= '<li>
                                                <a href="'.$this->getUrl(array('module' => $module->getKey(), 'controller' => 'index', 'action' => 'index')).'">
                                                    <img style="padding-right: 5px;" src="'.$this->getStaticUrl('../application/modules/'.$module->getKey().'/config/'.$module->getIconSmall()).'" />'
                                                    .$content['name'].'</a>
                                            </li>';
                                    }
                                }
                            }

                            if (!empty($modulesHtml) || !empty($systemModuleHtml)) {
                        ?>
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
                                <?php
                                    if($this->getUser()->isAdmin()) {
                                ?>  
                                <li>
                                    <a href="<?=$this->getUrl(array('module' => 'admin', 'controller' => 'modules', 'action' => 'index'))?>">
                                        <i class="fa fa-list-ol"></i> <?php echo $this->getTrans('overview'); ?>
                                    </a>
                                </li>
                                <li class="divider"></li>
                                <?php
                                    }
                                    echo $systemModuleHtml;
                                    echo '<li class="divider"></li>';
                                    echo $modulesHtml;
                                ?>
                            </ul>
                        </li>
                        <?php
                            }

                            if($this->getUser()->isAdmin()) {
                        ?>
                        <li <?php if($this->getRequest()->getModuleName() == 'admin' && $this->getRequest()->getControllerName() == 'boxes') {
                                        echo 'class="active"';
                                    }?>>
                            <a href="<?php echo $this->getUrl(array('module' => 'admin', 'controller' => 'boxes', 'action' => 'index')); ?>">
                                <i class="fa fa-inbox"></i> <?php echo $this->getTrans('boxes'); ?>
                            </a>
                        </li>
                        <?php
                            }

                            if($this->getUser()->isAdmin()) {
                        ?>
                        <li <?php if($this->getRequest()->getModuleName() == 'admin' && $this->getRequest()->getControllerName() == 'layouts') {
                                        echo 'class="active"';
                                    }?>>
                            <a href="<?php echo $this->getUrl(array('module' => 'admin', 'controller' => 'layouts', 'action' => 'index')); ?>">
                                <i class="fa fa-picture-o"></i> <?php echo $this->getTrans('layouts'); ?>
                            </a>
                        </li>
                        <?php
                            }
                        ?>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                    <?php
                        if($this->getUser()->isAdmin()) {
                    ?>
                    <li class="<?php if($this->getRequest()->getModuleName() == 'admin' && $this->getRequest()->getControllerName() == 'settings') {
                                    echo 'active';
                                }?>">
                        <a href="<?php echo $this->getUrl(array('module' => 'admin', 'controller' => 'settings', 'action' => 'index')); ?>">
                            <i class="fa fa-cogs"></i>
                        </a>
                    </li>
                    <?php
                        }
                    ?>
                    <li>
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
                    <!-- Search Block -->
                    <li>
                        <i id="search-header" class="fa fa-search search-btn"></i>
                        <div id="search-div" class="search-close">
                            <div class="input-group">
                                <input class="form-control" placeholder="Search" type="text">
                                <span class="input-group-btn">
                                    <button class="btn btn-default" type="button">Go</button>
                                </span>
                            </div>
                        </div>    
                    </li>
                    <!-- Search Block End -->
                </ul>
                </nav>
                <!-- TOP NAVBAR RIGHT END -->
            </nav>
            <!-- TOP NAVBAR END -->
	</header>
        <!-- HEADER END -->
	<?php
        $contentFullClass = 'content_full';
        if ($this->hasSidebar()) {
            $contentFullClass = '';
        ?>
        <!-- LEFT PANEL -->
	<aside id="left-panel">
            <nav>
		<ul>
		<?php
                foreach ($this->getMenus() as $key => $items) {
                                        echo '<li class="heading"><i class="fa fa-puzzle-piece"></i>  '.$this->getTrans($key).'</li>';

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
            </nav>
            <img class="watermark" src="<?php echo $this->getStaticUrl('img/ilch_logo.png'); ?>" />
        </aside>
        <!-- LEFT PANEL END -->
	<?php } ?>
        <!-- MAIN -->
	<div id="main" role="main" class="<?php echo $contentFullClass?>">
            <div id="ribbon">
                <?php echo $this->getAdminHmenu(); ?>
            </div>
            <!-- CONTENT -->
            <div id="content">
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
            <!-- CONTENT END -->
	</div>
        <!-- MAIN END -->

	<script>
            var iframeUrlImage = "<?=$this->getUrl('admin/media/iframe/index/type/image/');?>";
            var iframeUrlFile = "<?=$this->getUrl('admin/media/iframe/index/type/file/');?>";
            var iframeUrlMedia = "<?=$this->getUrl('admin/media/iframe/index/type/media/');?>";
            var iframeSingleUrlImage = "<?=$this->getUrl('admin/media/iframe/index/type/single/');?>";
            var iframeSingleUrlGallery = "<?=$this->getUrl('admin/media/iframe/multi/type/multi/');?>";
            var iframeSingleUrlDownloads = "<?=$this->getUrl('admin/media/iframe/multi/type/file/');?>";
            var ilchMediaPlugin = "<?=$this->getStaticUrl('../application/modules/media/static/js/ilchmedia/');?>";
        </script>

    </body>
</html>
