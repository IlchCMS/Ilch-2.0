<?php

/** @var \Ilch\View $this */

/** @var \Modules\Admin\Mappers\Module $moduleMapper */
$moduleMapper = $this->get('moduleMapper');

/** @var \Modules\Admin\Models\Module[] $modules */
$modules = $this->get('modules');

/** @var \Modules\Admin\Models\Module[] $configurations */
$configurations = $this->get('configurations');
/** @var array $dependencies */
$dependencies = $this->get('dependencies');
/** @var string $coreVersion */
$coreVersion = $this->get('coreVersion');

$modulesList = url_get_contents($this->get('updateserver'));
$modulesOnUpdateServer = json_decode($modulesList, true);
$cacheFilename = ROOT_PATH . '/cache/' . md5($this->get('updateserver')) . '.cache';
$cacheFileDate = null;
if (file_exists($cacheFilename)) {
    $cacheFileDate = new \Ilch\Date(date('Y-m-d H:i:s.', filemtime($cacheFilename)));
}

if ($modulesOnUpdateServer === null) {
    $modulesOnUpdateServer = [];
}

$content = [];
?>

<link href="<?=$this->getModuleUrl('static/css/extsearch.css') ?>" rel="stylesheet">

<h1><?=$this->getTrans('modulesInstalled') ?></h1>
<p><a href="<?=$this->getUrl(['action' => 'refreshurl', 'from' => 'index']) ?>" class="btn btn-primary"><?=$this->getTrans('searchForUpdates') ?></a> <?=(!empty($cacheFileDate)) ? '<span class="small">' . $this->getTrans('lastUpdateOn') . ' ' . $this->getTrans($cacheFileDate->format('l', true)) . $cacheFileDate->format(', d. ', true) . $this->getTrans($cacheFileDate->format('F', true)) . $cacheFileDate->format(' Y H:i', true) . '</span>' : $this->getTrans('lastUpdateOn') . ': ' . $this->getTrans('lastUpdateUnknown') ?></p>
<div class="checkbox">
  <label><input type="checkbox" name="setgotokey" onclick="gotokeyAll();" <?=$this->get('gotokey') ? 'checked' : '' ?>/><?=$this->getTrans('gotokey') ?></label>
