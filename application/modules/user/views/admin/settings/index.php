<form class="form-horizontal" method="POST" action="<?php echo $this->getUrl(array('action' => $this->getRequest()->getActionName())); ?>">
    <legend><?php echo $this->getTrans('menuSettings'); ?></legend>
    <?php echo $this->getTokenField(); ?>
    <div class="form-group">
        <label for="regist_accept" class="col-lg-2 control-label">
            <?php echo $this->getTrans('acceptUserRegis'); ?>:
        </label>
        <div class="col-lg-4">
            <label class="checkbox-inline">
                <input type="radio" 
                       name="regist_accept"
                       id="regist_accept_yes" 
                       value="1"
                       <?php if ($this->get('regist_accept') == '1') { echo 'checked="checked"';} ?>>
                       <label for="regist_accept_yes"><?php echo $this->getTrans('yes'); ?></label>
            </label>
            <label class="checkbox-inline">
                <input type="radio"
                       name="regist_accept"
                       id="regist_accept_no" 
                       value="0"
                       <?php if ($this->get('regist_accept') == '0') { echo 'checked="checked"';} ?>>
                       <label for="regist_accept_no"><?php echo $this->getTrans('no'); ?></label>
            </label>
        </div>
    </div>
    <div class="form-group">
        <label for="regist_confirm" class="col-lg-2 control-label">
            <?php echo $this->getTrans('confirmRegistrationEmail'); ?>:
        </label>
        <div class="col-lg-4">
            <label class="checkbox-inline">
                <input type="radio" 
                       name="regist_confirm"
                       id="regist_confirm_yes" 
                       value="1"
                       <?php if ($this->get('regist_confirm') == '1') { echo 'checked="checked"';} ?>>
                       <label for="regist_confirm_yes"><?php echo $this->getTrans('yes'); ?></label>
            </label>
            <label class="checkbox-inline">
                <input type="radio"
                       name="regist_confirm"
                       id="regist_confirm_no" 
                       value="0"
                       <?php if ($this->get('regist_confirm') == '0') { echo 'checked="checked"';} ?>>
                       <label for="regist_confirm_no"><?php echo $this->getTrans('no'); ?></label>
            </label>
        </div>
    </div>
    <div class="form-group">
        <label for="regist_rules" class="col-lg-2 control-label">
            <?php echo $this->getTrans('rulesForRegist'); ?>:
        </label>
        <div class="col-lg-4">
            <textarea class="form-control"
                      name="regist_rules" 
                      cols="60" 
                      rows="5"><?php echo $this->get('regist_rules'); ?></textarea>
        </div>
    </div>
    <div class="form-group">
        <label for="regist_confirm_mail" class="col-lg-2 control-label">
            <?php echo $this->getTrans('mailForRegist'); ?>:
        </label>
        <div class="col-lg-4">
            <textarea class="form-control"
                      name="regist_confirm_mail" 
                      cols="60" 
                      rows="5"><?php echo $this->get('regist_confirm_mail'); ?></textarea>
        </div>
    </div>
    <?=$this->getSaveBar()?>
</form>
