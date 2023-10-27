<?php $linkus = $this->get('linkus'); ?>

<h1>
    <?php if ($linkus != ''): ?>
        <?=$this->getTrans('edit') ?>
    <?php else: ?>
        <?=$this->getTrans('add') ?>
    <?php endif; ?>
</h1>
<form class="form-horizontal" method="POST" action="<?=$this->getUrl(['action' => $this->getRequest()->getActionName(), 'id' => $this->getRequest()->getParam('id')]) ?>">
    <?=$this->getTokenField() ?>
    <div class="row mb-3 <?=$this->validation()->hasError('title') ? 'has-error' : '' ?>">
        <label for="title" class="col-xl-2 control-label">
            <?=$this->getTrans('title') ?>:
        </label>
        <div class="col-xl-4">
            <input type="text"
                   class="form-control"
                   id="title"
                   name="title"
                   value="<?=($linkus != '') ? $this->escape($linkus->getTitle()) : $this->escape($this->get('post')['title']) ?>" />
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
                       value="<?=($linkus != '') ? $this->escape($linkus->getBanner()) : $this->escape($this->get('post')['banner']) ?>"
                       readonly />
                <span class="input-group-text"><a id="media" href="javascript:media_1()"><i class="fa-regular fa-image"></i></a></span>
            </div>
        </div>
    </div>
    <?=($linkus != '') ? $this->getSaveBar('updateButton') : $this->getSaveBar('addButton') ?>
</form>

<?=$this->getDialog('mediaModal', $this->getTrans('media'), '<iframe frameborder="0"></iframe>') ?>
<script>
<?=$this->getMedia()
        ->addMediaButton($this->getUrl('admin/media/iframe/index/type/single/input/_1/'))
        ->addInputId('_1')
        ->addUploadController($this->getUrl('admin/media/index/upload'))
?>
</script>
