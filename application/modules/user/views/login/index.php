<?php if ($this->getUser() == null): ?>
    <script>$(document).ready(function(){
    $('.providers').on('click', function (e) {
        e.preventDefault();

        var myForm = $(this).closest('form')[0];
        myForm.action = this.href;// the href of the link
        myForm.method = "POST";
        myForm.submit();
        return false; // cancel the actual link
    });
    });</script>
    <form class="form-horizontal" method="post">
        <h1><?=$this->getTrans('menuLogin') ?></h1>
        <?=$this->getTokenField() ?>
        <input type="hidden" name="login_redirect_url" value="<?=$this->escape($this->get('redirectUrl')) ?>" />
        <div class="row mb-3 <?=$this->validation()->hasError('login_emailname') ? 'has-error' : '' ?>">
            <label for="login_emailname" class="col-xl-2 control-label">
                <?=$this->getTrans('nameEmail') ?>:
            </label>
            <div class="col-xl-10">
                <div class="input-group">
                    <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-user"></i></span>
                    <input type="text"
                           class="form-control"
                           id="login_emailname"
                           name="login_emailname"
                           autocomplete="username" />
                </div>
            </div>
        </div>
        <div class="row mb-3 <?=$this->validation()->hasError('login_password') ? 'has-error' : '' ?>">
            <label for="login_password" class="col-xl-2 control-label">
                <?=$this->getTrans('password') ?>:
            </label>
            <div class="col-xl-10">
                <div class="input-group">
                    <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-lock"></i></span>
                    <input type="password"
                           class="form-control"
                           id="login_password"
                           name="login_password"
                           autocomplete="current-password"/>
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="offset-xl-2 col-xl-10">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="rememberMe" value="rememberMe"> <?=$this->getTrans('rememberMe') ?>
                    </label>
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="offset-xl-2 col-xl-10">
                <button type="submit" name="login" class="btn btn-outline-secondary">
                    <i class="fa-solid fa-fw fa-right-to-bracket"></i> <?=$this->getTrans('login') ?>
                </button>
                <span class="social-logins">
                    <?php if (count($this->get('providers')) > 0): ?>
                        <i class="fa-solid fa-fw fa-angle-right"></i>
                    <?php endif; ?>
                    <?php foreach ($this->get('providers') as $provider): ?>
                        <a
                            class="btn btn-link providers provider-<?= $provider->getKey() ?>"
                            href="<?= $this->getUrl([
                                'module' => $provider->getModule(),
                                'controller' => $provider->getAuthController(),
                                'action' => $provider->getAuthAction()
                            ]) ?>"
                        >
                            <i class="fa-solid fa-2x fa-fw <?= $provider->getIcon() ?>"></i>
                        </a>
                    <?php endforeach; ?>
                </span>
            </div>
        </div>
    </form>
    <div class="offset-xl-2 col-xl-10">
            <a href="<?=$this->getUrl(['module' => 'user', 'controller' => 'login', 'action' => 'forgotpassword']) ?>"><?=$this->getTrans('forgotPassword') ?></a><br />
    </div>
    <?php if ($this->get('regist_accept') == '1'): ?>
        <br /><br /><br />
        <h1><?=$this->getTrans('menuRegist') ?></h1>
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
        <a class="btn btn-outline-secondary" href="<?=$this->getUrl() ?>"><?=$this->getTrans('back') ?></a>
    </div>
<?php endif; ?>
