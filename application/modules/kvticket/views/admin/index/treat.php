<h1><?=($this->get('ticket') != '') ? $this->getTrans('edit') : $this->getTrans('add') ?></h1>
<form method="POST" action="" enctype="multipart/form-data">
    <?=$this->getTokenField() ?>
    <div class="row mb-3">
        <label for="status" class="col-xl-2 col-form-label">
            <?=$this->getTrans('status') ?>
        </label>
        <div class="col-xl-4">
            <select class="form-select" id="status" name="status">
                <option value="0" <?=($this->get('ticket') && $this->get('ticket')->getStatus() == 0) ? 'selected' : '' ?>><?=$this->getTrans('openTickets') ?></option>
                <option value="1" <?=($this->get('ticket') && $this->get('ticket')->getStatus() == 1) ? 'selected' : '' ?>><?=$this->getTrans('editTickets') ?></option>
                <option value="2" <?=($this->get('ticket') && $this->get('ticket')->getStatus() == 2) ? 'selected' : '' ?>><?=$this->getTrans('compTickets') ?></option>
                <option value="3" <?=($this->get('ticket') && $this->get('ticket')->getStatus() == 3) ? 'selected' : '' ?>><?=$this->getTrans('closeTickets') ?></option>
            </select>
        </div>
    </div>
    <div class="row mb-3">
        <label for="cat" class="col-xl-2 col-form-label">
            <?=$this->getTrans('cat') ?>
        </label>
        <div class="col-xl-4">
            <select class="form-select" id="cat" name="cat">
                <option value="0" <?=(!$this->get('ticket')) ? 'selected' : '' ?>><?=$this->getTrans('noSelection') ?></option>
            <?php foreach ($this->get('cats') as $cat): ?>
                <option value="<?=$cat->getId() ?>" <?=($this->get('ticket') && $this->get('ticket')->getCat() == $cat->getId()) ? 'selected' : '' ?>><?=$this->escape($cat->getTitle()) ?></option>
            <?php endforeach; ?>
            </select>
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
                   value="<?=($this->get('ticket') != '') ? $this->escape($this->get('ticket')->getTitle()) : $this->originalInput('title') ?>" />
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('text') ? ' has-error' : '' ?>">
        <label for="text" class="col-xl-2 col-form-label">
            <?=$this->getTrans('desc') ?>
        </label>
        <div class="col-xl-8">
                <textarea class="form-control ckeditor"
                          id="text"
                          name="text"
                          toolbar="ilch_html"
                          cols="60"
                          rows="5"><?=($this->get('ticket') != '') ? $this->escape($this->get('ticket')->getText()) : $this->originalInput('text') ?></textarea>
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('editor') ? ' has-error' : '' ?>">
        <label for="editor" class="col-xl-2 col-form-label">
            <?=$this->getTrans('editor') ?>
        </label>
        <div class="col-xl-4">
            <select class="form-select" id="editor" name="editor">
            <option value="0" <?=(!$this->get('ticket')) ? 'selected' : '' ?>><?=$this->getTrans('noSelection') ?></option>
                <?php foreach ($this->get('users') as $user): ?>
                <?php if ($user->hasAccess('module_kvticket',true) != ''): ?>
                    <option value="<?=$user->getId() ?>" <?=(($this->get('ticket') && $this->get('ticket')->getEditor() == $user->getId()) || ((!$this->get('ticket') || $this->get('ticket')->getEditor() == 0) && $user->getId() == $this->getUser()->getId())) ? 'selected' : '' ?>><?=$this->escape($user->getName()) ?></option>
                <?php endif; ?>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <?=($this->get('ticket') != '') ? $this->getSaveBar('edit') : $this->getSaveBar('add') ?>
</form>
<?=$this->getDialog('mediaModal', $this->getTrans('media'), '<iframe frameborder="0"></iframe>'); ?>
