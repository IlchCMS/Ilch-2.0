<link href="<?=$this->getModuleUrl('static/css/birthday.css') ?>" rel="stylesheet">

<h1><?=$this->getTrans('settings') ?></h1>

<form class="form-horizontal" method="POST" action="<?=$this->getUrl(['action' => $this->getRequest()->getActionName()]) ?>">
    <?=$this->getTokenField() ?>
    <div class="form-group <?=$this->validation()->hasError('numberOfBirthdaysShow') ? 'has-error' : '' ?>">
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
