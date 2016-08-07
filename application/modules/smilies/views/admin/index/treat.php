<legend><?=$this->getTrans('edit') ?></legend>
<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <div class="form-group">
        <label for="name" class="col-lg-2 control-label">
            <?=$this->getTrans('name') ?>:
        </label>
        <div class="col-lg-4">
            <input type="text"
                   class="form-control"
                   id="name"
                   name="name"
                   value="<?=$this->escape($this->get('smilie')->getName()) ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="selectedImage" class="col-lg-2 control-label">
            <?=$this->getTrans('image') ?>:
        </label>
        <div class="col-lg-4">
            <div class="input-group">
                <input type="text"
                       class="form-control"
                       id="selectedImage"
                       name="url"
                       value="<?=$this->escape($this->get('smilie')->getUrl()) ?>" />
                <span class="input-group-addon"><a id="media" href="javascript:media()"><i class="fa fa-picture-o"></i></a></span>
            </div>
        </div>
    </div>
    <?=$this->getSaveBar('updateButton') ?>
</form>

<?=$this->getDialog('mediaModal', $this->getTrans('media'), '<iframe frameborder="0"></iframe>'); ?>
<script>
<?=$this->getMedia()
        ->addMediaButton($this->getUrl('admin/smilies/iframe/smilies/'))
?>
</script>
