<!DOCTYPE html>
<html lang="de">
	<head>
		<meta charset="utf-8">
		<title>Ilch <?php echo VERSION; ?> - Login</title>
		<meta name="description" content="Ilch - Login">
		<link href="<?php echo $this->staticUrl('css/bootstrap.min.css'); ?>" rel="stylesheet">
		<link href="<?php echo $this->staticUrl('css/admin/main.css'); ?>" rel="stylesheet">
		<script src="<?php echo $this->staticUrl('js/jquery-1.7.min.js'); ?>"></script>
	</head>
	<body>
		<div id="sidebar">
			<div class="navbar">
				<div class="navbar-inner navbar-sidebar">
					<span class="brand">Ilch 2.0</span>
					<ul class="nav">
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">App Name<b class="caret"></b></a>
						</li>
					</ul>
				</div>
			</div>

			<ul class="nav nav-tabs nav-stacked">
				<li class="nav-header">
					Navigation
				</li>
				<li class="active">
					<a href="#">neue Seite anlegen</a>
				</li>
				<li>
					<a href="#">Library</a>
				</li>
				<li>
					<a href="#">Applications</a>
				</li>
				<li class="nav-header">
					Action
				</li>
				<li>
					<a href="#">Profile</a>
				</li>
				<li>
					<a href="#">Settings</a>
				</li>
				<li>
					<a href="#">Help</a>
				</li>
			</ul>
		</div>
		<div id="app">
			<div class="navbar">
				<div class="navbar-inner navbar-app">
					<!-- APP NAVBAR -->
				</div>
			</div>

			<div class="app_content">
				<!-- APP CONTENT -->
				<form class="form-horizontal">
					<div class="control-group warning">
						<label class="control-label" for="inputWarning">Input with warning</label>
						<div class="controls">
							<input type="text" id="inputWarning">
							<span class="help-inline">Something may have gone wrong</span>
						</div>
					</div>
					<div class="control-group error">
						<label class="control-label" for="inputError">Input with error</label>
						<div class="controls">
							<input type="text" id="inputError">
							<span class="help-inline">Please correct the error</span>
						</div>
					</div>
					<div class="control-group info">
						<label class="control-label" for="inputInfo">Input with info</label>
						<div class="controls">
							<input type="text" id="inputInfo">
							<span class="help-inline">Username is taken</span>
						</div>
					</div>
					<div class="control-group success">
						<label class="control-label" for="inputSuccess">Input with success</label>
						<div class="controls">
							<input type="text" id="inputSuccess">
							<span class="help-inline">Woohoo!</span>
						</div>
					</div>
				</form>
			</div>
		</div>
	</body>
</html>