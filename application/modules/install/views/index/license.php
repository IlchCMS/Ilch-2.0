<div class="form-group <?php if ($this->get('error') != '') { echo 'has-error'; } ?>">
    <div class="col-lg-12">
        <?php if (!empty($this->get('licenseMissing'))): ?>
            <div class="alert alert-danger" role="alert">
                <?=$this->getTrans($this->get('licenseMissing')) ?>
            </div>
        <?php else: ?>
            <div class="form-control license" disabled>
                <?=$this->get('licenseText') ?>
            </div>
        <?php endif; ?>
    </div>
    <?php if (empty($this->get('licenseMissing'))): ?>
        <label class="col-lg-12 checkbox inline <?php if ($this->get('error') != '') { echo 'text-danger'; } ?>" style="margin-left: 20px;">
            <input type="checkbox" name="licenseAccepted" value="1" <?php if ($this->get('licenseAccepted') != '') { echo 'checked'; } ?>> <?=$this->getTrans('acceptLicense') ?>
        </label>
    <?php endif; ?>
</div>
