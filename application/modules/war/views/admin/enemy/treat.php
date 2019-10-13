<h1><?=$this->getTrans('manageNewEnemy') ?></h1>
<form id="article_form" class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <div class="form-group<?=in_array('enemyName', $this->get('errorFields')) ? ' has-error' : '' ?>">
        <label for="enemyNameInput" class="col-lg-2 control-label">
            <?=$this->getTrans('enemyName') ?>:
        </label>
        <div class="col-lg-4">
            <input type="text"
                   class="form-control"
                   id="enemyNameInput"
                   name="enemyName"
                   value="<?=$this->escape(($this->get('enemy') != '') ? $this->get('enemy')->getEnemyName() : $this->get('post')['enemyName']) ?>" />
        </div>
    </div>
    <div class="form-group<?=in_array('enemyTag', $this->get('errorFields')) ? ' has-error' : '' ?>">
        <label for="enemyTagInput" class="col-lg-2 control-label">
            <?=$this->getTrans('enemyTag') ?>:
        </label>
        <div class="col-lg-4">
            <input type="text"
                   class="form-control"
                   id="enemyTagInput"
                   name="enemyTag"
                   value="<?=$this->escape(($this->get('enemy') != '') ? $this->get('enemy')->getEnemyTag() : $this->get('post')['enemyTag']) ?>" />
        </div>
    </div>
    <div class="form-group<?=in_array('enemyHomepage', $this->get('errorFields')) ? ' has-error' : '' ?>">
        <label for="enemyHomepageInput" class="col-lg-2 control-label">
            <?=$this->getTrans('enemyHomepage') ?>:
        </label>
        <div class="col-lg-4">
            <input type="text"
                   class="form-control"
                   id="enemyHomepageInput"
                   name="enemyHomepage"
                   value="<?=$this->escape(($this->get('enemy') != '') ? $this->get('enemy')->getEnemyHomepage() : $this->get('post')['enemyHomepage']) ?>" />
        </div>
    </div>
    <div class="form-group<?=in_array('enemyImage', $this->get('errorFields')) ? ' has-error' : '' ?>">
        <label for="selectedImage" class="col-lg-2 control-label">
            <?=$this->getTrans('enemyImage') ?>:
        </label>
        <div class="col-lg-4">
            <div class="input-group">
                <input type="text"
                       class="form-control"
                       id="selectedImage"
                       name="enemyImage"
                       placeholder="<?=$this->getTrans('enemyImageInfo') ?>"
                       value="<?=$this->escape(($this->get('enemy') != '') ? $this->get('enemy')->getEnemyImage() : $this->get('post')['enemyImage']) ?>" />
                <span class="input-group-addon">
                    <a id="media" href="javascript:media()"><i class="fa fa-picture-o"></i></a>
                </span>
            </div>
        </div>
    </div>
    <div class="form-group<?=in_array('enemyContactName', $this->get('errorFields')) ? ' has-error' : '' ?>">
        <label for="enemyContactNameInput" class="col-lg-2 control-label">
            <?=$this->getTrans('enemyContactName') ?>:
        </label>
        <div class="col-lg-4">
            <input type="text"
                   class="form-control"
                   id="enemyContactNameInput"
                   name="enemyContactName"
                   value="<?=$this->escape(($this->get('enemy') != '') ? $this->get('enemy')->getEnemyContactName() : $this->get('post')['enemyContactName']) ?>" />
        </div>
    </div>
    <div class="form-group<?=in_array('enemyContactEmail', $this->get('errorFields')) ? ' has-error' : '' ?>">
        <label for="enemyContactEmailInput" class="col-lg-2 control-label">
            <?=$this->getTrans('enemyContactEmail') ?>:
        </label>
        <div class="col-lg-4">
            <input type="email"
                   class="form-control"
                   id="enemyContactEmailInput"
                   name="enemyContactEmail"
                   value="<?=$this->escape(($this->get('enemy') != '') ? $this->get('enemy')->getEnemyContactEmail() : $this->get('post')['enemyContactEmail']) ?>" />
        </div>
    </div>
    <?=($this->get('enemy') != '') ? $this->getSaveBar('updateButton') : $this->getSaveBar('addButton') ?>
</form>

<?=$this->getDialog('mediaModal', $this->getTrans('media'), '<iframe frameborder="0"></iframe>') ?>
<script>
    <?=$this->getMedia()
        ->addMediaButton($this->getUrl('admin/media/iframe/index/type/single/'))
        ->addUploadController($this->getUrl('admin/media/index/upload'))
    ?>
</script>
