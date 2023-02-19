<h1><?=$this->getTrans('settings') ?></h1>
<form class="form-horizontal" method="POST">
    <?=$this->getTokenField() ?>
    <div class="form-group">
        <label for="selectedImage_1" class="col-lg-2 control-label">
            <?=$this->getTrans('favicon') ?>:
        </label>
        <div class="col-lg-4">
            <div class="input-group">
                <input type="text"
                       class="form-control"
                       id="selectedImage_1"
                       name="favicon"
                       placeholder="<?=$this->getTrans('choose') ?>"
                       value="<?=$this->escape($this->get('favicon')) ?>"
                       readonly />
                <span class="input-group-addon">
                    <a href="javascript:eraseValue('selectedImage_1')">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                </span>
                <span class="input-group-addon">
                    <a href="javascript:media_1()" id="media">
                        <i class="fa-regular fa-image"></i>
                    </a>
                </span>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="selectedImage_2" class="col-lg-2 control-label">
            <?=$this->getTrans('appleIcon') ?>:
        </label>
        <div class="col-lg-4">
            <div class="input-group">
                <input type="text"
                       class="form-control"
                       id="selectedImage_2"
                       name="appleIcon"
                       placeholder="<?=$this->getTrans('choose') ?>"
                       value="<?=$this->escape($this->get('appleIcon')) ?>"
                       readonly />
                <span class="input-group-addon">
                    <a href="javascript:eraseValue('selectedImage_2')">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                </span>
                <span class="input-group-addon">
                    <a href="javascript:media_2()" id="media">
                        <i class="fa-regular fa-image"></i>
                    </a>
                </span>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="pageTitle" class="col-lg-2 control-label">
            <?=$this->getTrans('pageTitle') ?>:
        </label>
        <div class="col-lg-4">
            <input type="text"
                   class="form-control"
                   id="pageTitle"
                   name="pageTitle"
                   value="<?=$this->escape($this->get('pageTitle')) ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="keywords" class="col-lg-2 control-label">
            <?=$this->getTrans('seoKeywords') ?>:
        </label>
        <div class="col-lg-4">
            <textarea class="form-control"
                      id="keywords"
                      name="keywords"><?=$this->escape($this->get('keywords')) ?></textarea>
        </div>
    </div>
    <div class="form-group">
        <label for="description" class="col-lg-2 control-label">
            <?=$this->getTrans('seoDescription') ?>:
        </label>
        <div class="col-lg-4">
            <textarea class="form-control"
                      id="description"
                      name="description"><?=$this->escape($this->get('description')) ?></textarea>
        </div>
    </div>
    <?=$this->getSaveBar() ?>
</form>

<?=$this->getDialog('mediaModal', $this->getTrans('media'), '<iframe frameborder="0"></iframe>') ?>
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

$('#keywords').tokenfield();
$('#keywords').on('tokenfield:createtoken', function (event) {
    var existingTokens = $(this).tokenfield('getTokens');
    $.each(existingTokens, function(index, token) {
        if (token.value === event.attrs.value)
            event.preventDefault();
    });
});
</script>
