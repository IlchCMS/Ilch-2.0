<link href="<?= $this->getModuleUrl('static/css/regist.css') ?>" rel="stylesheet">

<div class="col-xs-12 text-center">
    <div class="row user-regist-step">
        <div class="col-xs-4 step <?php if ($this->getRequest()->getActionName('regist') === 'index') { echo 'activestep'; } ?>">
            <span class="fa fa-gavel"></span>
            <p><?=$this->getTrans('rules') ?></p>
        </div>
        <div class="col-xs-4 step <?php if ($this->getRequest()->getActionName('regist') === 'input') { echo 'activestep'; } ?>">
            <span class="fa fa-user"></span>
            <p><?=$this->getTrans('logindata') ?></p>
        </div>
        <div class="col-xs-4 step <?php if ($this->getRequest()->getActionName('regist') === 'finish') { echo 'activestep'; } ?>">
            <span class="fa fa-check"></span>
            <p><?=$this->getTrans('finish') ?></p>
        </div>
    </div>
</div>
<div class="clearfix"></div>
