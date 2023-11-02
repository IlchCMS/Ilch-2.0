<?php
$profil = $this->get('profil');
$profileFields = $this->get('profileFields');
$profileFieldsContent = $this->get('profileFieldsContent');
$profileFieldsTranslation = $this->get('profileFieldsTranslation');
$birthday = '';
if (!empty($profil->getBirthday())) {
    $birthday = new \Ilch\Date($profil->getBirthday());
}
?>

<link href="<?=$this->getModuleUrl('static/css/user.css') ?>" rel="stylesheet">
<style>.input-group-addon.check {border-left: 1px solid #ccc !important; border-top-left-radius: 4px; border-bottom-left-radius: 4px;}</style>
<link href="<?=$this->getStaticUrl('js/tempus-dominus/dist/css/tempus-dominus.min.css') ?>" rel="stylesheet">

<div class="row">
    <div class="col-xl-12 profile">
        <?php include APPLICATION_PATH.'/modules/user/views/panel/navi.php'; ?>

        <div class="profile-content active">
            <h1><?=$this->getTrans('profileSettings') ?></h1>
            <form class="form-horizontal" method="POST">
                <?=$this->getTokenField() ?>
                <div class="row mb-3 <?=$this->validation()->hasError('email') ? 'has-error' : '' ?>">
                    <label class="col-xl-2 control-label">
                        <?=$this->getTrans('profileEmail') ?>*
                    </label>
                    <div class="col-xl-8">
                        <input type="text"
                               class="form-control"
                               name="email"
                               placeholder="<?=$this->escape($profil->getEmail()) ?>"
                               value="<?=($this->originalInput('email') != '') ? $this->escape($this->originalInput('email')) : $this->escape($profil->getEmail()) ?>"
                               required />
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-xl-2 control-label">
                        <?=$this->getTrans('profileFirstName') ?>
                    </label>
                    <div class="col-xl-8">
                        <input type="text"
                               class="form-control"
                               name="first-name"
                               placeholder="<?=$this->escape($profil->getFirstName()) ?>"
                               value="<?=($this->originalInput('firstname') != '') ? $this->escape($this->originalInput('firstname')) : $this->escape($profil->getFirstName()) ?>" />
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-xl-2 control-label">
                        <?=$this->getTrans('profileLastName') ?>
                    </label>
                    <div class="col-xl-8">
                        <input type="text"
                               class="form-control"
                               name="last-name"
                               placeholder="<?=$this->escape($profil->getLastName()) ?>"
                               value="<?=($this->originalInput('lastname') != '') ? $this->escape($this->originalInput('lastname')) : $this->escape($profil->getLastName()) ?>" />
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-xl-2 control-label">
                        <?=$this->getTrans('profileGender') ?>
                    </label>
                    <div class="col-xl-8">
                        <select class="form-control" id="gender" name="gender">
                            <option value="0" <?=(($this->originalInput('gender') != '' && $this->originalInput('gender') == 0) || $profil->getGender() == 0) ? "selected='selected'" : '' ?>><?=$this->getTrans('profileGenderUnknown') ?></option>
                            <option value="1" <?=(($this->originalInput('gender') != '' && $this->originalInput('gender') == 1) || $profil->getGender() == 1) ? "selected='selected'" : '' ?>><?=$this->getTrans('profileGenderMale') ?></option>
                            <option value="2" <?=(($this->originalInput('gender') != '' && $this->originalInput('gender') == 2) || $profil->getGender() == 2) ? "selected='selected'" : '' ?>><?=$this->getTrans('profileGenderFemale') ?></option>
                            <option value="3" <?=(($this->originalInput('gender') != '' && $this->originalInput('gender') == 3) || $profil->getGender() == 3) ? "selected='selected'" : '' ?>><?=$this->getTrans('profileGenderNonBinary') ?></option>
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-xl-2 control-label">
                        <?=$this->getTrans('profileCity') ?>
                    </label>
                    <div class="col-xl-8">
                       <input type="text"
                              class="form-control"
                              name="city"
                              placeholder="<?=$this->escape($profil->getCity()) ?>"
                              value="<?=($this->originalInput('city') != '') ? $this->escape($this->originalInput('city')) : $this->escape($profil->getCity()) ?>" />
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-xl-2 control-label">
                        <?=$this->getTrans('profileBirthday') ?>
                    </label>
                    <div id="birthday" class="col-xl-8 input-group ilch-date date form_datetime">
                        <input type="text"
                               class="form-control"
                               id="birthday"
                               name="birthday"
                               value="<?=($birthday != '') ? $birthday->format('d.m.Y') : '' ?>">
                        <span class="input-group-text">
                            <span class="fa-solid fa-calendar"></span>
                        </span>
                    </div>
                </div>
                <?php foreach ($profileFields as $profileField) :
                    $profileFieldName = $profileField->getKey();
                    foreach ($profileFieldsTranslation as $profileFieldTranslation) {
                        if ($profileField->getId() == $profileFieldTranslation->getFieldId()) {
                            $profileFieldName = $profileFieldTranslation->getName();
                            break;
                        }
                    }
                    if ($profileField->getType() != 1) :
                        $value = ($profileField->getType() == 4) ? [] : '';
                        $index = 'profileField'.$profileField->getId();
                        if ($this->originalInput($index) != '') {
                            $value = $this->escape($this->originalInput($index));
                        } else {
                            foreach ($profileFieldsContent as $profileFieldContent) {
                                if ($profileField->getId() == $profileFieldContent->getFieldId()) {
                                    if ($profileField->getType() == 4) {
                                        $value = json_decode($profileFieldContent->getValue(), true);
                                    } else {
                                        $value = $this->escape($profileFieldContent->getValue());
                                        break;
                                    }
                                }
                            }
                        } ?>
                        <div class="row mb-3">
                            <label class="col-xl-2 control-label">
                                <?=$this->escape($profileFieldName) ?>
                            </label>
                            <div class="col-xl-8">
                            <!-- radio -->
                            <?php if ($profileField->getType() == 3) :
                                $options = json_decode($profileField->getOptions(), true);
                                foreach ($options as $optValue): ?>
                                    <?=($profileField->getShow() == 0) ? '<div class="input-group">' : '<div class="form-check">' ?>
                                        <input type="radio" name="<?=$index ?>" id="<?=$optValue ?>" value="<?=$optValue ?>" class="form-check-input" <?=($optValue == $value) ? 'checked' : '' ?>/>
                                        <label class="form-check-label" for="<?=$optValue ?>"><?=$this->escape($optValue) ?></label>
                                        <?php if ($profileField->getShow() == 0) : ?>
                                            <span class="input-group-text check" rel="tooltip" title="<?=$this->getTrans('profileFieldHidden') ?>">
                                                <span class="fa-solid fa-eye-slash"></span>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            <!-- check -->
                            <?php elseif ($profileField->getType() == 4) :
                                $options = json_decode($profileField->getOptions(), true);
                                foreach ($options as $optKey => $optValue) : ?>
                                    <?=($profileField->getShow() == 0) ? '<div class="input-group">' : '<div class="form-check">' ?>
                                        <input type="checkbox" name="<?=$index ?>[<?=$optKey ?>]" id="<?=$optValue ?>" value="<?=$optValue ?>" class="form-check-input" <?=in_array($optValue, $value) ? 'checked' : '' ?>/>
                                        <label class="form-check-label" for="<?=$optValue ?>"><?=$this->escape($optValue) ?></label>
                                        <?php if ($profileField->getShow() == 0) : ?>
                                            <span class="input-group-text check" rel="tooltip" title="<?=$this->getTrans('profileFieldHidden') ?>">
                                                <span class="fa-solid fa-eye-slash"></span>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            <!-- drop -->
                            <?php elseif ($profileField->getType() == 5) :
                                $options = json_decode($profileField->getOptions(), true);?>
                                <?=($profileField->getShow() == 0) ? '<div class="input-group">' : '<div class="form-check">' ?>
                                    <select class="form-control" id="<?=$index ?>" name="<?=$index ?>">
                                        <?php foreach ($options as $optValue) : ?>
                                            <option value="<?=$optValue ?>" <?=($optValue == $value) ? 'selected' : '' ?>><?=$this->escape($optValue) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                <?php if ($profileField->getShow() == 0) : ?>
                                    <span class="input-group-text" rel="tooltip" title="<?=$this->getTrans('profileFieldHidden') ?>">
                                        <span class="fa-solid fa-eye-slash"></span>
                                    </span>
                                <?php endif; ?>
                                </div>
                            <!-- date -->
                            <?php elseif ($profileField->getType() == 6) : ?>
                                <?=($profileField->getShow() == 0) ? '<div class="input-group">' : '' ?>
                                    <input type="text"
                                           class="form-control ilch-date date form_datetime"
                                           name="<?=$index ?>"
                                           placeholder="<?=$value ?>"
                                           value="<?=$value ?>">
                                <?php if ($profileField->getShow() == 0) : ?>
                                    <span class="input-group-text" rel="tooltip" title="<?=$this->getTrans('profileFieldHidden') ?>">
                                        <span class="fa-solid fa-eye-slash"></span>
                                    </span>
                                </div>
                                <?php endif; ?>
                            <!-- field -->
                            <?php else : ?>
                                <?=($profileField->getShow() == 0) ? '<div class="input-group">' : '' ?>
                                <input type="text"
                                       class="form-control"
                                       name="<?=$index ?>"
                                       placeholder="<?=$value ?>"
                                       value="<?=$value ?>" />
                                <?php if ($profileField->getShow() == 0) : ?>
                                    <span class="input-group-text" rel="tooltip" title="<?=$this->getTrans('profileFieldHidden') ?>">
                                        <span class="fa-solid fa-eye-slash"></span>
                                    </span>
                                </div>
                                <?php endif; ?>
                            <?php endif; ?>
                            </div>
                        </div>
                    <?php else : ?>
                        <h1><?=$this->escape($profileFieldName) ?></h1>
                    <?php endif;
                endforeach; ?>
                <div class="row mb-3">
                    <div class="offset-xl-2 col-xl-8">
                        <input type="submit"
                               class="btn btn-outline-secondary"
                               name="saveEntry"
                               value="<?=$this->getTrans('profileSubmit') ?>" />
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="<?=$this->getStaticUrl('js/popper/dist/umd/popper.min.js') ?>" charset="UTF-8"></script>
<script src="<?=$this->getStaticUrl('js/tempus-dominus/dist/js/tempus-dominus.min.js') ?>" charset="UTF-8"></script>
<?php if (strncmp($this->getTranslator()->getLocale(), 'en', 2) !== 0) : ?>
    <script src="<?=$this->getStaticUrl('js/tempus-dominus/dist/locales/' . substr($this->getTranslator()->getLocale(), 0, 2) . '.js') ?>" charset="UTF-8"></script>
<?php endif; ?>
<script>
$(document).ready(function() {
    new tempusDominus.TempusDominus(document.getElementById('startDate'), {
        restrictions: {
          maxDate: new Date()
        },
        display: {
            calendarWeeks: true,
            buttons: {
                today: true,
                close: true
            },
            components: {
                clock: false
            }
        },
        localization: {
            locale: "<?=substr($this->getTranslator()->getLocale(), 0, 2) ?>",
            startOfTheWeek: 1,
            format: "dd.MM.yyyy"
        }
    });

    $("[rel='tooltip']").tooltip();
});
</script>
