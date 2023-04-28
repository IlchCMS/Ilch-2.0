<h1>
    <?php $user = $this->get('user') ?>
    <?=$this->getTrans('editUserProfileOf', $user->getName()) ?>
    <a class="badge" data-toggle="modal" data-target="#infoModal">
        <i class="fa-solid fa-info"></i>
    </a>
</h1>

<style>
    .row { padding: 5px 0; }
    .row:nth-of-type(odd) { background-color: rgba(0,0,0,.05); }
</style>

<div class="profil-content">
    <?php if (!empty($user->getFirstName())) : ?>
        <div class="row">
            <div class="col-lg-2 detail bold">
                <b><?=$this->getTrans('profileFirstName') ?></b>
            </div>
            <div class="col-lg-10 detail">
                <a href="<?=$this->getUrl(['controller' => 'index', 'action' => 'deleteProfilefield', 'user' => $this->getRequest()->getParam('user'), 'default' => 'firstname'], null, true) ?>"><i class="fa-regular fa-trash-can text-danger"></i></a>
                <?=$this->escape($user->getFirstName()) ?>
            </div>
        </div>
    <?php endif; ?>
    <?php if (!empty($user->getLastName())) : ?>
        <div class="row">
            <div class="col-lg-2 detail bold">
                <b><?=$this->getTrans('profileLastName') ?></b>
            </div>
            <div class="col-lg-10 detail">
                <a href="<?=$this->getUrl(['controller' => 'index', 'action' => 'deleteProfilefield', 'user' => $this->getRequest()->getParam('user'), 'default' => 'lastname'], null, true) ?>"><i class="fa-regular fa-trash-can text-danger"></i></a>
                <?=$this->escape($user->getLastName()) ?>
            </div>
        </div>
    <?php endif; ?>
    <?php if (!empty($user->getCity())) : ?>
        <div class="row">
            <div class="col-lg-2 detail bold">
                <b><?=$this->getTrans('profileCity') ?></b>
            </div>
            <div class="col-lg-10 detail">
                <a href="<?=$this->getUrl(['controller' => 'index', 'action' => 'deleteProfilefield', 'user' => $this->getRequest()->getParam('user'), 'default' => 'city'], null, true) ?>"><i class="fa-regular fa-trash-can text-danger"></i></a>
                <?=$this->escape($user->getCity()) ?>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php
$profileFields = $this->get('profileFields');
$profileFieldsContent = $this->get('profileFieldsContent');
$profileFieldsTranslation = $this->get('profileFieldsTranslation');

foreach ($profileFields as $profileField) {
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
    if (!empty($value)) {
        foreach ($profileFieldsTranslation as $profileFieldTrans) {
            if ($profileField->getId() == $profileFieldTrans->getFieldId()) {
                $profileFieldName = $profileFieldTrans->getName();
                break;
            }
        }
    }
    if (!empty($value)): ?>
    <div class="row grid-striped">
        <div class="col-lg-2">
            <b><?=$this->escape($profileFieldName) ?></b>
        </div>
        <div class="col-lg-10">
            <a href="<?=$this->getUrl(['controller' => 'index', 'action' => 'deleteProfilefield', 'user' => $this->getRequest()->getParam('user'), 'id' => $profileField->getId()], null, true) ?>"><i class="fa-regular fa-trash-can text-danger"></i></a>
            <?=$this->escape($value) ?>
        </div>
    </div>
    <?php endif;
}
?>
<?=$this->getDialog('infoModal', $this->getTrans('info'), $this->getTrans('editUserProfileInfoText')) ?>

