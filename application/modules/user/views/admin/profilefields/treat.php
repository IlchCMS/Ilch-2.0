<?php
$profileField = $this->get('profileField');

if ($profileField->getId()) {
    $fieldsetLegend = $this->getTrans('editProfileField');
} else {
    $fieldsetLegend = $this->getTrans('addProfileField');
}
?>

<fieldset>
    <legend><?=$fieldsetLegend ?></legend>
    <form action="<?=$this->getUrl(['module' => 'user', 'controller' => 'profilefields', 'action' => 'save']) ?>"
          method="POST"
          class="form-horizontal"
          id="profileFieldForm">
        <?=$this->getTokenField() ?>
        <input name="profileField[id]"
               type="hidden"
               value="<?=$profileField->getId() ?>" />
        <div class="form-group">
            <label for="profileFieldName"
                   class="col-lg-3 control-label">
                <?=$this->getTrans('profileFieldName') ?>
            </label>
            <div class="col-lg-9">
                <input name="profileField[name]"
                       type="text"
                       id="profileFieldName"
                       class="form-control required"
                       placeholder="<?=$this->getTrans('profileFieldName') ?>"
                       value="<?=$this->escape($profileField->getName()) ?>" />
            </div>
        </div>
        <?=$this->getSaveBar() ?>
    </form>
</fieldset>

<script>
$('#profileFieldForm').validate();
</script>
