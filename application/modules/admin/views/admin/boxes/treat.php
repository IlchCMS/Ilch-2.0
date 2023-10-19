<?php
$entrie = $this->get('box');
?>
<h1>
    <?=($entrie->getId()) ? $this->getTrans('edit') : $this->getTrans('add') ?>
</h1>

<form class="form-horizontal" method="POST">
    <?=$this->getTokenField() ?>
    <div class="row mb-3 <?=$this->validation()->hasError('boxTitle') ? 'has-error' : '' ?>">
        <label for="boxTitle" class="col-xl-2 control-label">
            <?=$this->getTrans('boxTitle') ?>:
        </label>
        <div class="col-xl-4">
            <input type="text"
                   class="form-control"
                   id="boxTitle"
                   name="boxTitle"
                   value="<?=$this->escape($this->originalInput('boxTitle', ($entrie->getId()?$entrie->getTitle():''))) ?>" />
        </div>
    </div>
    <div class="row mb-3 <?=$this->validation()->hasError('boxContent') ? 'has-error' : '' ?>">
        <label for="boxContent" class="col-xl-2 control-label">
            <?=$this->getTrans('boxContent') ?>:
        </label>
        <div class="col-xl-8">
            <textarea class="form-control ckeditor"
                      id="boxContent"
                      name="boxContent"
                      toolbar="ilch_html"><?=$this->escape($this->originalInput('boxContent', ($entrie->getId()?$entrie->getContent():''))) ?></textarea>
        </div>
    </div>
    <?php if ($this->get('multilingual') && $this->getRequest()->getParam('locale')): ?>
        <div class="row mb-3">
            <label for="boxLanguage" class="col-xl-2 control-label">
                <?=$this->getTrans('boxLanguage') ?>:
            </label>
            <div class="col-xl-2">
                <select class="form-control" id="boxLanguage" name="boxLanguage">
                    <?php foreach ($this->get('languages') as $key => $value): ?>
                        <?php if ($key == $this->get('contentLanguage')): ?>
                            <?php continue; ?>
                        <?php endif; ?>
                        <option value="<?=$key ?>" <?=($this->originalInput('boxLanguage', ($entrie->getId()?$entrie->getLocale():''))) == $key ? 'selected=""' : '' ?>><?=$this->escape($value) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    <?php endif; ?>
    <?=($entrie->getId()) ? $this->getSaveBar('updateButtonBox') : $this->getSaveBar('addButtonBox') ?>
</form>

<?=$this->getDialog('mediaModal', $this->getTrans('media'), '<iframe frameborder="0"></iframe>') ?>
<script>
<?php $boxID = $entrie->getId() ?? ''; ?>

$('#boxLanguage').change (
    this,
    function () {
        top.location.href = '<?=$this->getUrl(['id' => $boxID]) ?>/locale/'+$(this).val()
    }
);
</script>