</div>
<div id="modules" class="table-responsive">
    <table class="table table-hover table-striped">
        <colgroup>
            <col class="col-lg-2" />
            <col class="col-lg-1" />
            <col />
        </colgroup>
        <thead>
            <tr>
                <th><?=$this->getTrans('name') ?></th>
                <th><?=$this->getTrans('version') ?></th>
                <th><?=$this->getTrans('desc') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($modules as $module) :
                if (!$content[$module->getKey()]) {
                    $content[$module->getKey()] = $module->getContentForLocale($this->getTranslator()->getLocale());
                }

                $moduleUpdate = [];

                if (isset($configurations[$module->getKey()]) && $module->isNewVersion($configurations[$module->getKey()]->getVersion())) {
                    $moduleUpdate['local'] = $configurations[$module->getKey()];
                }
                $moduleModel = new \Modules\Admin\Models\Module();
                foreach ($modulesOnUpdateServer as $id => $moduleOnUpdateServer) {
                    if ($moduleOnUpdateServer['key'] == $module->getKey()) {
                        $moduleModel = new \Modules\Admin\Models\Module();
                        $moduleModel->setByArray($moduleOnUpdateServer);
                        $moduleUpdate['updateserver'] = $moduleModel;

                        unset($modulesOnUpdateServer[$id]);
                        break;
                    }
                }

                if ($this->getUser()->hasAccess('module_' . $module->getKey()) && !$module->getSystemModule()) : ?>
                    <tr id="Module_<?=$module->getKey() ?>">
                        <td>
                            <?=$content[$module->getKey()]['name'] ?>
                            <br />
                            <small>
                                <?=$this->getTrans('author') ?>:
                                <?php if ($module->getLink() != '') : ?>
                                    <a href="<?=$module->getLink() ?>" title="<?=$this->escape($module->getAuthor()) ?>" target="_blank" rel="noopener">
                                        <i><?=$this->escape($module->getAuthor()) ?></i>
                                    </a>
                                <?php else : ?>
                                    <i><?=$this->escape($module->getAuthor()) ?></i>
                                <?php endif; ?>
                            </small>
                            <br /><br />
                            <a href="<?=$this->getUrl(['module' => $module->getKey(), 'controller' => 'index', 'action' => 'index']) ?>" class="btn btn-default" title="<?=$this->getTrans('administrate') ?>">
                                <i class="fa-solid fa-pencil text-success"></i>
                            </a>
                            <?php if (!empty($moduleOnUpdateServer) && $module->getKey() == $moduleOnUpdateServer['key']) : ?>
                                <a href="<?=$this->getUrl(['action' => 'show', 'id' => $moduleOnUpdateServer['id']]) ?>" title="<?=$this->getTrans('info') ?>">
                                    <span class="btn btn-default">
                                        <i class="fa-solid fa-info text-info"></i>
                                    </span></a>
                            <?php else : ?>
                                <span class="btn btn-default"
                                      data-toggle="modal"
                                      data-target="#infoModal<?=$module->getKey() ?>"
                                      title="<?=$this->getTrans('info') ?>">
                                    <i class="fa-solid fa-info text-info"></i>
                                </span>
                            <?php endif; ?>
                            <?php
                            foreach ($moduleUpdate as $source => $moduleUpdateInformation) {
                                $icon = ($source === 'local') ? 'fa-solid fa-download' : 'fa-solid fa-cloud-arrow-down';
                                if (!$moduleUpdateInformation->hasPHPExtension()) : ?>
                                    <button class="btn disabled"
                                            title="<?=$this->getTrans('phpExtensionError') ?>">
                                        <i class="<?=$icon ?>"></i>
                                    </button>
                                <?php elseif (!$moduleUpdateInformation->hasPHPVersion()) : ?>
                                    <button class="btn disabled"
                                            title="<?=$this->getTrans('phpVersionError') ?>">
                                        <i class="<?=$icon ?>"></i>
                                    </button>
                                <?php elseif (!$moduleUpdateInformation->hasCoreVersion($this->get('coreVersion'))) : ?>
                                    <button class="btn disabled"
                                            title="<?=$this->getTrans('ilchCoreError') ?>">
                                        <i class="<?=$icon ?>"></i>
                                    </button>
                                <?php elseif (!$moduleMapper->checkOthersDependenciesVersion($module->getKey(), $dependencies)) : ?>
                                    <button class="btn disabled"
                                            data-toggle="modal"
                                            data-target="#dependencyInfoModal<?=$moduleUpdateInformation->getKey() ?>"
                                            title="<?=$this->getTrans('dependencyError') ?>">
                                        <i class="<?=$icon ?>"></i>
                                    </button>
                                <?php elseif (!$moduleUpdateInformation->checkOwnDependencies()) : ?>
                                    <button class="btn disabled"
                                            title="<?=$this->getTrans('dependencyError') ?>">
                                        <i class="<?=$icon ?>"></i>
                                    </button>
                                <?php elseif ($source === 'local' && !empty($moduleUpdateInformation)) : ?>
                                    <form method="POST" action="<?=$this->getUrl(['action' => 'localUpdate', 'key' => $moduleUpdateInformation->getKey(), 'from' => 'index']) ?>">
                                        <?=$this->getTokenField() ?>
                                        <input type="hidden" name="gotokey" value="<?=$this->get('gotokey') ? '1' : '0' ?>" />
                                        <button type="submit"
                                                class="btn btn-default showOverlay"
                                                title="<?=$this->getTrans('localModuleUpdate') ?>">
                                            <i class="<?=$icon ?>"></i>
                                        </button>
                                    </form>
                                <?php elseif ($source === 'updateserver' && $module->isNewVersion($moduleUpdateInformation->getVersion())) : ?>
                                    <form method="POST" action="<?=$this->getUrl(['action' => 'update', 'key' => $moduleUpdateInformation->getKey(), 'version' => $moduleUpdateInformation->getVersion(), 'from' => 'index']) ?>">
                                        <?=$this->getTokenField() ?>
                                        <input type="hidden" name="gotokey" value="<?=$this->get('gotokey') ? '1' : '0' ?>" />
                                        <button type="submit"
                                                class="btn btn-default showOverlay"
                                                title="<?=$this->getTrans('moduleUpdate') ?>">
                                            <i class="<?=$icon ?>"></i>
                                        </button>
                                    </form>
                                <?php endif; ?>
                                <?php
                            } ?>
                            <?php if (!$moduleMapper->checkOthersDependenciesVersion($module->getKey(), $dependencies)) : ?>
                                <button class="btn disabled"
                                        data-toggle="modal"
                                        data-target="#dependencyInfoModal<?=$module->getKey() ?>"
                                        title="<?=$this->getTrans('dependencyError') ?>">
                                    <i class="fa-regular fa-trash-can text-warning"></i>
                                </button>
                            <?php else : ?>
                                <a href="<?=$this->getUrl(['action' => 'uninstall', 'key' => $module->getKey()], null, true) ?>" class="btn btn-default" title="<?=$this->getTrans('uninstall') ?>">
                                    <i class="fa-regular fa-trash-can text-warning"></i>
                                </a>
                            <?php endif; ?>
                        </td>
                        <td><?=$module->getVersion() ?></td>
                        <td>
                            <?=$content[$module->getKey()]['description'] ?>
                            <?=$moduleModel->getOfficial() ? '<span class="ilch-official">ilch</span>' : '' ?>
                        </td>
                    </tr>

                    <?php
                    if ($module->getLink() != '') {
                        $author = '<a href="' . $module->getLink() . '" title="' . $this->escape($module->getAuthor()) . '" target="_blank" rel="noopener">' . $this->escape($module->getAuthor()) . '</a>';
                    } else {
                        $author = $this->escape($module->getAuthor());
                    }
                    $phpVersion = '';
                    if ($configurations[$module->getKey()]->getPHPVersion()) {
                        if ($configurations[$module->getKey()]->hasPHPVersion()) {
                            $phpVersion = '<font color="#3c763d">' . $configurations[$module->getKey()]->getPHPVersion() . '</font>';
                        } else {
                            $phpVersion = '<font color="#a94442">' . $configurations[$module->getKey()]->getPHPVersion() . '</font>';
                        }
                    }
                    $ilchCore = '';
                    if ($configurations[$module->getKey()]->getIlchCore()) {
                        if ($configurations[$module->getKey()]->hasCoreVersion($this->get('coreVersion'))) {
                            $ilchCore = '<font color="#3c763d">' . $configurations[$module->getKey()]->getIlchCore() . '</font>';
                        } else {
                            $ilchCore = '<font color="#a94442">' . $configurations[$module->getKey()]->getIlchCore() . '</font>';
                        }
                    }

                    $moduleInfo = '<b>' . $this->getTrans('name') . ':</b> ' . $content[$module->getKey()]['name'] . '<br />
                                   <b>' . $this->getTrans('version') . ':</b> ' . $this->escape($module->getVersion()) . '<br />
                                   <b>' . $this->getTrans('author') . ':</b> ' . $author . '<br />
                                   <b>' . $this->getTrans('ilchCoreVersion') . ':</b> ' . $ilchCore . '<br />
                                   <b>' . $this->getTrans('phpVersion') . ':</b> ' . $phpVersion . '<br />';

                    if (count($configurations[$module->getKey()]->getPHPExtension())) {
                        $phpExtension = [];
                        foreach ($configurations[$module->getKey()]->getPHPExtension() as $extension => $state) {
                            if ($state) {
                                $phpExtension[] = '<font color="#3c763d">' . $extension . '</font>';
                            } else {
                                $phpExtension[] = '<font color="#a94442">' . $extension . '</font>';
                            }
                        }
                        $phpExtension = implode(', ', $phpExtension);

                        $moduleInfo .= '<b>' . $this->getTrans('phpExtensions') . ':</b> ' . $phpExtension . '<br />';
                    }
                    if (count($configurations[$module->getKey()]->getDepends())) {
                        $moduleInfo .= '<b>' . $this->getTrans('dependencies') . ':</b><br />';

                        foreach ($configurations[$module->getKey()]->getDepends() as $key => $value) {
                            if ($configurations[$module->getKey()]->dependsCheck[$key]) {
                                $moduleInfo .= '<font color="#3c763d">' . $key . ' ' . str_replace(',', '', $value) . '</font><br />';
                            } else {
                                $moduleInfo .= '<font color="#a94442">' . $key . ' ' . str_replace(',', '', $value) . '</font><br />';
                            }
                        }
                    }

                    $moduleInfo .= '<br /><b>' . $this->getTrans('desc') . ':</b><br />' . $content[$module->getKey()]['description'];

                    $dependencyInfo = '<p>' . $this->getTrans('dependencyInfo') . '</p>';
                    foreach ($moduleMapper->checkOthersDependencies($module->getKey(), $dependencies) as $dependenciesKey => $dependenciesVersion) {
                        if (!$content[$dependenciesKey]) {
                            $content[$dependenciesKey] = $configurations[$dependenciesKey]->getContentForLocale($this->getTranslator()->getLocale());
                        }

                        $dependencyInfo .= '<b>' . $content[$dependenciesKey]['name'] . ': </b> ' . str_replace(',', '', $dependenciesVersion) . '<br />';
                    }

                    echo $this->getDialog('dependencyInfoModal' . $module->getKey(), $this->getTrans('dependencies') . ' ' . $this->getTrans('info'), $dependencyInfo);
                    ?>
                    <?=$this->getDialog('infoModal' . $module->getKey(), $this->getTrans('menuModules') . ' ' . $this->getTrans('info'), $moduleInfo) ?>
                <?php endif; ?>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<script src="<?=$this->getModuleUrl('static/js/jquery-loading-overlay/loadingoverlay.min.js') ?>"></script>
<script>
    function gotokeyAll() {
       $("[name='gotokey']").each(function() {
            if ($("[name='setgotokey']").prop('checked')) {
                $(this).prop('value',"1");
            } else {
                $(this).prop('value',"0");
            }
       });
    }
    $(document).ready(function() {
        $(".showOverlay").on('click', function(){
            $.LoadingOverlay("show");
            setTimeout(function(){
                $.LoadingOverlay("hide");
            }, 30000);
        });
    });
</script>
