<?php

/** @var \Ilch\View $this */

/** @var \Modules\Admin\Models\Module[] $modulesNotInstalled */
$modulesNotInstalled = $this->get('modulesNotInstalled');
?>
<link href="<?=$this->getModuleUrl('static/css/extsearch.css') ?>" rel="stylesheet">
<h1><?=$this->getTrans('modulesNotInstalled') ?></h1>
<?php if (!empty($modulesNotInstalled)) : ?>
    <?php
    $modulesList = url_get_contents($this->get('updateserver'));
    $modulesOnUpdateServer = json_decode($modulesList);
    $cacheFilename = ROOT_PATH . '/cache/' . md5($this->get('updateserver')) . '.cache';
    $cacheFileDate = new \Ilch\Date(date('Y-m-d H:i:s.', filemtime($cacheFilename)));
    ?>
    <p><a href="<?=$this->getUrl(['action' => 'refreshurl', 'from' => 'notinstalled']) ?>" class="btn btn-primary"><?=$this->getTrans('searchForUpdates') ?></a> <span class="small"><?=$this->getTrans('lastUpdateOn') ?> <?=$this->getTrans($cacheFileDate->format('l', true)) . $cacheFileDate->format(', d. ', true) . $this->getTrans($cacheFileDate->format('F', true)) . $cacheFileDate->format(' Y H:i', true) ?></span></p>
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
                <?php foreach ($modulesNotInstalled as $module) : ?>
                    <?php
                    $content = $module->getContentForLocale($this->getTranslator()->getLocale());

                    $moduleOnUpdateServerFound = null;
                    $moduleOnUpdateServer = null;
                    foreach ($modulesOnUpdateServer ?? [] as $moduleOnUpdateServer) {
                        if ($moduleOnUpdateServer->key == $module->getKey()) {
                            $moduleOnUpdateServerFound = $moduleOnUpdateServer;
                            break;
                        }
                    }
                    ?>
                    <tr id="Module_<?=$module->getKey() ?>">
                        <td>
                            <?=$content['name'] ?>
                            <br />
                            <small>
                                <?=$this->getTrans('author') ?>:
                                <?php if ($module->getLink() != '') : ?>
                                    <a href="<?=$module->getLink() ?>" alt="<?=$this->escape($module->getAuthor()) ?>" title="<?=$this->escape($module->getAuthor()) ?>" target="_blank" rel="noopener">
                                        <i><?=$this->escape($module->getAuthor()) ?></i>
                                    </a>
                                <?php else : ?>
                                    <i><?=$this->escape($module->getAuthor()) ?></i>
                                <?php endif; ?>
                            </small>
                            <br /><br />
                            <?php if (!$module->hasPHPExtension()) : ?>
                                <button class="btn disabled"
                                        title="<?=$this->getTrans('phpExtensionError') ?>">
                                    <i class="fa-regular fa-floppy-disk"></i>
                                </button>
                            <?php elseif (!$module->hasPHPVersion()) : ?>
                                <button class="btn disabled"
                                        title="<?=$this->getTrans('phpVersionError') ?>">
                                    <i class="fa-regular fa-floppy-disk"></i>
                                </button>
                            <?php elseif (!$module->hasCoreVersion($this->get('coreVersion'))) : ?>
                                <button class="btn disabled"
                                        title="<?=$this->getTrans('ilchCoreError') ?>">
                                    <i class="fa-regular fa-floppy-disk"></i>
                                </button>
                            <?php elseif (!$module->checkOwnDependencies()) : ?>
                                <button class="btn disabled"
                                        title="<?=$this->getTrans('dependencyError') ?>">
                                    <i class="fa-regular fa-floppy-disk"></i>
                                </button>
                            <?php else : ?>
                                <form method="POST" action="<?=$this->getUrl(['action' => 'install', 'key' => $module->getKey(), 'from' => 'notinstalled']) ?>">
                                    <?=$this->getTokenField() ?>
                                    <button type="submit"
                                            class="btn btn-default showOverlay"
                                            title="<?=$this->getTrans('installModule') ?>">
                                        <i class="fa-regular fa-floppy-disk"></i>
                                    </button>
                                </form>
                            <?php endif; ?>
                            <?php if ($moduleOnUpdateServer && $module->getKey() == $moduleOnUpdateServer->key) : ?>
                                <a href="<?=$this->getUrl(['action' => 'show', 'id' => $moduleOnUpdateServer->id]) ?>" title="<?=$this->getTrans('info') ?>">
                                    <span class="btn btn-default">
                                        <i class="fa-solid fa-info text-info"></i>
                                    </span>
                                </a>
                            <?php else : ?>
                                <span class="btn btn-default"
                                      data-toggle="modal"
                                      data-target="#infoModal<?=$module->getKey() ?>"
                                      title="<?=$this->getTrans('info') ?>">
                                    <i class="fa-solid fa-info text-info"></i>
                                </span>
                            <?php endif; ?>
                            <a href="<?=$this->getUrl(['action' => 'delete', 'key' => $module->getKey()], null, true) ?>" class="btn btn-default" title="<?=$this->getTrans('delete') ?>">
                                <i class="fa-regular fa-trash-can text-warning"></i>
                            </a>
                        </td>
                        <td><?=$module->getVersion() ?></td>
                        <td>
                            <?php if (!empty($content['description'])) : ?>
                                <?=$content['description'] ?>
                            <?php endif; ?>
                            <?=($module->getOfficial()) ? '<span class="ilch-official">ilch</span>' : '' ?>
                        </td>
                    </tr>

                    <?php
                    if ($module->getLink() != '') {
                        $author = '<a href="' . $module->getLink() . '" alt="' . $this->escape($module->getAuthor()) . '" title="' . $this->escape($module->getAuthor()) . '" target="_blank" rel="noopener">' . $this->escape($module->getAuthor()) . '</a>';
                    } else {
                        $author = $this->escape($module->getAuthor());
                    }

                    if ($module->hasPHPVersion()) {
                        $phpVersion = '<font color="#3c763d">' . $module->getPHPVersion() . '</font>';
                    } else {
                        $phpVersion = '<font color="#a94442">' . $module->getPHPVersion() . '</font>';
                    }

                    if ($module->hasCoreVersion($this->get('coreVersion'))) {
                        $ilchCore = '<font color="#3c763d">' . $module->getIlchCore() . '</font>';
                    } else {
                        $ilchCore = '<font color="#a94442">' . $module->getIlchCore() . '</font>';
                    }

                    $moduleInfo = '<b>' . $this->getTrans('name') . ':</b> ' . $this->escape($content['name']) . '<br />
                            <b>' . $this->getTrans('version') . ':</b> ' . $this->escape($module->getVersion()) . '<br />
                            <b>' . $this->getTrans('author') . ':</b> ' . $author . '<br /><br />
                            <b>' . $this->getTrans('requirements') . '</b><br />
                            <b>' . $this->getTrans('ilchCoreVersion') . ':</b> ' . $ilchCore . '<br />
                            <b>' . $this->getTrans('phpVersion') . ':</b> ' . $phpVersion . '<br />';
                    if (count($module->getPHPExtension())) {
                        $phpExtension = [];
                        foreach ($module->getPHPExtension() as $extension => $state) {
                            if ($state) {
                                $phpExtension[] = '<font color="#3c763d">' . $extension . '</font>';
                            } else {
                                $phpExtension[] = '<font color="#a94442">' . $extension . '</font>';
                            }
                        }
                        $phpExtension = implode(', ', $phpExtension);

                        $moduleInfo .= '<b>' . $this->getTrans('phpExtensions') . ':</b> ' . $phpExtension . '<br />';
                    }
                    if (count($module->getDepends())) {
                        $moduleInfo .= '<b>' . $this->getTrans('dependencies') . ':</b><br />';

                        foreach ($module->getDepends() as $key => $value) {
                            if ($module->dependsCheck[$key]) {
                                $moduleInfo .= '<font color="#3c763d">' . $key . ' ' . str_replace(',', '', $value) . '</font><br />';
                            } else {
                                $moduleInfo .= '<font color="#a94442">' . $key . ' ' . str_replace(',', '', $value) . '</font><br />';
                            }
                        }
                    }
                    $moduleInfo .= '<br /><b>' . $this->getTrans('desc') . ':</b><br />' . $content['description'];
                    ?>
                    <?=$this->getDialog('infoModal' . $module->getKey(), $this->getTrans('menuModules') . ' ' . $this->getTrans('info'), $moduleInfo) ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else : ?>
    <?=$this->getTrans('noNotInstalledModules') ?>
<?php endif; ?>
<script src="<?=$this->getModuleUrl('static/js/jquery-loading-overlay/loadingoverlay.min.js') ?>"></script>
<script>
    $(document).ready(function() {
        $(".showOverlay").on('click', function(event){
            $.LoadingOverlay("show");
            setTimeout(function(){
                $.LoadingOverlay("hide");
            }, 30000);
        });
    });
</script>
