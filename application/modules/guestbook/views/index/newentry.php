<form action="" class="form-horizontal" method="POST">
    <?php echo $this->getTokenField(); ?>
    <div class="form-group">
        <label class="col-lg-2 control-label">
            <?=$this->getTrans('name'); ?>*
        </label>
        <div class="col-lg-8">
            <input type="text"
                   class="form-control"
                   name="name"
                   placeholder="Name"
                   required />
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 control-label">
            <?=$this->getTrans('email'); ?>*
        </label>
        <div class="col-lg-8">
            <input type="text"
                   class="form-control"
                   name="email" 
                   placeholder="E-Mail" />
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 control-label">
            <?=$this->getTrans('page'); ?>
        </label>
        <div class="col-lg-8">
           <input type="text"
                  class="form-control"
                  name="homepage" 
                  placeholder="<?php echo $this->getTrans('page'); ?>" />
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 control-label">
            <?=$this->getTrans('message'); ?>*
        </label>
        <div class="col-lg-8">
            <textarea id="ilch_bbcode"
                      class="form-control"
                      name="text"
                      required>
            </textarea>
        </div>
    </div>
    <?php
        $message = $this->get('message');
        if(!empty($message))
        {
            echo $message;
        }
    ?>
    <div class="form-group">
        <label class="col-lg-2 control-label">
            <?=$this->getTrans('captcha'); ?>
        </label>
        <div class="col-lg-8">
            <?=$this->getCaptchaField();?>
            <input type="text"
                  id="captcha-form"
                  class="form-control"
                  autocomplete="off"
                  name="captcha" 
                  placeholder="<?php echo $this->getTrans('captcha'); ?>" />
            <a href="#" onclick="
                document.getElementById('captcha').src='<?php $this->getUrl()?>/application/libraries/captcha/captcha.php?'+Math.random();
                document.getElementById('captcha-form').focus();"
                id="change-image"><?php echo $this->getTrans('captchaRead'); ?></a>
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-8">
            <input type="submit" 
                   name="saveEntry" 
                   class="btn"
                   value="<?php echo $this->getTrans('submit'); ?>" />
        </div>
    </div>
</form>
