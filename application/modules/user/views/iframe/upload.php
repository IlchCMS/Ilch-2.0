<?php $ilchUpload = new \Ilch\Upload(); ?>

<link href="<?=$this->getBaseUrl('application/modules/media/static/css/media.css') ?>" rel="stylesheet">

<ul class="nav nav-pills">
    <li><a href="<?=$this->getUrl(['controller' => 'iframe', 'action' => 'upload', 'id' => $this->getRequest()->getParam('id')]) ?>"><?=$this->getTrans('upload') ?></a></li>
    <li><a href="<?=$_SESSION['media-url-media-button'] ?><?=$this->getRequest()->getParam('id') ?>"><?=$this->getTrans('media') ?></a></li>
</ul>

<h1><?=$this->getTrans('mediaUpload') ?></h1>
<div class="container">
    <form id="upload" method="post" action="<?=$_SESSION['media-url-upload-controller'] ?>" enctype="multipart/form-data">
        <?=$this->getTokenField() ?>
        <div id="drop">
            <p><?=$this->getTrans('drag') ?></p>
            <i class="fa-solid fa-cloud-arrow-up"></i>
            <p><?=$this->getTrans('or') ?></p>
            <a class="btn btn-small btn-primary"><?=$this->getTrans('browse') ?></a>
            <input type="file" name="upl" multiple />
        </div>
        <ul><!-- Uploads --></ul>
    </form>
</div>

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
