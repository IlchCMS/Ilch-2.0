<link href="<?php echo $this->getStaticUrl('../application/modules/media/static/css/media.css'); ?>" rel="stylesheet">
<legend><?php echo $this->getTrans('mediaUpload'); ?></legend>
<div class="row">
    <div class="col-lg-12">
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
</div>
<div class="content_savebox">
    <a class="btn btn-default pull-left" href="javascript:history.back()" role="button">Abbrechen</a>
</div>
        
<!-- MEDIA -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="<?php echo $this->getStaticUrl('../application/modules/media/static/js/jquery.knob.js'); ?>"></script>
<script src="<?php echo $this->getStaticUrl('../application/modules/media/static/js/jquery.ui.widget.js'); ?>"></script>
<script src="<?php echo $this->getStaticUrl('../application/modules/media/static/js/jquery.iframe-getTransport.js'); ?>"></script>
<script src="<?php echo $this->getStaticUrl('../application/modules/media/static/js/jquery.fileupload.js'); ?>"></script>
<script src="<?php echo $this->getStaticUrl('../application/modules/media/static/js/script.js'); ?>"></script>

		
