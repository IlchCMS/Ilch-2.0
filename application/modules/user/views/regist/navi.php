<?php 
if ($this->getRequest()->getActionName('regist') == 'index') {
    $ruleClass = 'btn-success';
    $inputClass = 'btn-default';
    $finishClass = 'btn-default';
}  elseif ($this->getRequest()->getActionName('regist') == 'input') {
    $ruleClass = 'btn-success';
    $inputClass = 'btn-success';
    $finishClass = 'btn-default';
}  elseif ($this->getRequest()->getActionName('regist') == 'finish') {
    $ruleClass = 'btn-success';
    $inputClass = 'btn-success';
    $finishClass = 'btn-success';
}
?>
<link href="<?= $this->getModuleUrl('static/css/regist.css') ?>" rel="stylesheet">

<legend><?=$this->getTrans('menuRegist') ?></legend>
<div class="process">
    <div class="process-row">
        <div class="process-step">
            <button type="button" class="btn <?=$ruleClass ?> btn-circle" disabled="disabled"><i class="fa fa-gavel fa-2x"></i></button>
            <p><?=$this->getTrans('rules') ?></p>
        </div>
        <div class="process-step">
            <button type="button" class="btn <?=$inputClass ?> btn-circle" disabled="disabled"><i class="fa fa-user fa-2x"></i></button>
            <p><?=$this->getTrans('logindata') ?></p>
        </div>
        <div class="process-step">
            <button type="button" class="btn <?=$finishClass ?> btn-circle" disabled="disabled"><i class="fa fa-check fa-2x"></i></button>
            <p><?=$this->getTrans('finish') ?></p>
        </div>
    </div>
</div>
