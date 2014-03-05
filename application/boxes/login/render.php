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
        <form action="" method="post">
           <?php echo $this->getTokenField();
                $errors = $this->get('errors');
            ?>
            <div class="ilch_form_group ilch_form_nolabel">
                <div class="controls">
                    <input name="loginbox_emailname"
                           type="text"
                           placeholder="<?php echo $this->getTrans('nameEmail')?>" />
                </div>
            </div>
            <div class="ilch_form_group ilch_form_nolabel">
                <div class="controls ">
                    <input name="loginbox_password"
                           type="password"
                           placeholder="<?php echo $this->getTrans('password')?>" />
                </div>
            </div>
            <div class="ilch_form_group ilch_form_nolabel">
                <div class="controls">
                    <button type="submit" name="login">
                        <?php echo $this->getTrans('login'); ?>
                    </button>
                </div>
            </div>
        </form>
        <?php if ($this->get('regist_accept') == '1') { ?>
            <a href="<?php echo $this->getUrl(array('module' => 'user', 'controller' => 'regist', 'action' => 'index')); ?>"><?php echo $this->getTrans('register'); ?></a><br />
        <?php } ?>
        <!--
            <a href="<?php echo $this->getUrl(array('module' => 'user', 'controller' => 'index', 'action' => 'index')); ?>"><?php echo $this->getTrans('forgotPassword'); ?></a>
        -->
<?php } ?>