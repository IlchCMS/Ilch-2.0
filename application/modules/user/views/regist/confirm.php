<?php 
$code = $this->getRequest()->getParam('code');
$confirm = $this->get('confirmed'); 

if (empty($code) || empty($confirm)) { 
 ?>
    <form class="form-horizontal" method="POST" action="<?php echo $this->getUrl(array('action' => $this->getRequest()->getActionName())); ?>">
        <?php echo $this->getTokenField();
            $errors = $this->get('errors');
        ?>
        <div class="form-group <?php if (!empty($errors['confirmedCode'])) { echo 'has-error'; }; ?>">
            <label for="confirmedCode" class="control-label col-lg-3">
                <?php echo $this->getTrans('confirmCode'); ?>:
            </label>
            <div class="col-lg-8">
                <input value=""
                       type="text"
                       name="confirmedCode"
                       class="form-control"
                       id="confirmedCode" />
                <?php
                    if (!empty($errors['confirmedCode'])) {
                        echo '<span class="help-inline">'.$this->getTrans($errors['confirmedCode']).'</span>';
                    }
                ?>
            </div>
        </div>
        <button type="submit" name="save" class="btn pull-right"><?php echo $this->getTrans('menuConfirm'); ?></button>
    </form>
<?php } else { ?>
    <div class="row">
        <div class="col-lg-1 fa-4x check">
            <i class="fa fa-check-circle text-success" title=""></i>
        </div>
        <div class="col-lg-11">
            Ihr Benutzerkonto wurde erfolgreich freigeschaltet.<br />
            Sie können sich jetzt mit Ihren Benutzerdaten anmelden.
        </div>
    </div>
<?php
}
?>