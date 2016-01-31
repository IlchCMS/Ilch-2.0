<?php
$user = $this->get('user');

if ($user->getId()) {
    $fieldsetLegend = $this->getTrans('editUser');
} else {
    $fieldsetLegend = $this->getTrans('addUser');
}
?>

<fieldset>
    <legend><?=$fieldsetLegend ?></legend>
    <form action="<?=$this->getUrl(array('action' => 'treat')) ?>"
          method="POST"
          class="form-horizontal"
          id="userForm">
        <?=$this->getTokenField() ?>
        <input name="user[id]"
               type="hidden"
               value="<?=$user->getId() ?>" />
        <div class="form-group">
            <label for="userName"
                   class="col-lg-3 control-label">
                <?=$this->getTrans('userName') ?>
            </label>
            <div class="col-lg-9">
                <input name="user[name]"
                       type="text"
                       id="userName"
                       class="form-control required"
                       placeholder="<?=$this->getTrans('userName') ?>"
                       value="<?=$this->escape($user->getName()) ?>" />
            </div>
        </div>
        <div class="form-group">
            <label for="userEmail" class="col-lg-3 control-label">
                <?=$this->getTrans('userEmail') ?>
            </label>
            <div class="col-lg-9">
                <input name="user[email]"
                       type="text"
                       id="userEmail"
                       class="form-control required email"
                       placeholder="<?=$this->getTrans('userEmail') ?>"
                       value="<?=$this->escape($user->getEmail()) ?>" />
            </div>
        </div>
        <div class="form-group">
            <label for="userPassword" class="col-lg-3 control-label">
                <?=$this->getTrans('userPassword') ?>
            </label>
            <div class="col-lg-9">
                <input name="user[password]"
                       type="password"
                       class="form-control"
                       placeholder="<?=$this->getTrans('userPassword') ?>"
                       value="" />
            </div>
        </div>
        <?php
        if ($user->getId()) {
            $dateConfirmed = $user->getDateConfirmed();

            if ($dateConfirmed->getTimestamp() == 0) {
                $dateConfirmed = $this->getTrans('notConfirmedYet');
            }

            $dateLastActivity = $user->getDateLastActivity();

            if ($dateLastActivity->getTimestamp() == 0) {
                $dateLastActivity = $this->getTrans('neverLoggedIn');
            }
        ?>
            <div class="form-group">
                <label class="col-lg-3 control-label">
                    <?=$this->getTrans('userDateCreated') ?>
                </label>
                <div class="col-lg-9">
                    <p class="form-control-static"><?=$this->escape($user->getDateCreated()) ?></p>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label">
                    <?=$this->getTrans('userDateConfirmed') ?>
                </label>
                <div class="col-lg-9">
                    <p class="form-control-static"><?=$this->escape($dateConfirmed) ?></p>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label">
                    <?=$this->getTrans('userDateLastActivity') ?>
                </label>
                <div class="col-lg-9">
                    <p class="form-control-static"><?=$this->escape($dateLastActivity) ?></p>
                </div>
            </div>
            <?php
        }
        ?>
        <div class="form-group">
            <label for="usergallery_allowed" class="col-lg-3 control-label">
                <?=$this->getTrans('usergalleryAllowed') ?>:
            </label>
            <div class="col-lg-9">
                <div class="flipswitch">
                    <input type="radio" class="flipswitch-input" name="user[opt_gallery]" value="1" id="opt-gallery-yes" <?php if ($user->getOptGallery() == '1') { echo 'checked="checked"'; } ?> />  
                    <label for="opt-gallery-yes" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('yes') ?></label>
                    <input type="radio" class="flipswitch-input" name="user[opt_gallery]" value="0" id="opt-gallery-no" <?php if ($user->getOptGallery() != '1') { echo 'checked="checked"'; } ?> />  
                    <label for="opt-gallery-no" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('no') ?></label>
                    <span class="flipswitch-selection"></span>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="assignedGroups" class="col-lg-3 control-label">
                    <?=$this->getTrans('assignedGroups') ?>
            </label>
            <div class="col-lg-9">
                <select id="assignedGroups"
                        class="chosen-select form-control"
                        data-placeholder="<?=$this->getTrans('selectAssignedGroups') ?>"
                        multiple
                        name="user[groups][]">
                        <?php
                        foreach ($this->get('groupList') as $group) {
                            ?>
                            <option value="<?=$group->getId() ?>"
                                    <?php
                                    foreach ($user->getGroups() as $assignedGroup) {
                                        if ($group->getId() === $assignedGroup->getId()) {
                                            echo 'selected="selected"';
                                            break;
                                        }
                                    }
                                    ?>>
                                <?=$this->escape($group->getName()) ?>
                            </option>
                            <?php
                        }
                        ?>
                </select>
            </div>
        </div>
        <?=$this->getSaveBar() ?>
    </form>
</fieldset>

<script>
$('#assignedGroups').chosen();
$('#assignedGroups_chosen').css('width', '100%'); // Workaround for chosen resize bug.
$('#userForm').validate();
</script>
