<link href="<?=$this->getModuleUrl('static/css/birthday.css') ?>" rel="stylesheet">

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
<form class="form-horizontal" method="POST" action="<?=$this->getUrl(['action' => $this->getRequest()->getActionName()]) ?>">
    <?=$this->getTokenField() ?>
    <div class="form-group <?=in_array('numberOfBirthdaysShow', $this->get('errorFields')) ? 'has-error' : '' ?>">
        <label for="numberOfBirthdaysShow" class="col-lg-2 control-label">
            <?=$this->getTrans('numberOfBirthdaysShow') ?>:
        </label>
        <div class="col-lg-1">
            <input type="number"
                   class="form-control"
                   id="numberOfBirthdaysShow"
                   name="numberOfBirthdaysShow"
                   min="1"
                   value="<?=$this->get('numberOfBirthdaysShow') ?>">
        </div>
    </div>
    <?=$this->getSaveBar() ?>
</form>
