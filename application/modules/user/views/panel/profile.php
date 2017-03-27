<?php
$profil = $this->get('profil');
$profileFields = $this->get('profileFields');
$profileFieldsContent = $this->get('profileFieldsContent');
$profileFieldsTranslation = $this->get('profileFieldsTranslation');
$birthday = new \Ilch\Date($profil->getBirthday());
?>

<link href="<?=$this->getModuleUrl('static/css/user.css') ?>" rel="stylesheet">
<link href="<?=$this->getStaticUrl('js/datetimepicker/css/bootstrap-datetimepicker.min.css') ?>" rel="stylesheet">

<div class="row">
    <div class="col-lg-12 profile">
        <?php include APPLICATION_PATH.'/modules/user/views/panel/navi.php'; ?>

        <div class="profile-content active">
            <h1><?=$this->getTrans('profileSettings') ?></h1>
            <?php if ($this->validation()->hasErrors()): ?>
                <div class="alert alert-danger" role="alert">
                    <strong> <?=$this->getTrans('errorsOccured') ?>:</strong>
                    <ul>
                        <?php foreach ($this->validation()->getErrorMessages() as $error): ?>
                            <li><?=$error ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            <form action="" class="form-horizontal" method="POST">
                <?=$this->getTokenField() ?>
                <div class="form-group <?=$this->validation()->hasError('email') ? 'has-error' : '' ?>">
                    <label class="col-lg-2 control-label">
                        <?=$this->getTrans('profileEmail'); ?>*
                    </label>
                    <div class="col-lg-8">
                        <input type="text"
                               class="form-control"
                               name="email"
                               placeholder="<?=$this->escape($profil->getEmail()) ?>"
                               value="<?=($this->originalInput('email') != '') ? $this->escape($this->originalInput('email')) : $this->escape($profil->getEmail()) ?>"
                               required />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-2 control-label">
                        <?=$this->getTrans('profileFirstName'); ?>
                    </label>
                    <div class="col-lg-8">
                        <input type="text"
                               class="form-control"
                               name="first-name"
                               placeholder="<?=$this->escape($profil->getFirstName()) ?>"
                               value="<?=($this->originalInput('firstname') != '') ? $this->escape($this->originalInput('firstname')) : $this->escape($profil->getFirstName()) ?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-2 control-label">
                        <?=$this->getTrans('profileLastName'); ?>
                    </label>
                    <div class="col-lg-8">
                        <input type="text"
                               class="form-control"
                               name="last-name"
                               placeholder="<?=$this->escape($profil->getLastName()) ?>"
                               value="<?=($this->originalInput('lastname') != '') ? $this->escape($this->originalInput('lastname')) : $this->escape($profil->getLastName()) ?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-2 control-label">
                        <?=$this->getTrans('profileGender'); ?>
                    </label>
                    <div class="col-lg-2">
                        <select class="form-control" id="gender" name="gender">
                            <option value="0" <?=($this->originalInput('gender') != '' AND $this->originalInput('gender') == 0) ? "selected='selected'" : ($profil->getGender() == 0) ? "selected='selected'" : '' ?>><?=$this->getTrans('profileGenderUnknow') ?></option>
                            <option value="1" <?=($this->originalInput('gender') != '' AND $this->originalInput('gender') == 1) ? "selected='selected'" : ($profil->getGender() == 1) ? "selected='selected'" : '' ?>><?=$this->getTrans('profileGenderMale') ?></option>
                            <option value="2"><?=$this->getTrans('profileGenderFemale') ?></option>
                        </select>
                    </div>
                </div>
                <div class="form-group <?=$this->validation()->hasError('homepage') ? 'has-error' : '' ?>">
                    <label class="col-lg-2 control-label">
                        <?=$this->getTrans('profileHomepage'); ?>
                    </label>
                    <div class="col-lg-8">
                       <input type="text"
                              class="form-control"
                              name="homepage"
                              placeholder="<?=$this->escape($profil->getHomepage()) ?>"
                              value="<?=($this->originalInput('homepage') != '') ? $this->escape($this->originalInput('homepage')) : $this->escape($profil->getHomepage()) ?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-2 control-label">
                        <?=$this->getTrans('profileFacebook'); ?>
                    </label>
                    <div class="col-lg-8">
                       <input type="text"
                              class="form-control"
                              name="facebook"
                              placeholder="<?=$this->escape($profil->getFacebook()) ?>"
                              value="<?=($this->originalInput('facebook') != '') ? $this->escape($this->originalInput('facebook')) : $this->escape($profil->getFacebook()) ?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-2 control-label">
                        <?=$this->getTrans('profileTwitter'); ?>
                    </label>
                    <div class="col-lg-8">
                       <input type="text"
                              class="form-control"
                              name="twitter"
                              placeholder="<?=$this->escape($profil->getTwitter()) ?>"
                              value="<?=($this->originalInput('twitter') != '') ? $this->escape($this->originalInput('twitter')) : $this->escape($profil->getTwitter()) ?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-2 control-label">
                        <?=$this->getTrans('profileGoogle'); ?>
                    </label>
                    <div class="col-lg-8">
                       <input type="text"
                              class="form-control"
                              name="google"
                              placeholder="<?=$this->escape($profil->getGoogle()) ?>"
                              value="<?=($this->originalInput('google') != '') ? $this->escape($this->originalInput('google')) : $this->escape($profil->getGoogle()) ?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-2 control-label">
                        <?=$this->getTrans('profileCity'); ?>
                    </label>
                    <div class="col-lg-8">
                       <input type="text"
                              class="form-control"
                              name="city"
                              placeholder="<?=$this->escape($profil->getCity()) ?>"
                              value="<?=($this->originalInput('city') != '') ? $this->escape($this->originalInput('city')) : $this->escape($profil->getCity()) ?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-2 control-label">
                        <?=$this->getTrans('profileBirthday'); ?>
                    </label>
                    <div class="col-lg-2 input-group ilch-date date form_datetime">
                        <input type="text"
                               class="form-control"
                               name="birthday"
                               value="<?php if ($profil->getBirthday() != '0000-00-00') { echo $birthday->format('d.m.Y'); } else { echo ''; } ?>">
                        <span class="input-group-addon">
                            <span class="fa fa-calendar"></span>
                        </span>
                    </div>
                </div>
                <?php
                foreach ($profileFields as $profileField) :
                    $profileFieldName = $profileField->getName();
                    foreach ($profileFieldsTranslation as $profileFieldTranslation) {
                        if($profileField->getId() == $profileFieldTranslation->getFieldId()) {
                            $profileFieldName = $profileFieldTranslation->getName();
                            break;
                        }
                    }
                    
                    if(!$profileField->getType()) :
                        $value = '';
                        if ($this->originalInput($profileField->getName()) != '') {
                            $value = $this->escape($this->originalInput($profileField->getName()));
                        } else {
                            foreach($profileFieldsContent as $profileFieldContent) {
                                if($profileField->getId() == $profileFieldContent->getFieldId()) {
                                    $value = $this->escape($profileFieldContent->getValue());
                                    break;
                                }
                            }
                        } ?>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">
                                <?=$this->escape($profileFieldName) ?>
                            </label>
                            <div class="col-lg-8">
                               <input type="text"
                                      class="form-control"
                                      name="<?=$this->escape($profileField->getName()) ?>"
                                      placeholder="<?=$value ?>"
                                      value="<?=$value ?>" />
                            </div>
                        </div>
                    <?php else : ?>
                        <h1><?=$this->escape($profileFieldName) ?></h1>
                    <?php endif;
                endforeach; ?>
                <div class="form-group">
                    <div class="col-lg-offset-2 col-lg-8">
                        <input type="submit"
                               class="btn"
                               name="saveEntry"
                               value="<?=$this->getTrans('profileSubmit') ?>" />
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?=$this->getStaticUrl('js/datetimepicker/js/bootstrap-datetimepicker.min.js') ?>" charset="UTF-8"></script>
<?php if (substr($this->getTranslator()->getLocale(), 0, 2) != 'en'): ?>
    <script type="text/javascript" src="<?=$this->getStaticUrl('js/datetimepicker/js/locales/bootstrap-datetimepicker.'.substr($this->getTranslator()->getLocale(), 0, 2).'.js') ?>" charset="UTF-8"></script>
<?php endif; ?>
<script type="text/javascript">
$(document).ready(function() {
    $(".form_datetime").datetimepicker({
        defaultDate: new Date(),
        endDate: new Date(),
        format: "dd.mm.yyyy",
        autoclose: true,
        language: '<?=substr($this->getTranslator()->getLocale(), 0, 2) ?>',
        minView: 2,
        todayHighlight: true
    });
});
</script>
