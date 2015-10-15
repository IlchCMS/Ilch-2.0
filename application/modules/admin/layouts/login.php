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
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">

        <link rel="shortcut icon" type="image/x-icon" href="<?=$this->getStaticUrl('img/favicon.ico') ?>">
        <link href="<?=$this->getStaticUrl('css/bootstrap.css') ?>" rel="stylesheet">
        <link href="<?=$this->getStaticUrl('css/font-awesome.css') ?>" rel="stylesheet">
        <link href="<?=$this->getStaticUrl('css/ilch.css') ?>" rel="stylesheet">
        <link href="<?=$this->getStaticUrl('../application/modules/admin/static/css/login.css') ?>" rel="stylesheet">
        <link href="<?=$this->getStaticUrl('css/ui-lightness/jquery-ui.css') ?>" rel="stylesheet">

        <script src="<?=$this->getStaticUrl('js/jquery.js') ?>"></script>
        <script src="<?=$this->getStaticUrl('js/jquery-ui.js') ?>"></script>
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="login_container">
                    <div class="form_container">
                        <img class="logo" src="<?=$this->getStaticUrl('img/ilch_logo.png') ?>" />
                        <?php $errors = $this->get('errors'); ?>
                        <?php $emailname = $this->get('emailname'); ?>
                        <form class="form-signin" method="POST" action="<?=$this->getUrl(array('module' => 'admin', 'controller' => 'login', 'action' => 'index')) ?>">
                            <?=$this->getTokenField() ?>
                            <h4><?=$this->getTrans('loginWelcome') ?></h4>
                            <div class="form-group <?php if (!empty($errors)) { echo 'has-error'; }; ?>">
                                <div class="input-group">
                                    <span class="input-group-addon" id="basic-addon1"><i class="fa fa-user"></i></span>
                                    <input type="text"
                                           name="emailname"
                                           class="form-control"
                                           placeholder="<?=$this->getTrans('emailname') ?>"
                                           value="<?=$this->escape($emailname) ?>">
                                </div>
                            </div>
                            <div class="form-group <?php if (!empty($errors)) { echo 'has-error'; }; ?>">
                                <div class="input-group">
                                    <span class="input-group-addon" id="basic-addon1"><i class="fa fa-lock"></i></span>
                                    <input type="password"
                                           name="password"
                                           class="form-control"
                                           placeholder="<?=$this->getTrans('password') ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <select class="form-control" name="language">
                                    <option value="">Standard</option>
                                    <?php foreach ($this->get('languages') as $key => $value): ?>
                                        <option value="<?=$key ?>"><?=$this->escape($value) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <button class="btn" type="submit"><?=$this->getTrans('signIn') ?></button>
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
