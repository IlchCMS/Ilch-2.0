<?php
    $profil = $this->get('profil');
?>
<div id="panel">
    <div class="row">
        <div class="col-lg-2">
            <img class="panel-profile-image" src="<?php echo $this->getStaticUrl().'../'.$this->escape($profil->getAvatar()); ?>" title="<?php echo $this->escape($profil->getName()); ?>">
            <ul class="nav">
            <?php foreach ($this->get('usermenu') as $usermenu): ?>
                <li><a class="" href="<?php echo $this->getUrl($usermenu->getKey()); ?>"><?php echo $usermenu->getTitle(); ?></a></li>
            <?php endforeach; ?>
            </ul>
        </div>
        <div class="col-lg-10">
            <legend><?php echo $this->getTrans('welcome'); ?> <?php echo $this->escape($profil->getName()); ?></legend>
        </div>
    </div>
</div>