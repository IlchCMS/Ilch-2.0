<?php

/** @var \Ilch\View $this */

/** @var \Modules\Admin\Mappers\Module $moduleMapper */
$moduleMapper = $this->get('moduleMapper');

/** @var \Modules\Admin\Models\Module[] $modules */
$modules = $this->get('modules');

/** @var \Modules\Admin\Models\Module[] $configurations */
$configurations = $this->get('configurations');
/** @var string $coreVersion */
$coreVersion = $this->get('coreVersion');
/** @var array $dependencies */
$dependencies = $this->get('dependencies');

$modulesList = url_get_contents($this->get('updateserver'));
$modulesOnUpdateServer = json_decode($modulesList, true);
$cacheFilename = ROOT_PATH . '/cache/' . md5($this->get('updateserver')) . '.cache';
$cacheFileDate = null;
if (file_exists($cacheFilename)) {
    $cacheFileDate = new \Ilch\Date(date('Y-m-d H:i:s.', filemtime($cacheFilename)));
}

/**
 * Define the custom sort function
 *
 * @param array $a
 * @param array $b
 * @return int
 */
function custom_sort(array $a, array $b): int
{
    return strcmp($a['name'], $b['name']);
}

$content = [];
?>

<link href="<?=$this->getModuleUrl('static/css/extsearch.css') ?>" rel="stylesheet">

<h1><?=$this->getTrans('search') ?></h1>
<p><a href="<?=$this->getUrl(['action' => 'refreshurl', 'from' => 'search']) ?>" class="btn btn-primary"><?=$this->getTrans('searchForUpdates') ?></a> <?=(!empty($cacheFileDate)) ? '<span class="small">' . $this->getTrans('lastUpdateOn') . ' ' . $this->getTrans($cacheFileDate->format('l', true)) . $cacheFileDate->format(', d. ', true) . $this->getTrans($cacheFileDate->format('F', true)) . $cacheFileDate->format(' Y H:i', true) . '</span>' : $this->getTrans('lastUpdateOn') . ': ' . $this->getTrans('lastUpdateUnknown') ?></p>
<div class="checkbox">
    <label><input type="checkbox" name="setgotokey" onclick="gotokeyAll();" <?=$this->get('gotokey') ? 'checked' : '' ?>/><?=$this->getTrans('gotokey') ?></label>
</div>
<?php
if (empty($modulesOnUpdateServer)) {
    echo $this->getTrans('noModulesAvailable');
    return;
}

// Sort the modules by name
usort($modulesOnUpdateServer, 'custom_sort');
?>

