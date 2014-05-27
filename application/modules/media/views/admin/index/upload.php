<link href="<?php echo $this->getStaticUrl('../application/modules/media/static/css/media.css'); ?>" rel="stylesheet">
<legend><?php echo $this->getTrans('mediaUpload'); ?> <a title='<?php echo 'post_max_size = ' . ini_get('post_max_size');?><br/><?php echo 'max_execution_time = ' . ini_get('max_execution_time'); ?>' rel='tooltip' data-html='true' data-placement='bottom'><i class="fa fa-info-circle"></i></a></legend>
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

<script src="<?php echo $this->getStaticUrl('../application/modules/media/static/js/jquery.knob.js'); ?>"></script>
<script src="<?php echo $this->getStaticUrl('../application/modules/media/static/js/jquery.ui.widget.js'); ?>"></script>
<script src="<?php echo $this->getStaticUrl('../application/modules/media/static/js/jquery.iframe-transport.js'); ?>"></script>
<script src="<?php echo $this->getStaticUrl('../application/modules/media/static/js/jquery.fileupload.js'); ?>"></script>
<script src="<?php echo $this->getStaticUrl('../application/modules/media/static/js/script.js'); ?>"></script>
<script language="javascript">
   $(document).ready(function(){
	$("[rel='tooltip']").tooltip();
    });
</script>