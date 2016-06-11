<?php 
$profil = $this->get('profil');

function getTransKey($usermenuId) {
    switch ($usermenuId) {
    case 1:
        return 'panel';
    case 2:
        return 'dialog';
    case 3:
        return 'gallery';
    case 4:
        return 'settingsSettings';
    }
}
?>

<img class="panel-profile-image" src="<?=$this->getStaticUrl().'../'.$this->escape($profil->getAvatar()) ?>" title="<?=$this->escape($profil->getName()) ?>">
<ul class="nav">
    <?php foreach ($this->get('usermenu') as $usermenu): ?>
        <?php if ($usermenu->getKey() == 'user/panel/gallery' AND $this->get('galleryAllowed') == 0 OR $usermenu->getKey() == 'user/panel/gallery' AND $profil->getOptGallery() == 0): ?>

        <?php else: ?>
           <li><a class="" href="<?=$this->getUrl($usermenu->getKey()) ?>"><?=$this->getTrans(getTransKey($usermenu->getId())) ?></a></li>
        <?php endif; ?>        
    <?php endforeach; ?>
</ul>
