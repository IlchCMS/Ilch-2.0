<legend><?=$this->getTrans('modulesInstalled') ?></legend>
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
            <?php foreach ($this->get('modules') as $module):
                $content = $module->getContentForLocale($this->getTranslator()->getLocale());
                if (substr($module->getIconSmall(), 0, 3) == 'fa-') {
                    $smallIcon = '<i class="fa '.$module->getIconSmall().'" style="padding-right: 5px;"></i>';
                } else {
                    $smallIcon = '<img style="padding-right: 5px;" src="'.$this->getStaticUrl('../application/modules/'.$module->getKey().'/config/'.$module->getIconSmall()).'" />';
                }

                if ($this->getUser()->hasAccess('module_'.$module->getKey()) && !$module->getSystemModule()): ?>
                    <tr>
                        <td>
                            <?=$smallIcon.$content['name'] ?>
                            <br />
                            <small><?=$this->getTrans('author') ?>: <?=$module->getAuthor() ?></small>
                            <br /><br />
                            <a href="<?=$this->getUrl(['module' => $module->getKey(), 'controller' => 'index', 'action' => 'index']) ?>"><?=$this->getTrans('administrate') ?></a>
                            <?php if (!isset($config->isSystemModule)): ?>
                                <small>
                                    | 
                                    <a href="<?=$this->getUrl(['action' => 'uninstall', 'key' => $module->getKey()], null, true) ?>"><?=$this->getTrans('uninstall') ?></a>
                                </small>
                            <?php endif; ?>
                        </td>
                        <td><?=$content['description'] ?></td>
                    </tr>
                <?php endif; ?>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
