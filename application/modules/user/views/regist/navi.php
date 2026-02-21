<link href="<?= $this->getModuleUrl('static/css/regist.css') ?>" rel="stylesheet">

<div class="col-xs-12 text-center">
    <div class="row user-regist-step">
        <div class="col-md-4 step <?=($this->getRequest()->getActionName('regist') === 'index') ? 'activestep' : '' ?>">
            <span class="fa-solid fa-gavel"></span>
            <p><?=$this->getTrans('rules') ?></p>
        </div>
        <div class="col-md-4 step <?=($this->getRequest()->getActionName('regist') === 'input') ? 'activestep' : '' ?>">
            <span class="fa-solid fa-user"></span>
            <p><?=$this->getTrans('logindata') ?></p>
        </div>
        <div class="col-md-4 step <?=($this->getRequest()->getActionName('regist') === 'finish') ? 'activestep' : '' ?>">
            <span class="fa-solid fa-check"></span>
            <p><?=$this->getTrans('finish') ?></p>
        </div>
    </div>
</div>
<div class="clearfix"></div>
