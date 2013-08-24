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
			}

			#install_steps {
				width:100%;
			}
		</style>
</head>
<body>
	<div class="navbar navbar-inverse navbar-fixed-top">
		<div class="navbar-inner">
			<div class="container-fluid">
				<a class="brand" href="#">Ilch CMS <?php echo VERSION; ?></a>
			</div>
		</div>
	</div>
	<div class="container-fluid">
		<div class="row-fluid">
			<div class="span3">
				<div class="tabbable tabs-left">
					<ul class="nav nav-tabs" id="install_steps">
						<li class="<?=(!isset($_GET['action'])|| $_GET['action'] == 'index') ? 'active': ''?>"><a data-toggle="tab">Willkommen / Sprache</a></li>
						<li class="<?=$_GET['action'] == 'license' ? 'active': ''?>"><a data-toggle="tab">Lizenz</a></li>
						<li class="<?=$_GET['action'] == 'systemcheck' ? 'active': ''?>"><a data-toggle="tab">System überprüfung</a></li>
						<li class="<?=$_GET['action'] == 'database' ? 'active': ''?>"><a data-toggle="tab">Datenbank</a></li>
						<li class="<?=$_GET['action'] == 'config' ? 'active': ''?>"><a data-toggle="tab">Konfiguration</a></li>
						<li class="<?=$_GET['action'] == 'finish' ? 'active': ''?>"><a data-toggle="tab">Fertig</a></li>
					</ul>
				</div>
			</div>
			<div class="span9">
				<div class="hero-unit">
					<?php echo $this->getContent(); ?>
				</div>
			</div>
		</div>
		<hr>
		<footer>
			<p>&copy; Ilch 2013</p>
		</footer>
	</div>
</body>
</html>