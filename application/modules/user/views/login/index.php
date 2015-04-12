<?php if($this->getUser() == null): ?>
    <form class="form-horizontal" action="" method="post">
        <?=$this->getTokenField() ?>
        <?php $errors = $this->get('errors'); ?>
    <div class="form-group <?php if (!empty($errors['loginContent_emailname'])) { echo 'has-error'; }; ?>">
        <label for="loginContent_emailname" class="col-lg-2 control-label">
            <?=$this->getTrans('name') ?>:
        </label>
        <div class="col-lg-8">
            <input class="form-control"
                   name="loginContent_emailname"
                   type="text"
                   placeholder="<?=$this->getTrans('nameEmail') ?>" />
            <?php if (!empty($errors['loginContent_emailname'])): ?>
                <span class="help-inline"><?=$this->getTrans($errors['loginContent_emailname']) ?></span>
            <?php endif; ?>
        </div>
    </div>
    <div class="form-group <?php if (!empty($errors['loginContent_password'])) { echo 'has-error'; }; ?>">
        <label for="loginContent_password" class="col-lg-2 control-label">
            <?=$this->getTrans('password') ?>:
        </label>
        <div class="col-lg-8">
            <input class="form-control"
                   name="loginContent_password"
                   type="password"
                   placeholder="<?=$this->getTrans('password') ?>" />
            <?php if (!empty($errors['loginContent_password'])): ?>
                <span class="help-inline"><?=$this->getTrans($errors['loginContent_password']) ?></span>
            <?php endif; ?>
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
            <div class="pull-right">
                <input type="submit" 
                       name="login" 
                       class="btn" 
                       value="<?=$this->getTrans('login') ?>" />
            </div>
        </div>  
    </form>


    <br />
    <div class="col-lg-offset-2 col-lg-8">
            <!--
            <a href="<?=$this->getUrl(array('module' => 'user', 'controller' => 'index', 'action' => 'index')) ?>"><?=$this->getTrans('forgotPassword') ?></a><br />
            -->
            <a href="<?=$this->getUrl(array('module' => 'user', 'controller' => 'regist', 'action' => 'confirm')) ?>">Die Aktivierung Manuell freischalten</a>

    </div>
    <?php if ($this->get('regist_accept') == '1'): ?>
    <br />
    <br />
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
