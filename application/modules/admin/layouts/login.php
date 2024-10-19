<?php

/** @var \Ilch\Layout\Base $this */

/**
 * View script for the admin login page.
 *
 * @copyright Ilch 2
 * @package ilch
 */

/** @var array $languages */
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
        <link href="<?=$this->getVendorUrl('npm-asset/jquery-ui/dist/themes/ui-lightness/jquery-ui.min.css') ?>" rel="stylesheet">

        <script src="<?=$this->getVendorUrl('npm-asset/jquery/dist/jquery.min.js') ?>"></script>
        <script src="<?=$this->getVendorUrl('npm-asset/jquery-ui/dist/jquery-ui.min.js') ?>"></script>
        <script src="<?=$this->getStaticUrl('../application/modules/admin/static/js/functions.js') ?>"></script>
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="login_container">
                    <div class="form_container">
                        <img class="logo" src="<?=$this->getStaticUrl('img/ilch_logo.png') ?>"  alt="ilch logo"/>
                        <form class="form-signin" method="POST" action="<?=$this->getUrl(['module' => 'admin', 'controller' => 'login', 'action' => 'index']) ?>">
                            <?=$this->getTokenField() ?>
                            <h4><?=$this->getTrans('loginWelcome') ?></h4>
                            <?=$this->getErrors() ?>
                            <div class="row mb-3<?=$this->getRequest()->getErrors()->hasError('emailname') ? ' has-error' : '' ?>">
                                <div class="input-group">
                                    <span class="input-group-text" id="basic-addon1"><label for="emailname"><i class="fa-solid fa-user"></i></label></span>
                                    <input type="text"
                                           class="form-control"
                                           name="emailname"
                                           id="emailname"
                                           placeholder="<?=$this->getTrans('emailname') ?>"
                                           value="<?=$this->escape($this->getRequest()->getOldInput('emailname')) ?>"
                                           autocomplete="username">
                                </div>
                            </div>
                            <div class="row mb-3<?=$this->getRequest()->getErrors()->hasError('password') ? ' has-error' : '' ?>">
                                <div class="input-group">
                                    <span class="input-group-text" id="basic-addon1"><label for="password"><i class="fa-solid fa-lock"></i></label></span>
                                    <input type="password"
                                           class="form-control"
                                           name="password"
                                           id="password"
                                           placeholder="<?=$this->getTrans('password') ?>"
                                           autocomplete="current-password">
                                </div>
                            </div>
                            <div class="row mb-3<?=$this->getRequest()->getErrors()->hasError('rememberMe') ? ' has-error' : '' ?>" style="padding: 0 13px;">
                                <div class="input-group">
                                    <span class="input-group-text" id="basic-addon1"><label for="rememberMe"><i class="fa-solid fa-bookmark"></i></label></span>
                                    <div class="form-control">
                                        <input class="form-check-input" type="checkbox" value="rememberMe" name="rememberMe" id="rememberMe" aria-label="<?=$this->getTrans('rememberMe') ?>">
                                    </div>
                                    <span class="input-group-text"><?=$this->getTrans('rememberMe') ?></span>
                                </div>
                            </div>
                            <div class="row mb-3<?=$this->getRequest()->getErrors()->hasError('language') ? ' has-error' : '' ?>" style="padding: 0 13px;">
                                <label for="language" class="col-form-label p-0"><?=$this->getTrans('language') ?></label>
                                <select class="form-select" name="language" id="language">
                                    <option value="default" <?=$this->getRequest()->getOldInput('language', 'default') == 'default' ? ' selected' : '' ?>><?=$this->getTrans('default') ?>Standard</option>
                                    <?php foreach ($languages as $key => $value) : ?>
                                        <option value="<?=$key ?>"<?=$this->getRequest()->getOldInput('language', $this->getTranslator()->getLocale()) == $key ? ' selected' : '' ?>><?=$this->escape($value) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-outline-secondary"><?=$this->getTrans('signIn') ?></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
