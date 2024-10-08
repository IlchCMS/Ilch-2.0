<?php

/** @var \Modules\Admin\Models\Module[] $modules */
$modules = $this->get('modules');

$modulesList = url_get_contents($this->get('updateserver'));
$modulesOnUpdateServer = json_decode($modulesList);
$versionsOfModules = $this->get('versionsOfModules');
$coreVersion = $this->get('coreVersion');
$dependencies = $this->get('dependencies');
$configurations = $this->get('configurations');
$cacheFilename = ROOT_PATH . '/cache/' . md5($this->get('updateserver')) . '.cache';
$cacheFileDate = null;
if (file_exists($cacheFilename)) {
    $cacheFileDate = new \Ilch\Date(date('Y-m-d H:i:s.', filemtime($cacheFilename)));
}

if ($modulesOnUpdateServer === null) {
    $modulesOnUpdateServer = [];
}

function checkOthersDependencies($module, $dependencies) {
    $dependencyCheck = [];
    foreach ($dependencies as $dependency) {
        $key = key($module);
        if (array_key_exists($key, $dependency)) {
            $parsed = explode(',', $dependency[$key]);
            if (!version_compare($module[$key], $parsed[1], $parsed[0])) {
                $dependencyCheck[array_search(array($key => $dependency[$key]), $dependencies)] = [$key => str_replace(',','', $dependency[$key])];
            }
        }
    }

    return $dependencyCheck;
}

function checkOwnDependencies($versionsOfModules, $moduleOnUpdateServer) {
    if (empty($moduleOnUpdateServer->depends)) {
        return true;
    }

    foreach ($moduleOnUpdateServer->depends as $key => $value) {
        if (array_key_exists($key, $versionsOfModules)) {
            $parsed = explode(',', $value);
            if (!version_compare($versionsOfModules[$key]['version'], $parsed[1], $parsed[0])) {
                return false;
            }
        }
    }

    return true;
}
?>

<link href="<?=$this->getModuleUrl('static/css/extsearch.css') ?>" rel="stylesheet">

<h1><?=$this->getTrans('modulesInstalled') ?></h1>
<p><a href="<?=$this->getUrl(['action' => 'refreshurl', 'from' => 'index']) ?>" class="btn btn-primary"><?=$this->getTrans('searchForUpdates') ?></a> <span class="small"><?=(!empty($cacheFileDate)) ? $this->getTrans('lastUpdateOn') . ' ' . $this->getTrans($cacheFileDate->format('l', true)) . $cacheFileDate->format(', d. ', true) . $this->getTrans($cacheFileDate->format('F', true)) . $cacheFileDate->format(' Y H:i', true) : $this->getTrans('lastUpdateOn') . ': ' . $this->getTrans('lastUpdateUnknown') ?></span></p>
<div class="checkbox">
  <label><input class="me-2" type="checkbox" name="setgotokey" onclick="gotokeyAll();" <?=$this->get('gotokey')? 'checked' : '' ?>/><?=$this->getTrans('gotokey') ?></label>
