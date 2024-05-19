<?php
$profil = $this->get('profil');

function getTransKey($usermenuId)
{
    switch ($usermenuId) {
    case 1:
        return 'panel';
    case 2:
        return 'dialog';
    case 3:
        return 'gallery';
    case 4:
        return 'friends';
    case 5:
        return 'settingsSettings';
    }
}
?>

<div class="profile-sidebar active">
    <div class="push-menu">
        <i class="fa-solid fa-bars float-end"></i>
    </div>
    <div class="profile-userpic">
        <img src="<?=$this->getStaticUrl() . '../' . $this->escape($profil->getAvatar()) ?>" class="rounded-circle" title="<?=$this->escape($profil->getName()) ?>" alt="<?=$this->getTrans('avatar') ?>">
    </div>
    <div class="profile-usertitle">
        <div class="profile-name">
            <?=$this->escape($profil->getName()) ?>
        </div>
    </div>
    <div class="profile-usermenu">
        <ul class="nav flex-column">
            <?php foreach ($this->get('usermenu') as $usermenu) {
    $class = '';
    if ($usermenu->getKey() == 'user/panel/'.$this->getRequest()->getActionName()) {
        $class = 'active';
    }

    if ($usermenu->getKey() === 'user/panel/gallery' && ($this->get('galleryAllowed') == 0 || $profil->getOptGallery() == 0)) {
    } else {
        echo '<li class="nav-item ' . $class . '"><a href="' . $this->getUrl($usermenu->getKey()) . '" class="nav-link">' . $this->getTrans(getTransKey($usermenu->getId())) . ' <i class="' . $usermenu->getIcon() . ' float-end"></i></a></li>';
    }
} ?>
        </ul>
    </div>
</div>

<script>
$(document).ready(function(){
    $(".push-menu").click(function(){
        $(".profile-sidebar").toggleClass("active");
        $(".profile-content").toggleClass("active");
    });
});
</script>
