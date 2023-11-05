<?php
/** @var \Ilch\Layout\Admin $this */

/** @var \Ilch\Config\Database $config */
$config = \Ilch\Registry::get('config');

/** @var \Ilch\Accesses $accesses */
$accesses = $this->get('accesses');
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <title><?=$config->get('page_title') ?> - Ilch - <?=$this->getTrans('admincenter') ?></title>

    <!-- META -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="description" content="Ilch - <?=$this->getTrans('admincenter') ?>">

    <!-- FAVICON -->
    <link rel="shortcut icon" type="image/x-icon" href="<?=$this->getStaticUrl('img/favicon.ico') ?>">

    <!-- STYLES -->
    <link href="<?=$this->getVendorUrl('twbs/bootstrap/dist/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?=$this->getVendorUrl('fortawesome/font-awesome/css/all.min.css') ?>" rel="stylesheet">
    <link href="<?=$this->getVendorUrl('fortawesome/font-awesome/css/v4-shims.min.css') ?>" rel="stylesheet">
    <link href="<?=$this->getStaticUrl('css/ilch.css') ?>" rel="stylesheet">
    <link href="<?=$this->getStaticUrl('../application/modules/admin/static/css/main.css') ?>" rel="stylesheet">
    <link href="<?=$this->getVendorUrl('npm-asset/jquery-ui/dist/themes/ui-lightness/jquery-ui.min.css') ?>" rel="stylesheet">
    <link href="<?=$this->getStaticUrl('css/chosen/bootstrap-chosen.css') ?>" rel="stylesheet">
    <link href="<?=$this->getVendorUrl('harvesthq/chosen/chosen.min.css') ?>" rel="stylesheet">
    <link href="<?=$this->getStaticUrl('js/tokenfield/css/bootstrap-tokenfield.min.css') ?>" rel="stylesheet">
    <link href="<?=$this->getStaticUrl('../application/modules/admin/static/css/admin.css') ?>" rel="stylesheet">

    <script>
        // Set a bunch of variables to later display translated messages. Used in ../application/modules/admin/static/js/functions.js
        var enableSelectedEntries = <?=json_encode($this->getTrans('enableSelectedEntries')) ?>;
        var deleteSelectedEntries = <?=json_encode($this->getTrans('deleteSelectedEntries')) ?>;
        var deleteEntry = <?=json_encode($this->getTrans('deleteEntry')) ?>;
    </script>

    <!-- SCRIPTS -->
    <script src="<?=$this->getVendorUrl('npm-asset/jquery/dist/jquery.min.js') ?>"></script>
    <script src="<?=$this->getVendorUrl('npm-asset/jquery-ui/dist/jquery-ui.min.js') ?>"></script>
    <script src="<?=$this->getStaticUrl('js/jquery.mjs.nestedSortable.js') ?>"></script>
    <script src="<?=$this->getVendorUrl('twbs/bootstrap/dist/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?=$this->getStaticUrl('../application/modules/admin/static/js/functions.js') ?>"></script>
    <script src="<?=$this->getVendorUrl('harvesthq/chosen/chosen.jquery.min.js') ?>"></script>
    <script src="<?=$this->getStaticUrl('js/tokenfield/bootstrap-tokenfield.min.js') ?>"></script>
    <script src="<?=$this->getStaticUrl('js/validate/jquery.validate.min.js') ?>"></script>
    <script src="<?=$this->getStaticUrl('js/validate/additional-methods.min.js') ?>"></script>
    <script src="<?=$this->getStaticUrl('js/validate/ilch-validate.js') ?>"></script>
    <script src="<?=$this->getVendorUrl('ckeditor/ckeditor/ckeditor.js') ?>"></script>
    <script src="<?=$this->getStaticUrl('js/ilch.js') ?>"></script>
    <script src="<?=$this->getStaticUrl('js/jquery.key.js') ?>"></script>
    <script>
        $.key('alt+a', function() { window.location.href ='<?=$this->getUrl(['module' => 'article', 'controller' => 'index', 'action' => 'index']) ?>'; });
        $.key('alt+u', function() { window.location.href ='<?=$this->getUrl(['module' => 'user', 'controller' => 'index', 'action' => 'index']) ?>'; });
        $.key('alt+s', function() { window.location.href ='<?=$this->getUrl(['module' => 'admin', 'controller' => 'settings', 'action' => 'index']) ?>'; });
        $.key('alt+h', function() { window.location.href ='<?=$this->getUrl(['module' => 'admin', 'controller' => 'infos', 'action' => 'index']) ?>'; });
        $.key('alt+k', function() { window.location.href ='<?=$this->getUrl(['module' => 'admin', 'controller' => 'infos', 'action' => 'shortcuts']) ?>'; });
        $.key('alt+i', function() { window.open('https://ilch.de/', '_blank'); });
    </script>
    <?php
    if (\Ilch\DebugBar::isInitialized()) {
        echo \Ilch\DebugBar::getInstance()->getJavascriptRenderer()->renderHead();
    }
    ?>
