<legend><?=$this->getTrans('menuGuestbook') ?></legend>
<?php // Fehlerausgabe der Validation ?>
<?php if($this->get('errors') !== null): ?>
    <div class="alert alert-danger" role="alert">
        <strong> Es sind folgende Fehler aufgetreten:</strong>
        <ul>
            <?php foreach($this->get('errors') as $error): ?>
                <li><?= $error; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
<?php // Ende Fehlerausgabe der Validation ?>

<form action="" class="form-horizontal" method="POST">
    <?=$this->getTokenField() ?>
    <div class="form-group hidden">
        <label class="col-lg-2 control-label">
            <?=$this->getTrans('bot') ?>*
        </label>
        <div class="col-lg-8">
            <input type="text"
                   class="form-control"
                   name="bot"
                   placeholder="Bot" />
        </div>
    </div>
    <div class="form-group<?=in_array('name', $this->get('errorFields')) ? ' has-error' : '' ?>">
        <label class="col-lg-2 control-label">
            <?=$this->getTrans('name') ?>*
        </label>
        <div class="col-lg-8">
            <input type="text"
                   class="form-control"
                   name="name"
                   placeholder="Name"
                   value="<?=$this->get('post')['name'] ?>" />
        </div>
    </div>
    <div class="form-group<?=in_array('email', $this->get('errorFields')) ? ' has-error' : '' ?>">
        <label class="col-lg-2 control-label">
            <?=$this->getTrans('email') ?>*
        </label>
        <div class="col-lg-8">
            <input type="text"
                   class="form-control"
                   name="email"
                   placeholder="E-Mail"
                   value="<?=$this->get('post')['email'] ?>" />
        </div>
    </div>
    <div class="form-group<?=in_array('homepage', $this->get('errorFields')) ? ' has-error' : '' ?>">
        <label class="col-lg-2 control-label">
            <?=$this->getTrans('page') ?>
        </label>
        <div class="col-lg-8">
           <input type="text"
                  class="form-control"
                  name="homepage"
                  placeholder="<?=$this->getTrans('page') ?>"
                  value="<?=$this->get('post')['homepage'] ?>" />
        </div>
    </div>
    <div class="form-group<?=in_array('text', $this->get('errorFields')) ? ' has-error' : '' ?>">
        <label class="col-lg-2 control-label">
            <?=$this->getTrans('message') ?>*
        </label>
        <div class="col-lg-8">
            <textarea id="ck_1"
                      class="form-control ckeditor"
                      toolbar="ilch_bbcode"
                      name="text"
                      required>
                <?=$this->get('post')['text'] ?>
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
    <div class="form-group<?=in_array('captcha', $this->get('errorFields')) ? ' has-error' : '' ?>">
        <label class="col-lg-2 control-label">
            <?=$this->getTrans('captcha') ?>
        </label>
        <div class="col-lg-8">
            <?=$this->getCaptchaField() ?>
        </div>
    </div>
    <div class="form-group<?=in_array('captcha', $this->get('errorFields')) ? ' has-error' : '' ?>">
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
    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-8">
            <input type="submit"
                   name="saveEntry"
                   class="btn"
                   value="<?=$this->getTrans('submit') ?>" />
        </div>
    </div>
</form>
