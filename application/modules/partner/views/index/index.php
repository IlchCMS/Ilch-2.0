<legend><?=$this->getTrans('menuPartnerAdd') ?></legend>
<form method="POST" class="form-horizontal" action="">
    <?=$this->getTokenField() ?>
    <div class="form-group">
        <label for="name" class="col-lg-2 control-label">
            <?=$this->getTrans('name') ?>:
        </label>
        <div class="col-lg-8">
            <input id="name"
                   class="form-control"
                   name="name"
                   type="text"
                   value="<?php if($this->get('name') != ''){ echo $this->escape($this->get('name')); } ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="link" class="col-lg-2 control-label">
            <?=$this->getTrans('link') ?>:
        </label>
        <div class="col-lg-8">
            <input id="link"
                   class="form-control"
                   name="link"
                   type="text"
                   placeholder="http://"
                   value="<?php if($this->get('link') != ''){ echo $this->escape($this->get('link')); } ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="banner" class="col-lg-2 control-label">
            <?=$this->getTrans('banner') ?>:
        </label>
        <div class="col-lg-8">
            <input id="banner"
                   class="form-control"
                   name="banner"
                   type="text"
                   placeholder="http://"
                   value="<?php if($this->get('banner') != ''){ echo $this->escape($this->get('banner')); } ?>" />
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 control-label">
            <?=$this->getTrans('captcha') ?>:
        </label>
        <div class="col-lg-8">
            <?=$this->getCaptchaField() ?>
        </div>   
    </div>
    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-8 input-group captcha">
            <input type="text"
                  id="captcha-form"
                  class="form-control"
                  autocomplete="off"
                  name="captcha"
                  placeholder="<?=$this->getTrans('captcha') ?>" />
            <span class="input-group-addon">
                <a href="javascript:void(0)" onclick="
                    document.getElementById('captcha').src='<?=$this->getUrl()?>/application/libraries/Captcha/Captcha.php?'+Math.random();
                    document.getElementById('captcha-form').focus();"
                    id="change-image">
                    <i class="fa fa-refresh"></i>
                </a>
            </span>
        </div>
    </div>
    <div class="col-lg-10" align="right">
        <?=$this->getSaveBar('addButton', 'Partner') ?>
    </div>
</form>
