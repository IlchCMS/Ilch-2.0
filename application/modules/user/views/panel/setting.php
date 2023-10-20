<link href="<?=$this->getModuleUrl('static/css/user.css') ?>" rel="stylesheet">

<div class="row">
    <div class="col-xl-12 profile">
        <?php include APPLICATION_PATH.'/modules/user/views/panel/navi.php'; ?>

        <div class="profile-content active">
            <h1><?=$this->getTrans('settingsSetting') ?></h1>
            <form class="form-horizontal" method="POST">
                <?=$this->getTokenField() ?>
                <div class="row mb-3 <?=$this->validation()->hasError('locale') ? 'has-error' : '' ?>">
                    <div class="col-xl-3 control-label">
                        <?=$this->getTrans('locale') ?>
                    </div>
                    <div class="col-xl-2">
                        <select class="form-control" id="locale" name="locale">
                            <?php foreach ($this->get('languages') as $key => $value): ?>
                                <?php $selected = ''; ?>
                                <?php if ($this->getUser()->getLocale() == $key): ?>
                                    <?php $selected = 'selected="selected"'; ?>
                                <?php endif; ?>

                                <option <?=$selected ?> value="<?=$key ?>"><?=$this->escape($value) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="row mb-3 <?=$this->validation()->hasError('optMail') ? 'has-error' : '' ?>">
                    <div class="col-xl-3 control-label">
                        <?=$this->getTrans('optMail') ?>
                    </div>
                    <div class="col-xl-4">
                        <div class="flipswitch">
                            <input type="radio" class="flipswitch-input" id="opt_mail_yes" name="optMail" value="1" <?php if ($this->getUser()->getOptMail() == '1') {
    echo 'checked="checked"';
} ?> />
                            <label for="opt_mail_yes" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('yes') ?></label>
                            <input type="radio" class="flipswitch-input" id="opt_mail_no" name="optMail" value="0" <?php if ($this->getUser()->getOptMail() == '0') {
    echo 'checked="checked"';
} ?> />
                            <label for="opt_mail_no" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('no') ?></label>
                            <span class="flipswitch-selection"></span>
                        </div>
                     </div>
                </div>

                <div class="form-group">
                    <div class="offset-xl-3 col-xl-12">
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
