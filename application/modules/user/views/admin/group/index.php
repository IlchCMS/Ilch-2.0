<?php if ($this->get('groupList') != ''): ?>
    <form class="form-horizontal" method="POST" action="">
        <?=$this->getTokenField() ?>
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <colgroup>
                    <col class="icon_width" >
                    <col class="icon_width" >
                    <col class="icon_width" >
                    <col class="col-lg-2" />
                    <col />
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
                                <input value="<?=$group->getId()?>" type="checkbox" name="check_groups[]" />
                            </td>
                            <td>
                                <?=$this->getEditIcon(array('action' => 'treat', 'id' => $group->getId())) ?>
                            </td>
                            <td>
                                <?=$this->getDeleteIcon(array('action' => 'delete', 'id' => $group->getId())) ?>
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
        <?=$this->getListBar(array('delete' => 'delete')) ?>
    </form>
<?php else: ?>
    <?=$this->getTrans('noGroupsExist') ?>
<?php endif; ?>
