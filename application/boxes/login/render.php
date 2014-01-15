<?php if($this->getUser() !== null) { ?>
    <?php echo $this->trans('hello'); ?> <b><?php echo $this->escape($this->getUser()->getName()); ?></b>
    <br />
    <!--
    <a href="<?php echo $this->url(array('module' => 'user', 'controller' => 'index', 'action' => 'index')); ?>"><?php echo $this->trans('message'); ?> (0)</a><br />
    <a href="<?php echo $this->url(array('module' => 'user', 'controller' => 'index', 'action' => 'index')); ?>"><?php echo $this->trans('UserPanel'); ?></a><br />
    -->
    <a href="<?php echo $this->url(array('module' => 'admin', 'controller' => 'admin')); ?>">
        <?php echo $this->trans('adminarea'); ?>
    </a>
    <br />
    <a href="<?php echo $this->url(array('module' => 'admin/admin', 'controller' => 'login', 'action' => 'logout', 'from_frontend' => 1)); ?>">
        <?php echo $this->trans('logout'); ?>
    </a>
    <?php }else{ ?>
        <form class="form-horizontal" action="" method="post">
           <?php echo $this->getTokenField(); ?>
            <div class="form-group">
                <div class="col-lg-12">
                    <input class="form-control"
                           name="emailname"
                           type="text"
                           placeholder="<?php echo $this->trans('nameEmail')?>" />
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-12">
                    <input class="form-control"
                           name="password"
                           type="password"
                           placeholder="<?php echo $this->trans('password')?>" />
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-12">
                    <button type="submit" name="login" class="btn">
                        <?php echo $this->trans('login'); ?>
                    </button>
                </div>
            </div>
            <?php
                if (!empty($errors)) {
                    foreach ($errors as $transKey) {
                        echo '<br /><span class="text-danger">'.$this->trans($transKey).'</span>';
                    }
                }
            ?>
        </form>
        <div style="font-size: 13px;">
        <?php if ($this->get('regist_accept') == '1') { ?>
            <a href="<?php echo $this->url(array('module' => 'user', 'controller' => 'regist', 'action' => 'index')); ?>"><?php echo $this->trans('register'); ?></a><br />
        <?php } ?>
        <!--
            <a href="<?php echo $this->url(array('module' => 'user', 'controller' => 'index', 'action' => 'index')); ?>"><?php echo $this->trans('forgotPassword'); ?></a>
        -->
        </div>
<?php } ?>