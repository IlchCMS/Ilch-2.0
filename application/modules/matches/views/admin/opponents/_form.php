<?php // Fehlerausgabe der Validation ?>
<?php if ($this->get('errors') !== null): ?>
<div class="alert alert-danger" role="alert">
    <strong><?= $this->getTrans('validation.errorsOccurred') ?></strong>
    <ul>
    <?php foreach ($this->get('errors') as $error): ?>
        <li><?= $error; ?></li>
    <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>
<?php // Ende Fehlerausgabe der Validation ?>

<?= $this->getTokenField(); ?>
<div class="form-group<?= in_array('name', $this->get('errorFields')) ? ' has-error' : '' ?>">
    <label for="name" class="col-sm-2 control-label"><?= $this->getTrans('opponents.name') ?>*</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" name="name" id="name" value="<?= $this->get('input')['name'] ?>">
    </div>
</div>
<div class="form-group<?= in_array('short_name', $this->get('errorFields')) ? ' has-error' : '' ?>">
    <label for="short_name" class="col-sm-2 control-label"><?= $this->getTrans('opponents.short_name') ?>*</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" name="short_name" id="short_name" value="<?= $this->get('input')['short_name'] ?>">
    </div>
</div>
<div class="form-group<?= in_array('website', $this->get('errorFields')) ? ' has-error' : '' ?>">
    <label for="website" class="col-sm-2 control-label"><?= $this->getTrans('opponents.website') ?></label>
    <div class="col-sm-10">
        <div class="input-group">
            <div class="input-group-addon">http://</div>
            <input type="text" class="form-control" name="website" id="website" value="<?= $this->get('input')['website'] ?>">
        </div>
    </div>
</div>
