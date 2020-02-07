<?php
/**
 * View script for the admin login page.
 *
 * @copyright Ilch 2.0
 * @package ilch
 */

$languages = (!empty($this->get('languages'))) ? $this->get('languages') : [];
?>

<!DOCTYPE html>
<html lang="de">
    <head>
        <meta charset="utf-8">
        <title>Ilch - Login</title>
        <meta name="description" content="Ilch - Login">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">

        <link rel="shortcut icon" type="image/x-icon" href="<?=$this->getStaticUrl('img/favicon.ico') ?>">
        <link href="<?=$this->getVendorUrl('twbs/bootstrap/dist/css/bootstrap.min.css') ?>" rel="stylesheet">
        <link href="<?=$this->getVendorUrl('fortawesome/font-awesome/css/all.min.css') ?>" rel="stylesheet">
        <link href="<?=$this->getVendorUrl('fortawesome/font-awesome/css/v4-shims.min.css') ?>" rel="stylesheet">
        <link href="<?=$this->getStaticUrl('css/ilch.css') ?>" rel="stylesheet">
        <link href="<?=$this->getStaticUrl('../application/modules/admin/static/css/login.css') ?>" rel="stylesheet">
        <link href="<?=$this->getVendorUrl('components/jqueryui/themes/ui-lightness/jquery-ui.min.css') ?>" rel="stylesheet">

        <script src="<?=$this->getVendorUrl('components/jquery/jquery.min.js') ?>"></script>
        <script src="<?=$this->getVendorUrl('components/jqueryui/jquery-ui.min.js') ?>"></script>
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="login_container">
                    <div class="form_container">
                        <img class="logo" src="<?=$this->getStaticUrl('img/ilch_logo.png') ?>"  alt="ilch logo"/>
                        <?php $errors = $this->get('errors'); ?>
                        <?php $emailname = $this->get('emailname'); ?>
                        <form class="form-signin" method="POST" action="<?=$this->getUrl(['module' => 'admin', 'controller' => 'login', 'action' => 'index']) ?>">
                            <?=$this->getTokenField() ?>
                            <h4><?=$this->getTrans('loginWelcome') ?></h4>
                            <div class="form-group <?php if (!empty($errors)) { echo 'has-error'; } ?>">
                                <div class="input-group">
                                    <span class="input-group-addon" id="basic-addon1"><i class="fa fa-user"></i></span>
                                    <input type="text"
                                           class="form-control"
                                           name="emailname"
                                           placeholder="<?=$this->getTrans('emailname') ?>"
                                           value="<?=$this->escape($emailname) ?>">
                                </div>
                            </div>
                            <div class="form-group <?php if (!empty($errors)) { echo 'has-error'; } ?>">
                                <div class="input-group">
                                    <span class="input-group-addon" id="basic-addon1"><i class="fa fa-lock"></i></span>
                                    <input type="password"
                                           class="form-control"
                                           name="password"
                                           placeholder="<?=$this->getTrans('password') ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="language" class="control-label"><?=$this->getTrans('language') ?></label>
                                <select class="form-control" name="language" id="language">
                                    <option value="">Standard</option>
                                    <?php foreach ($languages as $key => $value): ?>
                                        <option value="<?=$key ?>"><?=$this->escape($value) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <button type="submit" class="btn"><?=$this->getTrans('signIn') ?></button>
                            <br />
                            <?php if (!empty($errors)): ?>
                                <?php foreach ($errors as $transKey): ?>
                                    <span class="text-danger"><?=$this->getTrans($transKey) ?></span>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
