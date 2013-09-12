<?php
/**
 * View script for the admin login page.
 *
 * @author Jainta Martin
 * @copyright Ilch CMS 2.0
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
		<link href="<?php echo $this->staticUrl('css/admin/login.css'); ?>" rel="stylesheet">
		<script src="<?php echo $this->staticUrl('js/jquery-1.7.min.js'); ?>"></script>
	</head>
	<body>
		<div class="container login_container box_shadow">
			<?php
				$errors = $this->get('errors');
				$email = $this->get('email');
				$error = empty($errors) ? '' : 'error';
			?>
			<form method="POST" action="<?php echo $this->url(array('module' => 'admin', 'controller' => 'login', 'action' => 'index'))?>">
				<h3 class="form-signin-heading"><?php echo$this->trans('loginWelcome')?></h3>
				<div class="control-group <?php if(!empty($errors)){ echo 'error'; }; ?>">
					<div class="controls">
						<input type="text"
							   name="email"
							   class="input-block-level <?php echo $error?>"
							   placeholder="<?php echo $this->trans('email')?>"
							   value="<?php echo $this->escape($email); ?>">
					</div>

					<div class="controls">
						<input type="password"
							   name="password"
							   class="input-block-level <?php echo $error?>"
							   placeholder="<?php echo $this->trans('password')?>">
					</div>

					<button class="btn" type="submit"><?php echo $this->trans('signIn')?></button>
					<?php
						if(!empty($errors))
						{
							foreach($errors as $transKey)
							{
								echo '<span class="help-inline">'.$this->trans($transKey).'</span>';
							}
						}
					?>
				</div>
			</form>
		</div>
	</body>
</html>