<div id="modules" class="table-responsive">
    <div class="col-lg-12 input-group">
        <span class="input-group-addon"><i class="fa-solid fa-magnifying-glass"></i></span>
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
            <?php foreach ($modulesOnUpdateServer as $moduleOnUpdateServer) : ?>
                <?php
                $moduleModel = new \Modules\Admin\Models\Module();
                $moduleModel->setByArray($moduleOnUpdateServer);

                $module = $modules[$moduleModel->getKey()];
                $moduleLocal = $configurations[$moduleModel->getKey()];
                ?>
                <tr id="Module_<?=$moduleModel->getKey() ?>">
                    <td>
                        <a href="<?=$this->getUrl(['action' => 'show', 'id' => $moduleOnUpdateServer['id']]) ?>" title="<?=$this->getTrans('info') ?>"><?=$this->escape($moduleModel->getName()) ?></a>
                        <br />
                        <small>
                            <?=$this->getTrans('author') ?>: 
                            <?php if ($moduleModel->getLink() != '') : ?>
                                <a href="<?=$moduleModel->getLink() ?>" alt="<?=$this->escape($moduleModel->getAuthor()) ?>" title="<?=$this->escape($moduleModel->getAuthor()) ?>" target="_blank" rel="noopener"><i><?=$this->escape($moduleModel->getAuthor()) ?></i></a>
                            <?php else : ?>
                                <i><?=$this->escape($moduleModel->getAuthor()) ?></i>
                            <?php endif; ?>
                        </small>
                        <br /><br />
                        <?php
                        $isInstalled = isset($modules[$moduleModel->getKey()]);
                        $iconClass = ($isInstalled) ? 'fa-solid fa-arrows-rotate' : 'fa-solid fa-download';

                        if (!$moduleModel->hasPHPExtension()) : ?>
                            <button class="btn disabled"
                                    title="<?=$this->getTrans('phpExtensionError') ?>">
                                <i class="<?=$iconClass ?>"></i>
                            </button>
                        <?php elseif (!$moduleModel->hasPHPVersion()) : ?>
                            <button class="btn disabled"
                                    title="<?=$this->getTrans('phpVersionError') ?>">
                                <i class="<?=$iconClass ?>"></i>
                            </button>
                        <?php elseif (!$moduleModel->hasCoreVersion($this->get('coreVersion'))) : ?>
                            <button class="btn disabled"
                                    title="<?=$this->getTrans('ilchCoreError') ?>">
                                <i class="<?=$iconClass ?>"></i>
                            </button>
                        <?php elseif ($isInstalled && !$module->isNewVersion($moduleModel->getVersion())) : ?>
                            <button class="btn disabled"
                                    title="<?=$this->getTrans('alreadyExists') ?>">
                                <i class="fa-solid fa-check text-success"></i>
                            </button>
                        <?php elseif ($isInstalled && !$moduleMapper->checkOthersDependenciesVersion($module->getKey(), $dependencies)) : ?>
                            <button class="btn disabled"
                                    data-toggle="modal"
                                    data-target="#infoModal<?=$moduleModel->getKey() ?>"
                                    title="<?=$this->getTrans('dependencyError') ?>">
                                <i class="<?=$iconClass ?>"></i>
                            </button>
                        <?php elseif (!$moduleModel->checkOwnDependencies()) : ?>
                            <button class="btn disabled"
                                    title="<?=$this->getTrans('dependencyError') ?>">
                                <i class="<?=$iconClass ?>"></i>
                            </button>
                        <?php elseif ($isInstalled && $module->isNewVersion($moduleModel->getVersion())) : ?>
                            <form method="POST" action="<?=$this->getUrl(['action' => 'update', 'key' => $module->getKey(), 'version' => $moduleModel->getVersion(), 'from' => 'search']) ?>">
                                <?=$this->getTokenField() ?>
                                <input type="hidden" name="gotokey" value="<?=$this->get('gotokey') ? '1' : '0' ?>" />
                                <button type="submit"
                                        class="btn btn-default showOverlay"
                                        title="<?=$this->getTrans('moduleUpdate') ?>">
                                    <i class="fa-solid fa-arrows-rotate"></i>
                                </button>
                            </form>
                        <?php else : ?>
                            <form method="POST" action="<?=$this->getUrl(['action' => 'search', 'key' => $moduleModel->getKey(), 'version' => $moduleModel->getVersion(), 'from' => 'search']) ?>">
                                <?=$this->getTokenField() ?>
                                <input type="hidden" name="gotokey" value="<?=$this->get('gotokey') ? '1' : '0' ?>" />
                                <button type="submit"
                                        class="btn btn-default showOverlay"
                                        title="<?=$this->getTrans('moduleDownload') ?>">
                                    <i class="fa-solid fa-download"></i>
                                </button>
                            </form>
                        <?php endif; ?>

                        <a href="<?=$this->getUrl(['action' => 'show', 'id' => $moduleOnUpdateServer['id']]) ?>" title="<?=$this->getTrans('info') ?>">
                            <span class="btn btn-default">
                                <i class="fa-solid fa-info text-info"></i>
                            </span>
                        </a>
                    </td>
                    <td><?=$moduleModel->getVersion()?></td>
                    <td>
                        <?=$moduleOnUpdateServer['desc'] ?>
                        <?=$moduleModel->getOfficial() ? '<span class="ilch-official">ilch</span>' : '' ?>
                    </td>
                </tr>
                <?php
                $dependencyInfo = '<p>' . $this->getTrans('dependencyInfo') . '</p>';
                foreach ($moduleMapper->checkOthersDependencies($moduleModel->getKey(), $dependencies) as $dependenciesKey => $dependenciesVersion) {
                    $dependencyInfo .= '<b>' . $dependenciesKey . ':</b> ' . str_replace(',', '', $dependenciesVersion) . '<br />';
                }
                ?>
                <?=$this->getDialog('infoModal' . $moduleOnUpdateServer->key, $this->getTrans('dependencies') . ' ' . $this->getTrans('info'), $dependencyInfo) ?>
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
    // search
    $(document).ready(function() {
            $(".showOverlay").on('click', function(){
            $.LoadingOverlay("show");
            setTimeout(function(){
                $.LoadingOverlay("hide");
            }, 30000);
        });

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

                if(inputText !== '') {
                    $('.search-query-sf').remove();
                    tableBody.prepend('<tr class="search-query-sf"><td colspan="3"><strong><?=$this->getTrans('searchingFor') ?>: "'
                        + $(that).val()
                        + '"</strong></td></tr>');
                } else {
                    $('.search-query-sf').remove();
                }

                if( rowText.indexOf( inputText ) === -1 ) {
                    // hide rows
                    tableRowsClass.eq(i).hide();
                } else {
                    $('.search-sf').remove();
                    tableRowsClass.eq(i).show();
                }
            });

            // all tr elements are hidden
            if(tableRowsClass.children(':visible').length === 0) {
                tableBody.append('<tr class="search-sf"><td class="text-muted" colspan="3"><?=$this->getTrans('noResultFound') ?></td></tr>');
            }
        });
    });
</script>
