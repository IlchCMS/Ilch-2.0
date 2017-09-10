<?php
$modulesList = url_get_contents($this->get('updateserver'));
$modulesOnUpdateServer = json_decode($modulesList);
$versionsOfModules = $this->get('versionsOfModules');
$coreVersion = $this->get('coreVersion');
$dependencies = $this->get('dependencies');

// Define the custom sort function
function custom_sort($a,$b)
{
    return strcmp($a->name, $b->name);
}

// Sort the modules by name
usort($modulesOnUpdateServer, "custom_sort");

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
    <div class="col-lg-12 input-group">
        <span class="input-group-addon"><i class="fa fa-search"></i></span>
        <input class="form-control hasclear" id="user-search" placeholder="<?=$this->getTrans('search') ?>" required>
    </div>
    <br />
    <table class="table table-hover table-striped table-list-search">
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
                    <td><?=$moduleOnUpdateServer->version?></td>
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

<script>
// search
$(document).ready(function() {
    // something is entered in search form
    $('#user-search').keyup( function() {
        var that = this,
            tableBody = $('.table-list-search tbody'),
            tableRowsClass = $('.table-list-search tbody tr');

        $('.search-sf').remove();
        tableRowsClass.each( function(i, val) {

            // lower text for case insensitive
            var rowText = $(val).text().toLowerCase(),
                inputText = $(that).val().toLowerCase();

            if(inputText != '') {
                $('.search-query-sf').remove();
                tableBody.prepend('<tr class="search-query-sf"><td colspan="3"><strong><?=$this->getTrans('searchingFor') ?>: "'
                    + $(that).val()
                    + '"</strong></td></tr>');
            } else {
                $('.search-query-sf').remove();
            }

            if( rowText.indexOf( inputText ) == -1 ) {
                // hide rows
                tableRowsClass.eq(i).hide();
            } else {
                $('.search-sf').remove();
                tableRowsClass.eq(i).show();
            }
        });

        // all tr elements are hidden
        if(tableRowsClass.children(':visible').length == 0) {
            tableBody.append('<tr class="search-sf"><td class="text-muted" colspan="3"><?=$this->getTrans('noResultFound') ?></td></tr>');
        }
    });
});
</script>
