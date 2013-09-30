<?php
/**
 * View script for the admin login page.
 *
 * @author Jainta Martin
 * @copyright Ilch Pluto
 * @package ilch
 */
?>
<!DOCTYPE html>
<html lang="de">
	<head>
		<meta charset="utf-8">
		<title>Ilch <?php echo VERSION; ?> - Login</title>
		<meta name="description" content="Ilch - Login">
		<link href="<?php echo $this->staticUrl('css/bootstrap.min.css'); ?>" rel="stylesheet">
		<link href="<?php echo $this->staticUrl('css/global.css'); ?>" rel="stylesheet">
		<link href="<?php echo $this->staticUrl('css/modules/admin/login.css'); ?>" rel="stylesheet">
		<script src="<?php echo $this->staticUrl('js/jquery-1.7.min.js'); ?>"></script>
	</head>
	<body>
		<div class="container login_container">
			<img class="logo" src="<?php echo $this->staticUrl('img/ilch_logo.png'); ?>" />
			<?php
				$errors = $this->get('errors');
				$email = $this->get('email');
				$error = empty($errors) ? '' : 'error';
			?>
			<form class="form-horizontal" method="POST" action="<?php echo $this->url(array('module' => 'admin', 'controller' => 'login', 'action' => 'index'))?>">
				<h3 class="form-signin-heading"><?php echo$this->trans('loginWelcome')?></h3>
				<div class="form-group <?php if(!empty($errors)){ echo 'error'; }; ?>">
					<div class="col-lg-12">
						<input type="text"
							   name="email"
							   class="form-control <?php echo $error?>"
							   placeholder="<?php echo $this->trans('email')?>"
							   value="<?php echo $this->escape($email); ?>">
					</div>

					<div class="col-lg-12">
						<input type="password"
							   name="password"
							   class="form-control <?php echo $error?>"
							   placeholder="<?php echo $this->trans('password')?>">
					</div>

					<button class="btn" type="submit"><?php echo $this->trans('signIn')?></button>
					<?php
						if(!empty($errors))
						{
							foreach($errors as $transKey)
							{
								echo '<span class="help-block">'.$this->trans($transKey).'</span>';
							}
						}
					?>
				</div>
			</form>
		</div>
	</body>
</html>