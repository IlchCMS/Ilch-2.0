<legend><?=$this->getTrans('settings') ?></legend>
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
<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <div class="form-group<?=in_array('articlesPerPage', $this->get('errorFields')) ? ' has-error' : '' ?>">
        <label for="articlesPerPageInput" class="col-lg-2 control-label">
            <?=$this->getTrans('articlesPerPage') ?>:
        </label>
        <div class="col-lg-1">
            <input type="number"
                   class="form-control"
                   id="articlesPerPageInput"
                   name="articlesPerPage"
                   min="1"
                   value="<?=$this->escape($this->get('articlesPerPage')) ?>" />
        </div>
    </div>
    <?=$this->getSaveBar() ?>
</form>
