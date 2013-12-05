    <?php foreach ($this->get('catname') as $catname) : ?>
        
                <legend>Bilder hochladen nach: <?php echo $catname['name']; ?></legend>
    <?php endforeach; ?>
	<link href="<?php echo $this->staticUrl('css/modules/gallery/style.css'); ?>" rel="stylesheet" />
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
	<form id="upload" method="post" action="<?php echo $this->staticUrl('index.php/admin/gallery/index/saveimage/id/'.$this->getRequest()->getParam('id').''); ?>" enctype="multipart/form-data">
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
		<div class="row">
		<a class="btn btn-default pull-left" href="javascript:history.back()" role="button">Abbrechen</a>
		</div>
        
		<!-- JavaScript Includes -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
		<script src="<?php echo $this->staticUrl('js/modules/gallery/jquery.knob.js'); ?>"></script>

		<!-- jQuery File Upload Dependencies -->
		<script src="<?php echo $this->staticUrl('js/modules/gallery/jquery.ui.widget.js'); ?>"></script>
		<script src="<?php echo $this->staticUrl('js/modules/gallery/jquery.iframe-transport.js'); ?>"></script>
		<script src="<?php echo $this->staticUrl('js/modules/gallery/jquery.fileupload.js'); ?>"></script>
		
		<!-- Our main JS file -->
		<script src="<?php echo $this->staticUrl('js/modules/gallery/script.js'); ?>"></script>
