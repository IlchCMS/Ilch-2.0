<?php

/** @var \Ilch\View $this */

/** @var Modules\Faq\Models\Category|null $cat */
$cat = $this->get('cat');
?>

<h1>
    <?=($cat) ? $this->getTrans('edit') : $this->getTrans('add') ?>
</h1>
<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <div class="form-group <?=$this->validation()->hasError('title') ? 'has-error' : '' ?>">
        <label for="title" class="col-lg-2 control-label">
            <?=$this->getTrans('title') ?>:
        </label>
        <div class="col-lg-3">
            <input type="text"
                   class="form-control"
                   id="title"
                   name="title"
                   value="<?=$this->escape($this->originalInput('title', $cat->getTitle())) ?>" />
        </div>
    </div>
    <div class="form-group <?=$this->validation()->hasError('groups') ? 'has-error' : '' ?>">
        <label for="access" class="col-lg-2 control-label">
            <?=$this->getTrans('visibleFor') ?>:
        </label>
        <div class="col-lg-3">
            <select class="chosen-select form-control" id="access" name="groups[]" data-placeholder="<?=$this->getTrans('selectAssignedGroups') ?>" multiple>
                <option value="all" <?=in_array('all', $this->originalInput('groups', $this->get('groups'))) ? 'selected="selected"' : '' ?>><?=$this->getTrans('groupAll') ?></option>
                <?php foreach ($this->get('userGroupList') as $groupList) : ?>
                    <?php if ($groupList->getId() != 1) : ?>
                        <option value="<?=$groupList->getId() ?>" <?=in_array($groupList->getId(), $this->originalInput('groups', $this->get('groups'))) ? 'selected=""' : '' ?>><?=$groupList->getName() ?></option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <?=($cat->getId()) ? $this->getSaveBar('updateButton') : $this->getSaveBar('addButton') ?>
</form>

<script>
    $('#access').chosen();
</script>
