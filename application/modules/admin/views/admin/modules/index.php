<table class="table">
    <colgroup>
        <col class="col-lg-1" />
        <col class="col-lg-5" />
        <col class="col-lg-6" />
    </colgroup>
    <tr>
        <th><?=$this->getTrans('module')?></th>
        <th></th>
        <th></th>
    </tr>
<?php
foreach ($this->get('modules') as $module) {
    if($this->getUser()->hasAccess('module_'.$module->getId())) {
?>
    <tr>
        <td>
            <img src="<?=$this->getStaticUrl('../application/modules/'.$module->getKey().'/config/'.$module->getIconSmall())?>" />
            <?=$module->getName($this->getTranslator()->getLocale())?>
        </td>
        <td>
            <a href="<?=$this->getUrl(array('module' => $module->getKey(), 'controller' => 'index', 'action' => 'index'))?>">
                <?=$this->getTrans('treat')?>
            </a>
        </td>
        <td>
            <a class="pull-right" href="<?=$this->getUrl(array('action' => 'delete', 'key' => $module->getKey()), null, true)?>">
                <?=$this->getTrans('delete')?>
            </a>
        </td>
    </tr>
<?php
    }
}
?>
</table>