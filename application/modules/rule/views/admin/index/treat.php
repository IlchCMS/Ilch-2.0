<?php

/** @var \Ilch\View $this */

/** @var Modules\Rule\Mappers\Rule $ruleMapper */
$ruleMapper = $this->get('ruleMapper');

/** @var Modules\Rule\Models\Rule $rule */
$rule = $this->get('rule');

/** @var Modules\Rule\Models\Rule[]|null $rulesparents */
$rulesparents = $this->get('rulesparents');

/** @var Modules\User\Models\Group[]|null $userGroupList */
$userGroupList = $this->get('userGroupList');
?>
<h1><?=($rule->getId()) ? $this->getTrans('edit') : $this->getTrans('add') ?></h1>
<form method="POST" action="">
    <?=$this->getTokenField() ?>
    <div class="row mb-3<?=$this->validation()->hasError('paragraph') ? ' has-error' : '' ?>">
        <label for="paragraph" class="col-xl-2 col-form-label">
            <?=$this->getTrans('paragraph') ?>
        </label>
        <div class="col-xl-1">
            <input type="text"
                   class="form-control"
                   id="paragraph"
                   name="paragraph"
                   value="<?=$this->escape($this->originalInput('paragraph', $rule->getParagraph())) ?>"
                   required />
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('title') ? ' has-error' : '' ?>">
        <label for="title" class="col-xl-2 col-form-label">
            <?=$this->getTrans('title') ?>
        </label>
        <div class="col-xl-4">
            <input type="text"
                   class="form-control"
                   id="title"
                   name="title"
                   value="<?=$this->escape($this->originalInput('title', $rule->getTitle())) ?>"
                   required />
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('cat') ? ' has-error' : '' ?>">
        <label for="cat" class="col-xl-2 col-form-label">
            <?=$this->getTrans('cat') ?>
        </label>
        <div class="col-xl-4">
            <select class="chosen-select form-control" id="cat" name="cat">
                <?php foreach ($rulesparents as $item) : ?>
                    <option value="<?=$item->getId() ?>"<?=($this->originalInput('cat', $rule->getParentId()) == $item->getId()) ? ' selected' : '' ?>><?=$this->escape($item->getParagraph() . '. ' . $item->getTitle()) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('groups') ? ' has-error' : '' ?>">
        <label for="assignedGroupsRead" class="col-xl-2 col-form-label">
            <?=$this->getTrans('see') ?>
            <a href="#" data-bs-toggle="tooltip" data-bs-custom-class="custom-tooltip" data-bs-title="<?=$this->getTrans('seetext') ?>"><i class="fa-solid fa-circle-info"></i></a>
        </label>
        <div class="col-xl-4">
            <select class="chosen-select form-control" id="assignedGroupsRead" name="groups[]" data-placeholder="<?=$this->getTrans('selectAssignedGroups') ?>" multiple>
                <option value="all"<?=(in_array('all', $this->originalInput('groups', $this->get('groups')))) ? ' selected' : '' ?>><?=$this->getTrans('all') ?></option>
            <?php foreach ($userGroupList as $groupList) : ?>
                <?php if ($groupList->getId() != 1) : ?>
                    <option value="<?=$groupList->getId() ?>"<?=(in_array($groupList->getId(), $this->originalInput('groups', $this->get('groups')))) ? ' selected' : '' ?>><?=$this->escape($groupList->getName()) ?></option>
                <?php endif; ?>
            <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('text') ? ' has-error' : '' ?>">
        <label for="ck_1" class="col-xl-2 col-form-label">
            <?=$this->getTrans('text') ?>
        </label>
        <div class="col-xl-10">
            <textarea class="form-control ckeditor"
                      id="ck_1"
                      name="text"
                      toolbar="ilch_html"
                      rows="5"><?=$this->escape($this->originalInput('text', $rule->getText())) ?></textarea>
        </div>
    </div>
    <?=($rule->getId()) ? $this->getSaveBar('edit') : $this->getSaveBar('add') ?>
</form>

<?=$this->getDialog('mediaModal', $this->getTrans('media'), '<iframe style="border:0;"></iframe>') ?>
<script>
    $('#cat').chosen();
    $('#assignedGroupsRead').chosen();
</script>
