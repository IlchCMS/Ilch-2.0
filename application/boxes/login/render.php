<?php if($this->getUser() !== null) { ?>
    <?php echo $this->getTrans('hello'); ?> <b><?php echo $this->escape($this->getUser()->getName()); ?></b>
    <br />
    <!--
    <a href="<?php echo $this->getUrl(array('module' => 'user', 'controller' => 'index', 'action' => 'index')); ?>"><?php echo $this->getTrans('message'); ?> (0)</a><br />
    <a href="<?php echo $this->getUrl(array('module' => 'user', 'controller' => 'index', 'action' => 'index')); ?>"><?php echo $this->getTrans('UserPanel'); ?></a><br />
    -->
    <a href="<?php echo $this->getUrl(array('module' => 'admin', 'controller' => 'admin', 'action' => 'index')); ?>">
        <?php echo $this->getTrans('adminarea'); ?>
    </a>
    <br />
    <a href="<?php echo $this->getUrl(array('module' => 'admin/admin', 'controller' => 'login', 'action' => 'logout', 'from_frontend' => 1)); ?>">
        <?php echo $this->getTrans('logout'); ?>
    </a>
    <?php }else{ ?>
        <form class="form-horizontal" action="" method="post">
           <?php echo $this->getTokenField();
                $errors = $this->get('errors');
            ?>
            <div class="form-group <?php if (!empty($errors['loginbox_emailname'])) { echo 'has-error'; }; ?>">
                <div class="col-lg-12 ">
                    <input class="form-control"
                           name="loginbox_emailname"
                           type="text"
                           placeholder="<?php echo $this->getTrans('nameEmail')?>" />
                </div>
            </div>
            <div class="form-group <?php if (!empty($errors['loginbox_password'])) { echo 'has-error'; }; ?>">
                <div class="col-lg-12">
                    <input class="form-control"
                           name="loginbox_password"
                           type="password"
                           placeholder="<?php echo $this->getTrans('password')?>" />
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-12">
                    <button type="submit" name="login" class="btn">
                        <?php echo $this->getTrans('login'); ?>
                    </button>
                </div>
            </div>
        </form>
        <div style="font-size: 13px;">
        <?php if ($this->get('regist_accept') == '1') { ?>
            <a href="<?php echo $this->getUrl(array('module' => 'user', 'controller' => 'regist', 'action' => 'index')); ?>"><?php echo $this->getTrans('register'); ?></a><br />
        <?php } ?>
        <!--
            <a href="<?php echo $this->getUrl(array('module' => 'user', 'controller' => 'index', 'action' => 'index')); ?>"><?php echo $this->getTrans('forgotPassword'); ?></a>
        -->
        </div>
<?php } ?>