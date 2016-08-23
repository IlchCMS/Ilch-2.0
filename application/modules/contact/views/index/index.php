<?php $receivers = $this->get('receivers'); ?>

<legend><?=$this->getTrans('menuContact') ?></legend>
<?php if (!empty($this->get('errors'))): ?>
    <div class="alert alert-danger" role="alert">
        <strong> <?=$this->getTrans('errorsOccured') ?>:</strong>
        <ul>
            <?php foreach ($this->get('errors') as $error): ?>
                <li><?= $error; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
<?php if ($receivers != ''): ?>
    <form method="POST" class="form-horizontal" action="">
        <?=$this->getTokenField() ?>
        <div class="form-group <?=in_array('receiver', $this->get('errorFields')) ? 'has-error' : '' ?>">
            <label for="receiver" class="col-lg-2 control-label">
                <?=$this->getTrans('receiver') ?>:
            </label>
            <div class="col-lg-8">
                <select class="form-control" id="receiver" name="receiver">
                    <?php foreach ($receivers as $receiver):?>
                        <option value="<?=$receiver->getId() ?>"><?=$this->escape($receiver->getName()) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="form-group <?=in_array('senderName', $this->get('errorFields')) ? 'has-error' : '' ?>">
            <label for="name" class="col-lg-2 control-label">
                <?=$this->getTrans('name') ?>:
            </label>
            <div class="col-lg-8">
                <input type="text"
                       class="form-control"
                       id="name"
                       name="senderName"
                       value="<?=$this->get('post')['senderName'] ?>" />
            </div>
        </div>
        <div class="form-group <?=in_array('senderEmail', $this->get('errorFields')) ? 'has-error' : '' ?>">
            <label for="email" class="col-lg-2 control-label">
                <?=$this->getTrans('email') ?>:
            </label>
            <div class="col-lg-8">
                <input type="text"
                       class="form-control"
                       id="email"
                       name="senderEmail"
                       value="<?=$this->get('post')['senderEmail'] ?>" />
            </div>
        </div>
        <div class="form-group <?=in_array('message', $this->get('errorFields')) ? 'has-error' : '' ?>">
            <label for="message" class="col-lg-2 control-label">
                <?=$this->getTrans('message') ?>:
            </label>
            <div class="col-lg-8">
                <textarea class="form-control"
                          id="message"
                          name="message"
                          rows="5"><?=$this->get('post')['message'] ?></textarea>
            </div>
        </div>
        <div class="form-group <?=in_array('captcha', $this->get('errorFields')) ? 'has-error' : '' ?>">
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
                       class="form-control"
                       id="captcha-form"
                       name="captcha"
                       autocomplete="off"
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
        <div class="col-lg-10" align="right">
            <?=$this->getSaveBar('addButton', 'Contact') ?>
        </div>
    </form>
<?php endif; ?>
