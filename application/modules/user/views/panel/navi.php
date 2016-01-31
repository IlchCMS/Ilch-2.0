<?php 
$profil = $this->get('profil');
?>

<link href="<?=$this->getModuleUrl('static/css/user.css') ?>" rel="stylesheet">

<img class="panel-profile-image" src="<?=$this->getStaticUrl().'../'.$this->escape($profil->getAvatar()) ?>" title="<?=$this->escape($profil->getName()) ?>">
<ul class="nav">
    <?php foreach ($this->get('usermenu') as $usermenu): ?>
        <?php if ($usermenu->getKey() == 'user/panel/gallery' AND $this->get('galleryAllowed') == 0 OR $usermenu->getKey() == 'user/panel/gallery' AND $profil->getOptGallery() == 0): ?>

        <?php else: ?>
           <li><a class="" href="<?=$this->getUrl($usermenu->getKey()) ?>"><?=$usermenu->getTitle() ?></a></li>
        <?php endif; ?>        
    <?php endforeach; ?>
</ul>
