<?php $receivers = $this->get('receivers'); ?>

<legend><?=$this->getTrans('menuContact') ?></legend>
<?php if ($receivers != ''): ?>
    <form method="POST" class="form-horizontal" action="">
        <?=$this->getTokenField() ?>
        <div class="form-group">
            <label for="receiver" class="col-lg-2 control-label">
                <?=$this->getTrans('receiver') ?>:
            </label>
            <div class="col-lg-8">
                <select id="receiver"
                        class="form-control"
                        name="contact_receiver">
                        <?php foreach($receivers as $receiver):?>
                            <option value="<?=$receiver->getId() ?>"><?=$this->escape($receiver->getName()) ?></option>
                        <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="name" class="col-lg-2 control-label">
                <?=$this->getTrans('name') ?>:
            </label>
            <div class="col-lg-8">
                <input id="name"
                       class="form-control"
                       name="contact_name"
                       type="text"
                       value="" />
            </div>
        </div>
        <div class="form-group">
            <label for="email" class="col-lg-2 control-label">
                <?=$this->getTrans('email') ?>:
            </label>
            <div class="col-lg-8">
                <input id="email"
                       class="form-control"
                       name="contact_email"
                       type="text"
                       value="" />
            </div>
        </div>
        <div class="form-group">
            <label for="message" class="col-lg-2 control-label">
                <?=$this->getTrans('message') ?>:
            </label>
            <div class="col-lg-8">
                <textarea id="message" 
                          class="form-control"
                          rows="5"
                          name="contact_message"></textarea>
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
            <?=$this->getSaveBar('addButton', 'Contact') ?>
        </div>
    </form>
<?php endif; ?>
