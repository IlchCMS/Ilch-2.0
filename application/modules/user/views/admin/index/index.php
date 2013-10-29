<?php
if ($this->get('userList') != '') {
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
                                  title="<?php echo $this->trans('deleteUser'); ?>"></span>
                        </td>
                        <td><?php echo $user->getName(); ?></td>
                        <td><?php echo $user->getEmail(); ?></td>
                        <td><?php echo $user->getDateCreated(); ?></td>
                        <td><?php echo $dateConfirmed; ?></td>
                        <td><?php echo $dateLastActivity; ?></td>
                        <td><?php echo $groups; ?></td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>
    <script type="text/javascript">
    $('.editUser').on('click', function(event) {
        window.location = $(this).data('clickurl');
    });
    $('.deleteUser').on('click', function(event) {
        window.location = $(this).data('clickurl');
    });
    </script>
<?php
} else {
    echo $this->trans('noUsersExist');
}
?>