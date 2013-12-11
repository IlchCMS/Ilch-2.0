<?php
/**
 * Viewfile for group creation and editing.
 *
 * @copyright Ilch 2.0
 * @package ilch
 */

$group = $this->get('group');

if ($group->getId()) {
    $fieldsetLegend = $this->trans('editGroup');
}
else {
    $fieldsetLegend = $this->trans('addGroup');
}
?>
<fieldset>
    <legend>
        <?php echo $fieldsetLegend; ?>
    </legend>
    <form action="<?php echo $this->url(array('module' => 'user', 'controller' => 'group', 'action' => 'save')); ?>"
          method="POST"
          class="form-horizontal"
          id="groupForm">
        <?php echo $this->getTokenField(); ?>
        <input name="group[id]"
               type="hidden"
               value="<?php echo $group->getId(); ?>" />
        <div class="form-group">
            <label for="groupName"
                   class="col-lg-3 control-label">
                <?php echo $this->trans('groupName'); ?>
            </label>
            <div class="col-lg-9">
                <input name="group[name]"
                       type="text"
                       id="groupName"
                       class="form-control required"
                       placeholder="<?php echo $this->trans('groupName'); ?>"
                       value="<?php echo $this->escape($group->getName()); ?>" />
            </div>
        </div>
        <div class="content_savebox">
            <input id="formSubmit"
                   type="submit"
                   class="btn btn-default"
                   value="<?php echo $this->trans('save'); ?>" />
        </div>
    </form>
</fieldset>
<script>
    $('#groupForm').validate();
</script>