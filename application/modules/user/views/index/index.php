<?php
$userMapper = new Modules\User\Mappers\User();
?>

<link href="<?=$this->getModuleUrl('static/css/user.css') ?>" rel="stylesheet">

<legend><?=$this->getTrans('menuUserList') ?></legend>
<div class="row">
    <?php foreach ($this->get('userList') as $userlist): ?>
        <div class="col-lg-4">
            <div class="user">
                <img class="thumbnail" src="<?=$this->getStaticUrl().'../'.$this->escape($userlist->getAvatar()) ?>" alt="">
                <h3><a href="<?=$this->getUrl(array('controller' => 'profil', 'action' => 'index', 'user' => $userlist->getId())) ?>" title="<?=$this->escape($userlist->getName()) ?>s <?=$this->getTrans('profile') ?>"><?=$this->escape($userlist->getName()) ?></a></h3>
                <div class="userInfo">
                    <i class="fa fa-sign-in" title="<?=$this->getTrans('regist') ?>"></i> <?=$this->escape($userlist->getDateCreated()) ?><br />
                    <?php $dateLastActivity = $userlist->getDateLastActivity(); ?>
                    <?php if($dateLastActivity->getTimestamp() != 0): ?>
                        <i class="fa fa-eye" title="<?=$this->getTrans('dateLastVisited') ?>"></i> <?=$this->escape($dateLastActivity) ?>
                    <?php endif; ?>
                </div>
                <div class="userLinks">
                    <?=$this->getTrans('contact'); ?>:
                    <br>
                    <a href="<?=$this->getUrl(array('controller' => 'profil', 'action' => 'index', 'user' => $userlist->getId())) ?>" class="fa fa-user" title="<?=$this->escape($userlist->getName()) ?>s <?=$this->getTrans('profile') ?>"></a>
                    <?php if($this->getUser() AND $this->getUser()->getId() != $this->escape($userlist->getID())): ?>
                        <a href="<?=$this->getUrl(array('controller' => 'panel', 'action' => 'dialognew', 'id' => $userlist->getId())) ?>" class="fa fa-comment" title="<?=$this->getTrans('privateMessage') ?>"></a>
                    <?php endif; ?>
                    <?php if ($userlist->getOptMail() == 1 AND $this->getUser() AND $this->getUser()->getId() != $userlist->getID()): ?>
                        <a href="<?=$this->getUrl(array('controller' => 'mail', 'action' => 'index', 'user' => $userlist->getId())) ?>" class="fa fa-envelope" title="<?=$this->getTrans('email') ?>"></a>
                    <?php endif; ?>
                    <?php if($this->escape($userlist->getHomepage()) != ''): ?>
                        <a href="<?=$userMapper->getHomepage($this->escape($userlist->getHomepage())) ?>" class="fa fa-globe" title="<?=$this->getTrans('website') ?>"></a>
                    <?php endif; ?>
                    <?php if($this->escape($userlist->getFacebook()) != ''): ?>
                        <a href="<?=$userMapper->getHomepage($this->escape($userlist->getFacebook())) ?>" class="fa fa-facebook" title="<?=$this->getTrans('profileFacebook') ?>"></a>
                    <?php endif; ?>
                    <?php if($this->escape($userlist->getTwitter()) != ''): ?>
                        <a href="<?=$userMapper->getHomepage($this->escape($userlist->getTwitter())) ?>" class="fa fa-twitter" title="<?=$this->getTrans('profileTwitter') ?>"></a>
                    <?php endif; ?>
                    <?php if($this->escape($userlist->getGoogle()) != ''): ?>
                        <a href="<?=$userMapper->getHomepage($this->escape($userlist->getGoogle())) ?>" class="fa fa-google-plus" title="<?=$this->getTrans('profileGoogle') ?>"></a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
