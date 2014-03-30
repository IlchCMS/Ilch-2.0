<?php if($this->getUser() !== null) { ?>
    <?php echo $this->getTrans('hello'); ?> <b><?php echo $this->escape($this->getUser()->getName()); ?></b>
    <br />
    <?php
        if($this->getUser()->isAdmin()) {
    ?>
    <a href="<?php echo $this->getUrl(array('module' => 'admin', 'controller' => 'admin', 'action' => 'index')); ?>">
        <?php echo $this->getTrans('adminarea'); ?>
    </a>
    <br />
    <?php
        }
    ?>
    <a href="<?php echo $this->getUrl(array('module' => 'admin/admin', 'controller' => 'login', 'action' => 'logout', 'from_frontend' => 1)); ?>">
        <?php echo $this->getTrans('logout'); ?>
    </a>
    <?php }else{ ?>
        <form action="" class="form-horizontal" method="post">
           <?php echo $this->getTokenField();
                $errors = $this->get('errors');
            ?>
            <div class="form-group">
                <div class="col-lg-12">
                    <input name="loginbox_emailname"
                           class="form-control"
                           type="text"
                           placeholder="<?php echo $this->getTrans('nameEmail')?>" />
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-12">
                    <input name="loginbox_password"
                           class="form-control"
                           type="password"
                           placeholder="<?php echo $this->getTrans('password')?>" />
                </div>
            </div>
            <div class="form-group">
                 <div class="col-lg-4">
                    <button type="submit" class="btn" name="login">
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