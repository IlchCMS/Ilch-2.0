<?php 
$check = $this->getRequest()->getParam('check');
if (empty($check)) { 
 ?>
    <form class="form-horizontal" method="POST" action="<?php echo $this->url(array('action' => $this->getRequest()->getActionName())); ?>">
        <?php echo $this->getTokenField();
            $errors = $this->get('errors');
        ?>
        <div class="form-group <?php if (!empty($errors['check'])) { echo 'has-error'; }; ?>">
            <label for="confirmCode" class="control-label col-lg-3">
                <?php echo $this->trans('confirmCode'); ?>:
            </label>
            <div class="col-lg-6">
                <input value=""
                       type="text"
                       name="check"
                       class="form-control"
                       id="check" />
                <?php
                    if (!empty($errors['check'])) {
                        echo '<span class="help-inline">'.$this->trans($errors['check']).'</span>';
                    }
                ?>
            </div>
        </div>
        <button type="submit" name="save" class="btn pull-right"><?php echo $this->trans('menuConfirm'); ?></button>
    </form>
<?php }else{
    
    
    
    foreach ($this->get('checks') as $checks) {
        $checkCode = $checks->getCheck();
        if (empty($checkCode)) { 
            echo 'Es wurde kein Eintrag gefunden.<br />
                  <br />
                  Evtl. ist es schon zu lange her und der Eintrag wurde gelöscht oder der Eintrag wurde schon Aktiviiert.';   
        }else{
                echo 'test';
        }
    }
    
    
    
    
} ?>