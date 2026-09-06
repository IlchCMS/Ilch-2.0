<?php

/** @var \Ilch\View $this */

/** @var Modules\Kvteam\Models\Team $team */
$team = $this->get('team');
?>
<link href="<?=$this->getModuleUrl('static/css/team.css') ?>" rel="stylesheet">

<h1><?=$this->getTrans($team->getId() ? 'edit' : 'add') ?></h1>
<form method="POST" action="" enctype="multipart/form-data">
    <?=$this->getTokenField() ?>
    <div class="row mb-3<?=$this->validation()->hasError('title') ? ' has-error' : '' ?>">
        <label for="title" class="col-xl-2 col-form-label">
            <?=$this->getTrans('title') ?>
        </label>
        <div class="col-xl-4">
            <input type="text"
                   class="form-control"
                   id="title"
                   name="title"
                   value="<?=$this->escape($this->originalInput('title', $team->getTitle())) ?>" />
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('userIds') ? ' has-error' : '' ?>">
        <label for="userIds" class="col-xl-2 col-form-label">
            <?=$this->getTrans('members') ?>
        </label>
        <div class="col-xl-4">
            <select class="choices-select form-control"
                    id="userIds"
                    name="userIds[]"
                    data-placeholder="<?=$this->getTrans('selectMembers') ?>"
                    multiple>
                <?php foreach ($this->get('userList') as $userList) :
                    $userIds = explode(',', $team->getUserIds());
                    ?>
                    <option value="<?=$userList->getId() ?>" <?=(in_array($userList->getId(), $userIds) ? 'selected="selected"' : '') ?>>
                        <?=$this->escape($userList->getName()) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <?=$this->getSaveBar($team->getId() ? 'edit' : 'add') ?>
</form>

<script>
    $(document).ready(function() {
        new Choices('#userIds', {
            ...choicesOptions,
            searchEnabled: true
        })
    });
</script>
