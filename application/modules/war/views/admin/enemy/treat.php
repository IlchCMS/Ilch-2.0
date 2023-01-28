<?php $entrie = $this->get('enemy'); ?>
<h1><?=$this->getTrans('manageNewEnemy') ?></h1>
<form id="article_form" class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <div class="form-group<?=$this->validation()->hasError('enemyName') ? ' has-error' : '' ?>">
        <label for="enemyNameInput" class="col-lg-2 control-label">
            <?=$this->getTrans('enemyName') ?>:
        </label>
        <div class="col-lg-4">
            <input type="text"
                   class="form-control"
                   id="enemyNameInput"
                   name="enemyName"
                   value="<?=$this->escape($this->originalInput('enemyName', ($entrie->getId()?$entrie->getEnemyName():''))) ?>" />
        </div>
    </div>
    <div class="form-group<?=$this->validation()->hasError('enemyTag') ? ' has-error' : '' ?>">
        <label for="enemyTagInput" class="col-lg-2 control-label">
            <?=$this->getTrans('enemyTag') ?>:
        </label>
        <div class="col-lg-4">
            <input type="text"
                   class="form-control"
                   id="enemyTagInput"
                   name="enemyTag"
                   value="<?=$this->escape($this->originalInput('enemyTag', ($entrie->getId()?$entrie->getEnemyTag():''))) ?>" />
        </div>
    </div>
    <div class="form-group<?=$this->validation()->hasError('enemyHomepage') ? ' has-error' : '' ?>">
        <label for="enemyHomepageInput" class="col-lg-2 control-label">
            <?=$this->getTrans('enemyHomepage') ?>:
        </label>
        <div class="col-lg-4">
            <input type="text"
                   class="form-control"
                   id="enemyHomepageInput"
                   name="enemyHomepage"
                   value="<?=$this->escape($this->originalInput('enemyHomepage', ($entrie->getId()?$entrie->getEnemyHomepage():''))) ?>" />
        </div>
    </div>
    <div class="form-group<?=$this->validation()->hasError('enemyImage') ? ' has-error' : '' ?>">
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
                       value="<?=$this->escape($this->originalInput('enemyImage', ($entrie->getId()?$entrie->getEnemyImage():''))) ?>" />
                <span class="input-group-addon">
                    <a id="media" href="javascript:media()"><i class="fa-regular fa-image"></i></a>
                </span>
            </div>
        </div>
    </div>
    <div class="form-group<?=$this->validation()->hasError('enemyContactName') ? ' has-error' : '' ?>">
        <label for="enemyContactNameInput" class="col-lg-2 control-label">
            <?=$this->getTrans('enemyContactName') ?>:
        </label>
        <div class="col-lg-4">
            <input type="text"
                   class="form-control"
                   id="enemyContactNameInput"
                   name="enemyContactName"
                   value="<?=$this->escape($this->originalInput('enemyContactName', ($entrie->getId()?$entrie->getEnemyContactName():''))) ?>" />
        </div>
    </div>
    <div class="form-group<?=$this->validation()->hasError('enemyContactEmail') ? ' has-error' : '' ?>">
        <label for="enemyContactEmailInput" class="col-lg-2 control-label">
            <?=$this->getTrans('enemyContactEmail') ?>:
        </label>
        <div class="col-lg-4">
            <input type="email"
                   class="form-control"
                   id="enemyContactEmailInput"
                   name="enemyContactEmail"
                   value="<?=$this->escape($this->originalInput('enemyContactEmail', ($entrie->getId()?$entrie->getEnemyContactEmail():''))) ?>" />
        </div>
    </div>
    <?=($entrie->getId()) ? $this->getSaveBar('updateButton') : $this->getSaveBar('addButton') ?>
</form>

<?=$this->getDialog('mediaModal', $this->getTrans('media'), '<iframe style="border:0;"></iframe>') ?>
<script>
    <?=$this->getMedia()
        ->addMediaButton($this->getUrl('admin/media/iframe/index/type/single/'))
        ->addUploadController($this->getUrl('admin/media/index/upload'))
    ?>
</script>
