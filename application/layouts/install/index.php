<!DOCTYPE html>
<html lang="de">
<head>
		<meta charset="utf-8">
		<title>Ilch <?php echo VERSION; ?> - Installation</title>
		<meta name="description" content="Ilch - Installation">
		<link href="<?php echo $this->staticUrl('css/bootstrap.min.css'); ?>" rel="stylesheet">
		<script src="<?php echo $this->staticUrl('js/jquery-1.7.min.js'); ?>"></script>
		<link rel="shortcut icon" href="<?php echo $this->staticUrl('img/logo.png'); ?>">
		<style type="text/css">
			body {
				padding-top: 60px;
				padding-bottom: 40px;
				background-image: url(<?php echo $this->staticUrl('img/bg_grey.png'); ?>);
			}

			#install_steps {
				width: 100%;
				margin-bottom: 0px;
			}

			#install_container {
				box-sizing:border-box;
				height: 100%;
			}

			.content {
				width:600px;
				margin-left:200px;
				border:1px solid #ddd;
				position: absolute;
				-webkit-box-shadow: 15px 15px 30px #222;
				-moz-box-shadow: 15px 15px 30px #222;
				box-shadow: 15px 15px 30px #222;
			}

			.content .install_content{
				height: 350px;
				border-radius: 6px 6px 0px 0px;
				margin-bottom: 0px;
			}

			.content .save_box {
				background-color: #EEE;
				border-radius: 0px 0px 6px 6px;
			}

			.menu {
				padding-top: 10px;
				/*loat: left;*/
				width: 200px;
				position: absolute;
			}

			.tabs-left .nav-tabs > li > a {
				background-color: white;
				-webkit-box-shadow: 15px 15px 30px #222;
				-moz-box-shadow: 15px 15px 30px #222;
				box-shadow: 15px 15px 30px #222;
				
			}

			#install_steps .active a {
				background-color: #EEE;
			}
		</style>
</head>
<body>
	<div class="container">
		<div class="menu">
			<div class="tabbable tabs-left">
				<ul class="nav nav-tabs" id="install_steps">
					<li class="<?=$this->getRequest()->getActionName() == 'index' ? 'active': ''?>">
						<a data-toggle="tab"><?php echo $this->getTranslator()->trans('menuWelcomeAndLanguage'); ?></a>
					</li>
					<li class="<?=$this->getRequest()->getActionName() == 'license' ? 'active': ''?>">
						<a data-toggle="tab"><?php echo $this->getTranslator()->trans('menuLicence'); ?></a>
					</li>
					<li class="<?=$this->getRequest()->getActionName() == 'systemcheck' ? 'active': ''?>">
						<a data-toggle="tab"><?php echo $this->getTranslator()->trans('menuSystemCheck'); ?></a>
					</li>
					<li class="<?=$this->getRequest()->getActionName() == 'database' ? 'active': ''?>">
						<a data-toggle="tab"><?php echo $this->getTranslator()->trans('menuDatabase'); ?></a>
					</li>
					<li class="<?=$this->getRequest()->getActionName() == 'config' ? 'active': ''?>">
						<a data-toggle="tab"><?php echo $this->getTranslator()->trans('menuConfig'); ?></a>
					</li>
					<li class="<?=$this->getRequest()->getActionName() == 'finish' ? 'active': ''?>">
						<a data-toggle="tab"><?php echo $this->getTranslator()->trans('menuFinish'); ?></a>
					</li>
				</ul>
			</div>
		</div>
		<div class="content hero-unit">
			<form method="POST" action="<?php echo $this->url('install', 'index', $this->getRequest()->getActionName()); ?>">
				<div class="install_content">
					<input type="hidden" value="1" name="save" />
					<?php echo $this->getContent(); ?>
				</div>
				<div class="save_box">
					<button type="submit" class="btn">Next</button>
				</div>
			</form>
		</div>
	</div>
</body>
</html>