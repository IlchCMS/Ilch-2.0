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
				background-image: url(<?php echo $this->staticUrl('img/install/bg_grey.png'); ?>);
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
					<?php
						foreach($this->menu as $key => $values)
						{
							?>
								<li class="<?=$this->getRequest()->getActionName() == $key ? 'active': ''?>">
									<a data-toggle="tab">
										<?php
											echo $this->getTranslator()->trans($values['langKey']);

											if(isset($values['done']))
											{
												echo ' (Done)';
											}
										?>
									</a>
								</li>
						<?php
						}
					?>
				</ul>
			</div>
		</div>
		<div class="content hero-unit">
			<form class="form-inline" method="POST" action="<?php echo $this->url('install', 'index', $this->getRequest()->getActionName()); ?>">
				<div class="install_content">
					<?php echo $this->getContent(); ?>
				</div>
				<div class="save_box">
					<button type="submit" name="save" class="btn"><?php echo $this->getTranslator()->trans('nextButton'); ?></button>
				</div>
			</form>
		</div>
	</div>
</body>
</html>