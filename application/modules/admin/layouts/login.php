<?php
/**
 * View script for the admin login page.
 *
 * @copyright Ilch 2.0
 * @package ilch
 */
?>
<!DOCTYPE html>
<html lang="de">
    <head>
        <meta charset="utf-8">
        <title>Ilch - Login</title>
        <meta name="description" content="Ilch - Login">
        <link rel="shortcut icon" type="image/x-icon" href="<?php echo $this->getStaticUrl('img/favicon.ico'); ?>">
        <link href="<?php echo $this->getStaticUrl('css/bootstrap.css'); ?>" rel="stylesheet">
        <link href="<?php echo $this->getStaticUrl('css/ilch.css'); ?>" rel="stylesheet">
        <link href="<?php echo $this->getStaticUrl('../application/modules/admin/static/css/login.css'); ?>" rel="stylesheet">
        <link href="<?php echo $this->getStaticUrl('css/ui-lightness/jquery-ui.css'); ?>" rel="stylesheet">
        <script src="<?php echo $this->getStaticUrl('js/jquery.js'); ?>"></script>
        <script src="<?php echo $this->getStaticUrl('js/jquery-ui.js'); ?>"></script>
    </head>
    <body>
        <div class="login_container">
            <img class="logo" src="<?php echo $this->getStaticUrl('img/ilch_logo.png'); ?>" />
            <?php
                $errors = $this->get('errors');
                $emailname = $this->get('emailname');
            ?>
            <form class="form-signin" method="POST" action="<?php echo $this->getUrl(array('module' => 'admin', 'controller' => 'login', 'action' => 'index'))?>">
                <?php echo $this->getTokenField(); ?>
                <h4><?php echo$this->getTrans('loginWelcome')?></h4>
                <div class="form-group <?php if (!empty($errors)) { echo 'has-error'; }; ?>">
                    <input type="text"
                           name="emailname"
                           class="form-control"
                           placeholder="<?php echo $this->getTrans('emailname')?>"
                           value="<?php echo $this->escape($emailname); ?>">
                </div>
                <div class="form-group <?php if (!empty($errors)) { echo 'has-error'; }; ?>">
                    <input type="password"
                           name="password"
                           class="form-control"
                           placeholder="<?php echo $this->getTrans('password')?>">
                </div>
                <div class="form-group">
                    <select class="form-control" name="language">
                        <option value="">Standard</option>
                        <?php
                        foreach ($this->get('languages') as $key => $value) {
                            echo '<option value="'.$key.'">'.$this->escape($value).'</option>';
                        }
                        ?>
                </select>
                </div>
                <button class="btn" type="submit"><?php echo $this->getTrans('signIn')?></button>
                <br />
                <?php
                    if (!empty($errors)) {
                        foreach ($errors as $transKey) {
                            echo '<span class="text-danger">'.$this->getTrans($transKey).'</span>';
                        }
                    }
                ?>
            </form>
        </div>
    </body>
</html>
