<legend><?=$this->t('menuGuestbook') ?></legend>

<?php if (!empty($this->errors()->hasErrors())): ?>
    <div class="alert alert-danger" role="alert">
        <strong> <?=$this->t('errorsOccured') ?>:</strong>
        <ul>
            <?php foreach ($this->errors()->getErrorMessages() as $error): ?>
                <li><?= $error; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form action="" class="form-horizontal" method="POST">
    <?=$this->getTokenField() ?>
    <div class="form-group hidden">
        <label class="col-lg-2 control-label">
            <?=$this->t('bot') ?>*
        </label>
        <div class="col-lg-8">
            <input type="text"
                   class="form-control"
                   name="bot"
                   placeholder="Bot" />
        </div>
    </div>
    <div class="form-group<?= $this->errors()->hasError('name') ? ' has-error' : '' ?>">
        <label class="col-lg-2 control-label">
            <?=$this->t('name') ?>*
        </label>
        <div class="col-lg-8">
            <input type="text"
                   class="form-control"
                   name="name"
                   placeholder="Name"
                   value="<?=$this->old('name'); ?>" />
        </div>
    </div>
    <div class="form-group<?= $this->errors()->hasError('email') ? ' has-error' : '' ?>">
        <label class="col-lg-2 control-label">
            <?=$this->t('email') ?>*
        </label>
        <div class="col-lg-8">
            <input type="text"
                   class="form-control"
                   name="email"
                   placeholder="E-Mail"
                   value="<?=$this->old('email'); ?>" />
        </div>
    </div>
    <div class="form-group<?= $this->errors()->hasError('homepage') ? ' has-error' : '' ?>">
        <label class="col-lg-2 control-label">
            <?=$this->t('page') ?>
        </label>
        <div class="col-lg-8">
           <input type="text"
                  class="form-control"
                  name="homepage"
                  placeholder="<?=$this->t('page') ?>"
                  value="<?=$this->old('homepage'); ?>" />
        </div>
    </div>
    <div class="form-group<?= $this->errors()->hasError('text') ? ' has-error' : '' ?>">
        <label class="col-lg-2 control-label">
            <?=$this->t('message') ?>*
        </label>
        <div class="col-lg-8">
            <textarea class="form-control ckeditor"
                      id="ck_1"
                      name="text"
                      toolbar="ilch_bbcode"
                      required><?=$this->old('text'); ?></textarea>
        </div>
    </div>
    <?php
    $message = $this->get('message');
    if (!empty($message)) {
        echo $message;
    }
    ?>
    <div class="form-group<?= $this->errors()->hasError('captcha') ? ' has-error' : '' ?>">
        <label class="col-lg-2 control-label">
            <?=$this->t('captcha') ?>
        </label>
        <div class="col-lg-8">
            <?=$this->getCaptchaField() ?>
        </div>
    </div>
    <div class="form-group<?= $this->errors()->hasError('captcha') ? ' has-error' : '' ?>">
        <div class="col-lg-offset-2 col-lg-8 input-group captcha">
            <input type="text"
                  id="captcha-form"
                  class="form-control"
                  autocomplete="off"
                  name="captcha"
                  placeholder="<?=$this->t('captcha') ?>" />
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
    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-8">
            <?=$this->getSaveBar('addButton', 'Guestbook') ?>
        </div>
    </div>
</form>

<?=$this->getDialog("smiliesModal", $this->t('smilies'), "<iframe frameborder='0'></iframe>"); ?>
