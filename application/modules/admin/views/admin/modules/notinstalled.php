<legend><?=$this->getTrans('modulesNotInstalled') ?></legend>
<?php if ($this->get('modulesNotInstalled') != ''): ?>
    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <colgroup>
                <col class="col-lg-2" />
                <col class="col-lg-10" />
            </colgroup>
            <thead>
                <tr>
                    <th><?=$this->getTrans('modules') ?></th>
                    <th><?=$this->getTrans('desc') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($this->get('modulesNotInstalled') as $module):
                    $content = $module->getContentForLocale($this->getTranslator()->getLocale());
                    if (substr($module->getIconSmall(), 0, 3) == 'fa-') {
                        $smallIcon = '<i class="fa '.$module->getIconSmall().'" style="padding-right: 5px;"></i>';
                    } else {
                        $smallIcon = '<img style="padding-right: 5px;" src="'.$this->getStaticUrl('../application/modules/'.$module->getKey().'/config/'.$module->getIconSmall()).'" />';
                    }
                ?>
                    <tr>
                        <td>
                            <?=$smallIcon.$content['name'] ?>
                            <br /><br />
                            <small><?=$this->getTrans('author')?>: <?=$module->getAuthor() ?></small>
                            <br />
                            <a class="install_button" href="<?=$this->getUrl(['action' => 'install', 'key' => $module->getKey()], null, true) ?>">
                                <?=$this->getTrans('installModule') ?>
                            </a>
                            <small>
                                | 
                                <a class="delete_button" href="<?=$this->getUrl(['action' => 'delete', 'key' => $module->getKey()], null, true) ?>">
                                    <?=$this->getTrans('delete') ?>
                                </a>
                            </small>
                        </td>
                        <td>
                            <?php if (!empty($content['description'])): ?>
                                <?=$content['description'] ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <?=$this->getTrans('noNotInstalledModules') ?>
<?php endif; ?>
