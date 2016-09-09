<style>
.group-image {
    max-width: 100px;
    height: 50px;
    margin: -8px;
}
</style>

<legend><?=$this->getTrans('manageGroups') ?></legend>
<?php if ($this->get('groups') != ''): ?>
    <?=$this->get('pagination')->getHtml($this, []) ?>
    <form class="form-horizontal" method="POST" action="">
        <?=$this->getTokenField() ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <colgroup>
                    <col class="icon_width">
                    <col class="icon_width">
                    <col class="icon_width">
                    <col class="col-lg-2">
                    <col class="col-lg-1">
                    <col>
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
                            <td><input type="checkbox" name="check_groups[]" value="<?=$group->getId() ?>" /></td>
                            <td><?=$this->getEditIcon(['action' => 'treat', 'id' => $group->getId()]) ?></td>
                            <td><?=$this->getDeleteIcon(['action' => 'del', 'id' => $group->getId()]) ?></td>
                            <td><?=$this->escape($group->getGroupName()) ?></td>
                            <td><?=$this->escape($group->getGroupTag()) ?></td>
                            <td><img class="group-image" src="<?=$this->getBaseUrl($group->getGroupImage()) ?>" /></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?=$this->get('pagination')->getHtml($this, []) ?>
        <?=$this->getListBar(['delete' => 'delete']) ?>
    </form>
<?php else: ?>
    <?=$this->getTranslator()->trans('noGroup') ?>
<?php endif; ?>
