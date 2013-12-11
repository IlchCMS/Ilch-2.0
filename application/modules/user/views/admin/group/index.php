<?php
/**
 * Viewfile for the grouplist.
 *
 * @copyright Ilch 2.0
 * @package ilch
 */

if ($this->get('groupList') != '') {
?>
    <table class="table table-hover table-striped">
        <colgroup>
            <col class="col-lg-1" >
            <col class="col-lg-2" >
            <col />
        </colgroup>
        <thead>
            <tr>
                <th><?php echo $this->trans('treat'); ?></th>
                <th><?php echo $this->trans('groupName'); ?></th>
                <th><?php echo $this->trans('groupAssignedUsers'); ?></th>
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
                        <span class="editGroup clickable fa fa-edit"
                              data-clickurl="<?php echo $this->url(array('module' => 'user', 'controller' => 'group', 'action' => 'treat', 'id' => $group->getId())); ?>"
                              title="<?php echo $this->trans('editGroup'); ?>"></span>
                        <span class="deleteGroup clickable fa fa-times-circle"
                              data-clickurl="<?php echo $this->url(array('module' => 'user', 'controller' => 'group', 'action' => 'delete', 'id' => $group->getId())); ?>"
                              data-toggle="modal"
                              data-target="#deleteModal"
                              data-modaltext="<?php echo $this->escape($this->trans('askIfDeleteGroup', $group->getName())); ?>"
                              title="<?php echo $this->trans('deleteGroup'); ?>"></span>
                    </td>
                    <td><?php echo $this->escape($group->getName()); ?></td>
                    <td><?php echo count($assignedUsers); ?></td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
    <script>
    $('.editGroup').on('click', function(event) {
        window.location = $(this).data('clickurl');
    });

    $('.deleteGroup').on('click', function(event) {
        $('#modalButton').data('clickurl', $(this).data('clickurl'));
        $('#modalText').html($(this).data('modaltext'));
    });

    $('#modalButton').on('click', function(event) {
        window.location = $(this).data('clickurl');
    });
    </script>
<?php
} else {
    echo $this->trans('noGroupsExist');
}
?>