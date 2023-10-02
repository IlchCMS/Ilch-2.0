<?php

/** @var \Ilch\View $this */

/** @var array $languages */
$languages = $this->get('languages');

/** @var bool|null $licenseMissing */
$licenseMissing = $this->get('licenseMissing');

/** @var string $licenseText */
$licenseText = $this->get('licenseText');
?>
<div class="row mb-3">
    <div class="col-xl-12">
        <?php if ($this->get('licenseMissing')) : ?>
            <div class="alert alert-danger" role="alert">
                <?=$this->getTrans('licenseMissing') ?>
            </div>
        <?php else : ?>
            <div class="form-control license">
                <?=$licenseText ?>
            </div>
        <?php endif; ?>
    </div>
    <?php if (!$this->get('licenseMissing')) : ?>
        <label class="col-xl-12 checkbox inline <?=$this->validation()->hasError('licenseAccepted') ? 'text-danger' : '' ?>" style="margin-left: 20px;">
            <input type="checkbox" class="<?=$this->validation()->hasError('licenseAccepted') ? 'is-invalid' : '' ?>" name="licenseAccepted" value="1" <?=$this->originalInput('licenseAccepted', $this->get('licenseAccepted')) ? 'checked' : '' ?>> <?=$this->getTrans('acceptLicense') ?>
        </label>
    <?php endif; ?>
</div>
