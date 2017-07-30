<?php
$modulesList = url_get_contents($this->get('updateserver'));
$modulesOnUpdateServer = json_decode($modulesList);
$versionsOfModules = $this->get('versionsOfModules');
$coreVersion = $this->get('coreVersion');
$dependencies = $this->get('dependencies');

// Sort the modules by name
usort($modulesOnUpdateServer, "custom_sort");
// Define the custom sort function
function custom_sort($a,$b)
{
    return strcoll($a->name, $b->name);
}

function checkOthersDependencies($module, $dependencies)
{
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

function checkOwnDependencies($versionsOfModules, $moduleOnUpdateServer)
{
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

<h1><?=$this->getTrans('search') ?></h1>
<?php
if (empty($modulesOnUpdateServer)) {
    echo $this->getTrans('noModulesAvailable');
    return;
}
?>

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
            <?php foreach ($modulesOnUpdateServer as $moduleOnUpdateServer):  ?>
                <?php
                if (!empty($moduleOnUpdateServer->phpExtensions)) {
                    $extensionCheck = [];
                    foreach ($moduleOnUpdateServer->phpExtensions as $extension) {
                        $extensionCheck[] = extension_loaded($extension);
                    }
                }
                ?>
                <tr>
                    <td>
                        <a href="<?=$this->getUrl(['action' => 'show', 'id' => $moduleOnUpdateServer->id]); ?>" title="<?=$this->getTrans('info') ?>"><?=$this->escape($moduleOnUpdateServer->name) ?></a>
                        <br />
                        <small>
                            <?=$this->getTrans('author') ?>: 
                            <?php if ($moduleOnUpdateServer->link != ''): ?>
                                <a href="<?=$moduleOnUpdateServer->link ?>" alt="<?=$this->escape($moduleOnUpdateServer->author) ?>" title="<?=$this->escape($moduleOnUpdateServer->author) ?>" target="_blank"><i><?=$this->escape($moduleOnUpdateServer->author) ?></i></a>
                            <?php else: ?>
                                <i><?=$this->escape($moduleOnUpdateServer->author) ?></i>
                            <?php endif; ?>
                        </small>
                        <br /><br />
                        <?php
                        $isInstalled = in_array($moduleOnUpdateServer->key, $this->get('modules'));
                        $iconClass = ($isInstalled) ? 'fa fa-refresh' : 'fa fa-download';

                        if (!empty($moduleOnUpdateServer->phpExtensions) AND in_array(false, $extensionCheck)): ?>
                            <button class="btn disabled"
                                    title="<?=$this->getTrans('phpExtensionError') ?>">
                                <i class="<?=$iconClass ?>"></i>
                            </button>
                        <?php elseif (version_compare(phpversion(), $moduleOnUpdateServer->phpVersion, '<')): ?>
                            <button class="btn disabled"
                                    title="<?=$this->getTrans('phpVersionError') ?>">
                                <i class="<?=$iconClass ?>"></i>
                            </button>
                        <?php elseif (version_compare($coreVersion, $moduleOnUpdateServer->ilchCore, '<')): ?>
                            <button class="btn disabled"
                                    title="<?=$this->getTrans('ilchCoreError') ?>">
                                <i class="<?=$iconClass ?>"></i>
                            </button>
                        <?php elseif ($isInstalled && version_compare($versionsOfModules[$moduleOnUpdateServer->key]['version'], $moduleOnUpdateServer->version, '>=')): ?>
                            <button class="btn disabled"
                                    title="<?=$this->getTrans('alreadyExists') ?>">
                                <i class="fa fa-check text-success"></i>
                            </button>
                        <?php elseif ($isInstalled && !empty(checkOthersDependencies([$moduleOnUpdateServer->key => $moduleOnUpdateServer->version], $dependencies))): ?>
                            <button class="btn disabled"
                                    data-toggle="modal"
                                    data-target="#infoModal<?=$moduleOnUpdateServer->key ?>"
                                    title="<?=$this->getTrans('dependencyError') ?>">
                                <i class="<?=$iconClass ?>"></i>
                            </button>
                        <?php elseif (!checkOwnDependencies($versionsOfModules, $moduleOnUpdateServer)): ?>
                            <button class="btn disabled"
                                    title="<?=$this->getTrans('dependencyError') ?>">
                                <i class="<?=$iconClass ?>"></i>
                            </button>
                        <?php elseif ($isInstalled && version_compare($versionsOfModules[$moduleOnUpdateServer->key]['version'], $moduleOnUpdateServer->version, '<')): ?>
                            <form method="POST" action="<?=$this->getUrl(['action' => 'update', 'key' => $moduleOnUpdateServer->key, 'from' => 'search']) ?>">
                                <?=$this->getTokenField() ?>
                                <button type="submit"
                                        class="btn btn-default"
                                        title="<?=$this->getTrans('moduleUpdate') ?>">
                                    <i class="fa fa-refresh"></i>
                                </button>
                            </form>
                        <?php else: ?>
                            <form method="POST" action="<?=$this->getUrl(['action' => 'search', 'key' => $moduleOnUpdateServer->key]) ?>">
                                <?=$this->getTokenField() ?>
                                <button type="submit"
                                        class="btn btn-default"
                                        title="<?=$this->getTrans('moduleDownload') ?>">
                                    <i class="fa fa-download"></i>
                                </button>
                            </form>
                        <?php endif; ?>

                        <a href="<?=$this->getUrl(['action' => 'show', 'id' => $moduleOnUpdateServer->id]); ?>" title="<?=$this->getTrans('info') ?>">
                            <span class="btn btn-default">
                                <i class="fa fa-info text-info"></i>
                            </span>
                        </a>
                    </td>
                    <td><?=$moduleOnUpdateServer->desc ?></td>
                </tr>
                <?php
                    $dependencyInfo = '<p>'.$this->getTrans('dependencyInfo').'</p>';
                    foreach (checkOthersDependencies([$moduleOnUpdateServer->key => $moduleOnUpdateServer->version], $dependencies) as $key => $value) {
                        $dependencyInfo .= '<b>'.$key.':</b> '.key($value).$value[key($value)].'<br />';
                    }
                ?>
                <?=$this->getDialog('infoModal'.$moduleOnUpdateServer->key, $this->getTrans('dependencies').' '.$this->getTrans('info'), $dependencyInfo); ?>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
