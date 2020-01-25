<link href="<?=$this->getModuleUrl('static/css/extsearch.css') ?>" rel="stylesheet">
<h1><?=$this->getTrans('modulesNotInstalled') ?></h1>
<?php if (!empty($this->get('modulesNotInstalled'))): ?>
    <?php
    $modulesList = url_get_contents($this->get('updateserver'));
    $modulesOnUpdateServer = json_decode($modulesList);
    $cacheFilename = ROOT_PATH.'/cache/'.md5($this->get('updateserver')).'.cache';
    $cacheFileDate = new \Ilch\Date(date('Y-m-d H:i:s.', filemtime($cacheFilename)));

    function checkOwnDependencies($versionsOfModules, $dependencies) {
        foreach ($dependencies as $key => $value) {
            $parsed = explode(',', $value);
            if (!version_compare($versionsOfModules[$key]['version'], $parsed[1], $parsed[0])) {
                return false;
            }
        }

        return true;
    }
    ?>
    <p><a href="<?=$this->getUrl(['action' => 'refreshurl', 'from' => 'notinstalled']) ?>" class="btn btn-primary"><?=$this->getTrans('updateNow') ?></a> <span class="small"><?=$this->getTrans('lastUpdateOn') ?> <?=$this->getTrans($cacheFileDate->format('l', true)).$cacheFileDate->format(', d. ', true).$this->getTrans($cacheFileDate->format('F', true)).$cacheFileDate->format(' Y H:i', true) ?></span></p>
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
                <?php foreach ($this->get('modulesNotInstalled') as $module): ?>
                    <?php
                    $content = $module->getContentForLocale($this->getTranslator()->getLocale());

                    $moduleOnUpdateServerFound = null;
                    foreach ($modulesOnUpdateServer as $moduleOnUpdateServer) {
                        if ($moduleOnUpdateServer->key == $module->getKey()) {
                            $moduleOnUpdateServerFound = $moduleOnUpdateServer;
                            break;
                        }
                    }

                    if ($module->getPHPExtension() != '') {
                        $extensionCheck = [];
                        foreach ($module->getPHPExtension() as $extension) {
                            $extensionCheck[] = extension_loaded($extension);
                        }

                        $phpExtensions = array_combine($module->getPHPExtension(), $extensionCheck);
                        foreach ($phpExtensions as $key => $value) {
                            if ($value == true) {
                                $phpExtension[] = '<font color="#3c763d">'.$key.'</font>';
                            } else {
                                $phpExtension[] = '<font color="#a94442">'.$key.'</font>';
                            }
                        }

                        $phpExtension = implode(', ', $phpExtension);
                    }

                    if (version_compare(phpversion(), $module->getPHPVersion(), '>=')) {
                        $phpVersion = '<font color="#3c763d">'.$module->getPHPVersion().'</font>';
                    } else {
                        $phpVersion = '<font color="#a94442">'.$module->getPHPVersion().'</font>';
                    }

                    if (version_compare($this->get('coreVersion'), $module->getIlchCore(), '>=')) {
                        $ilchCore = '<font color="#3c763d">'.$module->getIlchCore().'</font>';
                    } else {
                        $ilchCore = '<font color="#a94442">'.$module->getIlchCore().'</font>';
                    }
                    ?>
                    <tr id="Module_<?=$module->getKey() ?>">
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
                            <?php if ($module->getPHPExtension() != '' AND in_array(false, $extensionCheck)): ?>
                                <button class="btn disabled"
                                        title="<?=$this->getTrans('phpExtensionError') ?>">
                                    <i class="fa fa-save"></i>
                                </button>
                            <?php elseif (!version_compare(phpversion(), $module->getPHPVersion(), '>=')): ?>
                                <button class="btn disabled"
                                        title="<?=$this->getTrans('phpVersionError') ?>">
                                    <i class="fa fa-save"></i>
                                </button>
                            <?php elseif (!version_compare($this->get('coreVersion'), $module->getIlchCore(), '>=')): ?>
                                <button class="btn disabled"
                                        title="<?=$this->getTrans('ilchCoreError') ?>">
                                    <i class="fa fa-save"></i>
                                </button>
                            <?php elseif (!checkOwnDependencies($this->get('versionsOfModules'), $this->get('dependencies')[$module->getKey()])): ?>
                                <button class="btn disabled"
                                        title="<?=$this->getTrans('dependencyError') ?>">
                                    <i class="fa fa-save"></i>
                                </button>
                            <?php else: ?>
                                <form method="POST" action="<?=$this->getUrl(['action' => 'install', 'key' => $module->getKey(), 'from' => 'notinstalled']) ?>">
                                    <?=$this->getTokenField() ?>
                                    <button type="submit"
                                            class="btn btn-default showOverlay"
                                            title="<?=$this->getTrans('installModule') ?>">
                                        <i class="fa fa-save"></i>
                                    </button>
                                </form>
                            <?php endif; ?>
                            <?php if ($module->getKey() == $moduleOnUpdateServer->key): ?>
                                <a href="<?=$this->getUrl(['action' => 'show', 'id' => $moduleOnUpdateServer->id]) ?>" title="<?=$this->getTrans('info') ?>">
                                    <span class="btn btn-default">
                                        <i class="fa fa-info text-info"></i>
                                    </span>
                                </a>
                            <?php else: ?>
                                <span class="btn btn-default"
                                      data-toggle="modal"
                                      data-target="#infoModal<?=$module->getKey() ?>"
                                      title="<?=$this->getTrans('info') ?>">
                                    <i class="fa fa-info text-info"></i>
                                </span>
                            <?php endif; ?>
                            <a href="<?=$this->getUrl(['action' => 'delete', 'key' => $module->getKey()], null, true) ?>" class="btn btn-default" title="<?=$this->getTrans('delete') ?>">
                                <i class="fa fa-trash-o text-warning"></i>
                            </a>
                        </td>
                        <td><?=$module->getVersion() ?></td>
                        <td>
                            <?php if (!empty($content['description'])): ?>
                                <?=$content['description'] ?>
                            <?php endif; ?>
                            <?=($module->getOfficial()) ? '<span class="ilch-official">ilch</span>' : '' ?>
                        </td>
                    </tr>

                    <?php
                    if ($module->getLink() != '') {
                        $author = '<a href="'.$module->getLink().'" alt="'.$this->escape($module->getAuthor()).'" title="'.$this->escape($module->getAuthor()).'" target="_blank">'.$this->escape($module->getAuthor()).'</a>';
                    } else {
                        $author = $this->escape($module->getAuthor());
                    }
                    $moduleInfo = '<b>'.$this->getTrans('name').':</b> '.$this->escape($content['name']).'<br />
                            <b>'.$this->getTrans('version').':</b> '.$this->escape($module->getVersion()).'<br />
                            <b>'.$this->getTrans('author').':</b> '.$author.'<br /><br />
                            <b>'.$this->getTrans('requirements').'</b><br />
                            <b>'.$this->getTrans('ilchCoreVersion').':</b> '.$ilchCore.'<br />
                            <b>'.$this->getTrans('phpVersion').':</b> '.$phpVersion.'<br />';
                    if ($module->getPHPExtension()) {
                        $moduleInfo .= '<b>'.$this->getTrans('phpExtensions').':</b> '.$phpExtension.'<br />';
                    }
                    if ($module->getDepends()) {
                        $moduleInfo .= '<b>'.$this->getTrans('dependencies').':</b><br />';

                        foreach ($module->getDepends() as $key => $value) {
                            $moduleInfo .= $key.' '. str_replace(',','', $value).'<br />';
                        }
                    }
                    $moduleInfo .= '<br /><b>'.$this->getTrans('desc').':</b><br />'.$content['description'];
                    ?>
                    <?=$this->getDialog('infoModal'.$module->getKey(), $this->getTrans('menuModules').' '.$this->getTrans('info'), $moduleInfo) ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <?=$this->getTrans('noNotInstalledModules') ?>
<?php endif; ?>
<script src="<?=$this->getModuleUrl('static/js/jquery-loading-overlay/loadingoverlay.min.js') ?>"></script>
<script>
$(document).ready(function() {
    $(".showOverlay").on('click', function(event){
        $.LoadingOverlay("show");
        setTimeout(function(){
            $.LoadingOverlay("hide");
        }, 10000);
    });
});
</script>
