<!DOCTYPE html>
<html lang="de">
	<head>
		<meta charset="utf-8">
		<title>Ilch <?php echo VERSION; ?> - Login</title>
		<meta name="description" content="Ilch - Login">
		<link href="<?php echo $this->staticUrl('css/bootstrap.min.css'); ?>" rel="stylesheet">
		<link href="<?php echo $this->staticUrl('css/admin/login.css'); ?>" rel="stylesheet">
		<script src="<?php echo $this->staticUrl('js/jquery-1.7.min.js'); ?>"></script>
	</head>
	<body>
		<div class="container login_container box_shadow">

			<form class="">
				<h3 class="form-signin-heading">Please sign in</h3>
				<input type="text" class="input-block-level" placeholder="Email address">
				<input type="password" class="input-block-level" placeholder="Password">
				<label class="checkbox">
				<input type="checkbox" value="remember-me"> Remember me
				</label>
				<button class="btn" type="submit">Sign in</button>
			</form>

		</div>
	</body>
</html>