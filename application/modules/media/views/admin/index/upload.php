<legend><?php echo $this->trans('mediaUpload'); ?></legend>
	<div class="row">
		<div class="col-lg-12">
	<form id="upload" method="post" action="<?php echo $this->Url('index.php/admin/media/index/upload'); ?>" enctype="multipart/form-data">
			<?php echo $this->getTokenField(); ?>
        <div id="drop">
				<p>Drop Here</p>

				<a>Browse</a>
				<input type="file" name="upl" multiple />
			</div>

			<ul>
				<!-- The file uploads will be shown here -->
			</ul>

		</form>
		</div>
		</div>

<div class="content_savebox">
            <a class="btn btn-default pull-left" href="javascript:history.back()" role="button">Abbrechen</a>
        </div>
        
        <!-- MEDIA -->
        <link href="<?php echo $this->staticUrl('../application/modules/media/static/css/media.css'); ?>" rel="stylesheet">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
		<script src="<?php echo $this->staticUrl('../application/modules/media/static/js/jquery.knob.js'); ?>"></script>
        <script src="<?php echo $this->staticUrl('../application/modules/media/static/js/jquery.ui.widget.js'); ?>"></script>
		<script src="<?php echo $this->staticUrl('../application/modules/media/static/js/jquery.iframe-transport.js'); ?>"></script>
		<script src="<?php echo $this->staticUrl('../application/modules/media/static/js/jquery.fileupload.js'); ?>"></script>
		<script src="<?php echo $this->staticUrl('../application/modules/media/static/js/script.js'); ?>"></script>

		
