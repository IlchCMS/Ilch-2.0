<h1><?=$this->getTrans('menuPartnerAdd') ?></h1>
<form id="partnerForm" name="partnerForm" method="POST" class="form-horizontal">
    <?=$this->getTokenField() ?>
    <div class="row form-group mb-15 d-none">
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
    <div class="row form-group mb-15 <?=$this->validation()->hasError('name') ? 'has-error' : '' ?>">
        <label for="name" class="col-xl-2 control-label">
            <?=$this->getTrans('name') ?>:
        </label>
        <div class="col-xl-8">
            <input type="text"
                   class="form-control"
                   id="name"
                   name="name"
                   placeholder="Name"
                   value="<?=($this->originalInput('name') != '' ? $this->escape($this->originalInput('name')) : '') ?>" />
        </div>
    </div>
    <div class="row form-group mb-15 <?=$this->validation()->hasError('link') ? 'has-error' : '' ?>">
        <label for="link" class="col-xl-2 control-label">
            <?=$this->getTrans('link') ?>:
        </label>
        <div class="col-xl-8">
            <input type="text"
                   class="form-control"
                   id="link"
                   name="link"
                   placeholder="http://"
                   value="<?=($this->originalInput('link') != '' ? $this->escape($this->originalInput('link')) : '') ?>" />
        </div>
    </div>
    <div class="row form-group mb-15 <?=$this->validation()->hasError('banner') ? 'has-error' : '' ?>">
        <label for="banner" class="col-xl-2 control-label">
            <?=$this->getTrans('banner') ?>:
        </label>
        <div class="col-xl-8">
            <input type="text"
                   class="form-control"
                   id="banner"
                   name="banner"
                   placeholder="http://"
                   value="<?=($this->originalInput('banner') != '' ? $this->escape($this->originalInput('banner')) : '') ?>" />
        </div>
    </div>
    <?php if ($this->get('captchaNeeded') && $this->get('defaultcaptcha')) : ?>
        <?=$this->get('defaultcaptcha')->getCaptcha($this) ?>
    <?php endif; ?>
    <div class="row form-group mb-15">
        <div class="offset-xl-2 col-xl-8">
            <?php
                if ($this->get('captchaNeeded')) {
                    if ($this->get('googlecaptcha')) {
                        echo $this->get('googlecaptcha')->setForm('partnerForm')->getCaptcha($this, 'addButton', 'Partner');
                    } else {
                        echo $this->getSaveBar('addButton', 'Partner');
                    }
                } else {
                    echo $this->getSaveBar('addButton', 'Partner');
                }
            ?>
        </div>
    </div>
</form>
