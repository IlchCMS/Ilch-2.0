<h1><?=$this->getTrans('add') ?></h1>
<?php if ($this->get('emails') != ''): ?>
    <form method="POST" action="">
        <?=$this->getTokenField() ?>
        <div class="row mb-3 <?=$this->validation()->hasError('subject') ? 'has-error' : '' ?>">
            <label for="subject" class="col-xl-2 control-label">
                <?=$this->getTrans('subject') ?>:
            </label>
            <div class="col-xl-4">
                <input type="text"
                       class="form-control"
                       id="subject"
                       name="subject"
                       value="<?=$this->escape($this->originalInput('subject')) ?>"
                       required />
            </div>
        </div>
        <div class="row mb-3 <?=$this->validation()->hasError('text') ? 'has-error' : '' ?>">
            <label for="ck_1" class="col-xl-2 control-label">
                <?=$this->getTrans('text') ?>:
            </label>
            <div class="col-xl-10">
                <textarea class="form-control ckeditor"
                          id="ck_1"
                          name="text"
                          toolbar="ilch_html"
                          rows="5"><?=$this->escape($this->originalInput('text')) ?></textarea>
            </div>
        </div>
        <?=$this->getSaveBar('send') ?>
    </form>
<?php else: ?>
    <?=$this->getTrans('noEmails') ?>
<?php endif; ?>

<?=$this->getDialog('mediaModal', $this->getTrans('media'), '<iframe frameborder="0"></iframe>') ?>
