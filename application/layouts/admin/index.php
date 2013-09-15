<!DOCTYPE html>
<html lang="de">
	<head>
		<meta charset="utf-8">
		<title>Ilch <?php echo VERSION; ?> - Admincenter</title>
		<meta name="description" content="Ilch - Login">
		<link href="<?php echo $this->staticUrl('css/bootstrap.min.css'); ?>" rel="stylesheet">
		<link href="<?php echo $this->staticUrl('css/admin/main.css'); ?>" rel="stylesheet">
		<script src="<?php echo $this->staticUrl('js/jquery-1.7.min.js'); ?>"></script>
		<script src="<?php echo $this->staticUrl('js/bootstrap.js'); ?>"></script>
	</head>
	<body>
		<div id="sidebar">
			<div class="navbar">
				<div class="navbar-inner navbar-sidebar">
					<span class="brand">Ilch 2.0</span>
					<ul class="nav pull-right">
						<li class="dropdown">
							 <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-search"></i> Suche<b class="caret"></b></a>
						</li>
					</ul>
				</div>
			</div>
			<ul class="nav nav-tabs nav-stacked">
				<li class="nav-header">
					Navigation
				</li>
				<?php
					foreach($this->get('menu') as $menu)
					{
						echo '<li><a href="#">Library</a></li>';
					}
				?>
			</ul>
		</div>
		<div id="app">
			<div class="navbar">
				<div class="navbar-inner navbar-app">
					<ul class="nav">
						<li>
							<a href="<?php echo $this->url(array('module' => 'admin', 'controller' => 'index', 'action' => 'index')); ?>">
								<i class="icon-home"></i> <?php echo $this->trans('home'); ?>
							</a>
						</li>
						<li>
							<a href="<?php echo $this->url(array('module' => 'admin', 'controller' => 'navigation', 'action' => 'index')); ?>">
								<i class="icon-th-list"></i> <?php echo $this->trans('navigation'); ?>
							</a>
						</li>
						<li class="dropdown">
							<a class="dropdown-toggle" data-toggle="dropdown" href="<?php echo $this->url(array('controller' => 'modules', 'controller' => 'index', 'action' => 'index')); ?>">
								<i class="icon-lock"></i> <?php echo $this->trans('modules'); ?>
								<b class="caret"></b>
							</a>
							<ul class="dropdown-menu">
								<?php
									foreach($this->get('modules') as $module)
									{
										echo '<li>
												<a href="'.$this->url(array('module' => $module->getKey(), 'controller' => 'index', 'action' => 'index')).'">'
													.$module->getName($this->getTranslator()->getLocale())
												.'</a>
											</li>';
									}
								?>
							</ul>
						</li>
						<li>
							<a href="<?php echo $this->url(array('module' => 'admin', 'controller' => 'layouts', 'action' => 'index')); ?>">
								<i class="icon-picture"></i> <?php echo $this->trans('layouts'); ?>
							</a>
						</li>
						<li>
							<a href="<?php echo $this->url(array('module' => 'admin', 'controller' => 'settings', 'action' => 'index')); ?>">
								<i class="icon-wrench"></i> <?php echo $this->trans('system'); ?>
							</a>
						</li>
					</ul>
					<ul class="nav pull-right">
						<li class="dropdown">
							<a class="dropdown-toggle" data-toggle="dropdown" href="#">
								<i class="icon-user"></i> <?php echo $this->getUser()->getName(); ?> <b class="caret"></b>
							</a>
							<ul class="dropdown-menu">
								<li>
									<a href="<?php echo $this->url(array('module' => 'admin', 'controller' => 'login', 'action' => 'logout'))?>">
										<i class="icon-off"></i> <?php echo $this->trans('logout');?>
									</a>
								</li>
							</ul>
						</li>
					</ul>
				</div>
			</div>
			<div class="app_content">
				<?php echo $this->getContent(); ?>
			</div>
		</div>
	</body>
</html>