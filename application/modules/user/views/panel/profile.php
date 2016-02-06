<?php
$profil = $this->get('profil');
$birthday = new \Ilch\Date($profil->getBirthday());
?>

<link href="<?=$this->getStaticUrl('js/datetimepicker/css/bootstrap-datetimepicker.min.css') ?>" rel="stylesheet">

<div id="panel">
    <div class="row">
        <div class="col-lg-2">
            <?php include APPLICATION_PATH.'/modules/user/views/panel/navi.php'; ?>
        </div>
        <div class="col-lg-10">
            <legend><?=$this->getTrans('profileSettings') ?></legend>
            <form action="" class="form-horizontal" method="POST">
                <?=$this->getTokenField() ?>
                <div class="form-group">
                    <label class="col-lg-2 control-label">
                        <?=$this->getTrans('profileEmail'); ?>*
                    </label>
                    <div class="col-lg-8">
                        <input type="text"
                               class="form-control"
                               name="email"
                               placeholder="<?=$this->escape($profil->getEmail()) ?>"
                               value="<?=$this->escape($profil->getEmail()) ?>"
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
                               value="<?=$this->escape($profil->getFirstName()) ?>"/>
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
                               value="<?=$this->escape($profil->getLastName()) ?>"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-2 control-label">
                        <?=$this->getTrans('profileHomepage'); ?>
                    </label>
                    <div class="col-lg-8">
                       <input type="text"
                              class="form-control"
                              name="homepage"
                              placeholder="<?=$this->escape($profil->getHomepage()) ?>"
                              value="<?=$this->escape($profil->getHomepage()) ?>" />
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
                              value="<?=$this->escape($profil->getFacebook()) ?>" />
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
                              value="<?=$this->escape($profil->getTwitter()) ?>" />
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
                              value="<?=$this->escape($profil->getGoogle()) ?>" />
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
                              value="<?=$this->escape($profil->getCity()) ?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-2 control-label">
                        <?=$this->getTrans('profileBirthday'); ?>
                    </label>
                    <div class="col-lg-2 input-group date form_datetime">
                        <input class="form-control"
                               type="text"
                               name="birthday"
                               value="<?php if ($profil->getBirthday() == '0000-00-00') { echo date('d.m.Y'); } else { echo $birthday->format('d.m.Y', true); } ?>">
                        <span class="input-group-addon">
                            <span class="fa fa-calendar"></span>
                        </span>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-lg-offset-2 col-lg-8">
                        <input type="submit"
                               name="saveEntry"
                               class="btn"
                               value="<?=$this->getTrans('profileSubmit') ?>" />
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?=$this->getStaticUrl('js/datetimepicker/js/bootstrap-datetimepicker.js') ?>" charset="UTF-8"></script>
<script type="text/javascript" src="<?=$this->getStaticUrl('js/datetimepicker/js/locales/bootstrap-datetimepicker.'.substr($this->getTranslator()->getLocale(), 0, 2).'.js') ?>" charset="UTF-8"></script>
<script type="text/javascript">
$(document).ready(function() {
    $(".form_datetime").datetimepicker({
        format: "dd.mm.yyyy",
        autoclose: true,
        language: '<?=substr($this->getTranslator()->getLocale(), 0, 2) ?>',
        minView: 2,
        todayHighlight: true
    });
});
</script>
