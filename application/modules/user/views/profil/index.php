<?php 
$userMapper = new Modules\User\Mappers\User();
$profil = $this->get('profil'); 
$birthday = new \Ilch\Date($profil->getBirthday());

$groups = '';
foreach ($profil->getGroups() as $group) {
    if ($groups != '') {
        $groups .= ', ';
    }

    $groups .= $group->getName();
} 
?>

<link href="<?=$this->getModuleUrl('static/css/user.css') ?>" rel="stylesheet">

<div class="profil">
    <div class="profil-header">
        <div class="row">
            <div class="col-lg-2">
                <img class="thumbnail" src="<?=$this->getStaticUrl().'../'.$this->escape($profil->getAvatar()) ?>" title="<?=$this->escape($profil->getName()) ?>">
            </div>
            <div class="col-lg-5 col-xs-12">
                <h3><?=$this->escape($profil->getName()) ?></h3>
                <?php if($this->getUser() AND $this->getUser()->getId() != $this->escape($profil->getID())): ?>
                    <a href="<?=$this->getUrl(array('controller' => 'panel', 'action' => 'dialognew', 'id' => $profil->getId())) ?>" >Neue Nachricht</a>
                 <?php endif; ?>
                <div class="detail">
                    <i class="fa fa-sign-in" title="<?=$this->getTrans('regist') ?>"></i> <?=$this->escape($profil->getDateCreated()) ?><br />
                    <?php $dateLastActivity = $profil->getDateLastActivity(); ?>
                    <?php if($dateLastActivity->getTimestamp() != 0): ?>
                        <i class="fa fa-eye" title="<?=$this->getTrans('dateLastVisited') ?>"></i> <?=$this->escape($profil->getDateLastActivity()) ?>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-lg-4 hidden-xs concatLinks-lg">
                <?php if ($this->getUser() AND $profil->getOptMail() == 1 AND $this->getUser()->getId() != $this->getRequest()->getParam('user')): ?>
                    <a href="<?=$this->getUrl(array('controller' => 'mail', 'action' => 'index', 'user' => $profil->getId())) ?>" class="fa fa-envelope" title="<?=$this->getTrans('email') ?>"></a>
                <?php endif; ?>
                <?php if ($this->get('gallery') != 0 AND $profil->getOptGallery() != 0 AND $this->get('galleryAllowed') != 0): ?>
                    <a href="<?=$this->getUrl(array('controller' => 'gallery', 'action' => 'index', 'user' => $profil->getId())) ?>" class="fa fa-picture-o" title="<?=$this->getTrans('gallery') ?>"></a>
                <?php endif; ?>
                <?php if ($profil->getHomepage() != ''): ?>
                    <a href="<?=$userMapper->getHomepage($this->escape($profil->getHomepage())) ?>" target="_blank" class="fa fa-globe" title="<?=$this->getTrans('website') ?>"></a>
                <?php endif; ?>
                <?php if ($profil->getFacebook() != ''): ?>
                    <a href="<?=$userMapper->getHomepage($this->escape($profil->getFacebook())) ?>" target="_blank" class="fa fa-facebook" title="<?=$this->getTrans('profileFacebook') ?>"></a>
                <?php endif; ?>
                <?php if ($profil->getTwitter() != ''): ?>
                    <a href="<?=$userMapper->getHomepage($this->escape($profil->getTwitter())) ?>" target="_blank" class="fa fa-twitter" title="<?=$this->getTrans('profileTwitter') ?>"></a>
                <?php endif; ?>
                <?php if ($profil->getGoogle() != ''): ?>
                    <a href="<?=$userMapper->getHomepage($this->escape($profil->getGoogle())) ?>" target="_blank" class="fa fa-google-plus" title="<?=$this->getTrans('profileGoogle') ?>"></a>
                <?php endif; ?>
            </div>
        </div>        
    </div>
    <br />
    <div class="profil-content">
        <legend><?=$this->getTrans('profileDetails') ?></legend>
        <div class="row">
            <div class="col-lg-2 detail bold">
                <?=$this->getTrans('profileFirstName') ?>:
            </div>
            <div class="col-lg-10 detail">
                <?=$this->escape($profil->getFirstName()) ?>
            </div>
        </div>
         <div class="row">
            <div class="col-lg-2 detail bold">
                <?=$this->getTrans('profileLastName') ?>:
            </div>
            <div class="col-lg-10 detail">
                <?=$this->escape($profil->getLastName()) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-2 detail bold">
                <?=$this->getTrans('profileCity') ?>:
            </div>
            <div class="col-lg-10 detail">
                <?=$this->escape($profil->getCity()) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-2 detail bold">
                <?=$this->getTrans('profileBirthday') ?>:
            </div>
            <div class="col-lg-10 detail">
                <?php if ($profil->getBirthday() != '0000-00-00') { echo $birthday->format('d-m-Y', true); } ?>
            </div>
        </div>
        <?php if ($profil->getSignature() != ''): ?>
            <div class="clearfix"></div>
            <br />
            <legend><?=$this->getTrans('profileSignature') ?></legend>
            <div class="row">
                <div class="col-lg-10 detail">
                    <?=nl2br($this->getHtmlFromBBCode($profil->getSignature())) ?>
                </div>
            </div>
        <?php endif; ?>
        <div class="clearfix"></div>
        <br />
        <legend><?=$this->getTrans('others') ?></legend>
        <div class="row">
            <div class="col-lg-2 detail bold">
                <?=$this->getTrans('groups') ?>:
            </div>
            <div class="col-lg-10 detail">
                <?=$this->escape($groups) ?>
            </div>
        </div>
    </div>
</div>
