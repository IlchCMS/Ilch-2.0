<?php include APPLICATION_PATH.'/modules/user/views/regist/navi.php'; ?>

<div class="regist panel panel-default">
    <div class="panel-heading">
        <?=$this->getTrans('finish') ?>
    </div>
    <div class="panel-body">
        <div class="col-lg-2 fa-4x check">
            <i class="fa fa-check-circle text-success" title="<?=$this->getTrans('finish') ?>"></i>
        </div>
        <div class="col-lg-10">
            <?=$this->getTranslator()->trans('registerThanks', $this->escape($_SESSION["name"])) ?>
            <br /><br />
        <?php if ($this->get('regist_confirm') == '1'): ?>
            <?=$this->getTranslator()->trans('registerMailSent', $this->escape($_SESSION["email"])) ?>
        <?php else: ?>
            <?=$this->getTrans('readyToLogin') ?>
        <?php endif; ?>
        </div>
    </div>
</div>
