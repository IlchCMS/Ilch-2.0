<?php if ($this->get('userList') != ''): ?>
    <form class="form-horizontal" method="POST" action="">
        <?=$this->getTokenField() ?>
        <div class="table-responsive">
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
                        <th><?=$this->getCheckAllCheckbox('check_users') ?></th>
                        <th></th>
                        <th></th>
                        <th><?=$this->getTrans('userName') ?></th>
                        <th><?=$this->getTrans('userEmail') ?></th>
                        <th><?=$this->getTrans('userDateCreated') ?></th>
                        <th><?=$this->getTrans('userGroups') ?></th>
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
                                <?=$this->getEditIcon(array('action' => 'treat', 'id' => $user->getId())) ?>
                            </td>
                            <td>
                                <?=$this->getDeleteIcon(array('action' => 'delete', 'id' => $user->getId())) ?>
                            </td>
                            <td><?=$this->escape($user->getName()) ?></td>
                            <td><?=$this->escape($user->getEmail()) ?></td>
                            <td><?=$this->escape($user->getDateCreated()) ?></td>
                            <td><?=$this->escape($groups) ?></td>
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
    <?=$this->getTrans('noUsersExist') ?>
<?php endif; ?>
