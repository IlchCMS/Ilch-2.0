<?php
/**
 * View script for the admin login page.
 *
 * @author Jainta Martin
 * @copyright Ilch 2.0
 * @package ilch
 */
?>
<!DOCTYPE html>
<html lang="de">
	<head>
		<meta charset="utf-8">
		<title>Ilch <?php echo VERSION; ?> - Login</title>
		<meta name="description" content="Ilch - Login">
		<link href="<?php echo $this->staticUrl('css/bootstrap.css'); ?>" rel="stylesheet">
		<link href="<?php echo $this->staticUrl('css/global.css'); ?>" rel="stylesheet">
		<link href="<?php echo $this->staticUrl('css/modules/admin/login.css'); ?>" rel="stylesheet">
		<link href="<?php echo $this->staticUrl('css/ui-lightness/jquery-ui.css'); ?>" rel="stylesheet">
		<script src="<?php echo $this->staticUrl('js/jquery.js'); ?>"></script>
		<script src="<?php echo $this->staticUrl('js/jquery-ui.js'); ?>"></script>
	</head>
	<body>
		<div class="login_container">
			<img class="logo" src="<?php echo $this->staticUrl('img/ilch_logo.png'); ?>" />
			<?php
				$errors = $this->get('errors');
				$emailname = $this->get('emailname');
			?>
			<form class="form-signin" method="POST" action="<?php echo $this->url(array('module' => 'admin', 'controller' => 'login', 'action' => 'index'))?>">
				<h4><?php echo$this->trans('loginWelcome')?></h4>
				<div class="form-group <?php if(!empty($errors)){ echo 'has-error'; }; ?>">
					<input type="text"
						   name="emailname"
						   class="form-control"
						   placeholder="<?php echo $this->trans('emailname')?>"
						   value="<?php echo $this->escape($emailname); ?>">
				</div>
				<div class="form-group <?php if(!empty($errors)){ echo 'has-error'; }; ?>">
					<input type="password"
						   name="password"
						   class="form-control"
						   placeholder="<?php echo $this->trans('password')?>">
				</div>
				<button class="btn" type="submit"><?php echo $this->trans('signIn')?></button>
				<br />
				<?php
					if(!empty($errors))
					{
						foreach($errors as $transKey)
						{
							echo '<span class="text-danger">'.$this->trans($transKey).'</span>';
						}
					}
				?>
			</form>
		</div>
	</body>
</html>