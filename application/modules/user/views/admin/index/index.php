<?php
/**
 * Viewfile for the userlist.
 *
 * @copyright Ilch 2.0
 * @package ilch
 */

if ($this->get('userList') != '') {
?>
    <table class="table table-hover table-striped">
        <colgroup>
            <col />
            <col class="col-lg-2">
            <col />
            <col class="col-lg-2">
            <col class="col-lg-2">
            <col class="col-lg-2">
        </colgroup>
        <thead>
            <tr>
                <th><?php echo $this->trans('treat'); ?></th>
                <th><?php echo $this->trans('userName'); ?></th>
                <th><?php echo $this->trans('userEmail'); ?></th>
                <th><?php echo $this->trans('userDateCreated'); ?></th>
                <th><?php echo $this->trans('userDateConfirmed'); ?></th>
                <th><?php echo $this->trans('userDateLastActivity'); ?></th>
                <th><?php echo $this->trans('userGroups'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($this->get('userList') as $user) {
                $groups = '';

                foreach($user->getGroups() as $group) {
                    if ($groups != '') {
                        $groups .= ', ';
                    }

                    $groups .= $group->getName();
                }

                if ($groups === '') {
                    $groups = $this->trans('noGroupsAssigned');
                }

                $dateConfirmed = $user->getDateConfirmed();

                if ($dateConfirmed->getTimestamp() == 0) {
                    $dateConfirmed = $this->trans('notConfirmedYet');
                }

                $dateLastActivity = $user->getDateLastActivity();

                if ($dateLastActivity->getTimestamp() == 0) {
                    $dateLastActivity = $this->trans('neverLoggedIn');
                }
                ?>
                <tr>
                    <td>
                        <span class="editUser clickable fa fa-edit"
                              data-clickurl="<?php echo $this->url(array('module' => 'user', 'controller' => 'index', 'action' => 'treat', 'id' => $user->getId())); ?>"
                              title="<?php echo $this->trans('editUser'); ?>"></span>
                        <span class="deleteUser clickable fa fa-times-circle"
                              data-clickurl="<?php echo $this->url(array('module' => 'user', 'controller' => 'index', 'action' => 'delete', 'id' => $user->getId())); ?>"
                              data-toggle="modal"
                              data-target="#deleteModal"
                              data-modaltext="<?php echo $this->escape($this->trans('askIfDeleteUser', $user->getName())); ?>"
                              title="<?php echo $this->trans('deleteUser'); ?>"></span>
                    </td>
                    <td><?php echo $this->escape($user->getName()); ?></td>
                    <td><?php echo $this->escape($user->getEmail()); ?></td>
                    <td><?php echo $this->escape($user->getDateCreated()); ?></td>
                    <td><?php echo $this->escape($dateConfirmed); ?></td>
                    <td><?php echo $this->escape($dateLastActivity); ?></td>
                    <td><?php echo $this->escape($groups); ?></td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
    <script>
    $('.editUser').on('click', function(event) {
        window.location = $(this).data('clickurl');
    });

    $('.deleteUser').on('click', function(event) {
        $('#modalButton').data('clickurl', $(this).data('clickurl'));
        $('#modalText').html($(this).data('modaltext'));
    });

    $('#modalButton').on('click', function(event) {
        window.location = $(this).data('clickurl');
    });
    </script>
<?php
} else {
    echo $this->trans('noUsersExist');
}
?>