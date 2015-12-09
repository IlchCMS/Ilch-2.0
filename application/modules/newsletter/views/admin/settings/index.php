<?php
$userMapper = new \Modules\User\Mappers\User()
?>
<legend><?=$this->getTrans('settings') ?></legend>

    <form class="form-horizontal" method="POST" action="">
        <?=$this->getTokenField() ?>
        <div class="form-group">
            <div class="btn-group btn-group-justified" role="group" aria-label="...">
                <div class="btn-group" role="group">
                    <input type="button" name="all" class="btn btn-default <?php if ($this->get('newsletter_config') == 'all') { echo 'active'; } ?>" value="Alle"/>
                </div>
                <div class="btn-group" role="group">
                    <input type="button" name="group" class="btn btn-default <?php if ($this->get('newsletter_config') == 'group') { echo 'active'; } ?>" value="Gruppe"/>
                </div>
                <div class="btn-group" role="group">
                    <input type="button" name="user" class="btn btn-default <?php if ($this->get('newsletter_config') == 'user') { echo 'active'; } ?>" value="Einzelne User"/>
                </div>
            </div>
        </div>
        

        
        
        <div id="confirmGroup" <?php if ($this->get('newsletter_config') != 'group') { echo 'class="hidden"'; } ?>>
            
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
                            <td><?=$this->escape($group->getName()) ?></td>
                            <td><?=count($assignedUsers) ?></td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
            
        </div>
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        <div id="confirmUser" <?php if ($this->get('newsletter_config') != 'user') { echo 'class="hidden"'; } ?>>
            
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
                                <?php 
                                $checked = '';
                                if (!empty($this->get('emails'))){
                                    foreach ($this->get('emails') as $email) {
                                        if ($user->getEmail() == $email->getEmail()){
                                            $checked = 'checked';
                                        }
                                    }
                                }
                                ?>
                                <input value="<?=$user->getEmail()?>" type="checkbox" name="check_users[]" <?php echo $checked; ?>/>
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
            
        </div>
        
        
        <?=$this->getSaveBar() ?>
    </form>


<script>
$('[name="all"]').click(function () {
    $('#confirmGroup').addClass('hidden');
    $('#confirmUser').addClass('hidden');
});
$('[name="group"]').click(function () {
    $('#confirmGroup').removeClass('hidden');
    $('#confirmUser').addClass('hidden');
});
$('[name="user"]').click(function () {
    $('#confirmUser').removeClass('hidden');
    $('#confirmGroup').addClass('hidden');
});
</script>