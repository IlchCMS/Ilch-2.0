<?php if ($this->getUser() !== null): ?>
    <?=$this->getTrans('hello') ?> <b><?=$this->escape($this->getUser()->getName()) ?></b>,
    <br />
    <a href="<?=$this->getUrl(['module' => 'user', 'controller' => 'panel', 'action' => 'index']) ?>">
        <?=$this->getTrans('userPanel') ?>
    </a>
    <br />
    <div class="ilch--new-message"></div>
    <?php if ($this->get('userAccesses') || $this->getUser()->isAdmin()): ?>
        <a target="_blank" href="<?=$this->getUrl(['module' => 'admin', 'controller' => 'admin', 'action' => 'index']) ?>">
            <?=$this->getTrans('admincenter') ?>
        </a>
        <br />
    <?php endif; ?>
    <a href="<?=$this->getUrl(['module' => 'admin/admin', 'controller' => 'login', 'action' => 'logout', 'from_frontend' => 1]) ?>">
        <?=$this->getTrans('logout') ?>
    </a>
<?php else: ?>
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
    <form action="<?=$this->getUrl(['module' => 'user', 'controller' => 'login', 'action' => 'index']) ?>" class="form-horizontal" method="post">
        <input type="hidden" name="login_redirect_url" value="<?=$this->escape($this->get('redirectUrl')) ?>" />
        <?php
        echo $this->getTokenField();
        $errors = $this->get('errors');
        ?>
        <div class="form-group">
            <div class="col-lg-12">
                <div class="input-group">
                    <span class="input-group-addon" id="basic-addon1"><i class="fa-solid fa-user"></i></span>
                    <input type="text"
                           class="form-control"
                           name="login_emailname"
                           placeholder="<?=$this->getTrans('nameEmail') ?>"
                           autocomplete="username" />
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="col-lg-12">
                <div class="input-group">
                    <span class="input-group-addon" id="basic-addon1"><i class="fa-solid fa-lock"></i></span>
                    <input type="password"
                           class="form-control"
                           name="login_password"
                           placeholder="<?=$this->getTrans('password') ?>"
                           autocomplete="current-password" />
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="col-lg-12">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="rememberMe" value="rememberMe"> <?=$this->getTrans('rememberMe') ?>
                    </label>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="col-lg-12">
                <button type="submit" class="btn" name="login">
                    <?=$this->getTrans('login') ?>
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
    <?php if ($this->get('regist_accept') == '1'): ?>
        <a href="<?=$this->getUrl(['module' => 'user', 'controller' => 'regist', 'action' => 'index']) ?>"><?=$this->getTrans('register') ?></a><br />
    <?php endif; ?>
    <a href="<?=$this->getUrl(['module' => 'user', 'controller' => 'login', 'action' => 'forgotpassword']) ?>"><?=$this->getTrans('forgotPassword') ?></a>
<?php endif; ?>

<?php if ($this->getUser() !== null): ?>
    <script>
        $(document).ready(function () {
            let notificationsDiv = $(".ilch--new-message"),
                messageCheckLink = "<?=$this->getUrl(['module' => 'user', 'controller' => 'ajax','action' => 'checknewmessage']) ?>",
                openFriendRequestsCheckLink = "<?=$this->getUrl(['module' => 'user', 'controller' => 'ajax','action' => 'checknewfriendrequests']) ?>",
                globalStore = [];

            function loadNotifications()
            {
                $.when(
                    $.get(messageCheckLink, function(newMessages) {
                        globalStore['newMessages'] = newMessages;
                    }),

                    $.get(openFriendRequestsCheckLink, function(newFriendRequests) {
                        globalStore['newFriendRequests'] = newFriendRequests;
                    }),
                ).then(function() {
                    notificationsDiv.html(globalStore['newMessages']);
                    notificationsDiv.append(globalStore['newFriendRequests'])
                });
            }

            loadNotifications();
            setInterval(loadNotifications, 60000);
        });
    </script>
<?php endif; ?>
