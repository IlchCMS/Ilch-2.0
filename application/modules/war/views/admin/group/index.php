<style>
.group-image{
    max-width: 100px;
    height: 50px;
    margin: -8px;
}
</style>

<legend><?=$this->getTrans('manageGroups') ?></legend>
<?php if ($this->get('groups') != ''): ?>
    <?=$this->get('pagination')->getHtml($this, array()) ?>
    <form class="form-horizontal" method="POST" action="">
        <?=$this->getTokenField()?>
        <table class="table table-striped table-hover table-responsive">
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
                    <th><?=$this->getCheckAllCheckbox('check_groups') ?></th>
                    <th></th>
                    <th></th>
                    <th><?=$this->getTrans('groupsName') ?></th>
                    <th><?=$this->getTrans('groupsTag') ?></th>
                    <th><?=$this->getTrans('groupsImage') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($this->get('groups') as $group): ?>
                    <tr>
                        <td><input value="<?=$group->getId()?>" type="checkbox" name="check_groups[]" /></td>
                        <td><?=$this->getEditIcon(array('action' => 'treat', 'id' => $group->getId())) ?></td>
                        <td><?=$this->getDeleteIcon(array('action' => 'del', 'id' => $group->getId())) ?></td>
                        <td><?=$group->getGroupName() ?></td>
                        <td><?=$group->getGroupTag() ?></td>
                        <td><img class="group-image" src="<?=$this->getBaseUrl($group->getGroupImage()) ?>" /></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?=$this->get('pagination')->getHtml($this, array()) ?>
        <?=$this->getListBar(array('delete' => 'delete')) ?>
    </form>
<?php else: ?>
    <?=$this->getTranslator()->trans('noGroup') ?>
<?php endif; ?>
