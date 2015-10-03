<!DOCTYPE html>
<html lang="de">
    <head>
        <title>Ilch - Admincenter</title>

        <!-- META -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
        <meta name="description" content="Ilch - Admincenter">

        <!-- FAVICON -->
        <link rel="shortcut icon" type="image/x-icon" href="<?=$this->getStaticUrl('img/favicon.ico') ?>">

        <!-- STYLES -->
        <link href="<?=$this->getStaticUrl('css/bootstrap.css') ?>" rel="stylesheet">
        <link href="<?=$this->getStaticUrl('css/font-awesome.min.css') ?>" rel="stylesheet">
        <link href="<?=$this->getStaticUrl('css/ilch.css') ?>" rel="stylesheet">
        <link href="<?=$this->getStaticUrl('../application/modules/admin/static/css/main.css') ?>" rel="stylesheet">
        <link href="<?=$this->getStaticUrl('css/ui-lightness/jquery-ui.css') ?>" rel="stylesheet">
        <link href="<?=$this->getStaticUrl('css/chosen/bootstrap-chosen.css') ?>" rel="stylesheet">
        <link href="<?=$this->getStaticUrl('css/chosen/chosen.css') ?>" rel="stylesheet">
        <link href="<?=$this->getStaticUrl('../application/modules/admin/static/css/admin.css') ?>" rel="stylesheet">

        <!-- SCRIPTS -->
        <script src="<?=$this->getStaticUrl('js/jquery.js') ?>"></script>
        <script src="<?=$this->getStaticUrl('js/jquery-ui.js') ?>"></script>
        <script src="<?=$this->getStaticUrl('js/jquery.ui.touch-punch.min.js') ?>"></script>
        <script src="<?=$this->getStaticUrl('js/jquery.mjs.nestedSortable.js') ?>"></script>
        <script src="<?=$this->getStaticUrl('js/bootstrap.js') ?>"></script>
        <script src="<?=$this->getStaticUrl('../application/modules/admin/static/js/functions.js') ?>"></script>
        <script src="<?=$this->getStaticUrl('js/chosen/chosen.jquery.min.js') ?>"></script>
        <script src="<?=$this->getStaticUrl('js/validate/jquery.validate.min.js') ?>"></script>
        <script src="<?=$this->getStaticUrl('js/validate/additional-methods.min.js') ?>"></script>
        <script src="<?=$this->getStaticUrl('js/validate/ilch-validate.js') ?>"></script>
        <script src="<?=$this->getStaticUrl('js/ckeditor/ckeditor.js') ?>"></script>
        <script src="<?=$this->getStaticUrl('js/ilch.js') ?>"></script>
    </head>
    <body id="body" class="">
        <script>
        /*
         * Custom validate messages.
         */
        jQuery.extend(jQuery.validator.messages, {
            required: <?=json_encode($this->getTrans('validateRequired')) ?>,
            email: <?=json_encode($this->getTrans('validateEmail')) ?>,
        });
        </script>

        <!-- HEADER -->
        <header id="header">
            <!-- TOP NAVBAR -->
            <?php $config = \Ilch\Registry::get('config'); ?>
            <nav class="navbar navbar-default topnavbar <?=$config->get('admin_layout_top_nav') ?>">
                <!-- TOP NAVBAR LEFT -->
                <div class="navbar-header leftbar">
                    <?php if ($this->hasSidebar()): ?>
                        <div id="hide-menu" class="btn-header pull-left">
                            <a href="javascript:void(0)" id="toggleLeftMenu" title="Collapse Menu">
                                <i class="fa fa-outdent"></i>
                            </a>
                        </div>
                    <?php endif; ?>
                    <img title="Version <?=VERSION ?>" class="pull-left logo" src="<?=$this->getStaticUrl('img/ilch_logo_2.png') ?>" />
                    <div class="hidden-md hidden-lg">
                        <a class="<?php if($this->getRequest()->getModuleName() == 'admin' && $this->getRequest()->getControllerName() == 'index') { echo 'active'; }?> home" href="<?=$this->getUrl(array('module' => 'admin', 'controller' => 'index', 'action' => 'index')) ?>">
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
                        <li class="<?php if($this->getRequest()->getModuleName() == 'admin' && $this->getRequest()->getControllerName() == 'index') { echo 'active'; } ?> visible-md visible-lg">
                            <a href="<?=$this->getUrl(array('module' => 'admin', 'controller' => 'index', 'action' => 'index')) ?>">
                                <i class="fa fa-home"></i>
                            </a>
                        </li>
                        <?php if($this->getUser()->isAdmin()): ?>
                            <li <?php if($this->getRequest()->getModuleName() == 'admin' && $this->getRequest()->getControllerName() == 'menu') { echo 'class="active"'; } ?>>
                                <a href="<?=$this->getUrl(array('module' => 'admin', 'controller' => 'menu', 'action' => 'index')) ?>">
                                    <i class="fa fa-list-ol"></i> <?=$this->getTrans('navigation') ?>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php $user = \Ilch\Registry::get('user'); ?>
                        <?php $modulesHtml = $systemModuleHtml = ''; ?>

                        <?php foreach ($this->get('modules') as $module): ?>
                            <?php if($user->hasAccess('module_'.$module->getKey())): ?>
                                <?php $content = $module->getContentForLocale($this->getTranslator()->getLocale()); ?>

                                <?php if ($module->getSystemModule()): ?>
                                    <?php $systemModuleHtml .= '<a class="list-group-item " href="'.$this->getUrl(array('module' => $module->getKey(), 'controller' => 'index', 'action' => 'index')).'">
                                                <img style="padding-right: 5px;" src="'.$this->getStaticUrl('../application/modules/'.$module->getKey().'/config/'.$module->getIconSmall()).'" />'
                                                .$content['name'].'</a>'; ?>
                                <?php else: ?>
                                    <?php $modulesHtml .= '<a class="list-group-item " href="'.$this->getUrl(array('module' => $module->getKey(), 'controller' => 'index', 'action' => 'index')).'">
                                                <img style="padding-right: 5px;" src="'.$this->getStaticUrl('../application/modules/'.$module->getKey().'/config/'.$module->getIconSmall()).'" />'
                                                .$content['name'].'</a>'; ?>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <?php if(!empty($modulesHtml) || !empty($systemModuleHtml)): ?>
                            <li id="ilch_dropdown" class="dropdown <?php if($this->getRequest()->getModuleName() !== 'admin') { echo 'active'; } ?>">
                                <a data-toggle="dropdown" class="dropdown-toggle" target="_blank" href="<?=$this->getUrl() ?>">
                                    <i class="fa fa-puzzle-piece"></i> <?=$this->getTrans('modules') ?>
                                    <b class="caret"></b>
                                </a>
                                <ul role="menu" class="dropdown-menu full">
                                    <?php if($this->getUser()->isAdmin()): ?>
                                        <a href="<?=$this->getUrl(array('module' => 'admin', 'controller' => 'modules', 'action' => 'index')) ?>">
                                            <i class="fa fa-list-ol"></i> <?=$this->getTrans('overview') ?>
                                        </a>
                                        <div class="divider"></div>
                                        <li>
                                            <div class="list-group list-group-horizontal">
                                                <?=$systemModuleHtml ?>
                                            </div>
                                            <div class="divider"></div>
                                            <div class="list-group list-group-horizontal">
                                                <?=$modulesHtml ?>
                                            </div>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </li>
                        <?php endif; ?>
                        <?php if($this->getUser()->isAdmin()): ?>
                            <li <?php if($this->getRequest()->getModuleName() == 'admin' && $this->getRequest()->getControllerName() == 'boxes') { echo 'class="active"'; } ?>>
                                <a href="<?=$this->getUrl(array('module' => 'admin', 'controller' => 'boxes', 'action' => 'index')) ?>">
                                    <i class="fa fa-inbox"></i> <?=$this->getTrans('boxes') ?>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if($this->getUser()->isAdmin()): ?>
                            <li <?php if($this->getRequest()->getModuleName() == 'admin' && $this->getRequest()->getControllerName() == 'layouts') { echo 'class="active"'; } ?>>
                                <a href="<?=$this->getUrl(array('module' => 'admin', 'controller' => 'layouts', 'action' => 'index')) ?>">
                                    <i class="fa fa-picture-o"></i> <?=$this->getTrans('layouts') ?>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <?php if($this->getUser()->isAdmin()): ?>
                            <li class="<?php if($this->getRequest()->getModuleName() == 'admin' && $this->getRequest()->getControllerName() == 'settings') { echo 'active'; } ?>">
                                <a href="<?=$this->getUrl(array('module' => 'admin', 'controller' => 'settings', 'action' => 'index')) ?>">
                                    <i class="fa fa-cogs"></i>
                                </a>
                            </li>
                        <?php endif; ?>
                        <li>
                            <a title="<?=$this->getTrans('openFrontend') ?>" target="_blank" href="<?=$this->getUrl() ?>">
                                <i class="fa fa-share"></i>
                            </a>
                        </li>
                        <li class="dropdown">
                            <a data-toggle="dropdown" class="dropdown-toggle" target="_blank" href="<?=$this->getUrl() ?>">
                                <i class="fa fa-question-circle"></i> <b class="caret"></b>
                            </a>
                            <ul role="menu" class="dropdown-menu">
                                <li>
                                    <a href="http://www.ilch.de" target="_blank">
                                        <i class="fa fa-home"></i>
                                        <?=$this->getTrans('officalSite') ?>
                                    </a>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <a href="http://www.ilch.de/forum.html" target="_blank">
                                        <i class="fa fa-comments-o"></i>
                                        <?=$this->getTrans('officalSupportForum') ?>
                                    </a>
                                </li>
                                <li>
                                    <a href="http://www.ilch.de/redmine/projects/dev2/wiki" target="_blank">
                                        <i class="fa fa-book"></i>
                                        <?=$this->getTrans('documentationFAQ') ?>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <?php if($this->getUser()->getFirstName() != ''): ?>
                            <?php $name = $this->getUser()->getFirstName().' '.$this->getUser()->getLastName(); ?>
                            <?php $nameInfo = $this->getUser()->getFirstName().'<br />'.$this->getUser()->getLastName(); ?>
                        <?php else: ?>
                            <?php $name = $this->getUser()->getName(); ?>
                        <?php endif; ?>
                        <li class="dropdown dropdown-user">
                            <a data-toggle="dropdown" class="dropdown-toggle" target="_blank" href="<?=$this->getUrl() ?>">
                                <i class="fa fa-user"></i> <?=$name ?>
                                <b class="caret"></b>
                            </a>
                            <ul role="menu" class="dropdown-menu">
                                <li class="text-center">
                                    <a href="<?=$this->getUrl(array('module' => 'admin', 'controller' => 'login', 'action' => 'logout')) ?>">
                                        <i class="fa fa-power-off"></i> <?=$this->getTrans('logout') ?>
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
        <?php $contentFullClass = 'content_full'; ?>
        <?php if ($this->hasSidebar()): ?>
            <?php $contentFullClass = ''; ?>
            <!-- LEFT PANEL -->
            <aside id="left-panel">
                <nav>
                    <ul>
                        <?php foreach ($this->getMenus() as $key => $items): ?>
                            <li class="heading"><i class="fa fa-puzzle-piece"></i> <?=$this->getTrans($key) ?></li>';
                            <?php foreach ($items as $key): ?>
                                <?php $class = ''; ?>
                                <?php if ($key['active']): ?>
                                    <?php $class = ' class="active"'; ?>
                                <?php endif; ?>

                                <li<?=$class ?>>
                                    <a href="<?=$key['url'] ?>"><i class="<?=$key['icon'] ?>"></i> <?=$this->getTrans($key['name']) ?></a>
                                </li>
                            <?php endforeach; ?>
                        <?php endforeach; ?>

                        <?php $actions = $this->getMenuAction(); ?>
                        <?php if (!empty($actions)): ?>
                            <li class="divider"></li>
                            <?php foreach ($actions as $action): ?>
                                <li>
                                    <a href="<?=$action['url'] ?>"><i class="<?=$action['icon'] ?>"></i> <?=$this->getTrans($action['name']) ?></a>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </nav>
                <img class="watermark" src="<?=$this->getStaticUrl('img/ilch_logo.png') ?>" />
            </aside>
            <!-- LEFT PANEL END -->
        <?php endif; ?>
        <!-- MAIN -->
        <div id="main" role="main" class="<?=$contentFullClass ?>">
            <div id="ribbon"><?=$this->getAdminHmenu() ?></div>
            <!-- CONTENT -->
            <div id="content">
                <div class="modal fade" id="deleteModal">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button"
                                        class="close"
                                        data-dismiss="modal"
                                        aria-hidden="true">&times;</button>
                                <h4 class="modal-title"><?=$this->getTrans('needAcknowledgement') ?></h4>
                            </div>
                            <div class="modal-body"><p id="modalText"></p></div>
                            <div class="modal-footer">
                                <button type="button"
                                        class="btn btn-primary"
                                        id="modalButton"><?=$this->getTrans('ack') ?></button>
                                <button type="button"
                                        class="btn btn-primary"
                                        data-dismiss="modal"><?=$this->getTrans('cancel') ?></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="MediaModal" tabindex="-1" role="dialog" aria-labelledby="MediaModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" 
                                        class="close" 
                                        data-dismiss="modal" 
                                        aria-hidden="true">&times;
                                </button>
                                <h4 class="modal-title" id="MediaModalLabel">Media</h4>
                            </div>
                            <div class="modal-body"><iframe frameborder="0"></iframe></div>
                            <div class="modal-footer">
                                <button type="button" 
                                        class="btn btn-default" 
                                        data-dismiss="modal">Close
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <?=$this->getContent() ?>
            </div>
            <!-- CONTENT END -->
        </div>
        <!-- MAIN END -->

        <script>
            var iframeUrlImageCkeditor = "<?=$this->getUrl('admin/media/iframe/indexckeditor/type/imageckeditor/') ?>";
            var iframeUrlVideoCkeditor = "<?=$this->getUrl('admin/media/iframe/indexckeditor/type/videockeditor/') ?>";
            var iframeUrlFileCkeditor = "<?=$this->getUrl('admin/media/iframe/indexckeditor/type/fileckeditor/') ?>";
            var iframeMediaUploadCkeditor = "<?=$this->getUrl('admin/media/iframe/uploadckeditor/') ?>";

            var ilchMediaPlugin = "<?=$this->getBaseUrl('application/modules/media/static/js/ilchmedia/') ?>";
        </script>
    </body>
</html>
