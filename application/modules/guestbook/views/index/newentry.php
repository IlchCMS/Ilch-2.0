<h1><?=$this->getTrans('menuGuestbook') ?></h1>

<form id="guestbookForm" name="guestbookForm" class="form-horizontal" method="POST">
    <?=$this->getTokenField() ?>
    <div class="form-group hidden">
        <label for="bot" class="col-lg-2 control-label">
            <?=$this->getTrans('bot') ?>*
        </label>
        <div class="col-lg-8">
            <input type="text"
                   class="form-control"
                   name="bot"
                   id="bot"
                   placeholder="Bot" />
        </div>
    </div>
    <div class="form-group <?=$this->validation()->hasError('name') ? 'has-error' : '' ?>">
        <label for="name" class="col-lg-2 control-label">
            <?=$this->getTrans('name') ?>*
        </label>
        <div class="col-lg-8">
            <input type="text"
                   class="form-control"
                   name="name"
                   id="name"
                   placeholder="<?=$this->getTrans('name') ?>"
                   value="<?=$this->escape($this->originalInput('name')) ?>" />
        </div>
    </div>
    <div class="form-group <?= $this->validation()->hasError('email') ? 'has-error' : '' ?>">
        <label for="email" class="col-lg-2 control-label">
            <?=$this->getTrans('email') ?>*
        </label>
        <div class="col-lg-8">
            <input type="email"
                   class="form-control"
                   name="email"
                   id="email"
                   placeholder="<?=$this->getTrans('emailVisibleForAdmins') ?>"
                   value="<?=$this->escape($this->originalInput('email')) ?>" />
        </div>
    </div>
    <div class="form-group <?= $this->validation()->hasError('homepage') ? 'has-error' : '' ?>">
        <label for="homepage" class="col-lg-2 control-label">
            <?=$this->getTrans('page') ?>
        </label>
        <div class="col-lg-8">
           <input type="text"
                  class="form-control"
                  name="homepage"
                  id="homepage"
                  placeholder="<?=$this->getTrans('page') ?>"
                  value="<?=$this->escape($this->originalInput('homepage')) ?>" />
        </div>
    </div>
    <div class="form-group <?= $this->validation()->hasError('text') ? 'has-error' : '' ?>">
        <label for="text" class="col-lg-2 control-label">
            <?=$this->getTrans('message') ?>*
        </label>
        <div class="col-lg-8">
            <textarea class="form-control ckeditor"
                      id="ck_1"
                      name="text"
                      id="text"
                      toolbar="ilch_html_frontend"
                      required><?=$this->escape($this->originalInput('text')) ?></textarea>
        </div>
    </div>
    <?php if ($this->get('captchaNeeded') && $this->get('defaultcaptcha')) : ?>
        <?=$this->get('defaultcaptcha')->getCaptcha($this) ?>
    <?php endif; ?>
    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-8">
            <?php
                if ($this->get('captchaNeeded')) {
                    if ($this->get('googlecaptcha')) {
                        echo $this->get('googlecaptcha')->setForm('guestbookForm')->getCaptcha($this, 'addButton', 'Guestbook');
                    } else {
                        echo $this->getSaveBar('addButton', 'Guestbook');
                    }
                } else {
                    echo $this->getSaveBar('addButton', 'Guestbook');
                }
            ?>
        </div>
    </div>
</form>
