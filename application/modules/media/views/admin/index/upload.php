<link href="<?=$this->getBaseUrl('application/modules/media/static/css/media.css') ?>" rel="stylesheet">
<legend><?=$this->getTrans('mediaUpload') ?> <a title='<?='post_max_size = '.ini_get('post_max_size') ?><br/><?='max_execution_time = '.ini_get('max_execution_time') ?>' rel='tooltip' data-html='true' data-placement='bottom'><i class="fa fa-info-circle"></i></a></legend>
<form id="upload" method="post" action="<?=$this->getUrl('index.php/admin/media/index/upload') ?>" enctype="multipart/form-data">
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
   $(document).ready(function(){
	$("[rel='tooltip']").tooltip();
    });
</script>
