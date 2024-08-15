<?php

/** @var \Ilch\View $this */

/** @var \Modules\War\Models\Group $entry */
$entry = $this->get('group');
?>
<h1><?=(!$entry->getId()) ? $this->getTrans('manageNewGroup') : $this->getTrans('treatGroup') ?></h1>
<form id="article_form" method="POST" action="">
    <?=$this->getTokenField() ?>
    <div class="row mb-3<?=$this->validation()->hasError('groupName') ? ' has-error' : '' ?>">
        <label for="groupNameInput" class="col-xl-2 col-form-label">
            <?=$this->getTrans('groupName') ?>:
        </label>
        <div class="col-xl-4">
            <input type="text"
                   class="form-control"
                   id="groupNameInput"
                   name="groupName"
                   value="<?=$this->escape($this->originalInput('groupName', ($entry->getId() ? $entry->getGroupName() : ''))) ?>" />
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('groupTag') ? ' has-error' : '' ?>">
        <label for="groupTagInput" class="col-xl-2 col-form-label">
            <?=$this->getTrans('groupTag') ?>:
        </label>
        <div class="col-xl-4">
            <input type="text"
                   class="form-control"
                   id="groupTagInput"
                   name="groupTag"
                   value="<?=$this->escape($this->originalInput('groupTag', ($entry->getId() ? $entry->getGroupTag() : ''))) ?>" />
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('groupImage') ? ' has-error' : '' ?>">
        <label for="selectedImage_1" class="col-xl-2 col-form-label">
            <?=$this->getTrans('groupImage') ?>:
        </label>
        <div class="col-xl-4">
            <div class="input-group">
                <input type="text"
                       class="form-control"
                       id="selectedImage_1"
                       name="groupImage"
                       placeholder="<?=$this->getTrans('groupImage') ?>"
                       value="<?=$this->escape($this->originalInput('groupImage', ($entry->getId() ? $entry->getGroupImage() : ''))) ?>" />
                <span class="input-group-text">
                    <a id="media" href="javascript:media_1()"><i class="fa-regular fa-image"></i></a>
                </span>
            </div>
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('groupDesc') ? ' has-error' : '' ?>">
        <label for="groupDesc" class="col-xl-2 col-form-label">
            <?=$this->getTrans('groupDesc') ?>:
        </label>
        <div class="col-xl-4">
            <div class="input-group">
                <textarea class="form-control"
                          name="groupDesc"
                          id="groupDesc"
                          cols="50"
                          rows="5"
                          placeholder="<?=$this->escape($this->originalInput('groupDesc', ($entry->getId() ? $entry->getGroupDesc() : ''))) ?>"></textarea>
            </div>
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('userGroup') ? ' has-error' : '' ?>">
        <label for="warGroup" class="col-xl-2 col-form-label">
            <?=$this->getTrans('assignedMember') ?>
        </label>
        <div class="col-xl-4">
            <select class="form-select" id="warGroup" name="userGroup">
                <optgroup label="<?=$this->getTrans('groupsName') ?>">
                    <?php
                    /** @var \Modules\User\Models\Group $group */
                    foreach ($this->get('userGroupList') as $group) : ?>
                        <?php if ($group->getId() != '3') : ?>
                            <option value="<?=$group->getId() ?>" <?=($this->originalInput('userGroup', ($entry->getId() ? $entry->getGroupMember() : 0))) == $group->getId() ? 'selected=""' : '' ?>><?=$this->escape($group->getName()) ?></option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </optgroup>
            </select>
        </div>
    </div>
    <?=($entry->getId()) ? $this->getSaveBar('updateButton') : $this->getSaveBar('addButton') ?>
</form>

<?=$this->getDialog('mediaModal', $this->getTrans('media'), '<iframe style="border:0;"></iframe>') ?>
<script>
    // Example for multiple input filds
    <?=$this->getMedia()
        ->addMediaButton($this->getUrl('admin/media/iframe/index/type/single/input/_1/'))
        ->addInputId('_1')
        ->addUploadController($this->getUrl('admin/media/index/upload'))
    ?>

    $('#assignedMember').chosen();
</script>
