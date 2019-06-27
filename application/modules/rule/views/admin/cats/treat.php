<h1><?=($this->get('cat') != '') ? $this->getTrans('edit') : $this->getTrans('add') ?></h1>
<form class="form-horizontal" method="POST">
    <?=$this->getTokenField(); ?>
    <div class="form-group <?=$this->validation()->hasError('paragraph') ? 'has-error' : '' ?>">
        <label for="paragraph" class="col-lg-2 control-label">
            <?=$this->getTrans('paragraph') ?>
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
    <?=($this->get('cat') != '') ? $this->getSaveBar('updateButton') : $this->getSaveBar('addButton') ?>
</form>
