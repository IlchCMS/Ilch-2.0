<?php 
$userMapper = $this->get('userMapper');
$profil = $this->get('profil');
$birthday = '';
if ($profil->getBirthday()) {
    $birthday = new \Ilch\Date($profil->getBirthday());
}
$profileFields = $this->get('profileFields');
$profileFieldsContent = $this->get('profileFieldsContent');
$profileFieldsTranslation = $this->get('profileFieldsTranslation');

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
                <?php if ($this->getUser() AND $this->getUser()->getId() != $this->escape($profil->getID())): ?>
                    <a href="<?=$this->getUrl(['controller' => 'panel', 'action' => 'dialognew', 'id' => $profil->getId()]) ?>" ><?=$this->getTrans('newMessage') ?></a>
                 <?php endif; ?>
                <div class="detail">
                    <i class="fa fa-sign-in" title="<?=$this->getTrans('regist') ?>"></i> <?=$this->escape($profil->getDateCreated()) ?><br />
                    <?php $dateLastActivity = $profil->getDateLastActivity(); ?>
                    <?php if ($dateLastActivity != ''): ?>
                        <i class="fa fa-eye" title="<?=$this->getTrans('dateLastVisited') ?>"></i> <?=$this->escape($profil->getDateLastActivity()) ?>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-lg-4 hidden-xs concatLinks-lg">
                <?php if ($this->getUser() AND $profil->getOptMail() == 1 AND $this->getUser()->getId() != $this->getRequest()->getParam('user')): ?>
                    <a href="<?=$this->getUrl(['controller' => 'mail', 'action' => 'index', 'user' => $profil->getId()]) ?>" class="fa fa-envelope" title="<?=$this->getTrans('email') ?>"></a>
                <?php endif; ?>
                <?php if ($this->get('gallery') != 0 AND $profil->getOptGallery() != 0 AND $this->get('galleryAllowed') != 0): ?>
                    <a href="<?=$this->getUrl(['controller' => 'gallery', 'action' => 'index', 'user' => $profil->getId()]) ?>" class="fa fa-picture-o" title="<?=$this->getTrans('gallery') ?>"></a>
                <?php endif; ?>
                <?php if ($profil->getHomepage() != ''): ?>
                    <a href="<?=$userMapper->getHomepage($this->escape($profil->getHomepage())) ?>" target="_blank" class="fa fa-globe" title="<?=$this->getTrans('website') ?>"></a>
                <?php endif; ?>
                <?php if ($profil->getFacebook() != ''): ?>
                    <a href="https://www.facebook.com/<?=$this->escape($profil->getFacebook()) ?>" target="_blank" class="fa fa-facebook" title="<?=$this->getTrans('profileFacebook') ?>"></a>
                <?php endif; ?>
                <?php if ($profil->getTwitter() != ''): ?>
                    <a href="https://twitter.com/<?=$this->escape($profil->getTwitter()) ?>" target="_blank" class="fa fa-twitter" title="<?=$this->getTrans('profileTwitter') ?>"></a>
                <?php endif; ?>
                <?php if ($profil->getGoogle() != ''): ?>
                    <a href="https://plus.google.com/<?=$this->escape($profil->getGoogle()) ?>" target="_blank" class="fa fa-google-plus" title="<?=$this->getTrans('profileGoogle') ?>"></a>
                <?php endif; ?>
                <?php if ($this->escape($profil->getSteam()) != ''): ?>
                    <a href="https://steamcommunity.com/id/<?=$this->escape($profil->getSteam()) ?>" target="_blank" class="fa fa-steam-square fa-lg user-link" title="<?=$this->getTrans('profileSteam') ?>"></a>
                <?php endif; ?>
                <?php if ($this->escape($profil->getTwitch()) != ''): ?>
                    <a href="https://www.twitch.tv/<?=$this->escape($profil->getTwitch()) ?>" target="_blank" class="fa fa-twitch fa-lg user-link" title="<?=$this->getTrans('profileTwitch') ?>"></a>
                <?php endif; ?>
                <?php if ($this->escape($profil->getTeamspeak()) != ''): ?>
                    <a href="ts3server://<?=$this->escape($profil->getTeamspeak()) ?>" target="_blank" class="fa fa-teamspeak fa-lg user-link" title="<?=$this->getTrans('profileTeamspeak') ?>"></a>
                <?php endif; ?>
                <?php if ($this->escape($profil->getDiscord()) != ''): ?>
                    <a href="https://discord.gg/<?=$this->escape($profil->getDiscord()) ?>" target="_blank" class="fa fa-discord fa-lg user-link" title="<?=$this->getTrans('profileDiscord') ?>"></a>
                <?php endif; ?>
            </div>
        </div>        
    </div>
    <br />
    <div class="profil-content">
        <h1><?=$this->getTrans('profileDetails') ?></h1>
        <div class="row">
            <div class="col-lg-2 detail bold">
                <?=$this->getTrans('profileFirstName') ?>
            </div>
            <div class="col-lg-10 detail">
                <?=$this->escape($profil->getFirstName()) ?>
            </div>
        </div>
         <div class="row">
            <div class="col-lg-2 detail bold">
                <?=$this->getTrans('profileLastName') ?>
            </div>
            <div class="col-lg-10 detail">
                <?=$this->escape($profil->getLastName()) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-2 detail bold">
                <?=$this->getTrans('profileGender') ?>
            </div>
            <div class="col-lg-10 detail">
                <?php if ($profil->getGender() == 1) {
                    echo $this->getTrans('profileGenderMale');
                } elseif ($profil->getGender() == 2) {
                    echo $this->getTrans('profileGenderFemale');
                } else {
                    echo $this->getTrans('profileGenderUnknow');
                } ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-2 detail bold">
                <?=$this->getTrans('profileCity') ?>
            </div>
            <div class="col-lg-10 detail">
                <?=$this->escape($profil->getCity()) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-2 detail bold">
                <?=$this->getTrans('profileBirthday') ?>
            </div>
            <div class="col-lg-10 detail">
                <?php if ($profil->getBirthday() != '') { echo $birthday->format('d-m-Y', true); } ?>
            </div>
        </div>

        <?php
        foreach ($profileFields as $profileField) {
            foreach ($profileFieldsContent as $profileFieldContent) {
                if($profileField->getId() == $profileFieldContent->getFieldId()) {
                    $profileFieldName = $profileField->getName();
                    foreach ($profileFieldsTranslation as $profileFieldTrans) {
                        if($profileField->getId() == $profileFieldTrans->getFieldId()) {
                            $profileFieldName = $profileFieldTrans->getName();
                            break;
                        }
                    }
                    if($profileField->getType() == 0) : ?>
                        <div class="row">
                            <div class="col-lg-2 detail bold">
                                <?=$this->escape($profileFieldName) ?>
                            </div>
                            <div class="col-lg-10 detail">
                                <?=$this->escape($profileFieldContent->getValue()) ?>
                            </div>
                        </div>
                    <?php else : ?>
                        <div class="clearfix"></div>
                        <br />
                        <h1><?=$this->escape($profileFieldName) ?></h1>
                    <?php endif;
                    break;
                }
            }
        }
        ?>

        <?php if ($profil->getSignature() != ''): ?>
            <div class="clearfix"></div>
            <br />
            <h1><?=$this->getTrans('profileSignature') ?></h1>
            <div class="row">
                <div class="col-lg-10 detail">
                    <?=nl2br($this->getHtmlFromBBCode($profil->getSignature())) ?>
                </div>
            </div>
        <?php endif; ?>
        <div class="clearfix"></div>
        <br />
        <h1><?=$this->getTrans('others') ?></h1>
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
