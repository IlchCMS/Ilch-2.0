<?php

use Ilch\Date;
use Modules\User\Mappers\User;
use Modules\User\Models\ProfileField;
use Modules\User\Models\ProfileFieldContent;
use Modules\User\Models\ProfileFieldTranslation;

/** @var User $userMapper */
$userMapper = $this->get('userMapper');

/** @var \Modules\User\Models\User $profil */
$profil = $this->get('profil');

$birthday = '';
if ($profil->getBirthday()) {
    $birthday = new Date($profil->getBirthday());
}
$date = new Date();

/** @var ProfileField[] $profileIconFields */
$profileIconFields = $this->get('profileIconFields');

/** @var ProfileField[] $profileFields */
$profileFields = $this->get('profileFields');

/** @var ProfileFieldContent[] $profileFieldsContent */
$profileFieldsContent = $this->get('profileFieldsContent');

/** @var ProfileFieldTranslation[] $profileFieldsTranslation */
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
            <div class="col-xl-3">
                <img class="img-thumbnail" src="<?=$this->getStaticUrl() . '../' . $this->escape($profil->getAvatar()) ?>" title="<?=$this->escape($profil->getName()) ?>" alt="<?=$this->getTrans('avatar') ?>">
                <?php if ($profil->getId() != $this->getUser()->getId()) : ?>
                <div style="margin-top: 5px">
                    <?php if ($this->get('isFriend')) : ?>
                        <a href="<?=$this->getUrl(['controller' => 'panel', 'action' => 'removeFriend', 'id' => $profil->getId()], null, true) ?>" class="btn btn-outline-secondary" title="<?=$this->getTrans('removeFriend') ?>"><?=$this->getTrans('removeFriend') ?></a>
                    <?php else : ?>
                        <a href="<?=$this->getUrl(['controller' => 'panel', 'action' => 'sendFriendRequest', 'id' => $profil->getId()], null, true) ?>" class="btn btn-outline-secondary" title="<?=$this->getTrans('sendFriendRequest') ?>"><?=$this->getTrans('sendFriendRequest') ?></a>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
            <div class="col-xl-5 col-12">
                <h3><?=$this->escape($profil->getName()) ?></h3>
                <div class="detail">
                    <i class="fa-solid fa-right-to-bracket" title="<?=$this->getTrans('regist') ?>"></i> <?=$this->escape($profil->getDateCreated()) ?><br />
                    <?php $dateLastActivity = $profil->getDateLastActivity(); ?>
                    <?php if ($dateLastActivity != '') : ?>
                        <i class="fa-solid fa-eye" title="<?=$this->getTrans('dateLastVisited') ?>"></i> <?=$this->escape($profil->getDateLastActivity()) ?>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-xl-4 d-none d-sm-block concatLinks-lg">
                <?php if ($this->getUser() && $this->getUser()->getId() != $this->getRequest()->getParam('user')) : ?>
                    <a href="<?=$this->getUrl(['controller' => 'panel', 'action' => 'dialognew', 'id' => $profil->getId()]) ?>" class="fa-solid fa-comment" title="<?=$this->getTrans('privateMessage') ?>"></a>
                <?php endif; ?>
                <?php if ($this->getUser() && $profil->getOptMail() == 1 && $this->getUser()->getId() != $this->getRequest()->getParam('user')) : ?>
                    <a href="<?=$this->getUrl(['controller' => 'mail', 'action' => 'index', 'user' => $profil->getId()]) ?>" class="fa-solid fa-envelope" title="<?=$this->getTrans('email') ?>"></a>
                <?php endif; ?>
                <?php if ($this->get('gallery') != 0 && $profil->getOptGallery() != 0 && $this->get('galleryAllowed') != 0) : ?>
                    <a href="<?=$this->getUrl(['controller' => 'gallery', 'action' => 'index', 'user' => $profil->getId()]) ?>" class="fa-regular fa-image" title="<?=$this->getTrans('gallery') ?>"></a>
                <?php endif; ?>

                <?php foreach ($profileIconFields as $profileIconField) {
    if ($profileIconField->getShow()) {
        foreach ($profileFieldsContent as $profileFieldContent) {
            if ($profileFieldContent->getValue() && $profileIconField->getId() == $profileFieldContent->getFieldId()) {
                $profileFieldName = $profileIconField->getKey();
                foreach ($profileFieldsTranslation as $profileFieldTrans) {
                    if ($profileIconField->getId() == $profileFieldTrans->getFieldId()) {
                        $profileFieldName = $profileFieldTrans->getName();
                        break;
                    }
                }

                echo '<a href="'.$profileIconField->getAddition().$profileFieldContent->getValue().'" target="_blank" rel="noopener" class="fa '.$profileIconField->getIcon().'" title="'.$profileFieldName.'"></a>';
                break;
            }
        }
    }
}
                ?>
            </div>
        </div>
    </div>
    <br />
    <div class="profil-content">
        <h1><?=$this->getTrans('profileDetails') ?></h1>
        <div class="row">
            <div class="col-xl-3 detail bold">
                <?=$this->getTrans('profileFirstName') ?>
            </div>
            <div class="col-xl-9 detail">
                <?=$this->escape($profil->getFirstName()) ?>
            </div>
        </div>
         <div class="row">
            <div class="col-xl-3 detail bold">
                <?=$this->getTrans('profileLastName') ?>
            </div>
            <div class="col-xl-9 detail">
                <?=$this->escape($profil->getLastName()) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-3 detail bold">
                <?=$this->getTrans('profileGender') ?>
            </div>
            <div class="col-xl-9 detail">
                <?php if ($profil->getGender() == 1) {
                    echo $this->getTrans('profileGenderMale');
                } elseif ($profil->getGender() == 2) {
                    echo $this->getTrans('profileGenderFemale');
                } elseif ($profil->getGender() == 3) {
                    echo $this->getTrans('profileGenderNonBinary');
                } else {
                    echo $this->getTrans('profileGenderUnknown');
                } ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-3 detail bold">
                <?=$this->getTrans('profileCity') ?>
            </div>
            <div class="col-xl-9 detail">
                <?=$this->escape($profil->getCity()) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-3 detail bold">
                <?=$this->getTrans('profileBirthday') ?>
            </div>
            <div class="col-xl-9 detail">
                <?php if ($profil->getBirthday() != '') {
                    echo $birthday->format('d-m-Y', true) . ' (' . floor(($date->format('Ymd') - str_replace("-", "", $this->escape($profil->getBirthday()))) / 10000) . ')';
                } ?>
            </div>
        </div>

        <?php foreach ($profileFields as $profileField) {
            if (!$profileField->getShow()) {
                continue;
            }
            $profileFieldName = $profileField->getKey();
            $value = '';
            foreach ($profileFieldsContent as $profileFieldContent) {
                if ($profileFieldContent->getValue() && $profileField->getId() == $profileFieldContent->getFieldId()) {
                    if ($profileField->getType() == 4) {
                        $value = implode(', ', json_decode($profileFieldContent->getValue(), true));
                    } else {
                        $value = $profileFieldContent->getValue();
                    }
                }
            }
            if ($profileField->getType() == 1 || ($profileField->getType() != 1 && !empty($value))) {
                foreach ($profileFieldsTranslation as $profileFieldTrans) {
                    if ($profileField->getId() == $profileFieldTrans->getFieldId()) {
                        $profileFieldName = $profileFieldTrans->getName();
                        break;
                    }
                }
            }

            if ($profileField->getType() == 1) : ?>
                <div class="clearfix"></div>
                <br />
                <h1><?=$this->escape($profileFieldName) ?></h1>
            <?php elseif ($profileField->getType() != 1 && !empty($value)) : ?>
                <div class="row">
                    <div class="col-xl-3 detail bold">
                        <?=$this->escape($profileFieldName) ?>
                    </div>
                    <div class="col-xl-9 detail">
                        <?=$this->escape($value) ?>
                    </div>
                </div>
            <?php endif;
        } ?>

        <?php if ($profil->getSignature() != '') : ?>
            <div class="clearfix"></div>
            <br />
            <h1><?=$this->getTrans('profileSignature') ?></h1>
            <div class="row">
                <div class="col-xl-9 detail">
                    <?=$this->alwaysPurify($profil->getSignature()) ?>
                </div>
            </div>
        <?php endif; ?>
        <div class="clearfix"></div>
        <br />
        <h1><?=$this->getTrans('others') ?></h1>
        <div class="row">
            <div class="col-xl-3 detail bold">
                <?=$this->getTrans('groups') ?>
            </div>
            <div class="col-xl-9 detail">
                <?=$this->escape($groups) ?>
            </div>
        </div>
    </div>
</div>

<?php if ($profil->getOptComments() && $profil->getAdminComments() && $this->get('commentsOnProfiles')) : ?>
    <?php
    // Add comments if not disabled by the user or the admin either for this user or globally.
    $commentsClass = new Ilch\Comments();
    echo $commentsClass->getComments(sprintf(Modules\User\Config\Config::COMMENT_KEY_TPL, $profil->getId()), $profil, $this);
    ?>
<?php endif; ?>
