<h1>
    <?php
    if ($this->get('box') != '') {
        echo $this->getTrans('edit');
    } else {
        echo $this->getTrans('add');
    }
    ?>
</h1>

<form class="form-horizontal" method="POST">
    <?=$this->getTokenField() ?>
    <div class="form-group <?=$this->validation()->hasError('boxTitle') ? 'has-error' : '' ?>">
        <label for="boxTitle" class="col-lg-2 control-label">
            <?=$this->getTrans('boxTitle') ?>:
        </label>
        <div class="col-lg-4">
            <input type="text"
                   class="form-control"
                   id="boxTitle"
                   name="boxTitle"
                   value="<?php if ($this->get('box') != '') { echo $this->escape($this->get('box')->getTitle()); } else { echo $this->get('post')['boxTitle']; } ?>" />
        </div>
    </div>
    <div class="form-group <?=$this->validation()->hasError('boxContent') ? 'has-error' : '' ?>">
        <label for="boxContent" class="col-lg-2 control-label">
            <?=$this->getTrans('boxContent') ?>:
        </label>
        <div class="col-lg-8">
            <textarea class="form-control ckeditor"
                      id="boxContent"
                      name="boxContent"
                      toolbar="ilch_html"><?php if ($this->get('box') != '') { echo $this->get('box')->getContent(); } else { echo $this->get('post')['boxContent']; } ?></textarea>
        </div>
    </div>
    <?php if ($this->get('multilingual') && $this->getRequest()->getParam('locale') != ''): ?>
        <div class="form-group">
            <label for="boxLanguage" class="col-lg-2 control-label">
                <?=$this->getTrans('boxLanguage') ?>:
            </label>
            <div class="col-lg-2">
                <select class="form-control" id="boxLanguage" name="boxLanguage">
                    <?php foreach ($this->get('languages') as $key => $value): ?>
                        <?php $selected = ''; ?>

                        <?php if ($key == $this->get('contentLanguage')): ?>
                            <?php continue; ?>
                        <?php endif; ?>

                        <?php if ($this->getRequest()->getParam('locale') == $key): ?>
                            <?php $selected = 'selected="selected"'; ?>
                        <?php endif; ?>

                        <option <?=$selected ?> value="<?=$key ?>"><?=$this->escape($value) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    <?php endif; ?>
    <?php if ($this->get('box') != ''): ?>
        <?=$this->getSaveBar('updateButtonBox') ?>
    <?php else: ?>
        <?=$this->getSaveBar('addButtonBox') ?>
    <?php endif; ?>
</form>

<?=$this->getDialog('mediaModal', $this->getTrans('media'), '<iframe frameborder="0"></iframe>'); ?>
<script>
<?php $boxID = ''; ?>
<?php if ($this->get('box') != ''): ?>
    <?php $boxID = $this->get('box')->getId(); ?>
<?php endif; ?>

$('#boxLanguage').change (
    this,
    function () {
        top.location.href = '<?=$this->getUrl(['id' => $boxID]); ?>/locale/'+$(this).val()
    }
);
</script>
