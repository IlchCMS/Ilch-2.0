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
			
			.form-horizontal .control-group .control-label {
				text-align: left;
			}

			#install_steps {
				padding-left: 120px;
			}

			.container {
				padding: 60px;
				background-color: white;
			}
			
			.install_container {
				border:1px solid #ddd;
				width: 900px;
				min-height: 500px;
				-webkit-box-shadow: 15px 15px 30px #222;
				-moz-box-shadow: 15px 15px 30px #222;
				box-shadow: 15px 15px 30px #222;
				border-radius: 6px 6px 0px 0px;
			}

			.save_box {
				padding:20px;
				position: relative;
				border:1px solid #ddd;
				width: 980px;
				-webkit-box-shadow: 15px 15px 30px #222;
				-moz-box-shadow: 15px 15px 30px #222;
				box-shadow: 15px 15px 30px #222;
				border-radius: 0px 0px 6px 6px;
			}
			
		</style>
</head>
<body>
	<form class="form-horizontal" method="POST" action="<?php echo $this->url('install', 'index', $this->getRequest()->getActionName()); ?>">
		<div class="container install_container">
			<ul class="nav nav-tabs" id="install_steps">
				<?php
					$done = 1;
					$menuCounter = count($this->menu);
					$lastAction = '';

					foreach($this->menu as $key => $values)
					{
						if(isset($values['done']))
						{
							$done++;
							$lastAction = $key;
						}
						?>
							<li class="<?php echo $this->getRequest()->getActionName() == $key ? 'active': ''; ?>">
								<a data-toggle="tab">
									<?php echo $this->getTranslator()->trans($values['langKey']); ?>
								</a>
							</li>
					<?php
					}

					$progress = 100 / $menuCounter * $done;
				?>
			</ul>
			<div class="progress progress-success progress-striped">
				<div class="bar" style="width: <?=$progress?>%"></div>
			</div>
				<div class="install_content">
					<?php echo $this->getContent(); ?>
				</div>
		</div>
		<div class="container save_box">
			<?php
				if(!in_array($this->getRequest()->getActionName(), array('index', 'finish')))
				{
			?>
					<a href="<?php echo $this->url('install', 'index', $lastAction); ?>" class="btn pull-left">
						<?php echo $this->getTranslator()->trans('backButton'); ?>
					</a>
			<?php
				}
			?>

			<?php
				if($this->getRequest()->getActionName() != 'finish')
				{
			?>
					<button type="submit" name="save" class="btn pull-right">
						<?php 
							$buttonTrans = 'nextButton';

							if($this->getRequest()->getActionName() == 'config')
							{
								$buttonTrans = 'installButton';
							}

							echo $this->getTranslator()->trans($buttonTrans);
						?>
					</button>
			<?php
				}
			?>
		</div>
	</form>
</body>
</html>