</head>
<body id="body" class="">
<script>
    /*
     * Custom validate messages.
     */
    jQuery.extend(jQuery.validator.messages, {
        required: <?=json_encode($this->getTrans('validateRequired')) ?>,
        email: <?=json_encode($this->getTrans('validateEmail')) ?>
    });
</script>

<!-- HEADER -->
<header id="header">
    <!-- TOP NAVBAR -->
    <nav class="navbar navbar-expand-lg topnavbar navbar-light bg-light" style="padding:0px;">
        <!-- TOP NAVBAR LEFT -->
        <div class="navbar-header leftbar">
            <?php if ($this->hasSidebar()) : ?>
                <div id="hide-menu" class="btn-header float-start">
                    <a id="toggleLeftMenu" title="Collapse Menu" data-toggle="collapse" data-target="#left-panel">
                        <i class="fa-solid fa-outdent"></i>
                    </a>
                </div>
            <?php endif; ?>
            <img title="Version <?=$config->get('version') ?>" class="pull-left logo hidden-sm" src="<?=$this->getStaticUrl('img/ilch_logo_2.png') ?>"  alt="Version <?=$config->get('version') ?>"/>
            <div class=" d-sm-block d-md-block d-lg-none">
                <a class="float-start <?=($this->getRequest()->getModuleName() === 'admin' && $this->getRequest()->getControllerName() === 'index') ? 'active' : ''?> home" href="<?=$this->getUrl(['module' => 'admin', 'controller' => 'index', 'action' => 'index']) ?>">
                    <i class="fa-solid fa-house"></i>
                </a>
                <button class="float-end" type="button" class="pull-right navbar-toggle" data-bs-toggle="collapse" data-bs-target="#rightbar">
                    <i class="fa-solid fa-table-cells"></i>
                </button>
            </div>
        </div>
        <!-- TOP NAVBAR LEFT END -->
        <!-- TOP NAVBAR RIGHT -->
        <nav id="rightbar" class="rightbar navbar-collapse collapse">
            <ul class="nav navbar-nav me-auto">
                <li class="<?=($this->getRequest()->getModuleName() === 'admin' && $this->getRequest()->getControllerName() === 'index') ? 'active' : '' ?> d-none d-lg-block">
                    <a href="<?=$this->getUrl(['module' => 'admin', 'controller' => 'index', 'action' => 'index']) ?>">
                        <i class="fa-solid fa-house"></i>
                    </a>
                </li>
                <?php if ($this->getUser()->isAdmin()) : ?>
                    <li <?=($this->getRequest()->getModuleName() === 'admin' && $this->getRequest()->getControllerName() === 'menu') ? 'class="active"' : '' ?>>
                        <a href="<?=$this->getUrl(['module' => 'admin', 'controller' => 'menu', 'action' => 'index']) ?>">
                            <i class="fa-solid fa-list-ol hidden-sm hidden-md"></i> <?=$this->getTrans('navigation') ?>
                        </a>
                    </li>
                <?php endif; ?>
                <?php
                /** @var \Modules\User\Models\User $user */
                $user = \Ilch\Registry::get('user');
                $modulesHtml = $systemModuleHtml = $layoutModuleHtml = '';

                /** @var \Modules\Admin\Models\Module $module */
                foreach ($this->get('modules') as $module) {
                    if ($user->hasAccess('module_' . $module->getKey()) || ($module->getKey() == 'article' && ($accesses && $accesses->hasAccess('Admin', null, $accesses::TYPE_ARTICLE)))) {
                        $content = $module->getContentForLocale($this->getTranslator()->getLocale());
                        if (strncmp($module->getIconSmall(), 'fa-', 3) === 0) {
                            $smallIcon = '<i class="fa ' . $module->getIconSmall() . '" style="padding-right: 5px;"></i>';
                        } else {
                            $smallIcon = '<img style="padding-right: 5px;" src="' . $this->getStaticUrl('../application/modules/' . $module->getKey() . '/config/' . $module->getIconSmall()) . '" />';
                        }

                        if ($module->getSystemModule()) {
                            $systemModuleHtml .= '<a class="list-group-item" href="' . $this->getUrl(['module' => $module->getKey(), 'controller' => 'index', 'action' => 'index']) . '">
                                        ' . $smallIcon . $content['name'] . '
                                        </a>';
                        } elseif ($module->getLayoutModule()) {
                            $layoutModuleHtml .= '<a class="list-group-item" href="' . $this->getUrl(['module' => $module->getKey(), 'controller' => 'index', 'action' => 'index']) . '">
                                        ' . $smallIcon . $content['name'] . '
                                        </a>';
                        } else {
                            $modulesHtml .= '<a class="list-group-item" href="' . $this->getUrl(['module' => $module->getKey(), 'controller' => 'index', 'action' => 'index']) . '">
                                        ' . $smallIcon . $content['name'] . '
                                        </a>';
                        }
                    }
                }
                ?>
                <?php if (!empty($modulesHtml) || !empty($systemModuleHtml) || !empty($layoutModuleHtml)) : ?>
                    <li id="ilch_dropdown" class="dropdown <?=($this->getRequest()->getModuleName() !== 'admin') ? 'active' : '' ?>">
                         <a class="list-group-item nav-link dropdown-toggle" data-bs-toggle="collapse" data-bs-target="#navbar" href="#">
                            <i class="fa-solid fa-puzzle-piece hidden-sm hidden-md"></i> <?=$this->getTrans('modules') ?>
                        </a>
                        <ul  class="dropdown-menu full" id="navbar">
                            <?php if ($this->getUser()->isAdmin()) : ?>
                                <a href="<?=$this->getUrl(['module' => 'admin', 'controller' => 'modules', 'action' => 'index']) ?>">
                                    <i class="fa-solid fa-list-ol"></i> <?=$this->getTrans('overview') ?>
                                </a>
                                <li><hr class="dropdown-divider"></li>
                            <?php endif; ?>
                            <li class="list-group-item ilch--flex-wrap">
                                <div class="list-group list-group-horizontal">
                                    <?=$systemModuleHtml ?>
                                </div>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li class="list-group-item ilch--flex-wrap">
                                <div class="list-group list-group-horizontal">
                                    <?=$modulesHtml ?>
                                </div>
                            </li>
                                <?php if (!empty($layoutModuleHtml)) : ?>
                                    <li><hr class="dropdown-divider"></li>
                                    <div class="list-group list-group-horizontal">
                                        <?=$layoutModuleHtml ?>
                                    </div>
                                <?php endif; ?>
                            </li>
                        </ul>
                    </li>
                <?php endif; ?>
                <?php if ($accesses && $accesses->hasAccess('Admin', null, $accesses::TYPE_PAGE)) : ?>
                    <li <?=($this->getRequest()->getModuleName() === 'admin' && $this->getRequest()->getControllerName() === 'page') ? 'class="active"' : '' ?>>
                        <a href="<?=$this->getUrl(['module' => 'admin', 'controller' => 'page', 'action' => 'index']) ?>">
                            <i class="fa-regular fa-file-lines hidden-sm hidden-md"></i> <?=$this->getTrans('menuSites') ?>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if ($accesses && $accesses->hasAccess('Admin', null, $accesses::TYPE_BOX)) : ?>
                    <li <?=($this->getRequest()->getModuleName() === 'admin' && $this->getRequest()->getControllerName() === 'boxes') ? 'class="active"' : '' ?>>
                        <a href="<?=$this->getUrl(['module' => 'admin', 'controller' => 'boxes', 'action' => 'index']) ?>">
                            <i class="fa-solid fa-inbox hidden-sm hidden-md"></i> <?=$this->getTrans('menuBoxes') ?>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if ($user->isAdmin()) : ?>
                    <li <?=($this->getRequest()->getModuleName() === 'admin' && $this->getRequest()->getControllerName() === 'layouts') ? 'class="active"' : '' ?>>
                        <a href="<?=$this->getUrl(['module' => 'admin', 'controller' => 'layouts', 'action' => 'index']) ?>">
                            <i class="fa-regular fa-image hidden-sm hidden-md"></i> <?=$this->getTrans('menuLayouts') ?>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
            <ul class="nav navbar-nav navbar-right d-flex">
                <?php if ($this->getUser()->isAdmin()) : ?>
                    <li class="<?=($this->getRequest()->getModuleName() === 'admin' && ($this->getRequest()->getControllerName() === 'settings' || $this->getRequest()->getControllerName() === 'backup' || $this->getRequest()->getControllerName() === 'emails')) ? 'active' : '' ?>">
                        <a href="<?=$this->getUrl(['module' => 'admin', 'controller' => 'settings', 'action' => 'index']) ?>">
                            <i class="fa-solid fa-gears"></i> <span class="d-lg-none"><?=$this->getTrans('menuSettings') ?></span>
                        </a>
                    </li>
                <?php endif; ?>
                <li>
                    <a title="<?=$this->getTrans('openFrontend') ?>" target="_blank" href="<?=$this->getUrl() ?>">
                        <i class="fa-solid fa-share"></i> <span class="d-lg-none"><?=$this->getTrans('menuFrontend') ?></span>
                    </a>
                </li>
                <li class="dropdown <?=($this->getRequest()->getModuleName() === 'admin' &&  $this->getRequest()->getControllerName() === 'infos') ? 'active' : '' ?>">
                    <a data-toggle="dropdown" class="dropdown-toggle" data-bs-toggle="collapse" data-bs-target="#menu" href="#" target="_blank" href="<?=$this->getUrl() ?>">
                        <i class="fa-solid fa-circle-question"></i> <span class="d-lg-none"><?=$this->getTrans('menuInfos') ?></span> <b class="caret"></b>
                    </a>
                    <ul id="menu" class="dropdown-menu" style="right:0;">
                        <li>
                            <a href="https://www.ilch.de" target="_blank" rel="noopener">
                                <i class="fa-solid fa-house"></i>
                                <?=$this->getTrans('officialSite') ?>
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a href="https://www.ilch.de/forum.html" target="_blank" rel="noopener">
                                <i class="fa-regular fa-comments"></i>
                                <?=$this->getTrans('officialSupportForum') ?>
                            </a>
                        </li>
                        <li>
                            <a href="https://github.com/IlchCMS/Ilch-2.0/wiki" target="_blank" rel="noopener">
                                <i class="fa-solid fa-book"></i>
                                <?=$this->getTrans('documentationFAQ') ?>
                            </a>
                        </li>
                        <?php if ($this->getUser()->isAdmin()) : ?>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a href="<?=$this->getUrl(['module' => 'admin', 'controller' => 'infos', 'action' => 'index']) ?>">
                                    <i class="fa-solid fa-circle-info"></i>
                                    <?=$this->getTrans('menuInfos') ?>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </li>
                <?php if (!empty($this->getUser()->getFirstName())) : ?>
                    <?php $name = $this->getUser()->getFirstName() . ' ' . $this->getUser()->getLastName(); ?>
                <?php else : ?>
                    <?php $name = $this->getUser()->getName(); ?>
                <?php endif; ?>
                <li class="dropdown dropdown-user">
                    <a data-toggle="dropdown" class="dropdown-toggle" data-bs-toggle="collapse" data-bs-target="#dropdown-user"  href="#" target="_blank" href="<?=$this->getUrl() ?>">
                        <i class="fa-solid fa-user hidden-sm hidden-md"></i> <?=$this->escape($name) ?>
                        <b class="caret"></b>
                    </a>
                    <ul id="dropdown-user" class="dropdown-menu" style="right:0;">
                        <li class="logout">
                            <a href="<?=$this->getUrl(['module' => 'admin', 'controller' => 'login', 'action' => 'logout']) ?>">
                                <i class="fa-solid fa-power-off"></i> <?=$this->getTrans('logout') ?>
                            </a>
                        </li>
                    </ul>
                </li>
                <!-- Search Block -->
                <li>
                    <i id="search-header" class="fa-solid fa-magnifying-glass search-btn"><span class="search-text d-lg-none"><?=$this->getTrans('search') ?></span></i>
                    <div id="search-div" class="search-close">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="<?=$this->getTrans('search') ?>">
                            <span class="input-group-btn">
                                        <button type="button" class="btn btn-default"><?=$this->getTrans('go') ?></button>
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
<?php if ($this->hasSidebar()) : ?>
    <?php $contentFullClass = ''; ?>
    <!-- LEFT PANEL -->
    <aside id="left-panel" class="navbar-collapse collapse">
        <nav>
            <ul>
                <?php
                foreach ($this->getMenus() as $vals => $items) {
                    echo '<li class="heading">
                                <i class="fa-solid fa-puzzle-piece"></i> ' . $this->getTrans($vals) . '
                            </li>';
                    foreach ($items as $key => $value) {
                        $class = '';
                        if ($value['active']) {
                            $class = ' class="active"';
                        }
                        echo '<li' . $class . '>';
                        echo '<a href'
                            . '="' . $value['url'] . '"><i class="' . $value['icon'] . '"></i> ' . $this->getTrans($value['name']) . '</a>';
                        echo '<ul>';
                        foreach ($value as $keys => $values) {
                            if (is_array($values)) {
                                $class = '';
                                if ($values['active']) {
                                    $class = ' class="active"';
                                }
                                echo '<li' . $class . '>';
                                echo '<a href="' . $values['url'] . '"><i class="' . $values['icon'] . '"></i> &nbsp;' . $this->getTrans($values['name']) . '</a>';
                                echo '</li>';
                            }
                        }
                        echo '</li>';
                        echo '</ul>';
                    }
                }
                ?>
            </ul>
        </nav>
        <div class="watermark"></div>
    </aside>
    <!-- LEFT PANEL END -->
<?php endif; ?>
<!-- MAIN -->
<div id="main" role="main" class="<?=$contentFullClass ?><?=(!empty($config->get('admin_layout_hmenu'))) ? ' ribbon-fixed' : '' ?>">
    <div id="ribbon"><?=$this->getAdminHmenu() ?></div>
    <!-- CONTENT -->
    <div id="content">
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
</script>
<?=(\Ilch\DebugBar::isInitialized()) ? \Ilch\DebugBar::getInstance()->getJavascriptRenderer()->render() : ''?>
</body>
</html>
