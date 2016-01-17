<?php
$profil = $this->get('profil');
?>

<div id="panel">
    <div class="row">
        <div class="col-lg-2">
            <img class="panel-profile-image" src="<?=$this->getStaticUrl().'../'.$this->escape($profil->getAvatar()) ?>" title="<?=$this->escape($profil->getName()) ?>">
            <ul class="nav">
                <?php foreach ($this->get('usermenu') as $usermenu): ?>
                    <li><a class="" href="<?=$this->getUrl($usermenu->getKey()) ?>"><?=$usermenu->getTitle() ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="col-lg-10">
            <legend><?=$this->getTrans('welcome'); ?> <?=$this->escape($profil->getName()) ?></legend>
        </div>
    </div>
</div>
