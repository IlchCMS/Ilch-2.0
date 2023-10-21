<?php
$friends = $this->get('friends');
?>

<link href="<?=$this->getModuleUrl('static/css/user.css') ?>" rel="stylesheet">

<div class="row">
    <div class="col-xl-12 profile">
        <?php include APPLICATION_PATH.'/modules/user/views/panel/navi.php'; ?>

        <div class="profile-content active">
            <h1><?=$this->getTrans('friends') ?></h1>
            <?php foreach ($friends as $friend) : ?>
            <div class="col-6 col-lg-2">
                <div class="friend card panel-default">
                    <div class="card-body">
                        <?php $avatar = (empty($friend->getAvatar())) ? $this->getStaticUrl().'/img/noavatar.jpg' : $this->getStaticUrl().'../'.$this->escape($friend->getAvatar())?>
                        <a href="<?=$this->getUrl(['controller' => 'panel', 'action' => 'removeFriend', 'id' => $friend->getFriendUserId()], null, true) ?>" class="btn btn-outline-secondary" style="margin-bottom: 5px;" title="<?=$this->getTrans('removeFriend') ?>"><?=$this->getTrans('removeFriend') ?></a>
                        <img class="img-thumbnail" src="<?=$avatar ?>" title="<?=$this->escape($friend->getName()) ?>" alt="<?=$this->getTrans('avatar') ?>">
                        <a href="<?=$this->getUrl(['controller' => 'profil', 'action' => 'index', 'user' => $friend->getFriendUserId()]) ?>" title="<?=$this->escape($friend->getName()) ?>s <?=$this->getTrans('profile') ?>" class="user-link"><?=$this->escape($friend->getName()) ?></a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
