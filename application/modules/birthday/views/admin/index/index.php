<link href="<?=$this->getModuleUrl('static/css/birthday.css') ?>" rel="stylesheet">

<legend><?=$this->getTrans('settings') ?></legend>
<form class="form-horizontal" method="POST" action="<?=$this->getUrl(['action' => $this->getRequest()->getActionName()]) ?>">
    <?=$this->getTokenField() ?>
    <div class="form-group">
        <label for="entrySettings" class="col-lg-2 control-label">
            <?=$this->getTrans('numberOfBirthsdayShow') ?>:
        </label>
        <div class="col-lg-1">
            <input type="number"
                   class="form-control"
                   id="entrySettings"
                   name="entrySettings"
                   min="1"
                   value="<?=$this->get('setShow') ?>">
        </div>
    </div>
    <?=$this->getSaveBar() ?>
</form>
