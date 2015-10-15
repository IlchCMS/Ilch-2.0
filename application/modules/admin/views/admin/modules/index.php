<table class="table table-hover">
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
    <?php foreach ($this->get('modules') as $module): ?>
        <?php $content = $module->getContentForLocale($this->getTranslator()->getLocale()); ?>
        <?php if($this->getUser()->hasAccess('module_'.$module->getKey()) && !$module->getSystemModule()): ?>
            <tbody>
                <tr>
                    <td>
                        <br />
                        <img src="<?=$this->getStaticUrl('../application/modules/'.$module->getKey().'/config/'.$module->getIconSmall()) ?>" />
                        <?=$content['name'] ?>
                        <br /><br />
                        <small><?=$this->getTrans('author')?>: <?=$module->getAuthor() ?></small>
                        <br />
                        <a href="<?=$this->getUrl(array('module' => $module->getKey(), 'controller' => 'index', 'action' => 'index')) ?>">
                            <?=$this->getTrans('administrate') ?>
                        </a>
                        <?php if(!isset($config->isSystemModule)): ?>
                            <small>| <a class="delete_button" href="<?=$this->getUrl(array('action' => 'delete', 'key' => $module->getKey()), null, true) ?>">
                                <?=$this->getTrans('delete') ?>
                            </a></small>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if (!empty($content['description'])): ?>
                            <?=$content['description'] ?>
                        <?php endif; ?>
                    </td>
                </tr>
            </tbody>
        <?php endif; ?>
    <?php endforeach; ?>
</table>
