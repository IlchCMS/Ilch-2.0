<?php

/** @var \Ilch\View $this */

/** @var Modules\Link\Models\Link $link */
$link = $this->get('link');

/** @var Modules\Link\Models\Category[]|null $cats */
$cats = $this->get('cats');
?>
<h1><?=($link->getId()) ? $this->getTrans('menuActionEditLink') : $this->getTrans('menuActionNewLink') ?></h1>
<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <div class="row mb-3 <?=$this->validation()->hasError('name') ? 'has-error' : '' ?>">
        <label for="name" class="col-xl-2 control-label">
            <?=$this->getTrans('name') ?>:
        </label>
        <div class="col-xl-4">
            <input type="text"
                   class="form-control"
                   id="name"
                   name="name"
                   placeholder="Name"
                   value="<?=$this->escape($this->originalInput('Name', $link->getName())) ?>" />
        </div>
    </div>
    <div class="row mb-3 <?=$this->validation()->hasError('link') ? 'has-error' : '' ?>">
        <label for="link" class="col-xl-2 control-label">
            <?=$this->getTrans('link') ?>:
        </label>
        <div class="col-xl-4">
            <input type="text"
                   class="form-control"
                   id="link"
                   name="link"
                   placeholder="https://"
                   value="<?=$this->escape($this->originalInput('link', $link->getLink())) ?>" />
        </div>
    </div>
    <div class="row mb-3 <?=$this->validation()->hasError('banner') ? 'has-error' : '' ?>">
        <label for="selectedImage_1" class="col-xl-2 control-label">
            <?=$this->getTrans('banner') ?>:
        </label>
        <div class="col-xl-4">
            <div class="input-group">
                <input type="text"
                       class="form-control"
                       id="selectedImage_1"
                       name="banner"
                       placeholder="<?=$this->getTrans('httpOrMedia') ?>"
                       value="<?=$this->escape($this->originalInput('banner', $link->getBanner())) ?>" />
                <span class="input-group-text"><a id="media" href="javascript:media_1()"><i class="fa-regular fa-image"></i></a></span>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <label for="desc" class="col-xl-2 control-label">
            <?=$this->getTrans('description') ?>:
        </label>
        <div class="col-xl-4">
            <textarea class="form-control"
                      id="desc"
                      name="desc" 
                      cols="45" 
                      rows="3"><?=$this->escape($this->originalInput('desc', $link->getDesc())) ?></textarea>
        </div>
    </div>
    <div class="row mb-3 <?=$this->validation()->hasError('catId') ? 'has-error' : '' ?>">
        <label for="catId" class="col-xl-2 control-label">
            <?=$this->getTrans('category') ?>:
        </label>
        <div class="col-xl-4">
            <select class="form-control" id="catId" name="catId">
                <option value="0" <?=($this->originalInput('catId', $link->getCatId()) == 0) ? ' selected' : '' ?>>-- <?=$this->getTrans('optionNoCategory') ?> --</option>
            <?php foreach ($cats as $model) : ?>
                <option value="<?=$model->getId() ?>"<?=($this->originalInput('catId', $link->getCatId()) == $model->getId()) ? ' selected' : '' ?>><?=$this->escape($model->getName()) ?></option>
            <?php endforeach; ?>
            </select>
        </div>
    </div>
    <?=$this->getSaveBar($link->getId() ? 'updateButton' : 'addButton') ?>
</form>

<?=$this->getDialog('mediaModal', $this->getTrans('media'), '<iframe style="border: none;"></iframe>') ?>
<script>
<?=$this->getMedia()
        ->addMediaButton($this->getUrl('admin/media/iframe/index/type/single/input/_1/'))
        ->addInputId('_1')
        ->addUploadController($this->getUrl('admin/media/index/upload'))
?>
</script>
