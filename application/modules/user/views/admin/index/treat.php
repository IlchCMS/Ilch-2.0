<?php
/**
 * Viewfile for user creation and editing.
 *
 * @copyright Ilch 2.0
 * @package ilch
 */

$user = $this->get('user');

if ($user->getId()) {
    $fieldsetLegend = $this->trans('editUser');
}
else {
    $fieldsetLegend = $this->trans('addUser');
}
?>
<fieldset>
    <legend>
        <?php echo $fieldsetLegend; ?>
    </legend>
    <form action="<?php echo $this->url(array('module' => 'user', 'controller' => 'index', 'action' => 'save')); ?>"
          method="POST"
          class="form-horizontal"
          id="userForm">
        <?php echo $this->getTokenField(); ?>
        <input name="user[id]"
               type="hidden"
               value="<?php echo $user->getId(); ?>" />
        <div class="form-group">
            <label for="userName"
                   class="col-lg-3 control-label">
                <?php echo $this->trans('userName'); ?>
            </label>
            <div class="col-lg-9">
                <input name="user[name]"
                       type="text"
                       id="userName"
                       class="form-control required"
                       placeholder="<?php echo $this->trans('userName'); ?>"
                       value="<?php echo $this->escape($user->getName()); ?>" />
            </div>
        </div>
        <div class="form-group">
            <label for="userEmail" class="col-lg-3 control-label">
                <?php echo $this->trans('userEmail'); ?>
            </label>
            <div class="col-lg-9">
                <input name="user[email]"
                       type="text"
                       id="userEmail"
                       class="form-control required email"
                       placeholder="<?php echo $this->trans('userEmail'); ?>"
                       value="<?php echo $this->escape($user->getEmail()); ?>" />
            </div>
        </div>
        <?php
        if ($user->getId()) {
            $dateConfirmed = $user->getDateConfirmed();

            if ($dateConfirmed->getTimestamp() == 0) {
                $dateConfirmed = $this->trans('notConfirmedYet');
            }

            $dateLastActivity = $user->getDateLastActivity();

            if ($dateLastActivity->getTimestamp() == 0) {
                $dateLastActivity = $this->trans('neverLoggedIn');
            }
            ?>
            <div class="form-group">
                <label class="col-lg-3 control-label">
                    <?php echo $this->trans('userDateCreated'); ?>
                </label>
                <div class="col-lg-9">
                    <p class="form-control-static"><?php echo $this->escape($user->getDateCreated()); ?></p>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label">
                    <?php echo $this->trans('userDateConfirmed'); ?>
                </label>
                <div class="col-lg-9">
                    <p class="form-control-static"><?php echo $this->escape($dateConfirmed); ?></p>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label">
                    <?php echo $this->trans('userDateLastActivity'); ?>
                </label>
                <div class="col-lg-9">
                    <p class="form-control-static"><?php echo $this->escape($dateLastActivity); ?></p>
                </div>
            </div>
            <?php
        }
        ?>
        <div class="form-group">
            <label for="assignedGroups" class="col-lg-3 control-label">
                    <?php echo $this->trans('assignedGroups'); ?>
            </label>
            <div class="col-lg-9">
                <select id="assignedGroups"
                        class="chosen-select form-control"
                        data-placeholder="<?php echo $this->trans('selectAssignedGroups'); ?>"
                        multiple
                        name="user[groups][]">
                        <?php
                        foreach ($this->get('groupList') as $group) {
                            ?>
                            <option value="<?php echo $group->getId(); ?>"
                                    <?php
                                    foreach ($user->getGroups() as $assignedGroup) {
                                        if ($group->getId() === $assignedGroup->getId()) {
                                            echo 'selected="selected"';
                                            break;
                                        }
                                    }
                                    ?>>
                                <?php echo $this->escape($group->getName()); ?>
                            </option>
                            <?php
                        }
                        ?>
                </select>
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
    $('#assignedGroups').chosen();
    $('#assignedGroups_chosen').css('width', '100%'); // Workaround for chosen resize bug.
    $('#userForm').validate();
</script>