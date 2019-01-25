<?php
$profil = $this->get('profil');
$openFriendRequests = $this->get('openFriendRequests');
?>

<link href="<?=$this->getModuleUrl('static/css/user.css') ?>" rel="stylesheet">

<div class="row">
    <div class="col-lg-12 profile">
        <?php include APPLICATION_PATH.'/modules/user/views/panel/navi.php'; ?>

        <div class="profile-content active">
            <h1><?=$this->getTrans('welcome'); ?> <?=$this->escape($profil->getName()) ?></h1>

            <?php if(!empty($openFriendRequests)): ?>
                <h1><?=$this->getTrans('friendRequests') ?></h1>
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <colgroup>
                            <col class="icon_width">
                            <col class="icon_width">
                            <col class="col-lg-12">
                        </colgroup>
                        <thead>
                        <tr>
                            <th></th>
                            <th></th>
                            <th><?=$this->getTrans('userName') ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($openFriendRequests as $openFriendRequest): ?>
                            <tr>
                                <td><a href="<?=$this->getUrl(['action' => 'approveFriendRequest', 'id' => $openFriendRequest->getUserId()], null, true) ?>" title="<?=$this->getTrans('approveFriendRequest') ?>"><i class="fa fa-check text-success"></i></a></td>
                                <td><a href="<?=$this->getUrl(['action' => 'removeFriend', 'id' => $openFriendRequest->getUserId()], null, true) ?>" title="<?=$this->getTrans('declineFriendRequest') ?>"><i class="fa fa-ban text-danger"></i></a></td>
                                <td><a href="<?=$this->getUrl(['controller' => 'profil', 'action' => 'index', 'user' => $openFriendRequest->getUserId()]) ?>" title="<?=$this->escape($openFriendRequest->getName()) ?>s <?=$this->getTrans('profile') ?>"><?=$openFriendRequest->getName() ?></a></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
