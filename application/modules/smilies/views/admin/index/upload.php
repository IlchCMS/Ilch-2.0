<?php $ilchUpload = new \Ilch\Upload(); ?>

<link href="<?=$this->getBaseUrl('application/modules/media/static/css/media.css') ?>" rel="stylesheet">

<h1><?=$this->getTrans('upload') ?></h1>
<form id="upload" method="post" action="" enctype="multipart/form-data">
    <?=$this->getTokenField() ?>
    <div id="drop">
        <p><?=$this->getTrans('drag') ?></p>
        <i class="fa fa-cloud-upload"></i>
        <p><?=$this->getTrans('or') ?></p>
        <a class="btn btn-small btn-primary"><?=$this->getTrans('browse') ?></a>
        <input type="file" name="upl" multiple />
    </div>
    <ul><!-- Uploads --></ul>
</form>

<script src="<?=$this->getBaseUrl('application/modules/media/static/js/jquery.knob.js') ?>"></script>
<script src="<?=$this->getBaseUrl('application/modules/media/static/js/jquery.ui.widget.js') ?>"></script>
<script src="<?=$this->getBaseUrl('application/modules/media/static/js/jquery.iframe-transport.js') ?>"></script>
<script src="<?=$this->getBaseUrl('application/modules/media/static/js/jquery.fileupload.js') ?>"></script>
<script src="<?=$this->getBaseUrl('application/modules/media/static/js/script.js') ?>"></script>


<script language="javascript">
const allowedExtensions = <?=json_encode(explode(' ', $this->get('allowedExtensions'))); ?>;
var maxFileSize = <?=$ilchUpload->returnBytes(ini_get('upload_max_filesize')); ?>;
var fileTooBig = <?=json_encode($this->getTrans('fileTooBig'));?>;
var extensionNotAllowed = <?=json_encode($this->getTrans('extensionNotAllowed')); ?>;
$(document).ready(function(){
    $("[rel='tooltip']").tooltip();
});
</script>
