<h1><?=$this->getTrans('menuContact') ?></h1>
<?php if (!empty($this->get('welcomeMessage'))) : ?>
<div class="panel panel-default">
    <div class="panel-body welcomeMessage"><?=$this->alwaysPurify($this->get('welcomeMessage')) ?></div>
</div>
<?php endif; ?>
<?php if ($this->get('receivers') != ''): ?>
    <form id="contactForm" name="contactForm" method="POST" class="form-horizontal">
        <?=$this->getTokenField() ?>
        <div class="row mb-3 d-none">
            <label class="col-xl-2 control-label">
                <?=$this->getTrans('bot') ?>*
            </label>
            <div class="col-xl-8">
                <input type="text"
                       class="form-control"
                       name="bot"
                       placeholder="Bot" />
            </div>
        </div>
        <div class="row mb-3 <?=$this->validation()->hasError('receiver') ? 'has-error' : '' ?>">
            <label for="receiver" class="col-xl-2 control-label">
                <?=$this->getTrans('receiver') ?>
            </label>
            <div class="col-xl-8">
                <select class="form-control" id="receiver" name="receiver">
                    <?php foreach ($this->get('receivers') as $receiver): ?>
                        <option value="<?=$receiver->getId() ?>"><?=$this->escape($receiver->getName()) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="row mb-3 <?=$this->validation()->hasError('senderName') ? 'has-error' : '' ?>">
            <label for="name" class="col-xl-2 control-label">
                <?=$this->getTrans('name') ?>
            </label>
            <div class="col-xl-8">
                <input type="text"
                       class="form-control"
                       id="name"
                       name="senderName"
                       value="<?=$this->escape($this->originalInput('senderName')) ?>" />
            </div>
        </div>
        <div class="row mb-3 <?=$this->validation()->hasError('senderEmail') ? 'has-error' : '' ?>">
            <label for="email" class="col-xl-2 control-label">
                <?=$this->getTrans('email') ?>
            </label>
            <div class="col-xl-8">
                <input type="email"
                       class="form-control"
                       id="email"
                       name="senderEmail"
                       value="<?=$this->originalInput('senderEmail') ?>" />
            </div>
        </div>
        <div class="row mb-3 <?=$this->validation()->hasError('message') ? 'has-error' : '' ?>">
            <label for="message" class="col-xl-2 control-label">
                <?=$this->getTrans('message') ?>
            </label>
            <div class="col-xl-8">
                <textarea class="form-control"
                          id="message"
                          name="message"
                          rows="5"><?=$this->escape($this->originalInput('message')) ?></textarea>
            </div>
        </div>
        <div class="row mb-3 <?=$this->validation()->hasError('privacy') ? 'has-error' : '' ?>">
            <div class="offset-xl-2 col-xl-8">
                <div class="checkbox inline <?=$this->validation()->hasError('privacy') ? 'has-error' : '' ?>">
                    <input type="checkbox" style="margin-left: 0;" id="privacy" name="privacy" value="1"<?=($this->originalInput('privacy')) ? ' checked' : '' ?>> <label for="privacy"><?=$this->getTrans('acceptPrivacy') ?></label>
                </div>
            </div>
        </div>
        <?php if ($this->get('captchaNeeded') && $this->get('defaultcaptcha')) : ?>
            <?=$this->get('defaultcaptcha')->getCaptcha($this) ?>
        <?php endif; ?>
        <div class="col-xl-10" align="right">
            <?php
                if ($this->get('captchaNeeded')) {
                    if ($this->get('googlecaptcha')) {
                        echo $this->get('googlecaptcha')->setForm('contactForm')->getCaptcha($this, 'addButton', 'Contact');
                    } else {
                        echo $this->getSaveBar('addButton', 'Contact');
                    }
                } else {
                    echo $this->getSaveBar('addButton', 'Contact');
                }
            ?>
        </div>
    </form>
<?php endif; ?>
