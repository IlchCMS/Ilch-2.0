<?php
$config = \Ilch\Registry::get('config');
$groupAccesses = explode(',', $config->get('event_add_entries_accesses'));
?>

<link href="<?=$this->getModuleUrl('static/css/events.css') ?>" rel="stylesheet">

<nav class="navbar navbar-expand-lg navbar-light bg-light mb-3">
    <div class="container-fluid">
        <a class="navbar-brand"><?=$this->getTrans('navigation') ?></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#bs-example-navbar-collapse-1" aria-controls="bs-example-navbar-collapse-1" aria-expanded="false" aria-label="<?=$this->getTrans('naviToggleNavigation') ?>">
           <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="navbar-nav">
                <li class="nav-item <?php if ($this->getRequest()->getActionName() === 'index') { echo 'active'; } ?>"><a href="<?=$this->getUrl(['controller' => 'index', 'action' => 'index']) ?>" class="nav-link"><i class="fa-solid fa-list"></i>&nbsp; <?=$this->getTrans('naviEventsAll') ?></a></li>
                <li class="nav-item <?php if ($this->getRequest()->getActionName() === 'upcoming') { echo 'active'; } ?>"><a href="<?=$this->getUrl(['controller' => 'show', 'action' => 'upcoming']) ?>" class="nav-link"><i class="fa-solid fa-clock-rotate-left fa-flip-horizontal"></i>&nbsp; <?=$this->getTrans('naviEventsUpcoming') ?></a></li>
                <li class="nav-item <?php if ($this->getRequest()->getActionName() === 'current') { echo 'active'; } ?>"><a href="<?=$this->getUrl(['controller' => 'show', 'action' => 'current']) ?>" class="nav-link"><i class="fa-solid fa-clock-rotate-left fa-flip-horizontal"></i>&nbsp; <?=$this->getTrans('naviEventsCurrent') ?></a></li>
                <li class="nav-item <?php if ($this->getRequest()->getActionName() === 'past') { echo 'active'; } ?>"><a href="<?=$this->getUrl(['controller' => 'show', 'action' => 'past']) ?>" class="nav-link"><i class="fa-solid fa-clock-rotate-left"></i>&nbsp; <?=$this->getTrans('naviEventsPast') ?></a></li>
                <?php if ($this->getUser()): ?>
                    <li class="nav-item <?php if ($this->getRequest()->getActionName() === 'participation') { echo 'active'; } ?>"><a href="<?=$this->getUrl(['controller' => 'show', 'action' => 'participation']) ?>" class="nav-link"><i class="fa-solid fa-right-to-bracket"></i>&nbsp; <?=$this->getTrans('naviEventsParticipation') ?></a></li>
                <?php endif; ?>
            </ul>
            <?php if ($this->getUser() && (in_array($this->getUser()->getId(), $groupAccesses) || $this->getUser()->hasAccess('module_events'))): ?>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item <?php if ($this->getRequest()->getActionName() === 'treat') { echo 'active'; } ?>"><a href="<?=$this->getUrl(['controller' => 'index', 'action' => 'treat']) ?>" class="nav-link"><i class="fa-solid fa-plus"></i>&nbsp; <?=$this->getTrans('naviEventsAdd') ?></a></li>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</nav>
