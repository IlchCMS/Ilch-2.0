<?php if($this->getUser() == null): ?>
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
                    <input class="form-control"
                           name="login_emailname"
                           id="login_emailname"
                           type="text" />
                    <?php if (!empty($errors['login_emailname'])): ?>
                        <span class="help-inline"><?=$this->getTrans($errors['login_emailname']) ?></span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="form-group <?php if (!empty($errors['login_password'])) { echo 'has-error'; }; ?>">
            <label for="login_password" class="col-lg-2 control-label">
                <?=$this->getTrans('password') ?>:
            </label>
            <div class="col-lg-8">
                <div class="input-group">
                    <span class="input-group-addon" id="basic-addon1"><i class="fa fa-lock"></i></span>
                    <input class="form-control"
                           name="login_password"
                           id="login_password"
                           type="password" />
                    <?php if (!empty($errors['login_password'])): ?>
                        <span class="help-inline"><?=$this->getTrans($errors['login_password']) ?></span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        
        <div class="clearfix">   
            <!--            
            <div class="col-lg-offset-2 col-lg-8 pull-left">
                <label class="checkbox-inline">
                    <input type="checkbox" 
                           name="regist_confirm"
                           id="regist_confirm_yes" 
                           value="1" />
                           <label for="regist_confirm">Mich bei jedem Besuch automatisch anmelden</label>
                </label>
            </div>
            -->
            <div class="col-lg-10" align="right">
                <input type="submit" 
                       name="login" 
                       class="btn" 
                       value="<?=$this->getTrans('login') ?>" />
            </div>
        </div>  
    </form>
    <div class="col-lg-offset-2 col-lg-8">
            <a href="<?=$this->getUrl(array('module' => 'user', 'controller' => 'login', 'action' => 'forgotpassword')) ?>"><?=$this->getTrans('forgotPassword') ?></a><br />
            <a href="<?=$this->getUrl(array('module' => 'user', 'controller' => 'regist', 'action' => 'confirm')) ?>">Die Aktivierung Manuell freischalten</a>

    </div>
    <?php if ($this->get('regist_accept') == '1'): ?>
        <br /><br /><br />
        <legend><?=$this->getTrans('menuRegist') ?></legend>
        <p>
            Die Registrierung ist in wenigen Augenblicken erledigt und ermöglicht ihnen, auf weitere Funktionen zuzugreifen. Die Administration kann registrierten Benutzern auch zusätzliche Berechtigungen zuweisen.
        </p>
        <p>
            <a href="<?=$this->getUrl(array('module' => 'user', 'controller' => 'regist', 'action' => 'index')) ?>" class="btn btn-default pull-left">
                <?=$this->getTrans('register') ?>
            </a>
        </p>
    <?php endif; ?>
<?php endif; ?>
