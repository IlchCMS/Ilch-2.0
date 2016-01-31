<link href="<?=$this->getBaseUrl('application/modules/media/static/css/media.css') ?>" rel="stylesheet">

<ul class="nav nav-pills">
    <li><a href="<?=$this->getUrl(array('controller' => 'iframe', 'action' => 'upload', 'id' => $this->getRequest()->getParam('id'))) ?>"><?=$this->getTrans('upload') ?></a></li>
    <li><a href="<?=$_SESSION['media-url-media-button'] ?><?=$this->getRequest()->getParam('id') ?>"><?=$this->getTrans('media') ?></a></li>
</ul>

<legend><?=$this->getTrans('mediaUpload') ?></legend>
<div class="container">
    <form id="upload" method="post" action="<?=$_SESSION['media-url-upload-controller'] ?>" enctype="multipart/form-data">
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
</div>

<script src="<?=$this->getBaseUrl('application/modules/media/static/js/jquery.knob.js') ?>"></script>
<script src="<?=$this->getBaseUrl('application/modules/media/static/js/jquery.ui.widget.js') ?>"></script>
<script src="<?=$this->getBaseUrl('application/modules/media/static/js/jquery.iframe-transport.js') ?>"></script>
<script src="<?=$this->getBaseUrl('application/modules/media/static/js/jquery.fileupload.js') ?>"></script>
<script src="<?=$this->getBaseUrl('application/modules/media/static/js/script.js') ?>"></script>
