<h1><?=$this->getTrans('manage') ?></h1>
<form class="form-horizontal" method="POST" action="<?=$this->getUrl(['action' => $this->getRequest()->getActionName(), 'id' => 1]) ?>">
    <?=$this->getTokenField() ?>
    <div class="form-group">
        <label for="ck_2" class="col-lg-2 control-label">
            <?=$this->getTrans('imprint') ?>:
        </label>
        <div class="col-lg-12">
           <textarea class="form-control ckeditor"
                     id="ck_2"
                     name="imprint"
                     toolbar="ilch_html"
                     cols="60"
                     rows="5"><?= ($this->originalInput('imprint') != '') ? $this->originalInput('imprint') : $this->escape($this->get('imprint')->getImprint()) ?></textarea>
        </div>
    </div>
    <?=$this->getSaveBar('updateButton') ?>
</form>

<?=$this->getDialog('mediaModal', $this->getTrans('media'), '<iframe frameborder="0"></iframe>'); ?>
