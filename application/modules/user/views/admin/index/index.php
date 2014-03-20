<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

if ($this->get('userList') != '') {
?>
<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField()?>
    <table class="table table-hover table-striped">
        <colgroup>
            <col class="icon_width" />
            <col class="icon_width">
            <col class="icon_width" />
            <col class="col-lg-2" />
            <col class="col-lg-2" />
            <col class="col-lg-2" />
            <col />
        </colgroup>
        <thead>
            <tr>
                <th><?=$this->getCheckAllCheckbox('check_users')?></th>
                <th></th>
                <th></th>
                <th><?php echo $this->getTrans('userName'); ?></th>
                <th><?php echo $this->getTrans('userEmail'); ?></th>
                <th><?php echo $this->getTrans('userDateCreated'); ?></th>
                <th><?php echo $this->getTrans('userGroups'); ?></th>
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
                    $groups = $this->getTrans('noGroupsAssigned');
                }

                $dateConfirmed = $user->getDateConfirmed();

                if ($dateConfirmed->getTimestamp() == 0) {
                    $dateConfirmed = $this->getTrans('notConfirmedYet');
                }

                $dateLastActivity = $user->getDateLastActivity();

                if ($dateLastActivity !== null && $dateLastActivity->getTimestamp() == 0) {
                    $dateLastActivity = $this->getTrans('neverLoggedIn');
                }
                ?>
                <tr>
                    <td>
                        <input value="<?=$user->getId()?>" type="checkbox" name="check_users[]" />
                    </td>
                    <td>
                        <?=$this->getEditIcon(array('action' => 'treat', 'id' => $user->getId()))?>
                    </td>
                    <td>
                        <?=$this->getDeleteIcon(array('action' => 'delete', 'id' => $user->getId()))?>
                    </td>
                    <td><?php echo $this->escape($user->getName()); ?></td>
                    <td><?php echo $this->escape($user->getEmail()); ?></td>
                    <td><?php echo $this->escape($user->getDateCreated()); ?></td>
                    <td><?php echo $this->escape($groups); ?></td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
    <?=$this->getListBar(array('delete' => 'delete'))?>
</form>
<?php
} else {
    echo $this->getTrans('noUsersExist');
}
?>