<link href="<?=$this->getModuleUrl('static/css/events.css') ?>" rel="stylesheet">

<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand"><?=$this->getTrans('navigation') ?></a>
        </div>
    
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li <?php if ($this->getRequest()->getActionName() == 'index') { echo 'class="active"'; } ?>><a href="<?=$this->getUrl(array('controller' => 'index', 'action' => 'index')); ?>"><i class="fa fa-list"></i>&nbsp; <?=$this->getTrans('naviEventsAll') ?></a></li>
                <li <?php if ($this->getRequest()->getActionName() == 'upcoming') { echo 'class="active"'; } ?>><a href="<?=$this->getUrl(array('controller' => 'show', 'action' => 'upcoming')); ?>"><i class="fa fa-history fa-flip-horizontal"></i>&nbsp; <?=$this->getTrans('naviEventsUpcoming') ?></a></li>
                <li <?php if ($this->getRequest()->getActionName() == 'past') { echo 'class="active"'; } ?>><a href="<?=$this->getUrl(array('controller' => 'show', 'action' => 'past')); ?>"><i class="fa fa-history"></i>&nbsp; <?=$this->getTrans('naviEventsPast') ?></a></li>
                <?php if ($this->getUser()): ?>
                    <li <?php if ($this->getRequest()->getActionName() == 'participation') { echo 'class="active"'; } ?>><a href="<?=$this->getUrl(array('controller' => 'show', 'action' => 'participation')); ?>"><i class="fa fa-sign-in"></i>&nbsp; <?=$this->getTrans('naviEventsParticipation') ?></a></li>
                    <li <?php if ($this->getRequest()->getActionName() == 'my') { echo 'class="active"'; } ?>><a href="<?=$this->getUrl(array('controller' => 'show', 'action' => 'my')); ?>"><i class="fa fa-home"></i>&nbsp; <?=$this->getTrans('naviEventsMy') ?></a></li>
                <?php endif; ?>
            </ul>
            <?php if ($this->getUser()): ?>
                <ul class="nav navbar-nav navbar-right">
                    <li <?php if ($this->getRequest()->getActionName() == 'treat') { echo 'class="active"'; } ?>><a href="<?=$this->getUrl(array('controller' => 'index', 'action' => 'treat')); ?>"><i class="fa fa-plus"></i>&nbsp; <?=$this->getTrans('naviEventsAdd') ?></a></li>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</nav>
