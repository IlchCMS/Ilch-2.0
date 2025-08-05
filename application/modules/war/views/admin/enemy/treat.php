<?php

/** @var \Ilch\View $this */

/** @var \Modules\War\Models\Enemy $entry */
$entry = $this->get('enemy'); ?>
<h1><?=$this->getTrans('manageNewEnemy') ?></h1>
<form id="article_form" method="POST" action="">
    <?=$this->getTokenField() ?>
    <div class="row mb-3<?=$this->validation()->hasError('enemyName') ? ' has-error' : '' ?>">
        <label for="enemyNameInput" class="col-xl-2 col-form-label">
            <?=$this->getTrans('enemyName') ?>:
        </label>
        <div class="col-xl-4">
            <input type="text"
                   class="form-control"
                   id="enemyNameInput"
                   name="enemyName"
                   value="<?=$this->originalInput('enemyName', $entry->getEnemyName(), true) ?>" />
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('enemyTag') ? ' has-error' : '' ?>">
        <label for="enemyTagInput" class="col-xl-2 col-form-label">
            <?=$this->getTrans('enemyTag') ?>:
        </label>
        <div class="col-xl-4">
            <input type="text"
                   class="form-control"
                   id="enemyTagInput"
                   name="enemyTag"
                   value="<?=$this->originalInput('enemyTag', $entry->getEnemyTag(), true) ?>" />
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('enemyHomepage') ? ' has-error' : '' ?>">
        <label for="enemyHomepageInput" class="col-xl-2 col-form-label">
            <?=$this->getTrans('enemyHomepage') ?>:
        </label>
        <div class="col-xl-4">
            <input type="text"
                   class="form-control"
                   id="enemyHomepageInput"
                   name="enemyHomepage"
                   value="<?=$this->originalInput('enemyHomepage', $entry->getEnemyHomepage(), true) ?>" />
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('enemyImage') ? ' has-error' : '' ?>">
        <label for="selectedImage" class="col-xl-2 col-form-label">
            <?=$this->getTrans('enemyImage') ?>:
        </label>
        <div class="col-xl-4">
            <div class="input-group">
                <input type="text"
                       class="form-control"
                       id="selectedImage"
                       name="enemyImage"
                       placeholder="<?=$this->getTrans('enemyImageInfo') ?>"
                       value="<?=$this->originalInput('enemyImage', $entry->getEnemyImage(), true) ?>" />
                <span class="input-group-text">
                    <a id="media" href="javascript:media()"><i class="fa-regular fa-image"></i></a>
                </span>
            </div>
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('enemyContactName') ? ' has-error' : '' ?>">
        <label for="enemyContactNameInput" class="col-xl-2 col-form-label">
            <?=$this->getTrans('enemyContactName') ?>:
        </label>
        <div class="col-xl-4">
            <input type="text"
                   class="form-control"
                   id="enemyContactNameInput"
                   name="enemyContactName"
                   value="<?=$this->originalInput('enemyContactName', $entry->getEnemyContactName(), true) ?>" />
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('enemyContactEmail') ? ' has-error' : '' ?>">
        <label for="enemyContactEmailInput" class="col-xl-2 col-form-label">
            <?=$this->getTrans('enemyContactEmail') ?>:
        </label>
        <div class="col-xl-4">
            <input type="email"
                   class="form-control"
                   id="enemyContactEmailInput"
                   name="enemyContactEmail"
                   value="<?=$this->originalInput('enemyContactEmail', $entry->getEnemyContactEmail(), true) ?>" />
        </div>
    </div>
    <?=$this->getSaveBar($entry->getId() ? 'updateButton' : 'addButton') ?>
</form>

<?=$this->getDialog('mediaModal', $this->getTrans('media'), '<iframe style="border:0;"></iframe>') ?>
<script>
    <?=$this->getMedia()
        ->addMediaButton($this->getUrl('admin/media/iframe/index/type/single/'))
        ->addUploadController($this->getUrl('admin/media/index/upload'))
    ?>
</script>
