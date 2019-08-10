<?php if ($this->get('groupList') != ''): ?>
    <form class="form-horizontal" method="POST">
        <?=$this->getTokenField() ?>
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <colgroup>
                    <col class="icon_width">
                    <col class="icon_width">
                    <col class="icon_width">
                    <col class="col-lg-2">
                    <col>
                </colgroup>
                <thead>
                    <tr>
                        <th><input type="checkbox" class="check_all" data-childs="check_groups" /></th>
                        <th></th>
                        <th></th>
                        <th><?=$this->getTrans('groupName') ?></th>
                        <th><?=$this->getTrans('groupAssignedUsers') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $groupUsers = $this->get('groupUsersList');

                    foreach ($this->get('groupList') as $group) {
                        $assignedUsers = $groupUsers[$group->getId()];
                        ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="check_groups[]" value="<?=$group->getId() ?>" />
                            </td>
                            <td>
                                <?=((($this->getUser()->isAdmin() and $group->getId() == 1) or $group->getId() != 1)?$this->getEditIcon(['action' => 'treat', 'id' => $group->getId()]):'') ?>
                            </td>
                            <td>
                                <?=((($this->getUser()->isAdmin() and $group->getId() == 1) or $group->getId() != 1)?$this->getDeleteIcon(['action' => 'delete', 'id' => $group->getId()]):'') ?>
                            </td>
                            <td><?=$this->escape($group->getName()) ?></td>
                            <td><?=count($assignedUsers) ?></td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <?=$this->getListBar(['delete' => 'delete']) ?>
    </form>
<?php else: ?>
    <?=$this->getTrans('noGroupsExist') ?>
<?php endif; ?>
