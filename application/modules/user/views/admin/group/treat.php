<?php
$group = $this->get('group');
$userMapper = $this->get('userMapper');

if ($group->getId()) {
    $fieldsetLegend = $this->getTrans('editGroup');
} else {
    $fieldsetLegend = $this->getTrans('addGroup');
}
?>

<h1><?=$fieldsetLegend ?></h1>
<form action="<?=$this->getUrl(['module' => 'user', 'controller' => 'group', 'action' => 'save']) ?>" method="POST" class="form-horizontal" id="groupForm">
    <?=$this->getTokenField() ?>
    <input type="hidden"
           name="group[id]"
           value="<?=$group->getId() ?>" />
    <div class="row mb-3">
        <label for="groupName" class="col-xl-3 control-label">
            <?=$this->getTrans('groupName') ?>
        </label>
        <div class="col-xl-9">
            <input type="text"
                   class="form-control required"
                   id="groupName"
                   name="group[name]"
                   placeholder="<?=$this->getTrans('groupName') ?>"
                   value="<?=$this->escape($group->getName()) ?>" />
        </div>
    </div>
    <div class="row mb-3">
        <table class="table table-borderless">
            <colgroup><col class="col-xl-6">
                <col class="col-xl-6">
                <col>
            </colgroup>
            <thead>
                <tr>
                    <th class="th-lg table-light"><?=$this->getTrans('users') ?></th>
                    <th class="th-lg table-light"><?=$this->getTrans('assignedUsers') ?></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                    <div class="unassigned_users_list" id="unassigned_users_list">
                        <ol id="unassigned_users" class="sortable connectedSortable">
                        <?php foreach ($this->get('UsersList') as $user): ?>
                            <?php if (!in_array($user->getId(), $this->get('groupUsersList'))): ?>
                            <li class="handle_li" value="<?=$user->getId() ?>"><div><span class="fa-solid fa-sort"></span> <?=$user->getName() ?></div></li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        </ol>
                    </div>
                    </td>
                    <td>
                        <div class="assigned_users_list" id="assigned_users_list">
                            <ol id="assigned_users" class="sortable connectedSortable">
                            <?php foreach ($this->get('groupUsersList') as $user_Id): ?>
                                <?php $user = $userMapper->getUserById($user_Id); ?>
                                <?php if (!$user) {
    $user = $userMapper->getDummyUser();
} ?>
                                <li class="handle_li" value="<?=$user_Id ?>"><div><span class="fa-solid fa-sort"></span> <?=$user->getName() ?></div></li>
                            <?php endforeach; ?>
                            </ol>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <input type="hidden" id="hiddenMenu" name="hiddenMenu" value="" />
    <?=$this->getSaveBar() ?>
</form>

<script>
$('#groupForm').validate();
$(document).ready (function () {
    $('#groupForm').submit (function () {
        $('#hiddenMenu').val(JSON.stringify($('#assigned_users').sortable('toArray', {attribute: 'value'})));
    });

    $('#unassigned_users, #assigned_users').sortable({
        connectWith: ".connectedSortable",
        dropOnEmpty: true,
        placeholder: 'placeholder'
    }).disableSelection();
});

//attach on load
$(function() {
   $(".handle_li").dblclick(function(){
       if( $(this).parent().attr("id") == "unassigned_users" ){
            $(this).detach().appendTo("#assigned_users");
        }
        else{
            $(this).detach().appendTo("#unassigned_users");
        }
   });
});
</script>
<style>
.connectedSortable{
    min-height: 50px;
}
</style>
