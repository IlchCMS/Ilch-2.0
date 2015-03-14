<link href="<?php echo $this->getStaticUrl('../application/modules/media/static/css/media.css'); ?>" rel="stylesheet">
<legend><?php echo $this->getTrans('mediaUpload'); ?></legend>
<div class="container">
    <form id="upload" method="post" action="<?php echo $this->getUrl('index.php/admin/media/index/upload'); ?>" enctype="multipart/form-data">
    <?php echo $this->getTokenField(); ?>
        <div id="drop">
            <p>drag and drop files here</p>
            <i class="fa fa-cloud-upload"></i>
            <p>or</p>
            <a class="btn btn-small btn-primary">Browse</a>
            <input type="file" name="upl" multiple />
        </div>
        <ul><!-- Uploads --></ul>
    </form>
</div>

<script src="<?php echo $this->getStaticUrl('../application/modules/media/static/js/jquery.knob.js'); ?>"></script>
<script src="<?php echo $this->getStaticUrl('../application/modules/media/static/js/jquery.ui.widget.js'); ?>"></script>
<script src="<?php echo $this->getStaticUrl('../application/modules/media/static/js/jquery.iframe-transport.js'); ?>"></script>
<script src="<?php echo $this->getStaticUrl('../application/modules/media/static/js/jquery.fileupload.js'); ?>"></script>
<script src="<?php echo $this->getStaticUrl('../application/modules/media/static/js/script.js'); ?>"></script>
