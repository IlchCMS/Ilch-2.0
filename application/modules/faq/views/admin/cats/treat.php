<?php

/** @var \Ilch\View $this */

/** @var Modules\Faq\Models\Category|null $cat */
$cat = $this->get('cat');
?>

<h1>
    <?=$this->getTrans($cat->getId() ? 'edit' : 'add') ?>
</h1>
<form method="POST" action="">
    <?=$this->getTokenField() ?>
    <div class="row mb-3<?=$this->validation()->hasError('title') ? ' has-error' : '' ?>">
        <label for="title" class="col-xl-2 col-form-label">
            <?=$this->getTrans('title') ?>:
        </label>
        <div class="col-xl-3">
            <input type="text"
                   class="form-control"
                   id="title"
                   name="title"
                   value="<?=$this->originalInput('title', $cat->getTitle(), true) ?>" />
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('groups') ? ' has-error' : '' ?>">
        <label for="access" class="col-xl-2 col-form-label">
            <?=$this->getTrans('visibleFor') ?>:
        </label>
        <div class="col-xl-3">
            <select class="choices-select form-control" id="access" name="groups[]" data-placeholder="<?=$this->getTrans('selectAssignedGroups') ?>" multiple>
                <option value="all" <?=in_array('all', $this->originalInput('groups', $this->get('groups'))) ? 'selected="selected"' : '' ?>><?=$this->getTrans('groupAll') ?></option>
                <?php
                /** @var Modules\User\Models\Group $groupList */
                foreach ($this->get('userGroupList') as $groupList) : ?>
                    <?php if ($groupList->getId() != 1) : ?>
                        <option value="<?=$groupList->getId() ?>" <?=in_array($groupList->getId(), $this->originalInput('groups', $this->get('groups'))) ? 'selected=""' : '' ?>><?=$groupList->getName() ?></option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <?=$this->getSaveBar($cat->getId() ? 'updateButton' : 'addButton') ?>
</form>

<script>
    $(document).ready(function() {
        new Choices('#access', {
            ...choicesOptions,
            searchEnabled: true
        })
    });
</script>
