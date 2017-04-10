<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <legend><?=$this->getTrans('menuMail') ?></legend>
    <div class="form-group">
        <label for="navbarFixed" class="col-lg-2 control-label">
            <?=$this->getTrans('smtpMode') ?>:
        </label>
        <div class="col-lg-8">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" name="smtp_mode" value="1" id="navbarFixed-on" <?php if ($this->get('smtp_mode') == '1') { echo 'checked="checked"'; } ?> />
                <label for="navbarFixed-on" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('on') ?></label>
                <input type="radio" class="flipswitch-input" name="smtp_mode" value="0" id="navbarFixed-off" <?php if ($this->get('smtp_mode') == '0') { echo 'checked="checked"'; } ?> />
                <label for="navbarFixed-off" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('off') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="smtp_server" class="col-lg-2 control-label">
            <?=$this->getTrans('SMTP-Server-Adresse') ?>:
        </label>
        <div class="col-lg-6">
            <input class="form-control"
                   id="smtp_server"
                   name="smtp_server"
                   type="text"
                   value="<?=$this->escape($this->get('smtp_server')) ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="smtp_port" class="col-lg-2 control-label">
            <?=$this->getTrans('smtp_port') ?>:
        </label>
        <div class="col-lg-2">
            <input class="form-control"
                   id="smtp_port"
                   name="smtp_port"
                   type="text"
                   value="<?=$this->escape($this->get('smtp_port')) ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="smtp_secure" class="col-lg-2 control-label">
            <?=$this->getTrans('smtp_secure') ?>:
        </label>
        <div class="col-lg-2">
            <select class="form-control" id="smtp_secure" name="smtp_secure">
                <optgroup>
                    <?php $selected = '';
                    if ($this->escape($this->get('smtp_secure') == 'TLS')) {
                        $selected = 'selected="selected"';
                    } elseif ($this->escape($this->get('smtp_secure') == 'SSL')) {
                        $selected = 'selected="selected"';
                    } elseif ($this->escape($this->get('smtp_secure') == 'STARTTLS')) {
                        $selected = 'selected="selected"';
                    }
                    ?>
                    <option <?=$selected ?> value="TLS">TLS</option>
                    <option <?=$selected ?> value="SSL">SSL</option>
                    <option <?=$selected ?> value="STARTTLS">STARTTLS</option>
                </optgroup>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label for="smtp_user" class="col-lg-2 control-label">
            <?=$this->getTrans('smtp_user') ?>:
        </label>
        <div class="col-lg-6">
            <input class="form-control"
                   id="smtp_user"
                   name="smtp_user"
                   type="text"
                   value="<?=$this->escape($this->get('smtp_user')) ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="smtp_pass" class="col-lg-2 control-label">
            <?=$this->getTrans('smtp_pass') ?>:
        </label>
        <div class="col-lg-6">
            <input class="form-control"
                   id="smtp_pass"
                   name="smtp_pass"
                   type="password"
                   value="<?=$this->escape($this->get('smtp_pass')) ?>" />
        </div>
    </div>
    <?=$this->getSaveBar() ?>
</form>
