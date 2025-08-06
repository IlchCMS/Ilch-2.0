<?php

/** @var \Ilch\View $this */

/** @var \Modules\Partner\Models\Partner $partner */
$partner = $this->get('partner');
?>
<h1><?=$this->getTrans($partner->getId() ? 'edit' : 'add') ?></h1>
<form method="POST">
    <?=$this->getTokenField() ?>
    <div class="row mb-3<?=$this->validation()->hasError('name') ? ' has-error' : '' ?>">
        <label for="name" class="col-xl-2 col-form-label">
            <?=$this->getTrans('name') ?>:
        </label>
        <div class="col-xl-6">
            <input type="text"
                   class="form-control"
                   id="name"
                   name="name"
                   placeholder="Name"
                   value="<?=$this->originalInput('name', $partner->getName(), true) ?>" />
        </div>
    </div>
    <div class="row mb-3">
        <label for="link" class="col-xl-2 col-form-label">
            <?=$this->getTrans('link') ?>:
        </label>
        <div class="col-xl-3<?=$this->validation()->hasError('link') || $this->validation()->hasError('target') ? ' has-error' : '' ?>">
            <input type="text"
                   class="form-control"
                   id="link"
                   name="link"
                   placeholder="http://"
                   value="<?=$this->originalInput('link', $partner->getLink(), true) ?>" />
        </div>
        <div class="col-xl-3">
            <select class="form-select" id="target" name="target">
                <option value="0"<?=$this->originalInput('target', $partner->getTarget()) == 0 ? ' selected="selected"' : '' ?>><?=$this->getTrans('targetBlank') ?></option>
                <option value="1"<?=$this->originalInput('target', $partner->getTarget()) == 1 ? ' selected="selected"' : '' ?>><?=$this->getTrans('targetSelf') ?></option>
            </select>
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('banner') ? ' has-error' : '' ?>">
        <label for="selectedImage_1" class="col-xl-2 col-form-label">
            <?=$this->getTrans('banner') ?>:
        </label>
        <div class="col-xl-6">
            <div class="input-group">
                <input type="text"
                       class="form-control"
                       id="selectedImage_1"
                       name="banner"
                       placeholder="<?=$this->getTrans('httpOrMedia') ?>"
                       value="<?=$this->originalInput('banner', $partner->getBanner(), true) ?>" />
                <span class="input-group-text"><a id="media" href="javascript:media_1()"><i class="fa-regular fa-image"></i></a></span>
            </div>
        </div>
    </div>
    <?=$this->getSaveBar($partner->getId() ? 'updateButton' : 'addButton') ?>
</form>

<?=$this->getDialog('mediaModal', $this->getTrans('media'), '<iframe frameborder="0"></iframe>') ?>
<script>
<?=$this->getMedia()
    ->addMediaButton($this->getUrl('admin/media/iframe/index/type/single/input/_1/'))
    ->addInputId('_1')
    ->addUploadController($this->getUrl('admin/media/index/upload'))
?>
</script>
