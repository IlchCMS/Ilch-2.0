<!DOCTYPE html>
<html lang="de">
	<head>
		<meta charset="utf-8">
		<title>Ilch <?php echo VERSION; ?> - Admincenter</title>
		<meta name="description" content="Ilch - Login">
		<link href="<?php echo $this->staticUrl('css/bootstrap.min.css'); ?>" rel="stylesheet">
		<link href="<?php echo $this->staticUrl('css/font-awesome.min.css'); ?>" rel="stylesheet">
		<link href="<?php echo $this->staticUrl('css/global.css'); ?>" rel="stylesheet">
		<link href="<?php echo $this->staticUrl('css/modules/admin/main.css'); ?>" rel="stylesheet">

		<script src="<?php echo $this->staticUrl('js/jquery-1.7.min.js'); ?>"></script>
		<script src="<?php echo $this->staticUrl('js/bootstrap.js'); ?>"></script>
		<script src="<?php echo $this->staticUrl('js/modules/admin/functions.js'); ?>"></script>

	</head>
	<body>
		<nav class="navbar navbar-default navbar-fixed-top topnavbar">
			<div class="navbar-header leftbar">
				<a class="navbar-brand" href="#">Ilch</a>
				<ul class="nav navbar-nav">
					<li class="dropdown">
						 <a href="#" id="search" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-search"></i> <?php echo $this->trans('search'); ?><b class="caret"></b></a>
					</li>
				</ul>
			</div>
			<div class="collapse navbar-collapse navbar-ex1-collapse">
				<ul class="nav navbar-nav">
					<li>
						<a href="<?php echo $this->url(array('module' => 'admin', 'controller' => 'index', 'action' => 'index')); ?>">
							<i class="icon-home"></i> <?php echo $this->trans('home'); ?>
						</a>
					</li>
					<li>
						<a href="#<?php echo $this->url(array('module' => 'admin', 'controller' => 'navigation', 'action' => 'index')); ?>">
							<i class="icon-th-list"></i> <?php echo $this->trans('navigation'); ?>
						</a>
					</li>
					<li class="dropdown">
						<a class="dropdown-toggle" data-toggle="dropdown" href="<?php echo $this->url(array('controller' => 'modules', 'controller' => 'index', 'action' => 'index')); ?>">
							<i class="icon-puzzle-piece"></i> <?php echo $this->trans('modules'); ?>
							<b class="caret"></b>
						</a>
						<ul class="dropdown-menu">
							<?php
								foreach($this->get('modules') as $module)
								{
									echo '<li>
											<a href="'.$this->url(array('module' => $module->getKey(), 'controller' => 'index', 'action' => 'index')).'">
												<img style="padding-right: 5px;" src="'.$this->staticUrl('img/modules/'.$module->getKey().'/'.$module->getIconSmall()).'" />'
												.$module->getName($this->getTranslator()->getLocale()).'</a>
										</li>';
								}
							?>
						</ul>
					</li>
					<li>
						<a href="#<?php echo $this->url(array('module' => 'admin', 'controller' => 'layouts', 'action' => 'index')); ?>">
							<i class="icon-picture"></i> <?php echo $this->trans('layouts'); ?>
						</a>
					</li>
					<li>
						<a href="<?php echo $this->url(array('module' => 'admin', 'controller' => 'settings', 'action' => 'index')); ?>">
							<i class="icon-wrench"></i> <?php echo $this->trans('system'); ?>
						</a>
					</li>
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						<i class="icon-user"></i> <?php echo $this->getUser()->getName(); ?>
						<span class="caret"></span>
					</a>
						<ul class="dropdown-menu">
							<li><a href="#">Profil</a></li>
							<li class="divider"></li>
							<li>
								<a href="<?php echo $this->url(array('module' => 'admin', 'controller' => 'login', 'action' => 'logout'))?>">
									<i class="icon-off"></i> <?php echo $this->trans('logout');?>
								</a>
							</li>
						</ul>
					</li>
				</ul>
			</div>
		</nav>
		<div id="app">
				<?php
					$contentFullClass = 'app_right_full';

					if(($this->getRequest()->getControllerName() !== 'index' && $this->getRequest()->getModuleName() == 'admin') || $this->getRequest()->getModuleName() !== 'admin')
					{
						$contentFullClass = '';
				?>
					<div class="app_left">
						<i class="icon-angle-left toggleSidebar slideLeft"></i>
						<div id="sidebar_content">
							<ul class="nav nav-list">
								<?php
									foreach($this->getMenus() as $key => $items)
									{
										echo '<li class="nav-header">'.$this->trans($key).'</li>';

										foreach($items as $key)
										{
											$class = '';

											if($key['active'])
											{
												$class = 'active';
											}

											echo '<li class="'.$class.'">
												      <a href="'.$key['url'].'"><i class="'.$key['icon'].'"></i> '.$this->trans($key['name']).'</a>
												  </li>';
										}
									}

									$actions = $this->getMenuAction();

									if(!empty($actions))
									{
										echo '<li class="divider"></li>';

										foreach($actions as $action)
										{
											echo '<li>
													  <a href="'.$action['url'].'"><i class="'.$action['icon'].'"></i> '.$this->trans($action['name']).'</a>
												  </li>';
										}
									}
								?>
							</ul>
							<img class="watermark" src="<?php echo $this->staticUrl('img/ilch_logo_sw.png'); ?>" />
						</div>
					</div>
				<?php
					}
				?>

			<div class="app_right <?php echo $contentFullClass?>">
				<i class="toggleSidebar slideRight"></i>
				<?php echo $this->getContent(); ?>
			</div>
		</div>

		<script>
			$('.toggleSidebar').on('click', toggleSidebar);
		</script>
	</body>
</html>