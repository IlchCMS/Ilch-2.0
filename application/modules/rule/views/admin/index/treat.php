<h1><?=($this->get('rule') != '') ? $this->getTrans('edit') : $this->getTrans('add') ?></h1>
<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <div class="form-group <?=$this->validation()->hasError('paragraph') ? 'has-error' : '' ?>">
        <label for="paragraph" class="col-lg-2 control-label">
            <?=$this->getTrans('paragraph') ?>
        </label>
        <div class="col-lg-1">
            <input type="number"
                   class="form-control"
                   id="paragraph"
                   name="paragraph"
                   min="1"
                   value="<?=($this->get('rule') != '') ? $this->escape($this->get('rule')->getParagraph()) : $this->originalInput('paragraph') ?>" />
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
                   value="<?=($this->get('rule') != '') ? $this->escape($this->get('rule')->getTitle()) : $this->originalInput('title') ?>" />
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
                      rows="5"><?=($this->get('rule') != '') ? $this->escape($this->get('rule')->getText()) : $this->originalInput('text') ?></textarea>
        </div>
    </div>
    <?=($this->get('rule') != '') ? $this->getSaveBar('edit') : $this->getSaveBar('add') ?>
</form>
