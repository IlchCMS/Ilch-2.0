<?php

/** @var \Ilch\View $this */
?>
<h1><?=$this->getTrans('settings') ?></h1>
<form method="POST">
    <?=$this->getTokenField() ?>
    <div class="row mb-3<?=$this->validation()->hasError('adminNotification') ? ' has-error' : '' ?>">
        <div class="col-xl-2 col-form-label">
            <?=$this->getTrans('adminNotification') ?>
        </div>
        <div class="col-xl-4">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" id="adminNotification-on" name="adminNotification" value="1" <?=($this->get('adminNotification') === '1') ? 'checked="checked"' : '' ?> />
                <label for="adminNotification-on" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('on') ?></label>
                <input type="radio" class="flipswitch-input" id="adminNotification-off" name="adminNotification" value="0" <?=($this->get('adminNotification') !== '1') ? 'checked="checked"' : '' ?> />
                <label for="adminNotification-off" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('off') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('userNotification') ? ' has-error' : '' ?>">
        <div class="col-xl-2 col-form-label">
            <?=$this->getTrans('userNotification') ?>
        </div>
        <div class="col-xl-4">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" id="userNotification-on" name="userNotification" value="1" <?=($this->get('userNotification') === '1') ? 'checked="checked"' : '' ?> />
                <label for="userNotification-on" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('on') ?></label>
                <input type="radio" class="flipswitch-input" id="userNotification-off" name="userNotification" value="0" <?=($this->get('userNotification') !== '1') ? 'checked="checked"' : '' ?> />
                <label for="userNotification-off" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('off') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
        </div>
    </div>
    <div id="notifyGroupsDiv" class="row mb-3<?=$this->validation()->hasError('notifyGroups') ? ' has-error' : '' ?>" <?=($this->get('userNotification') !== '1') ? 'hidden' : '' ?>>
        <label for="notifyGroups" class="col-xl-2 col-form-label">
            <?=$this->getTrans('notifyGroups') ?>
        </label>
        <div class="col-xl-4">
            <select class="choices-select form-control" id="notifyGroups" name="notifyGroups[]" data-placeholder="<?=$this->getTrans('selectAssignedGroups') ?>" multiple>
                <?php foreach ($this->get('userGroupList') as $groupList) : ?>
                    <option value="<?=$groupList->getId() ?>"<?=(in_array($groupList->getId(), $this->get('groups'))) ? ' selected' : '' ?>><?=$groupList->getName() ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <?=$this->getSaveBar() ?>
</form>

<script>
    $(document).ready(function() {
        new Choices('#notifyGroups', {
            ...choicesOptions,
            searchEnabled: true
        })
    });

    $('[name="userNotification"]').click(function () {
        if ($(this).val() == "1") {
            $('#notifyGroupsDiv').removeAttr('hidden');
        } else {
            $('#notifyGroupsDiv').attr('hidden', '');
        }
    });
</script>
