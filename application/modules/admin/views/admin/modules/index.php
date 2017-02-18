<?php
$modulesList = url_get_contents('http://ilch2.de/downloads/modules/list.php');
$modulesOnUpdateServer = json_decode($modulesList);
$versionsOfModules = $this->get('versionsOfModules');
$coreVersion = $this->get('coreVersion');
$dependencies = $this->get('dependencies');
$configurations = $this->get('configurations');

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

<legend><?=$this->getTrans('modulesInstalled') ?></legend>
<div id="modules" class="table-responsive">
    <table class="table table-hover table-striped">
        <colgroup>
            <col class="col-lg-2">
            <col>
        </colgroup>
        <thead>
            <tr>
                <th><?=$this->getTrans('modules') ?></th>
                <th><?=$this->getTrans('desc') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($this->get('modules') as $module):
                $content = $module->getContentForLocale($this->getTranslator()->getLocale());

                $moduleOnUpdateServerFound = null;
                $filename = '';
                foreach ($modulesOnUpdateServer as $moduleOnUpdateServer) {
                    if ($moduleOnUpdateServer->key == $module->getKey()) {
                        $filename = basename($moduleOnUpdateServer->downloadLink);
                        $filename = strstr($filename,'.',true);
                        $moduleOnUpdateServerFound = $moduleOnUpdateServer;
                        break;
                    }
                }

                if ($this->getUser()->hasAccess('module_'.$module->getKey()) && !$module->getSystemModule()): ?>
                    <tr>
                        <td>
                            <?=$content['name'] ?>
                            <br />
                            <small>
                                <?=$this->getTrans('author') ?>:
                                <?php if ($module->getLink() != ''): ?>
                                    <a href="<?=$module->getLink() ?>" alt="<?=$this->escape($module->getAuthor()) ?>" title="<?=$this->escape($module->getAuthor()) ?>" target="_blank">
                                        <i><?=$this->escape($module->getAuthor()) ?></i>
                                    </a>
                                <?php else: ?>
                                    <i><?=$this->escape($module->getAuthor()) ?></i>
                                <?php endif; ?>
                            </small>
                            <br /><br />
                            <a href="<?=$this->getUrl(['module' => $module->getKey(), 'controller' => 'index', 'action' => 'index']) ?>" class="btn btn-default" title="<?=$this->getTrans('administrate') ?>">
                                <i class="fa fa-pencil text-success"></i>
                            </a>
                            <span class="btn btn-default"
                                  data-toggle="modal"
                                  data-target="#infoModal<?=$module->getKey() ?>"
                                  title="<?=$this->getTrans('info') ?>">
                                <i class="fa fa-info text-info"></i>
                            </span>
                            <?php if (!isset($config->isSystemModule)): ?>
                                <?php if (!empty(checkOthersDependencies([$module->getKey() => $module->getVersion()], $dependencies))): ?>
                                    <button class="btn disabled"
                                            data-toggle="modal"
                                            data-target="#dependencyInfoModal<?=$module->getKey() ?>"
                                            title="<?=$this->getTrans('dependencyError') ?>">
                                        <i class="fa fa-trash-o text-warning"></i>
                                    </button>
                                <?php else: ?>
                                <a href="<?=$this->getUrl(['action' => 'uninstall', 'key' => $module->getKey()], null, true) ?>" class="btn btn-default" title="<?=$this->getTrans('uninstall') ?>">
                                   <i class="fa fa-trash-o text-warning"></i>
                                </a>
                                <?php endif; ?>
                            <?php endif; ?>
                            <?php
                            if (!empty($moduleOnUpdateServerFound)) {
                                if (!empty($moduleOnUpdateServerFound->phpExtensions)) {
                                    $extensionCheck = [];
                                    foreach ($moduleOnUpdateServerFound->phpExtensions as $extension) {
                                        $extensionCheck[] = extension_loaded($extension);
                                    }
                                }
                                if (!empty($moduleOnUpdateServerFound->phpExtensions) AND in_array(false, $extensionCheck)): ?>
                                    <button class="btn disabled"
                                            title="<?=$this->getTrans('phpExtensionError') ?>">
                                        <i class="fa fa-refresh"></i>
                                    </button>
                                <?php elseif (version_compare(phpversion(), $moduleOnUpdateServerFound->phpVersion, '<')): ?>
                                    <button class="btn disabled"
                                            title="<?=$this->getTrans('phpVersionError') ?>">
                                        <i class="fa fa-refresh"></i>
                                    </button>
                                <?php elseif (version_compare($coreVersion, $moduleOnUpdateServerFound->ilchCore, '<')): ?>
                                    <button class="btn disabled"
                                            title="<?=$this->getTrans('ilchCoreError') ?>">
                                        <i class="fa fa-refresh"></i>
                                    </button>
                                <?php elseif (!empty(checkOthersDependencies([$moduleOnUpdateServerFound->key => $moduleOnUpdateServerFound->version], $dependencies))): ?>
                                    <button class="btn disabled"
                                            data-toggle="modal"
                                            data-target="#dependencyInfoModal<?=$moduleOnUpdateServerFound->key ?>"
                                            title="<?=$this->getTrans('dependencyError') ?>">
                                        <i class="fa fa-refresh"></i>
                                    </button>
                                <?php elseif (!checkOwnDependencies($versionsOfModules, $moduleOnUpdateServerFound)): ?>
                                    <button class="btn disabled"
                                            title="<?=$this->getTrans('dependencyError') ?>">
                                        <i class="fa fa-refresh"></i>
                                    </button>
                                <?php elseif (version_compare($versionsOfModules[$moduleOnUpdateServerFound->key]['version'], $moduleOnUpdateServerFound->version, '<')): ?>
                                    <form method="POST" action="<?=$this->getUrl(['action' => 'update', 'key' => $moduleOnUpdateServerFound->key, 'from' => 'index']) ?>">
                                        <?=$this->getTokenField() ?>
                                        <button type="submit"
                                                class="btn btn-default"
                                                name="url"
                                                value="<?=$moduleOnUpdateServerFound->downloadLink ?>"
                                                title="<?=$this->getTrans('moduleUpdate') ?>">
                                            <i class="fa fa-refresh"></i>
                                        </button>
                                    </form>
                                <?php endif; ?>
                            <?php } ?>
                        </td>
                        <td><?=$content['description'] ?></td>
                    </tr>

                    <?php
                    if ($module->getLink() != '') {
                        $author = '<a href="'.$module->getLink().'" alt="'.$this->escape($module->getAuthor()).'" title="'.$this->escape($module->getAuthor()).'" target="_blank">'.$this->escape($module->getAuthor()).'</a>';
                    } else {
                        $author = $this->escape($module->getAuthor());
                    }
                    $moduleInfo = '<b>'.$this->getTrans('name').':</b> '.$content['name'].'<br />
                                   <b>'.$this->getTrans('version').':</b> '.$this->escape($module->getVersion()).'<br />
                                   <b>'.$this->getTrans('author').':</b> '.$author.'<br />
                                   <b>'.$this->getTrans('ilchCoreVersion').':</b> '.$configurations[$module->getKey()]['ilchCore'].'<br />
                                   <b>'.$this->getTrans('phpVersion').':</b> '.$configurations[$module->getKey()]['phpVersion'].'<br />
                                   <b>'.$this->getTrans('dependencies').':</b><br />';
                    $dependenciesForInfo = (!empty($configurations[$module->getKey()]['depends']) ? $configurations[$module->getKey()]['depends'] : []);
                    foreach ($dependenciesForInfo as $key => $value) {
                        $moduleInfo .= $key.' '. str_replace(',','', $value).'<br />';
                    }

                    $moduleInfo .= '<br /><b>'.$this->getTrans('desc').':</b><br />'.$content['description'];

                    if (!empty($moduleOnUpdateServerFound)) {
                        foreach (checkOthersDependencies([$moduleOnUpdateServerFound->key => $moduleOnUpdateServerFound->version], $dependencies) as $key => $value) {
                            $dependencyInfo .= '<b>'.$key.':</b> '.key($value).$value[key($value)].'<br />';
                        }

                        $dependencyInfo = '<p>'.$this->getTrans('dependencyInfo').'</p>';
                        echo $this->getDialog('dependencyInfoModal'.$module->getKey(), $this->getTrans('dependencies').' '.$this->getTrans('info'), $dependencyInfo);
                    }
                    ?>
                    <?=$this->getDialog('infoModal'.$module->getKey(), $this->getTrans('menuModules').' '.$this->getTrans('info'), $moduleInfo); ?>
                <?php endif; ?>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
