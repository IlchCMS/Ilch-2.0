<legend><?=$this->getTrans('manageNewEnemy') ?></legend>
<form id="article_form" class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <div class="form-group">
        <label for="enemyNameInput" class="col-lg-2 control-label">
            <?=$this->getTrans('enemyName') ?>:
        </label>
        <div class="col-lg-4">
            <input type="text"
                   class="form-control"
                   id="enemyNameInput"
                   name="enemyName"
                   value="<?php if ($this->get('enemy') != '') { echo $this->get('enemy')->getEnemyName(); } ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="enemyTagInput" class="col-lg-2 control-label">
            <?=$this->getTrans('enemyTag') ?>:
        </label>
        <div class="col-lg-4">
            <input type="text"
                   class="form-control"
                   id="enemyTagInput"
                   name="enemyTag"
                   value="<?php if ($this->get('enemy') != '') { echo $this->get('enemy')->getEnemyTag(); } ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="enemyHomepageInput" class="col-lg-2 control-label">
            <?=$this->getTrans('enemyHomepage') ?>:
        </label>
        <div class="col-lg-4">
            <input type="text"
                   class="form-control"
                   id="enemyHomepageInput"
                   name="enemyHomepage"
                   value="<?php if ($this->get('enemy') != '') { echo $this->get('enemy')->getEnemyHomepage(); } ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="selectedImage"
                class="col-lg-2 control-label">
            <?=$this->getTrans('enemyImage') ?>:
        </label>
        <div class="col-lg-4">
            <div class="input-group">
                <input type="text"
                       class="form-control"
                       id="selectedImage"
                       name="enemyImage"
                       placeholder="<?=$this->getTrans('enemyImageInfo') ?>"
                       value="<?php if ($this->get('enemy') != '') { echo $this->get('enemy')->getEnemyImage(); } ?>" />
                <span class="input-group-addon"><a id="media" href="javascript:media()"><i class="fa fa-picture-o"></i></a></span>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="enemyContactNameInput" class="col-lg-2 control-label">
            <?=$this->getTrans('enemyContactName') ?>:
        </label>
        <div class="col-lg-4">
            <input type="text"
                   class="form-control"
                   id="enemyContactNameInput"
                   name="enemyContactName"
                   value="<?php if ($this->get('enemy') != '') { echo $this->get('enemy')->getEnemyContactName(); } ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="enemyContactEmailInput" class="col-lg-2 control-label">
            <?=$this->getTrans('enemyContactEmail') ?>:
        </label>
        <div class="col-lg-4">
            <input type="text"
                   class="form-control"
                   id="enemyContactEmailInput"
                   name="enemyContactEmail"
                   value="<?php if ($this->get('enemy') != '') { echo $this->get('enemy')->getEnemyContactEmail(); } ?>" />
        </div>
    </div>
    <?php if ($this->get('enemy') != ''): ?>
        <?=$this->getSaveBar('updateButton') ?>
    <?php else: ?>
        <?=$this->getSaveBar('addButton') ?>
    <?php endif; ?>
</form>

<?=$this->getDialog('mediaModal', $this->getTrans('media'), '<iframe frameborder="0"></iframe>'); ?>
<script>
<?=$this->getMedia()
        ->addMediaButton($this->getUrl('admin/media/iframe/index/type/single/'))
        ->addUploadController($this->getUrl('admin/media/index/upload'))
?>
</script>
