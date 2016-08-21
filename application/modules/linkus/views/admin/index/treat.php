<?php $linkus = $this->get('linkus'); ?>
<legend>
    <?php if ($linkus != ''): ?>
        <?=$this->getTrans('edit') ?>
    <?php else: ?>
        <?=$this->getTrans('add') ?>
    <?php endif; ?>
</legend>
<?php if (!empty($this->get('errors'))): ?>
    <div class="alert alert-danger" role="alert">
        <strong> <?=$this->getTrans('errorsOccured') ?>:</strong>
        <ul>
            <?php foreach ($this->get('errors') as $error): ?>
                <li><?= $error; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
<form class="form-horizontal" method="POST" action="<?=$this->getUrl(['action' => $this->getRequest()->getActionName(), 'id' => $this->getRequest()->getParam('id')]) ?>">
    <?=$this->getTokenField() ?>
    <div class="form-group<?=in_array('title', $this->get('errorFields')) ? ' has-error' : '' ?>">
        <label for="title" class="col-lg-2 control-label">
            <?=$this->getTrans('title') ?>:
        </label>
        <div class="col-lg-4">
            <input type="text"
                   class="form-control"
                   id="title"
                   name="title"
                   value="<?php if ($linkus != '') { echo $this->escape($linkus->getTitle()); } else { echo $this->get('post')['title']; } ?>" />
        </div>
    </div>
    <div class="form-group<?=in_array('banner', $this->get('errorFields')) ? ' has-error' : '' ?>">
        <label for="selectedImage_1" class="col-lg-2 control-label">
            <?=$this->getTrans('banner') ?>:
        </label>
        <div class="col-lg-4">
            <div class="input-group">
                <input type="text"
                       class="form-control"
                       id="selectedImage_1"
                       name="banner"
                       value="<?php if ($linkus != '') { echo $this->escape($linkus->getBanner()); } else { echo $this->get('post')['banner']; } ?>" 
                       readonly />
                <span class="input-group-addon"><a id="media" href="javascript:media_1()"><i class="fa fa-picture-o"></i></a></span>
            </div>
        </div>
    </div>
    <?php if ($linkus != ''): ?>
        <?=$this->getSaveBar('updateButton') ?>
    <?php else: ?>
        <?=$this->getSaveBar('addButton') ?>
    <?php endif; ?>
</form>

<?=$this->getDialog('mediaModal', $this->getTrans('media'), '<iframe frameborder="0"></iframe>'); ?>
<script>
// Example for multiple input filds
<?=$this->getMedia()
        ->addMediaButton($this->getUrl('admin/media/iframe/index/type/single/input/_1/'))
        ->addInputId('_1')
        ->addUploadController($this->getUrl('admin/media/index/upload'))
?>
</script>
