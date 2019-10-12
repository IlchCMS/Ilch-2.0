<h1><?=($this->get('rule') != '') ? $this->getTrans('edit') : $this->getTrans('add') ?></h1>
<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <div class="form-group <?=$this->validation()->hasError('paragraph') ? 'has-error' : '' ?>">
        <label for="paragraph" class="col-lg-2 control-label">
            <?=$this->getTrans('paragraph') ?>
        </label>
        <div class="col-lg-1">
            <input type="text"
                   class="form-control"
                   id="paragraph"
                   name="paragraph"
                   value="<?=($this->get('rule') != '') ? $this->escape($this->get('rule')->getParagraph()) : $this->escape($this->originalInput('paragraph')) ?>"
                   required />
        </div>
    </div>
    <div class="form-group <?=$this->validation()->hasError('title') ? 'has-error' : '' ?>">
        <label for="title" class="col-lg-2 control-label">
            <?=$this->getTrans('title') ?>
        </label>
        <div class="col-lg-4">
            <input type="text"
                   class="form-control"
                   id="title"
                   name="title"
                   value="<?=($this->get('rule') != '') ? $this->escape($this->get('rule')->getTitle()) : $this->escape($this->originalInput('title')) ?>"
                   required />
        </div>
    </div>
    <div class="form-group <?=$this->validation()->hasError('cat') ? 'has-error' : '' ?>">
        <label for="cat" class="col-lg-2 control-label">
            <?=$this->getTrans('cat') ?>
        </label>
        <div class="col-lg-4">
            <select class="form-control" id="cat" name="cat" required>
                <?php foreach ($this->get('rulesparents') as $item): ?>
                    <option value="<?=$item->getId() ?>"<?=(!empty($this->get('rule')) && $this->get('rule')->getParent_Id() == $item->getId()) ? ' selected': '' ?>><?=$this->escape($item->getParagraph().'. '.$item->getTitle()) ?></option>
                <?php endforeach; ?>
            </select>
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
    <div class="form-group <?=$this->validation()->hasError('text') ? 'has-error' : '' ?>">
        <label for="ck_1" class="col-lg-2 control-label">
            <?=$this->getTrans('text') ?>
        </label>
        <div class="col-lg-10">
            <textarea class="form-control ckeditor"
                      id="ck_1"
                      name="text"
                      toolbar="ilch_html"
                      rows="5"
                      required><?=($this->get('rule') != '') ? $this->escape($this->get('rule')->getText()) : $this->escape($this->originalInput('text')) ?></textarea>
        </div>
    </div>
    <?=($this->get('rule') != '') ? $this->getSaveBar('edit') : $this->getSaveBar('add') ?>
</form>

<script>
    $('#assignedGroupsRead').chosen();
</script>
