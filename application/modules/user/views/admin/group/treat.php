<?php
$group = $this->get('group');

if ($group->getId()) {
    $fieldsetLegend = $this->getTrans('editGroup');
} else {
    $fieldsetLegend = $this->getTrans('addGroup');
}
?>

<fieldset>
    <legend><?=$fieldsetLegend ?></legend>
    <form action="<?=$this->getUrl(['module' => 'user', 'controller' => 'group', 'action' => 'save']) ?>"
          method="POST"
          class="form-horizontal"
          id="groupForm">
        <?=$this->getTokenField() ?>
        <input name="group[id]"
               type="hidden"
               value="<?=$group->getId() ?>" />
        <div class="form-group">
            <label for="groupName"
                   class="col-lg-3 control-label">
                <?=$this->getTrans('groupName') ?>
            </label>
            <div class="col-lg-9">
                <input name="group[name]"
                       type="text"
                       id="groupName"
                       class="form-control required"
                       placeholder="<?=$this->getTrans('groupName') ?>"
                       value="<?=$this->escape($group->getName()) ?>" />
            </div>
        </div>
        <?=$this->getSaveBar() ?>
    </form>
</fieldset>

<script>
$('#groupForm').validate();
</script>
