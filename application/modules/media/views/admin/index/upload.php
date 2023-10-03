<?php $ilchUpload = new \Ilch\Upload(); ?>

<link href="<?=$this->getBaseUrl('application/modules/media/static/css/media.css') ?>" rel="stylesheet">

<h1><?=$this->getTrans('mediaUpload') ?> <a title='<?='post_max_size = '.ini_get('post_max_size') ?><br/><?='max_execution_time = '.ini_get('max_execution_time') ?>' data-bs-toggle='tooltip' data-bs-html='true' data-bs-placement='bottom'><i class="fa fa-info-circle"></i></a></h1>
<form id="upload" method="post" action="<?=$this->getUrl('admin/media/index/upload') ?>" enctype="multipart/form-data">
    <?=$this->getTokenField() ?>
    <div id="drop">
        <p><?=$this->getTrans('drag') ?></p>
        <i class="fa fa-cloud-upload"></i>
        <p><?=$this->getTrans('or') ?></p>
        <a class="btn btn-sm btn-primary"><?=$this->getTrans('browse') ?></a>
        <input type="file" name="upl" multiple />
    </div>
    <ul><!-- Uploads --></ul>
</form>

<script src="<?=$this->getBaseUrl('application/modules/media/static/js/jquery.knob.min.js') ?>"></script>
<script src="<?=$this->getVendorUrl('blueimp/jquery-file-upload/js/vendor/jquery.ui.widget.js') ?>"></script>
<script src="<?=$this->getVendorUrl('blueimp/jquery-file-upload/js/jquery.iframe-transport.js') ?>"></script>
<script src="<?=$this->getVendorUrl('blueimp/jquery-file-upload/js/jquery.fileupload.js') ?>"></script>
<script src="<?=$this->getBaseUrl('application/modules/media/static/js/script.js') ?>"></script>

<script>
const allowedExtensions = <?=json_encode(explode(' ', $this->get('allowedExtensions'))) ?>;
var maxFileSize = <?=$ilchUpload->returnBytes(ini_get('upload_max_filesize')) ?>;
var fileTooBig = <?=json_encode($this->getTrans('fileTooBig')) ?>;
var extensionNotAllowed = <?=json_encode($this->getTrans('extensionNotAllowed')) ?>;
$(document).ready(function() {
    $("[rel='tooltip']").tooltip();
});
</script>
