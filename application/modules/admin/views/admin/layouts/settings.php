<legend><?=$this->getTrans('settings') ?></legend>
<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <div class="form-group">
        <label for="selectedImage_1" class="col-lg-2 control-label">
            <?=$this->getTrans('favicon') ?>:
        </label>
        <div class="col-lg-4">
            <div class="input-group">
                <?php if ($this->get('favicon')): ?>
                    <span class="input-group-addon"><a href="javascript:eraseValue('selectedImage_1')"><i class="fa fa-trash text-danger"></i></a></span>
                <?php endif; ?>
                <input type="text"
                       class="form-control"
                       id="selectedImage_1"
                       name="favicon"
                       placeholder="<?=$this->getTrans('httpOrMedia') ?>"
                       value="<?=$this->escape($this->get('favicon')); ?>"
                       readonly />
                <span class="input-group-addon"><a href="javascript:media_1()" id="media"><i class="fa fa-picture-o"></i></a></span>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="selectedImage_2" class="col-lg-2 control-label">
            <?=$this->getTrans('appleIcon') ?>:
        </label>
        <div class="col-lg-4">
            <div class="input-group">
                <?php if ($this->get('appleIcon')): ?>
                    <span class="input-group-addon"><a href="javascript:eraseValue('selectedImage_2')"><i class="fa fa-trash text-danger"></i></a></span>
                <?php endif; ?>
                <input type="text"
                       class="form-control"
                       id="selectedImage_2"
                       name="appleIcon"
                       placeholder="<?=$this->getTrans('httpOrMedia') ?>"
                       value="<?=$this->escape($this->get('appleIcon')); ?>"
                       readonly />
                <span class="input-group-addon"><a href="javascript:media_2()" id="media"><i class="fa fa-picture-o"></i></a></span>
            </div>
        </div>
    </div>
    <?=$this->getSaveBar() ?>
</form>

<?=$this->getDialog('mediaModal', $this->getTrans('media'), '<iframe frameborder="0"></iframe>'); ?>
<script>
<?=$this->getMedia()
        ->addMediaButton($this->getUrl('admin/media/iframe/index/type/single/input/_1/'))
        ->addInputId('_1')
        ->addUploadController($this->getUrl('admin/media/index/upload'))
?>
<?=$this->getMedia()
        ->addMediaButton($this->getUrl('admin/media/iframe/index/type/single/input/_2/'))
        ->addInputId('_2')
        ->addUploadController($this->getUrl('admin/media/index/upload'))
?>

function eraseValue(id) {
    document.getElementById(id).value = "";
}
</script>
