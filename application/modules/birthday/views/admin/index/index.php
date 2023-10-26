<link href="<?=$this->getModuleUrl('static/css/birthday.css') ?>" rel="stylesheet">

<h1><?=$this->getTrans('settings') ?></h1>

<form class="form-horizontal" method="POST" action="<?=$this->getUrl(['action' => $this->getRequest()->getActionName()]) ?>">
    <?=$this->getTokenField() ?>
    <div class="row mb-3 <?=$this->validation()->hasError('numberOfBirthdaysShow') ? 'has-error' : '' ?>">
        <label for="numberOfBirthdaysShow" class="col-xl-2 control-label">
            <?=$this->getTrans('numberOfBirthdaysShow') ?>:
        </label>
        <div class="col-xl-1">
            <input type="number"
                   class="form-control"
                   id="numberOfBirthdaysShow"
                   name="numberOfBirthdaysShow"
                   min="1"
                   value="<?=$this->get('numberOfBirthdaysShow') ?>">
        </div>
    </div>
    <div class="row mb-3 <?=$this->validation()->hasError('visibleForGuest') ? 'has-error' : '' ?>">
        <div class="col-xl-2 control-label">
            <?=$this->getTrans('visibleForGuest') ?>:
        </div>
        <div class="col-xl-2">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" id="visibleForGuest-on" name="visibleForGuest" value="1" <?php if ($this->get('visibleForGuest')) { echo 'checked="checked"'; } ?> />
                <label for="visibleForGuest-on" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('on') ?></label>
                <input type="radio" class="flipswitch-input" id="visibleForGuest-off" name="visibleForGuest" value="0" <?php if (!$this->get('visibleForGuest')) { echo 'checked="checked"'; } ?> />
                <label for="visibleForGuest-off" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('off') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
        </div>
    </div>
    <?=$this->getSaveBar() ?>
</form>
