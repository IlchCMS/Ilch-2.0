<?php if($this->getUser() !== null): ?>
    <?=$this->getTrans('hello') ?> <b><?=$this->escape($this->getUser()->getName()) ?></b>,
    <br />
    <a href="<?=$this->getUrl(array('module' => 'user', 'controller' => 'panel', 'action' => 'index')) ?>">
        <?=$this->getTrans('panel') ?>
    </a>
    <br />
    <?php if($this->getUser()->isAdmin()): ?>
        <a target="_blank" href="<?=$this->getUrl(array('module' => 'admin', 'controller' => 'admin', 'action' => 'index')) ?>">
            <?=$this->getTrans('adminarea') ?>
        </a>
        <br />
    <?php endif; ?>
    <a href="<?=$this->getUrl(array('module' => 'admin/admin', 'controller' => 'login', 'action' => 'logout', 'from_frontend' => 1)) ?>">
        <?=$this->getTrans('logout') ?>
    </a>
<?php else: ?>
    <form action="" class="form-horizontal" method="post">
        <?=$this->getTokenField();
        $errors = $this->get('errors');
        ?>
        <div class="form-group">
            <div class="col-lg-12">
                <div class="input-group">
                    <span class="input-group-addon" id="basic-addon1"><i class="fa fa-user"></i></span>
                    <input name="loginbox_emailname"
                           class="form-control"
                           type="text"
                           placeholder="<?=$this->getTrans('nameEmail') ?>" />
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="col-lg-12">
                <div class="input-group">
                    <span class="input-group-addon" id="basic-addon1"><i class="fa fa-lock"></i></span>
                    <input name="loginbox_password"
                           class="form-control"
                           type="password"
                           placeholder="<?=$this->getTrans('password') ?>" />
                </div>
            </div>
        </div>
        <div class="form-group">
             <div class="col-lg-4">
                <button type="submit" class="btn" name="login">
                    <?=$this->getTrans('login') ?>
                </button>
             </div>
        </div>
    </form>
    <?php if ($this->get('regist_accept') == '1'): ?>
        <a href="<?=$this->getUrl(array('module' => 'user', 'controller' => 'regist', 'action' => 'index')) ?>"><?=$this->getTrans('register') ?></a><br />
    <?php endif; ?>
    <a href="<?=$this->getUrl(array('module' => 'user', 'controller' => 'login', 'action' => 'forgotpassword')) ?>"><?=$this->getTrans('forgotPassword') ?></a>
<?php endif; ?>
