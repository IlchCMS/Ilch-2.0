<?php if ($this->getUser() == null): ?>
    <legend><?=$this->getTrans('menuLogin') ?></legend>
    <form class="form-horizontal" action="" method="post">
        <?=$this->getTokenField() ?>
        <input type="hidden" name="login_redirect_url" value="<?=$this->get('redirectUrl');?>" />
        <?php $errors = $this->get('errors'); ?>
        <div class="form-group <?php if (!empty($errors['login_emailname'])) { echo 'has-error'; }; ?>">
            <label for="login_emailname" class="col-lg-2 control-label">
                <?=$this->getTrans('nameEmail') ?>:
            </label>
            <div class="col-lg-8">
                <div class="input-group">
                    <span class="input-group-addon" id="basic-addon1"><i class="fa fa-user"></i></span>
                    <input type="text"
                           class="form-control"
                           id="login_emailname"
                           name="login_emailname" />
                </div>
                <?php if (!empty($errors['login_emailname'])): ?>
                    <span class="help-inline"><?=$this->getTrans($errors['login_emailname']) ?></span>
                <?php endif; ?>
            </div>
        </div>
        <div class="form-group <?php if (!empty($errors['login_password'])) { echo 'has-error'; }; ?>">
            <label for="login_password" class="col-lg-2 control-label">
                <?=$this->getTrans('password') ?>:
            </label>
            <div class="col-lg-8">
                <div class="input-group">
                    <span class="input-group-addon" id="basic-addon1"><i class="fa fa-lock"></i></span>
                    <input type="password"
                           class="form-control"
                           id="login_password"
                           name="login_password" />
                </div>
                <?php if (!empty($errors['login_password'])): ?>
                    <span class="help-inline"><?=$this->getTrans($errors['login_password']) ?></span>
                <?php endif; ?>
            </div>
        </div>
        <label class="col-lg-offset-2 col-lg-8">
            <input type="checkbox"
                   name="rememberMe"
                   value="rememberMe"> <?=$this->getTrans('rememberMe') ?>
        </label>
        <div class="clearfix">   
            <div class="col-lg-10" align="right">
                <input type="submit" 
                       class="btn" 
                       name="login" 
                       value="<?=$this->getTrans('login') ?>" />
            </div>
        </div>  
    </form>
    <div class="col-lg-offset-2 col-lg-8">
            <a href="<?=$this->getUrl(['module' => 'user', 'controller' => 'login', 'action' => 'forgotpassword']) ?>"><?=$this->getTrans('forgotPassword') ?></a><br />
            <a href="<?=$this->getUrl(['module' => 'user', 'controller' => 'regist', 'action' => 'confirm']) ?>"><?=$this->getTrans('manuallyActivateRegistration') ?></a>

    </div>
    <?php if ($this->get('regist_accept') == '1'): ?>
        <br /><br /><br />
        <legend><?=$this->getTrans('menuRegist') ?></legend>
        <p>
            <?=$this->getTrans('registDescription') ?>
        </p>
        <p>
            <a href="<?=$this->getUrl(['module' => 'user', 'controller' => 'regist', 'action' => 'index']) ?>" class="btn btn-default pull-left">
                <?=$this->getTrans('register') ?>
            </a>
        </p>
    <?php endif; ?>
<?php else: ?>
    <div class="center-block"><p><h4 class="text-center"><?=$this->getTrans('alreadyLoggedIn') ?></h4></p></div>
    <div class="row text-center">
        <a class="btn btn-default" href="<?=$this->getUrl() ?>"><?=$this->getTrans('back') ?></a>
    </div>
<?php endif; ?>
