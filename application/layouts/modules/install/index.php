<!DOCTYPE html>
<html lang="de">
	<head>
		<meta charset="utf-8">
		<title>Ilch <?php echo VERSION; ?> - Installation</title>
		<meta name="description" content="Ilch - Installation">
		<link href="<?php echo $this->staticUrl('css/bootstrap.min.css'); ?>" rel="stylesheet">
		<link href="<?php echo $this->staticUrl('css/global.css'); ?>" rel="stylesheet">
		<link href="<?php echo $this->staticUrl('css/modules/install/install.css'); ?>" rel="stylesheet">
		<script src="<?php echo $this->staticUrl('js/jquery-1.7.min.js'); ?>"></script>
	</head>
	<body>
		<form class="form-horizontal" method="POST" action="<?php echo $this->url(array('action' => $this->getRequest()->getActionName())); ?>">
			<div class="container install_container">
				<img class="logo" src="<?php echo $this->staticUrl('img/ilch_logo.png'); ?>" />
				<ul class="nav nav-tabs" id="install_steps">
					<?php
						$done = 1;
						$menuCounter = count($this->get('menu'));
						$lastAction = '';

						foreach($this->get('menu') as $key => $values)
						{
							if(isset($values['done']))
							{
								$done++;
								$lastAction = $key;
							}
							?>
								<li class="<?php echo $this->getRequest()->getActionName() == $key ? 'active': ''; ?>">
									<a data-toggle="tab">
										<?php echo $this->trans($values['langKey']); ?>
									</a>
								</li>
						<?php
						}

						$progress = 100 / $menuCounter * $done;
					?>
				</ul>
				<br />
				<div class="progress  progress-striped">
					<div class="progress-bar progress-bar-success"
						role="progressbar"
						aria-valuenow="<?php echo $progress; ?>"
						aria-valuemin="0"
						aria-valuemax="100"
						style="width: <?php echo $progress; ?>%;">
					</div>
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
						<a href="<?php echo $this->url(array('action' => $lastAction)); ?>" class="btn pull-left">
							<?php echo $this->trans('backButton'); ?>
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

								echo $this->trans($buttonTrans);
							?>
						</button>
				<?php
					}
				?>
			</div>
		</form>
	</body>
</html>