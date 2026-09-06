<h1><?=$this->getTrans('entry') ?></h1>
<form method="POST" action="">
    <?=$this->getTokenField() ?>
    <div class="row mb-3">
        <label for="cat" class="col-xl-2 col-form-label">
            <?=$this->getTrans('cat') ?>
        </label>
        <div class="col-xl-4">
            <select class="form-select" id="cat" name="cat">
                <option value="0" selected><?=$this->getTrans('noSelection') ?></option>
            <?php foreach ($this->get('cats') as $cat): ?>
                <option value="<?=$cat->getId() ?>"><?=$this->escape($cat->getTitle()) ?></option>
            <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('title') ? ' has-error' : '' ?>">
        <label for="title" class="col-xl-2 col-form-label">
            <?=$this->getTrans('title') ?>
        </label>
        <div class="col-xl-8">
            <input type="text"
                   class="form-control"
                   id="title"
                   name="title"
                   value="<?=$this->originalInput('title') ?>" />
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('text') ? ' has-error' : '' ?>">
        <label for="text" class="col-xl-2 col-form-label">
            <?=$this->getTrans('desc') ?>
        </label>
        <div class="col-xl-8">
            <textarea class="form-control ckeditor"
                      id="text"
                      name="text"
                      toolbar="ilch_html_frontend"
                      rows="5"><?=$this->originalInput('text') ?></textarea>
        </div>
    </div>
    <?php if ($this->get('captchaNeeded')) : ?>
    <div class="row mb-3<?=$this->validation()->hasError('captcha') ? ' has-error' : '' ?>">
        <label class="col-xl-2 col-form-label">
            <?=$this->getTrans('captcha') ?>
        </label>
        <div class="col-xl-8">
            <?=$this->getCaptchaField() ?>
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('captcha') ? ' has-error' : '' ?>">
        <div class="offset-xl-2 col-xl-3 input-group captcha">
            <input type="text"
                   class="form-control"
                   id="captcha-form"
                   name="captcha"
                   autocomplete="off"
                   placeholder="<?=$this->getTrans('captcha') ?>" />
            <span class="input-group-text">
                <a href="javascript:void(0)" onclick="
                        document.getElementById('captcha').src='<?=$this->getUrl() ?>/application/libraries/Captcha/Captcha.php?'+Math.random();
                        document.getElementById('captcha-form').focus();"
                   id="change-image">
                    <i class="fa-solid fa-arrows-rotate"></i>
                </a>
            </span>
        </div>
    </div>
    <?php endif; ?>
    <div class="row">
        <div class="offset-xl-2 col-xl-10">
            <?=$this->getSaveBar('addButton', 'Ticket') ?>
        </div>
    </div>
</form>
<?=$this->getDialog('mediaModal', $this->getTrans('media'), '<iframe frameborder="0"></iframe>'); ?>
