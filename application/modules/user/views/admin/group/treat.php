<?php
$group = $this->get('group');

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
    <div class="form-group">
        <label for="groupName" class="col-lg-3 control-label">
            <?=$this->getTrans('groupName') ?>
        </label>
        <div class="col-lg-9">
            <input type="text"
                   class="form-control required"
                   id="groupName"
                   name="group[name]"
                   placeholder="<?=$this->getTrans('groupName') ?>"
                   value="<?=$this->escape($group->getName()) ?>" />
        </div>
    </div>
    <?=$this->getSaveBar() ?>
</form>

<script>
$('#groupForm').validate();
</script>
