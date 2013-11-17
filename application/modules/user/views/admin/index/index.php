<?php
if ($this->get('userList') != '') {
?>
    <?php
    if ($this->get('showDelUserMsg')) {
        ?>
        <div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <?php echo $this->trans('delUserMsg'); ?>
        </div>
        <?php
    }
    ?>
    <div class="panel panel-default">
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
                    <th></th>
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
                            <span class="editUser clickable glyphicon glyphicon-edit"
                                  data-clickurl="<?php echo $this->url(array('module' => 'user', 'controller' => 'index', 'action' => 'treat', 'id' => $user->getId())); ?>"
                                  title="<?php echo $this->trans('editUser'); ?>"></span>
                            <span class="deleteUser clickable glyphicon glyphicon-remove"
                                  data-clickurl="<?php echo $this->url(array('module' => 'user', 'controller' => 'index', 'action' => 'delete', 'id' => $user->getId())); ?>"
                                  data-toggle="modal"
                                  data-target="#deleteModal"
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
    </div>
    <div class="modal fade"
         id="deleteModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button"
                            class="close"
                            data-dismiss="modal"
                            aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><?php echo $this->trans('needAcknowledgement'); ?></h4>
                </div>
                <div class="modal-body">
                    <p><?php echo $this->trans('askIfDeleteUser', $this->escape($user->getName())); ?></p>
                </div>
                <div class="modal-footer">
                    <button type="button"
                            class="btn btn-primary"
                            id="deleteUserButton"><?php echo $this->trans('ack'); ?></button>
                    <button type="button"
                            class="btn btn-primary"
                            data-dismiss="modal"><?php echo $this->trans('cancel'); ?></button>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
    $('.editUser').on('click', function(event) {
        window.location = $(this).data('clickurl');
    });

    $('.deleteUser').on('click', function(event) {
        $('#deleteUserButton').data('clickurl', $(this).data('clickurl'));
    });

    $('#deleteUserButton').on('click', function(event) {
        window.location = $(this).data('clickurl');
    });
    </script>
<?php
} else {
    echo $this->trans('noUsersExist');
}
?>