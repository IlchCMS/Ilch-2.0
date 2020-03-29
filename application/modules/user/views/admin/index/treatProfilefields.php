<h1>
    <?=$this->getTrans('editUserProfileFieldsOf', $this->get('username')) ?>
    <a class="badge" data-toggle="modal" data-target="#infoModal">
        <i class="fas fa-info"></i>
    </a>
</h1>

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
            $value = $profileFieldContent->getValue();
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
    <div class="row">
        <div class="col-lg-2">
            <b><?=$this->escape($profileFieldName) ?></b>
        </div>
        <div class="col-lg-10">
            <a href="<?=$this->getUrl(['controller' => 'index', 'action' => 'deleteProfilefield', 'user' => $this->getRequest()->getParam('user'), 'id' => $profileField->getId()], null, true) ?>"><i class="far fa-trash-alt text-danger"></i></a>
            <?=$this->escape($value) ?>
        </div>
    </div>
    <?php endif;
}
?>
<?=$this->getDialog('infoModal', $this->getTrans('info'), $this->getTrans('editUserProfileFieldsInfoText')) ?>

