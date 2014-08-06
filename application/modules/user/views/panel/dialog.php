<?php
    $profil = $this->get('profil');
?>
<div id="panel">
    <div class="row">
        <div class="col-lg-2">
            <img class="panel-profile-image" src="<?php echo $this->getStaticUrl().'../'.$this->escape($profil->getAvatar()); ?>" title="<?php echo $this->escape($profil->getName()); ?>">
            <ul class="nav">
            <?php foreach ($this->get('usermenu') as $sermenu): ?>
                <li><a class="" href="<?php echo $this->getUrl($sermenu->getKey()); ?>"><?php echo $sermenu->getTitle(); ?></a></li>
            <?php endforeach; ?>
            </ul>
        </div>
        <div class="col-lg-10">
            <legend><?php echo $this->getTrans('dialog'); ?></legend>
            <div class="panel-body">
                <ul class="dialog">
                <?php if ($this->get('dialog') == !'') {?>
                <?php foreach ($this->get('dialog') as $dialog): ?>
                    <li class="left clearfix">
                        <span class="pull-left">
                            <img class="avatar" src="<?php echo $this->getUrl().'/'.$dialog->getAvatar(); ?>" alt="User Avatar" class="img-circle">
                        </span>
                        <div class="dialog-body clearfix">
                            <div class="header">
                                <strong>
                                    <a href="<?php echo $this->getUrl(array('module' => 'user', 'controller' => 'panel', 'action' => 'dialogview', 'id' => $dialog->getCId())); ?>"><?php echo $dialog->getName(); ?></a>
                                </strong>
                                <small class="pull-right">
                                    <span class="glyphicon glyphicon-time"></span> <?php echo $dialog->getTime(); ?>
                                </small>
                            </div>
                            <p>
                               <?php echo nl2br($this->getHtmlFromBBCode($dialog->getText())) ?>
                            </p>
                        </div>
                    </li>
                <?php endforeach; ?>
                <?php } else { ?> 
                    <p><?php echo $this->getTrans('noDialog'); ?></p>
                <?php }?>   
                </ul>
            </div>
        </div>
    </div>
</div>
<style>
    .dialog
    {
        list-style: none;
        margin: 0;
        padding: 0;
    }
    .dialog li
    {
        margin-bottom: 10px;
        padding-bottom: 5px;
        border-bottom: 1px dotted #B3A9A9;
    }

    .dialog li.left .dialog-body
    {
        margin-left: 60px;
    }

    .dialog li.right .dialog-body
    {
        margin-right: 60px;
    }
    .dialog li .dialog-body p
    {
        margin: 0;
        padding-top: 10px;
        color: #777777;
    }
    .avatar{
        width: 40px;
        height: auto;
    }
    .panel-primary {
        border-color: #DDD;
    }
    .panel-primary > .panel-heading {
        color: #000;
        background-color: #DDD;
        border-color: #DDD;
    }
</style>