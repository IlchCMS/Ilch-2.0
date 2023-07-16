<?php

/** @var \Ilch\View $this */

/** @var \Modules\Teams\Models\Teams $team */
$team = $this->get('team');

/** @var \Modules\User\Models\User[]|null $userList */
$userList = $this->get('userList');
/** @var \Modules\User\Models\Group[]|null $userGroupList */
$userGroupList = $this->get('userGroupList');
?>
<link href="<?=$this->getModuleUrl('static/css/teams.css') ?>" rel="stylesheet">

<h1>
    <?=($team->getId()) ? $this->getTrans('edit') : $this->getTrans('add') ?>
    <a class="badge" data-toggle="modal" data-target="#infoModal">
        <i class="fa-solid fa-info"></i>
    </a>
</h1>
<form class="form-horizontal" method="POST" enctype="multipart/form-data">
    <?=$this->getTokenField() ?>
    <div class="form-group <?=$this->validation()->hasError('name') ? 'has-error' : '' ?>">
        <label for="name" class="col-lg-2 control-label">
            <?=$this->getTrans('teamName') ?>
        </label>
        <div class="col-lg-4">
            <input type="text"
                   class="form-control"
                   id="name"
                   name="name"
                   value="<?=$this->escape($this->originalInput('name', $team->getName())) ?>" />
        </div>
    </div>
    <div class="form-group <?=$this->validation()->hasError('upl') ? 'has-error' : '' ?>">
        <label for="upl" class="col-lg-2 control-label">
            <?=$this->getTrans('img') ?>:
        </label>
        <div class="col-lg-4">
            <div class="row">
                <?php if ($team->getImg() != '') : ?>
                    <div class="col-lg-12">
                        <img src="<?=$this->getBaseUrl($team->getImg()) ?>" alt="<?=$this->getTrans('img') . ' ' . $this->escape($team->getName()) ?>">

                        <label for="image_delete" style="margin-left: 10px; margin-top: 10px;">
                            <input type="checkbox" id="image_delete" name="image_delete"> <?=$this->getTrans('imageDelete') ?>
                        </label>
                    </div>
                <?php endif; ?>
                <div class="col-lg-12 input-group">
                    <span class="input-group-btn">
                        <span class="btn btn-primary btn-file">
                            Browse&hellip; <input type="file" name="img" accept="image/*">
                        </span>
                    </span>
                    <input type="text"
                           name="upl"
                           id="upl"
                           class="form-control"
                           readonly />
                </div>
            </div>
        </div>
    </div>
    <div class="form-group <?=$this->validation()->hasError('leader') ? 'has-error' : '' ?>">
        <label for="leader" class="col-lg-2 control-label">
            <?=$this->getTrans('leader') ?>
        </label>
        <div class="col-lg-4">
            <select class="chosen-select form-control"
                    id="leader"
                    name="leader[]"
                    data-placeholder="<?=$this->getTrans('selectLeader') ?>"
                    multiple>
                <?php
                $leaderIds = explode(',', $team->getLeader()) ?? [];
                /** @var \Modules\User\Models\User $user */
                ?>
                <?php foreach ($userList ?? [] as $user) : ?>
                    <option value="<?=$user->getId() ?>" <?=(in_array($user->getId(), $leaderIds) ? 'selected="selected"' : '') ?>>
                        <?=$this->escape($user->getName()) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="form-group <?=$this->validation()->hasError('coLeader') ? 'has-error' : '' ?>">
        <label for="coLeader" class="col-lg-2 control-label">
            <?=$this->getTrans('coLeader') ?>
        </label>
        <div class="col-lg-4">
            <select class="chosen-select form-control"
                    id="coLeader"
                    name="coLeader[]"
                    data-placeholder="<?=$this->getTrans('selectCoLeader') ?>"
                    multiple>
                <?php
                $coLeaderIds = explode(',', $team->getCoLeader()) ?? [];
                /** @var \Modules\User\Models\User $user */
                ?>
                <?php foreach ($userList ?? [] as $user) : ?>
                    <option value="<?=$user->getId() ?>" <?=(in_array($user->getId(), $coLeaderIds) ? 'selected="selected"' : '') ?>>
                        <?=$this->escape($user->getName()) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="form-group <?=$this->validation()->hasError('members') ? 'has-error' : '' ?>">
        <label for="groupId" class="col-lg-2 control-label">
            <?=$this->getTrans('group') ?>
        </label>
        <div class="col-lg-4">
            <select class="form-control" id="groupId" name="groupId">
                <optgroup label="<?=$this->getTrans('groups') ?>">
                    <?php
                    /** @var \Modules\User\Models\Group $group */
                    ?>
                    <?php foreach ($userGroupList ?? [] as $group) : ?>
                        <?php if ($group->getId() != 3) : ?>
                            <option <?=($team->getGroupId() == $group->getId() ? 'selected="selected"' : '') ?> value="<?=$group->getId() ?>"><?=$this->escape($group->getName()) ?></option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </optgroup>
            </select>
        </div>
    </div>
    <div class="form-group <?=$this->validation()->hasError('optShow') ? 'has-error' : '' ?>">
        <label for="optShow" class="col-lg-2 control-label">
            <?=$this->getTrans('optShow') ?>:
        </label>
        <div class="col-lg-4">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" id="optShow-on" name="optShow" value="1" <?=($this->originalInput('optShow', $team->getOptShow()) ? 'checked="checked"' : '') ?> />
                <label for="optShow-on" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('yes') ?></label>
                <input type="radio" class="flipswitch-input" id="optShow-off" name="optShow" value="0" <?=(!$this->originalInput('optShow', $team->getOptShow()) ? 'checked="checked"' : '') ?> />
                <label for="optShow-off" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('no') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
        </div>
    </div>
    <div class="form-group <?=$this->validation()->hasError('optIn') ? 'has-error' : '' ?>">
        <label for="optIn" class="col-lg-2 control-label">
            <?=$this->getTrans('optIn') ?>:
        </label>
        <div class="col-lg-4">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" id="optIn-on" name="optIn" value="1" <?=($this->originalInput('optIn', $team->getOptIn()) ? 'checked="checked"' : '') ?> />
                <label for="optIn-on" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('yes') ?></label>
                <input type="radio" class="flipswitch-input" id="optIn-off" name="optIn" value="0" <?=(!$this->originalInput('optIn', $team->getOptIn()) ? 'checked="checked"' : '') ?> />
                <label for="optIn-off" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('no') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
        </div>
    </div>
    <div class="form-group <?=$this->validation()->hasError('notifyLeader') ? 'has-error' : '' ?>" id="notifyLeader">
        <label for="notifyLeader" class="col-lg-2 control-label">
            <?=$this->getTrans('notifyLeader') ?>:
        </label>
        <div class="col-lg-4">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" id="notifyLeader-on" name="notifyLeader" value="1" <?=($this->originalInput('notifyLeader', $team->getNotifyLeader()) ? 'checked="checked"' : '') ?> />
                <label for="notifyLeader-on" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('yes') ?></label>
                <input type="radio" class="flipswitch-input" id="notifyLeader-off" name="notifyLeader" value="0" <?=(!$this->originalInput('notifyLeader', $team->getNotifyLeader()) ? 'checked="checked"' : '') ?> />
                <label for="notifyLeader-off" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('no') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
        </div>
    </div>
    <?=($team->getId()) ? $this->getSaveBar('edit') : $this->getSaveBar('add') ?>
</form>

<?=$this->getDialog('infoModal', $this->getTrans('info'), $this->getTrans('teamUsersInfoText')) ?>

<script>
$('#leader').chosen();
$('#coLeader').chosen();

$(document).on('change', '.btn-file :file', function() {
    let input = $(this),
        numFiles = input.get(0).files ? input.get(0).files.length : 1,
        label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
    input.trigger('fileselect', [numFiles, label]);
});

$(document).ready( function() {
    $('.btn-file :file').on('fileselect', function(event, numFiles, label) {
        let input = $(this).parents('.input-group').find(':text'),
            log = numFiles > 1 ? numFiles + ' files selected' : label;

        if (input.length) {
            input.val(log);
        } else {
            if (log) alert(log);
        }
    });
});

$('#notifyLeader-on').change(function() {
    $('#optIn-on').prop('checked', true);
});

$('#optIn-off').change(function() {
    $('#notifyLeader-off').prop('checked', true);
});
</script>
