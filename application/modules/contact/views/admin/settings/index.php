<h1><?=$this->getTrans('settings') ?></h1>
<form method="POST">
    <?=$this->getTokenField() ?>
    <div class="row mb-3 <?= $this->validation()->hasError('welcomeMessage') ? 'has-error' : '' ?>">
        <label for="ck_1" class="col-xl-2 col-form-label">
            <?=$this->getTrans('welcomeMessage') ?>:
        </label>
        <div class="col-xl-8">
            <textarea class="form-control ckeditor"
                      id="ck_1"
                      name="welcomeMessage"
                      toolbar="ilch_html"
                      required><?=($this->escape($this->get('welcomeMessage')) != '') ? $this->escape($this->get('welcomeMessage')) : $this->escape($this->originalInput('welcomeMessage')) ?></textarea>
        </div>
    </div>
    <?=$this->getSaveBar() ?>
</form>
<?=$this->getDialog('mediaModal', $this->getTrans('media'), '<iframe frameborder="0"></iframe>'); ?>
