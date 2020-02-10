<h1>
    <?php if ($this->get('cat') != ''): ?>
        <?=$this->getTrans('edit') ?>
   <?php else: ?>
        <?=$this->getTrans('add') ?>
    <?php endif; ?>
</h1>
<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <div class="form-group">
        <label for="title" class="col-lg-2 control-label">
            <?=$this->getTrans('title') ?>:
        </label>
        <div class="col-lg-3">
            <input type="text"
                   class="form-control"
                   id="title"
                   name="title"
                   value="<?php if ($this->get('cat') != '') { echo $this->escape($this->get('cat')->getTitle()); } ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="access" class="col-lg-2 control-label">
            <?=$this->getTrans('visibleFor') ?>:
        </label>
        <div class="col-lg-3">
            <select class="chosen-select form-control" id="access" name="groups[]" data-placeholder="<?=$this->getTrans('selectAssignedGroups') ?>" multiple>
                <?php foreach ($this->get('userGroupList') as $groupList): ?>
                    <option value="<?=$groupList->getId() ?>"<?=(in_array($groupList->getId(), $this->get('groups'))) ? ' selected' : '' ?>><?=$groupList->getName() ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <?php if ($this->get('cat') != ''): ?>
        <?=$this->getSaveBar('updateButton') ?>
    <?php else: ?>
        <?=$this->getSaveBar('addButton') ?>
    <?php endif; ?>
</form>

<script>
    $('#access').chosen();
</script>
