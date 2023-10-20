<form class="form-horizontal" method="POST">
    <?=$this->getTokenField() ?>
    <legend><?=$this->getTrans('menuMail') ?></legend>
    <div id="infobox" class="mailSettingsInfobox alert alert-info">
        <p><?=$this->getTrans('standardMail') . ': ' . $this->get('standardMail') ?></p>
        <p id="smtpModeDescription"><?=($this->get('smtp_mode') === '1') ? $this->getTrans('smtpModeEnabledDescription') : $this->getTrans('smtpModeDisabledDescription') ?></p>
    </div>
    <div class="row mb-3">
        <label for="navbarFixed" class="col-xl-2 control-label">
            <?=$this->getTrans('smtpMode') ?>:
        </label>
        <div class="col-xl-8">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" name="smtp_mode" value="1" id="navbarFixed-on" <?php if ($this->get('smtp_mode') == '1') { echo 'checked="checked"'; } ?> />
                <label for="navbarFixed-on" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('on') ?></label>
                <input type="radio" class="flipswitch-input" name="smtp_mode" value="0" id="navbarFixed-off" <?php if ($this->get('smtp_mode') == '0' || empty($this->get('smtp_mode'))) { echo 'checked="checked"'; } ?> />
                <label for="navbarFixed-off" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('off') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
        </div>
    </div>
    <div id="smtpSettings" class="smtpSettings <?=($this->get('smtp_mode') !== '1') ? 'hidden' : '' ?>">
        <div class="row mb-3">
            <label for="smtp_server" class="col-xl-2 control-label">
                <?=$this->getTrans('smtp_server') ?>:
            </label>
            <div class="col-xl-6">
                <input class="form-control"
                       id="smtp_server"
                       name="smtp_server"
                       type="text"
                       value="<?=$this->escape($this->get('smtp_server')) ?>" />
            </div>
        </div>
        <div class="row mb-3">
            <label for="smtp_port" class="col-xl-2 control-label">
                <?=$this->getTrans('smtp_port') ?>:
            </label>
            <div class="col-xl-2">
                <input class="form-control"
                       id="smtp_port"
                       name="smtp_port"
                       type="text"
                       value="<?=$this->escape($this->get('smtp_port')) ?>" />
            </div>
        </div>
        <div class="row mb-3">
            <label for="smtp_secure" class="col-xl-2 control-label">
                <?=$this->getTrans('smtp_secure') ?>:
            </label>
            <div class="col-xl-2">
                <select class="form-control" id="smtp_secure" name="smtp_secure">
                    <optgroup label="<?=$this->getTrans('smtp_secure') ?>">
                        <?php
                        $values = ['', 'ssl', 'tsl'];

                        foreach ($values as $value) :
                            $selected = '';
                            if ($this->get('smtp_secure') == $value) {
                                $selected = 'selected="selected"';
                            } ?>
                            <option <?=$selected ?> value="<?=$this->escape($value) ?>"><?=$this->escape($value) ?></option>
                        <?php
                        endforeach; ?>
                    </optgroup>
                </select>
            </div>
        </div>
        <div class="row mb-3">
            <label for="smtp_user" class="col-xl-2 control-label">
                <?=$this->getTrans('smtp_user') ?>:
            </label>
            <div class="col-xl-6">
                <input class="form-control"
                       id="smtp_user"
                       name="smtp_user"
                       type="text"
                       value="<?=$this->escape($this->get('smtp_user')) ?>" />
            </div>
        </div>
        <div class="row mb-3">
            <label for="smtp_pass" class="col-xl-2 control-label">
                <?=$this->getTrans('smtp_pass') ?>:
            </label>
            <div class="col-xl-6">
                <input class="form-control"
                       id="smtp_pass"
                       name="smtp_pass"
                       type="password"
                       value="<?=$this->escape($this->get('smtp_pass')) ?>" />
            </div>
        </div>
    </div>
    <legend><?=$this->getTrans('emailBlacklist') ?></legend>
    <div class="row mb-3">
        <label for="emailBlacklist" class="col-xl-2 control-label">
            <?=$this->getTrans('emailBlacklist') ?>:
        </label>
        <div class="col-xl-6">
            <textarea class="form-control"
                      id="emailBlacklist"
                      name="emailBlacklist"
                      rows="20"
                      title="<?=$this->getTrans('editEmailBlacklist') ?>"><?=$this->escape($this->get('emailBlacklist')) ?></textarea>
        </div>
    </div>
    <?=$this->getSaveBar() ?>
</form>

<script>
    $('[name="smtp_mode"]').click(function () {
        if ($(this).val() == "1") {
            $('#smtpSettings').removeClass('hidden');
            $('#smtpModeDescription').html("<?=$this->getTrans('smtpModeEnabledDescription') ?>");
        } else {
            $('#smtpSettings').addClass('hidden');
            $('#smtpModeDescription').html("<?=$this->getTrans('smtpModeDisabledDescription') ?>");
        }
    });
</script>
