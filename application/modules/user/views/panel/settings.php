<?php
    $profil = $this->get('profil');
?>
<div id="panel">
    <div class="row">
        <div class="col-sm-3 col-md-2 col-lg-2">
            <img class="panel-profile-image" src="<?php echo $this->getStaticUrl().'../'.$this->escape($profil->getAvatar()); ?>" title="<?php echo $this->escape($profil->getName()); ?>">
            <ul class="nav">
            <?php foreach ($this->get('usermenu') as $usermenu): ?>
                <li><a class="" href="<?php echo $this->getUrl($usermenu->getKey()); ?>"><?php echo $usermenu->getTitle(); ?></a></li>
            <?php endforeach; ?>
            </ul>
        </div>
        <div class=" col-sm-9 col-md-10 col-lg-10">
            <legend><?php echo $this->getTrans('menuSettings'); ?></legend>
            <div class="col-sm-6 col-md-4">
                <div class="thumbnail media">
                    <div class="media-body">
                        <h4 class="media-heading"><a href="<?php echo $this->getUrl(array('module' => 'user', 'controller' => 'panel', 'action' => 'editprofile')); ?>"><?php echo $this->getTrans('settingsProfile'); ?></a></h4>
                        <hr>
                        <p><?php echo $this->getTrans('settingsProfileInfo'); ?></p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-4">
                <div class="thumbnail media">
                    <div class="media-body">
                        <h4 class="media-heading"><a href="<?php echo $this->getUrl(array('module' => 'user', 'controller' => 'panel', 'action' => 'avatar')); ?>"><?php echo $this->getTrans('settingsAvatar'); ?></a></h4>
                        <hr>
                        <p><?php echo $this->getTrans('settingsAvatarInfo'); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>