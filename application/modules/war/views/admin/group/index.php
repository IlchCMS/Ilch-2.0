<legend><?=$this->getTrans('manageGroups'); ?></legend>
<?php
if ($this->get('groups') != '') {
?>
<form class="form-horizontal" method="POST" action="">
<?=$this->getTokenField()?>
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <colgroup>
                <col class="icon_width">
                <col class="icon_width">
                <col class="icon_width">
                <col class="col-lg-2">
                <col class="col-lg-1">
                <col />
            </colgroup>
            <thead>
                <tr>
                    <th><?=$this->getCheckAllCheckbox('check_groups')?></th>
                    <th></th>
                    <th></th>
                    <th><?=$this->getTrans('groupsName'); ?></th>
                    <th><?=$this->getTrans('groupsTag'); ?></th>
                    <th><?=$this->getTrans('groupsImage'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($this->get('groups') as $group) : ?>
                    <tr>
                        <td><input value="<?=$group->getId()?>" type="checkbox" name="check_groups[]" /></td>
                        <td>
                            <?=$this->getEditIcon(array('action' => 'treat', 'id' => $group->getId())); ?>
                        </td>
                        <td>
                            <?php $deleteArray = array('action' => 'del', 'id' => $group->getId()); ?>
                            <?=$this->getDeleteIcon($deleteArray)?>
                        </td>
                        <td>
                            <?=$this->escape($group->getGroupName()); ?>
                        </td>
                        <td>
                            <?=$this->escape($group->getGroupTag()); ?>
                        </td>
                        <td>
                            <img class="group-image" src="<?=$this->getBaseUrl($this->escape($group->getGroupImage())); ?>" />
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php
    $actions = array('delete' => 'delete');

    echo $this->getListBar($actions);
    ?>
</form>
<?php
} else {
    echo $this->getTranslator()->trans('noGroup');
}
?>
<style>
    .group-image{
        max-width: 100px;
        height: 50px;
        margin: -8px;
    }
</style>
