<legend><?=$this->getTrans('modulesNotInstalled') ?></legend>
<?php if (!empty($this->get('modulesNotInstalled'))): ?>
    <?php
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
    <div class="table-responsive">
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
                <?php foreach ($this->get('modulesNotInstalled') as $module): ?>
                    <?php
                    $content = $module->getContentForLocale($this->getTranslator()->getLocale());

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

                        $phpExtension = implode(", ", $phpExtension);
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
                            <?php if ($module->getPHPExtension() != '' AND in_array(false, $extensionCheck)): ?>
                                <button class="btn disabled"
                                        title="<?=$this->getTrans('phpExtensionError') ?>">
                                    <i class="fa fa-save"></i>
                                </button>
                            <?php elseif (!checkOwnDependencies($this->get('versionsOfModules'), $this->get('dependencies')[$module->getKey()])): ?>
                                <button class="btn disabled"
                                        title="<?=$this->getTrans('dependencyError') ?>">
                                    <i class="fa fa-save"></i>
                                </button>
                            <?php else: ?>
                                <a href="<?=$this->getUrl(['action' => 'install', 'key' => $module->getKey()], null, true) ?>" class="btn btn-default" title="<?=$this->getTrans('installModule') ?>">
                                    <i class="fa fa-save"></i>
                                </a>
                            <?php endif; ?>
                            <span class="btn btn-default"
                                  data-toggle="modal"
                                  data-target="#infoModal<?=$module->getKey() ?>"
                                  title="<?=$this->getTrans('info') ?>">
                                <i class="fa fa-info text-info"></i>
                            </span>
                            <a href="<?=$this->getUrl(['action' => 'delete', 'key' => $module->getKey()], null, true) ?>" class="btn btn-default" title="<?=$this->getTrans('delete') ?>">
                                <i class="fa fa-trash-o text-warning"></i>
                            </a>
                        </td>
                        <td>
                            <?php if (!empty($content['description'])): ?>
                                <?=$content['description'] ?>
                            <?php endif; ?>
                        </td>
                    </tr>

                    <?php
                    if ($module->getLink() != '') {
                        $author = '<a href="'.$module->getLink().'" alt="'.$this->escape($module->getAuthor()).'" title="'.$this->escape($module->getAuthor()).'" target="_blank">'.$this->escape($module->getAuthor()).'</a>';
                    } else {
                        $author = $this->escape($module->getAuthor());
                    }
                    $phpExtensions = '';
                    if ($module->getPHPExtension() != '') {
                        $phpExtensions = '<b>'.$this->getTrans('phpExtensions').':</b> '.$phpExtension.'<br /><br />';
                    }
                    $moduleInfo = '<b>'.$this->getTrans('name').':</b> '.$this->escape($content['name']).'<br />
                                   <b>'.$this->getTrans('version').':</b> '.$this->escape($module->getVersion()).'<br />
                                   <b>'.$this->getTrans('author').':</b> '.$author.'<br /><br />
                                   <b>'.$this->getTrans('requirements').'</b><br />
                                   <b>'.$this->getTrans('ilchCoreVersion').':</b> '.$ilchCore.'<br />
                                   <b>'.$this->getTrans('phpVersion').':</b> '.$phpVersion.'<br />
                                   '.$phpExtensions.'
                                   <b>'.$this->getTrans('dependencies').':</b><br />';
                    foreach ($module->getDepends() as $key => $value) {
                        $moduleInfo .= $key.' '. str_replace(',','', $value).'<br />';
                    }
                    $moduleInfo .= '<br /><b>'.$this->getTrans('desc').':</b><br />'.$content['description'];
                    ?>
                    <?=$this->getDialog('infoModal'.$module->getKey(), $this->getTrans('menuModules').' '.$this->getTrans('info'), $moduleInfo); ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <?=$this->getTrans('noNotInstalledModules') ?>
<?php endif; ?>