</div>
<div id="modules" class="table-responsive">
    <table class="table table-hover table-striped">
        <colgroup>
            <col class="col-xl-2" />
            <col class="col-xl-1" />
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
            <?php foreach ($modules as $module):
                $content = $module->getContentForLocale($this->getTranslator()->getLocale());
                $moduleUpdate = [];

                if (!empty($configurations[$module->getKey()]['version']) && version_compare($module->getVersion(), $configurations[$module->getKey()]['version'], '<')) {
                    $moduleUpdate['local'] = json_decode(json_encode($configurations[$module->getKey()]));
                }

                foreach ($modulesOnUpdateServer as $moduleOnUpdateServer) {
                    if ($moduleOnUpdateServer->key == $module->getKey()) {
                        $moduleUpdate['updateserver'] = $moduleOnUpdateServer;
                        break;
                    }
                }

                if ($this->getUser()->hasAccess('module_' . $module->getKey()) && !$module->getSystemModule()): ?>
                    <tr id="Module_<?=$module->getKey() ?>">
                        <td>
                            <?=$content['name'] ?>
                            <br />
                            <small>
                                <?=$this->getTrans('author') ?>:
                                <?php if ($module->getLink() != ''): ?>
                                    <a href="<?=$module->getLink() ?>" title="<?=$this->escape($module->getAuthor()) ?>" target="_blank" rel="noopener">
                                        <i><?=$this->escape($module->getAuthor()) ?></i>
                                    </a>
                                <?php else: ?>
                                    <i><?=$this->escape($module->getAuthor()) ?></i>
                                <?php endif; ?>
                            </small>
                            <br /><br />
                            <a href="<?=$this->getUrl(['module' => $module->getKey(), 'controller' => 'index', 'action' => 'index']) ?>" class="btn btn-outline-secondary" title="<?=$this->getTrans('administrate') ?>">
                                <i class="fa-solid fa-pencil text-success"></i>
                            </a>
                            <?php if (!empty($moduleOnUpdateServer) && $module->getKey() == $moduleOnUpdateServer->key): ?>
                                <a href="<?=$this->getUrl(['action' => 'show', 'id' => $moduleOnUpdateServer->id]) ?>" title="<?=$this->getTrans('info') ?>">
                                    <span class="btn btn-outline-secondary">
                                        <i class="fa-solid fa-info text-info"></i>
                                    </span></a>
                            <?php else: ?>
                                <span class="btn btn-outline-secondary"
                                      data-bs-toggle="modal"
                                      data-bs-target="#infoModal<?=$module->getKey() ?>"
                                      title="<?=$this->getTrans('info') ?>">
                                    <i class="fa-solid fa-info text-info"></i>
                                </span>
                            <?php endif; ?>
                            <?php
                            foreach($moduleUpdate as $source => $moduleUpdateInformation) {
                                if (!empty($moduleUpdateInformation->phpExtensions)) {
                                    $extensionCheck = [];
                                    foreach ($moduleUpdateInformation->phpExtensions as $extension) {
                                        $extensionCheck[] = extension_loaded($extension);
                                    }
                                }

                                $icon = ($source === 'local') ? 'fa-solid fa-download': 'fa-solid fa-cloud-arrow-down';
                                if (!empty($moduleUpdateInformation->phpExtensions) && in_array(false, $extensionCheck)): ?>
                                    <button class="btn disabled"
                                            title="<?=$this->getTrans('phpExtensionError') ?>">
                                        <i class="<?=$icon ?>"></i>
                                    </button>
                                <?php elseif (version_compare(PHP_VERSION, $moduleUpdateInformation->phpVersion, '<')): ?>
                                    <button class="btn disabled"
                                            title="<?=$this->getTrans('phpVersionError') ?>">
                                        <i class="<?=$icon ?>"></i>
                                    </button>
                                <?php elseif (version_compare($coreVersion, $moduleUpdateInformation->ilchCore, '<')): ?>
                                    <button class="btn disabled"
                                            title="<?=$this->getTrans('ilchCoreError') ?>">
                                        <i class="<?=$icon ?>"></i>
                                    </button>
                                <?php elseif (version_compare($versionsOfModules[$moduleUpdateInformation->key]['version'], $moduleUpdateInformation->version, '<') && version_compare('2.2.0', $moduleUpdateInformation->ilchCore, '>')): ?>
                                    <button class="btn disabled"
                                            title="<?=$this->getTrans('moduleTooOld') ?>">
                                        <i class="<?=$icon ?>"></i>
                                    </button>
                                <?php elseif (!empty(checkOthersDependencies([$moduleUpdateInformation->key => $moduleUpdateInformation->version], $dependencies))): ?>
                                    <button class="btn disabled"
                                            data-bs-toggle="modal"
                                            data-bs-target="#dependencyInfoModal<?=$moduleUpdateInformation->key ?>"
                                            title="<?=$this->getTrans('dependencyError') ?>">
                                        <i class="<?=$icon ?>"></i>
                                    </button>
                                <?php elseif (!checkOwnDependencies($versionsOfModules, $moduleUpdateInformation)): ?>
                                    <button class="btn disabled"
                                            title="<?=$this->getTrans('dependencyError') ?>">
                                        <i class="<?=$icon ?>"></i>
                                    </button>
                                <?php elseif ($source === 'local' && !empty($moduleUpdateInformation)): ?>
                                    <form method="POST" action="<?=$this->getUrl(['action' => 'localUpdate', 'key' => $moduleUpdateInformation->key, 'from' => 'index']) ?>">
                                        <?=$this->getTokenField() ?>
                                        <input type="hidden" name="gotokey" value="<?=$this->get('gotokey')? '1' : '0' ?>" />
                                        <button type="submit"
                                                class="btn btn-outline-secondary showOverlay"
                                                title="<?=$this->getTrans('localModuleUpdate') ?>">
                                            <i class="<?=$icon ?>"></i>
                                        </button>
                                    </form>
                                <?php elseif ($source === 'updateserver' && version_compare($versionsOfModules[$moduleUpdateInformation->key]['version'], $moduleUpdateInformation->version, '<')): ?>
                                    <form method="POST" action="<?=$this->getUrl(['action' => 'update', 'key' => $moduleUpdateInformation->key, 'version' => $moduleUpdateInformation->version, 'from' => 'index']) ?>">
                                        <?=$this->getTokenField() ?>
                                        <input type="hidden" name="gotokey" value="<?=$this->get('gotokey')? '1' : '0' ?>" />
                                        <button type="submit"
                                                class="btn btn-outline-secondary showOverlay"
                                                title="<?=$this->getTrans('moduleUpdate') ?>">
                                            <i class="<?=$icon ?>"></i>
                                        </button>
                                    </form>
                                <?php endif; ?>
                            <?php } ?>
                            <?php if (!empty(checkOthersDependencies([$module->getKey() => $module->getVersion()], $dependencies))): ?>
                                <button class="btn disabled"
                                        data-bs-toggle="modal"
                                        data-bs-target="#dependencyInfoModal<?=$module->getKey() ?>"
                                        title="<?=$this->getTrans('dependencyError') ?>">
                                    <i class="fa-regular fa-trash-can text-warning"></i>
                                </button>
                            <?php else: ?>
                                <a href="<?=$this->getUrl(['action' => 'uninstall', 'key' => $module->getKey()], null, true) ?>" class="btn btn-outline-secondary" title="<?=$this->getTrans('uninstall') ?>">
                                    <i class="fa-regular fa-trash-can text-warning"></i>
                                </a>
                            <?php endif; ?>
                        </td>
                        <td><?=$module->getVersion() ?></td>
                        <td>
                            <?=$content['description'] ?>
                            <?=(!empty($moduleOnUpdateServer->official) && $moduleOnUpdateServer->official == true) ? '<span class="ilch-official">ilch</span>' : '' ?>
                        </td>
                    </tr>

                    <?php
                    if ($module->getLink() != '') {
                        $author = '<a href="' . $module->getLink() . '" title="' . $this->escape($module->getAuthor()) . '" target="_blank" rel="noopener">' . $this->escape($module->getAuthor()) . '</a>';
                    } else {
                        $author = $this->escape($module->getAuthor());
                    }
                    $ilchCoreVersion = (!empty($configurations[$module->getKey()]['ilchCore']) ? $configurations[$module->getKey()]['ilchCore'] : '');
                    $phpVersion = (!empty($configurations[$module->getKey()]['phpVersion']) ? $configurations[$module->getKey()]['phpVersion'] : '');
                    $moduleInfo = '<b>' . $this->getTrans('name') . ':</b> ' . $content['name'] . '<br />
                                   <b>' . $this->getTrans('version') . ':</b> ' . $this->escape($module->getVersion()) . '<br />
                                   <b>' . $this->getTrans('author') . ':</b> ' . $author . '<br />
                                   <b>' . $this->getTrans('ilchCoreVersion') . ':</b> ' . $ilchCoreVersion . '<br />
                                   <b>' . $this->getTrans('phpVersion') . ':</b> ' . $phpVersion . '<br />
                                   <b>' . $this->getTrans('dependencies') . ':</b><br />';
                    $dependenciesForInfo = (!empty($configurations[$module->getKey()]['depends']) ? $configurations[$module->getKey()]['depends'] : []);
                    foreach ($dependenciesForInfo as $key => $value) {
                        $moduleInfo .= $key . ' '. str_replace(',','', $value) . '<br />';
                    }

                    $moduleInfo .= '<br /><b>' . $this->getTrans('desc') . ':</b><br />' . $content['description'];

                    $dependencyInfo = '<p>' . $this->getTrans('dependencyInfo') . '</p>';
                    foreach (checkOthersDependencies([$module->getKey() => $module->getVersion()], $dependencies) as $key => $value) {
                        $dependencyInfo .= '<b>' . $key . ':</b> '.key($value) . $value[key($value)] . '<br />';
                    }

                    echo $this->getDialog('dependencyInfoModal' . $module->getKey(), $this->getTrans('dependencies') . ' ' . $this->getTrans('info'), $dependencyInfo);
                    ?>
                    <?=$this->getDialog('infoModal' . $module->getKey(), $this->getTrans('menuModules') . ' ' . $this->getTrans('info'), $moduleInfo) ?>
                <?php endif; ?>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div class="loadingoverlay" hidden>
    <div class="d-flex justify-content-center">
      <div class="spinner-border" style="width: 6rem; height: 6rem;" role="status">
        <span class="visually-hidden"><?=$this->getTrans('processingPleaseWait') ?></span>
      </div>
    </div>
</div>

<script>
let delayedShow;

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
        let loadingOverlay = $(".loadingoverlay");

        delayedShow = setTimeout(function(){
            loadingOverlay.removeAttr('hidden');
        }, 200);

        setTimeout(function(){
            loadingOverlay.attr('hidden', '');
        }, 30000);
    });

    clearTimeout(delayedShow);
    $(".loadingoverlay").attr('hidden', '');
});
</script>
