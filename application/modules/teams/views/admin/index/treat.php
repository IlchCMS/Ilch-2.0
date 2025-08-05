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
    <?=$this->getTrans($team->getId() ? 'edit' : 'add') ?>
    <a class="badge rounded-pill bg-secondary" data-bs-toggle="modal" data-bs-target="#infoModal">
        <i class="fa-solid fa-info"></i>
    </a>
</h1>
<form method="POST" enctype="multipart/form-data">
    <?=$this->getTokenField() ?>
    <div class="row mb-3<?=$this->validation()->hasError('name') ? ' has-error' : '' ?>">
        <label for="name" class="col-xl-2 col-form-label">
            <?=$this->getTrans('teamName') ?>
        </label>
        <div class="col-xl-4">
            <input type="text"
                   class="form-control"
                   id="name"
                   name="name"
                   value="<?=$this->originalInput('name', $team->getName(), true) ?>" />
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('upl') ? ' has-error' : '' ?>">
        <label for="upl" class="col-xl-2 col-form-label">
            <?=$this->getTrans('img') ?>:
        </label>
        <div class="col-lg-4">
            <?php if (file_exists($team->getImg())) : ?>
                <div class="col-xl-12 mb-3">
                    <img src="<?=$this->getBaseUrl($team->getImg()) ?>" alt="<?=$this->getTrans('img') . ' ' . $this->escape($team->getName()) ?>">
                    <label for="image_delete" style="margin-left: 10px; margin-top: 10px;">
                        <input type="checkbox" id="image_delete" name="image_delete"> <?=$this->getTrans('imageDelete') ?>
                    </label>
                </div>
            <?php endif; ?>
            <div class="col-lg-12">
                <input class="form-control" type="file" id="upl" name="img" accept="image/*">
            </div>
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('leader') ? ' has-error' : '' ?>">
        <label for="leader" class="col-xl-2 col-form-label">
            <?=$this->getTrans('leader') ?>
        </label>
        <div class="col-xl-4">
            <select class="choices-select form-control"
                    id="leader"
                    name="leader[]"
                    data-placeholder="<?=$this->getTrans('selectLeader') ?>"
                    multiple>
                <?php
                $leaderIds = $this->originalInput('leader', explode(',', $team->getLeader()) ?? []);
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
    <div class="row mb-3<?=$this->validation()->hasError('coLeader') ? ' has-error' : '' ?>">
        <label for="coLeader" class="col-xl-2 col-form-label">
            <?=$this->getTrans('coLeader') ?>
        </label>
        <div class="col-xl-4">
            <select class="choices-select form-control"
                    id="coLeader"
                    name="coLeader[]"
                    data-placeholder="<?=$this->getTrans('selectCoLeader') ?>"
                    multiple>
                <?php
                $coLeaderIds = $this->originalInput('coLeader', explode(',', $team->getCoLeader()) ?? []);
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
    <div class="row mb-3<?=$this->validation()->hasError('members') ? ' has-error' : '' ?>">
        <label for="groupId" class="col-xl-2 col-form-label">
            <?=$this->getTrans('group') ?>
        </label>
        <div class="col-xl-4">
            <select class="form-select" id="groupId" name="groupId">
                <optgroup label="<?=$this->getTrans('groups') ?>">
                    <?php
                    /** @var \Modules\User\Models\Group $group */
                    ?>
                    <?php foreach ($userGroupList ?? [] as $group) : ?>
                        <?php if ($group->getId() != 3) : ?>
                            <option <?=($this->originalInput('groupId', $team->getGroupId()) == $group->getId() ? 'selected="selected"' : '') ?> value="<?=$group->getId() ?>"><?=$this->escape($group->getName()) ?></option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </optgroup>
            </select>
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('optShow') ? ' has-error' : '' ?>">
        <label for="optShow" class="col-xl-2 col-form-label">
            <?=$this->getTrans('optShow') ?>:
        </label>
        <div class="col-xl-4">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" id="optShow-on" name="optShow" value="1" <?=($this->originalInput('optShow', $team->getOptShow()) ? 'checked="checked"' : '') ?> />
                <label for="optShow-on" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('yes') ?></label>
                <input type="radio" class="flipswitch-input" id="optShow-off" name="optShow" value="0" <?=(!$this->originalInput('optShow', $team->getOptShow()) ? 'checked="checked"' : '') ?> />
                <label for="optShow-off" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('no') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('optIn') ? ' has-error' : '' ?>">
        <label for="optIn" class="col-xl-2 col-form-label">
            <?=$this->getTrans('optIn') ?>:
        </label>
        <div class="col-xl-4">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" id="optIn-on" name="optIn" value="1" <?=($this->originalInput('optIn', $team->getOptIn()) ? 'checked="checked"' : '') ?> />
                <label for="optIn-on" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('yes') ?></label>
                <input type="radio" class="flipswitch-input" id="optIn-off" name="optIn" value="0" <?=(!$this->originalInput('optIn', $team->getOptIn()) ? 'checked="checked"' : '') ?> />
                <label for="optIn-off" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('no') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('notifyLeader') ? ' has-error' : '' ?>" id="notifyLeader">
        <label for="notifyLeader" class="col-xl-2 col-form-label">
            <?=$this->getTrans('notifyLeader') ?>:
        </label>
        <div class="col-xl-4">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" id="notifyLeader-on" name="notifyLeader" value="1" <?=($this->originalInput('notifyLeader', $team->getNotifyLeader()) ? 'checked="checked"' : '') ?> />
                <label for="notifyLeader-on" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('yes') ?></label>
                <input type="radio" class="flipswitch-input" id="notifyLeader-off" name="notifyLeader" value="0" <?=(!$this->originalInput('notifyLeader', $team->getNotifyLeader()) ? 'checked="checked"' : '') ?> />
                <label for="notifyLeader-off" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('no') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
        </div>
    </div>
    <?=$this->getSaveBar($team->getId() ? 'edit' : 'add') ?>
</form>

<?=$this->getDialog('infoModal', $this->getTrans('info'), $this->getTrans('teamUsersInfoText')) ?>

<script>
    $(document).ready(function() {
        new Choices('#leader', {
            ...choicesOptions,
            searchEnabled: true
        })

        new Choices('#coLeader', {
            ...choicesOptions,
            searchEnabled: true
        })
    });

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
