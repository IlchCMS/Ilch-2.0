<h1><?=($this->get('cat') != '') ? $this->getTrans('edit') : $this->getTrans('add') ?></h1>
<form class="form-horizontal" method="POST">
    <?=$this->getTokenField(); ?>
    <div class="form-group <?=$this->validation()->hasError('paragraph') ? 'has-error' : '' ?>">
        <label for="paragraph" class="col-lg-2 control-label">
            <?=$this->getTrans('art') ?>
        </label>
        <div class="col-lg-1">
            <input type="text"
                   class="form-control"
                   id="paragraph"
                   name="paragraph"
                   value="<?=($this->get('cat') != '') ? $this->escape($this->get('cat')->getParagraph()) : $this->originalInput('paragraph') ?>"
                   required />
        </div>
    </div>
    <div class="form-group <?=$this->validation()->hasError('name') ? 'has-error' : '' ?>">
        <label for="name" class="col-lg-2 control-label">
            <?=$this->getTrans('name') ?>:
        </label>
        <div class="col-lg-3">
            <input type="text"
                   class="form-control"
                   id="name"
                   name="name"
                   value="<?=($this->get('cat') != '') ? $this->escape($this->get('cat')->getTitle()) : $this->originalInput('name') ?>"
                   required />
        </div>
    </div>
    <div class="form-group">
        <label for="assignedGroupsRead" class="col-lg-2 control-label">
            <?=$this->getTrans('see') ?>
            <a href="#" data-toggle="tooltip" title="<?=$this->getTrans('seetext') ?>"><i class="fa fa-info-circle"></i></a>
        </label>
        <div class="col-lg-4">
            <select class="chosen-select form-control" id="assignedGroupsRead" name="groups[]" data-placeholder="<?=$this->getTrans('selectAssignedGroups') ?>" multiple>
                <?php foreach ($this->get('userGroupList') as $groupList): ?>
                    <option value="<?=$groupList->getId() ?>"<?=(in_array($groupList->getId(), $this->get('groups'))) ? ' selected' : '' ?>><?=$this->escape($groupList->getName()) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <?=($this->get('cat') != '') ? $this->getSaveBar('updateButton') : $this->getSaveBar('addButton') ?>
</form>

<script>
    $('#assignedGroupsRead').chosen();
</script>
