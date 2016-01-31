<?php
$profil = $this->get('profil');
?>

<link href="<?=$this->getModuleUrl('static/css/user.css') ?>" rel="stylesheet">

<div id="panel">
    <div class="row">
        <div class="col-lg-2">
            <?php include APPLICATION_PATH.'/modules/user/views/panel/navi.php'; ?>
        </div>
        <div class="col-lg-10">
            <legend><?=$this->getTrans('dialog'); ?></legend>
            <div class="panel-body">
                <ul class="dialog">
                <?php if ($this->get('dialog') == !''): ?>
                <?php foreach ($this->get('dialog') as $dialog): ?>
                    <li class="left clearfix">
                        <span class="pull-left">
                            <img class="img-circle avatar" src="<?=$this->getUrl().'/'.$dialog->getAvatar() ?>" alt="User Avatar">
                        </span>
                        <div class="dialog-body clearfix">
                            <div class="header">
                                <strong>
                                    <a href="<?=$this->getUrl(array('controller' => 'panel', 'action' => 'dialogview', 'id' => $dialog->getCId())) ?>"><?=$dialog->getName() ?></a>
                                </strong>
                                <small class="pull-right">
                                    <span class="glyphicon glyphicon-time"></span> <?=$dialog->getTime() ?>
                                </small>
                            </div>
                            <p>
                               <?=nl2br($this->getHtmlFromBBCode($dialog->getText())) ?>
                            </p>
                        </div>
                    </li>
                <?php endforeach; ?>
                <?php else: ?> 
                    <p><?=$this->getTrans('noDialog') ?></p>
                <?php endif; ?>   
                </ul>
            </div>
        </div>
    </div>
</div>
