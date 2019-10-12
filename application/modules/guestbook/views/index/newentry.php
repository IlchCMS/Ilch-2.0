<h1><?=$this->getTrans('menuGuestbook') ?></h1>

<form class="form-horizontal" method="POST">
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
    <div class="form-group <?=$this->validation()->hasError('name') ? 'has-error' : '' ?>">
        <label class="col-lg-2 control-label">
            <?=$this->getTrans('name') ?>*
        </label>
        <div class="col-lg-8">
            <input type="text"
                   class="form-control"
                   name="name"
                   placeholder="<?=$this->getTrans('name') ?>"
                   value="<?=$this->escape($this->originalInput('name')) ?>" />
        </div>
    </div>
    <div class="form-group <?= $this->validation()->hasError('email') ? 'has-error' : '' ?>">
        <label class="col-lg-2 control-label">
            <?=$this->getTrans('email') ?>*
        </label>
        <div class="col-lg-8">
            <input type="email"
                   class="form-control"
                   name="email"
                   placeholder="<?=$this->getTrans('emailVisibleForAdmins') ?>"
                   value="<?=$this->escape($this->originalInput('email')) ?>" />
        </div>
    </div>
    <div class="form-group <?= $this->validation()->hasError('homepage') ? 'has-error' : '' ?>">
        <label class="col-lg-2 control-label">
            <?=$this->getTrans('page') ?>
        </label>
        <div class="col-lg-8">
           <input type="text"
                  class="form-control"
                  name="homepage"
                  placeholder="<?=$this->getTrans('page') ?>"
                  value="<?=$this->escape($this->originalInput('homepage')) ?>" />
        </div>
    </div>
    <div class="form-group <?= $this->validation()->hasError('text') ? 'has-error' : '' ?>">
        <label class="col-lg-2 control-label">
            <?=$this->getTrans('message') ?>*
        </label>
        <div class="col-lg-8">
            <textarea class="form-control ckeditor"
                      id="ck_1"
                      name="text"
                      toolbar="ilch_bbcode"
                      required><?=$this->escape($this->originalInput('text')) ?></textarea>
        </div>
    </div>
    <?php if ($this->get('captchaNeeded')) : ?>
    <div class="form-group <?= $this->validation()->hasError('captcha') ? 'has-error' : '' ?>">
        <label class="col-lg-2 control-label">
            <?=$this->getTrans('captcha') ?>
        </label>
        <div class="col-lg-8">
            <?=$this->getCaptchaField() ?>
        </div>
    </div>
    <div class="form-group <?= $this->validation()->hasError('captcha') ? 'has-error' : '' ?>">
        <div class="col-lg-offset-2 col-lg-8 input-group captcha">
            <input type="text"
                  id="captcha-form"
                  class="form-control"
                  autocomplete="off"
                  name="captcha"
                  placeholder="<?=$this->getTrans('captcha') ?>" />
            <span class="input-group-addon">
                <a href="javascript:void(0)" onclick="
                    document.getElementById('captcha').src='<?=$this->getUrl() ?>/application/libraries/Captcha/Captcha.php?'+Math.random();
                    document.getElementById('captcha-form').focus();"
                    id="change-image">
                    <i class="fa fa-refresh"></i>
                </a>
            </span>
        </div>
    </div>
    <?php endif; ?>
    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-8">
            <?=$this->getSaveBar('addButton', 'Guestbook') ?>
        </div>
    </div>
</form>
