<h1>
    <?=($this->get('cat') != '') ? $this->getTrans('edit') : $this->getTrans('add') ?>
</h1>

<form method="POST" action="">
    <?=$this->getTokenField() ?>
    <div class="row mb-3">
        <label for="title" class="col-xl-2 col-form-label">
            <?=$this->getTrans('catTitle') ?>:
        </label>
        <div class="col-xl-4">
            <input type="text"
                   class="form-control"
                   id="title"
                   name="title"
                   value="<?=($this->get('cat') != '') ? $this->escape($this->get('cat')->getTitle()) : '' ?>" />
        </div>
    </div>
    <div class="row mb-3">
        <label for="access" class="col-xl-2 col-form-label">
            <?=$this->getTrans('visibleFor') ?>:
        </label>
        <div class="col-xl-4">
            <select class="choices-select form-control" id="access" name="groups[]" data-placeholder="<?=$this->getTrans('selectAssignedGroups') ?>" multiple>
                <?php foreach ($this->get('userGroupList') as $groupList) : ?>
                    <option value="<?=$groupList->getId() ?>"<?=(in_array($groupList->getId(), $this->get('groups'))) ? ' selected' : '' ?>>
                        <?=$this->escape($groupList->getName()) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <?php if ($this->get('cat') != '') : ?>
        <?=$this->getSaveBar('updateButton') ?>
    <?php else : ?>
        <?=$this->getSaveBar('addButton') ?>
    <?php endif; ?>
</form>

<script>
    $(document).ready(function() {
        new Choices('#access', {
            removeItemButton: true,
            searchEnabled: true,
            shouldSort: false,
            loadingText: '<?=$this->getTranslator()->trans('choicesLoadingText') ?>',
            noResultsText: '<?=$this->getTranslator()->trans('choicesNoResultsText') ?>',
            noChoicesText: '<?=$this->getTranslator()->trans('choicesNoChoicesText') ?>',
            itemSelectText: '<?=$this->getTranslator()->trans('choicesItemSelectText') ?>',
            uniqueItemText: '<?=$this->getTranslator()->trans('choicesUniqueItemText') ?>',
            customAddItemText: '<?=$this->getTranslator()->trans('choicesCustomAddItemText') ?>'
        })
    });
</script>
