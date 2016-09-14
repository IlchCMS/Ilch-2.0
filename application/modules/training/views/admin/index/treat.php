<link href="<?=$this->getModuleUrl('static/css/training.css') ?>" rel="stylesheet">
<link href="<?=$this->getStaticUrl('js/datetimepicker/css/bootstrap-datetimepicker.min.css') ?>" rel="stylesheet">

<legend>
    <?php
    if ($this->get('training') != '') {
        echo $this->getTrans('edit');
    } else {
        echo $this->getTrans('add');
    }
    ?>
</legend>
<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField(); ?>
    <div class="form-group">
        <label for="title" class="col-lg-2 control-label">
            <?=$this->getTrans('title') ?>:
        </label>
        <div class="col-lg-4">
            <input type="text"
                   class="form-control"
                   id="title"
                   name="title"
                   value="<?php if ($this->get('training') != '') { echo $this->escape($this->get('training')->getTitle()); } ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="date" class="col-md-2 control-label">
            <?=$this->getTrans('dateTime') ?>:
        </label>
        <div class="col-lg-2 input-group date form_datetime">
            <input type="text"
                   class="form-control"
                   id="date"
                   name="date"
                   value="<?php if ($this->get('training') != '') { echo date('d.m.Y H:i', strtotime($this->get('training')->getDate())); } ?>"
                   readonly>
            <span class="input-group-addon">
                <span class="fa fa-calendar"></span>
            </span>
        </div>
    </div>
    <div class="form-group">
        <label for="time" class="col-lg-2 control-label">
            <?=$this->getTrans('time') ?>:
        </label>
        <div class="col-lg-1">
            <input type="number"
                   class="form-control"
                   id="time"
                   name="time"
                   min="0"
                   value="<?php if ($this->get('training') != '') { echo $this->escape($this->get('training')->getTime()); } else { echo '30'; } ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="place" class="col-lg-2 control-label">
            <?=$this->getTrans('place') ?>:
        </label>
        <div class="col-lg-4">
            <input type="text"
                   class="form-control"
                   id="place"
                   name="place"
                   value="<?php if ($this->get('training') != '') { echo $this->escape($this->get('training')->getPlace()); } ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="contact" class="col-lg-2 control-label">
            <?=$this->getTrans('contactPerson') ?>:
        </label>
        <div class="col-lg-2">
            <select class="form-control" id="contact" name="contact">
                <?php
                foreach ($this->get('users') as $user) {
                    $selected = '';
                    if ($this->get('training') != '' AND $this->get('training')->getContact() == $user->getId()) {
                        $selected = 'selected="selected"';
                    }
                    echo '<option '.$selected.' value="'.$user->getId().'">'.$this->escape($user->getName()).'</option>';
                }
                ?>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label for="voiceServer" class="col-lg-2 control-label">
            <?=$this->getTrans('voiceServer') ?>:
        </label>
        <div class="col-lg-2">
            <input type="checkbox"
                   id="voiceServer"
                   name="voiceServer"
                   value="1"
                   onclick="showMe('voiceServerInfo', this)"
                   <?php if ($this->get('training') != '' AND $this->get('training')->getVoiceServer() == 1) { echo 'checked="checked"';} ?>>
        </div>
    </div>
    <?php
    if ($this->get('training') != '') {
        if ($this->get('training')->getVoiceServer() == 0) {
            $voiceDisplay = 'style="display: none;"';
        } else {
            $voiceDisplay = '';
        }
    } else {
        $voiceDisplay = 'style="display: none;"';
    }
    ?>
    <div id="voiceServerInfo" <?=$voiceDisplay ?>>
        <div class="form-group">
            <label for="voiceServerIP" class="col-lg-2 control-label">
                <?=$this->getTrans('voiceServerIP') ?>:
            </label>
            <div class="col-lg-4">
                <input type="text"
                       class="form-control"
                       id="voiceServerIP"
                       name="voiceServerIP"
                       placeholder="IP:Port"
                       value="<?php if ($this->get('training') != '') { echo $this->escape($this->get('training')->getVoiceServerIP()); } ?>" />
            </div>
        </div>
        <div class="form-group">
            <label for="voiceServerPW" class="col-lg-2 control-label">
                <?=$this->getTrans('voiceServerPW') ?>:
            </label>
            <div class="col-lg-4">
                <input type="text"
                       class="form-control"
                       id="voiceServerPW"
                       name="voiceServerPW"
                       placeholder="********"
                       value="<?php if ($this->get('training') != '') { echo $this->escape($this->get('training')->getVoiceServerPW()); } ?>" />
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="gameServer" class="col-lg-2 control-label">
            <?=$this->getTrans('gameServer') ?>:
        </label>
        <div class="col-lg-2">
            <input type="checkbox"
                   id="gameServer"
                   name="gameServer"
                   value="1"
                   onclick="showMe('gameServerInfo', this)"
                   <?php if ($this->get('training') != '' AND $this->get('training')->getGameServer() == 1) { echo 'checked="checked"';} ?>>
        </div>
    </div>
    <?php
    if ($this->get('training') != '') {
        if ($this->get('training')->getGameServer() == 0) {
            $gameDisplay = 'style="display: none;"';
        } else {
            $gameDisplay = '';
        }
    } else {
        $gameDisplay = 'style="display: none;"';
    }
    ?>
    <div id="gameServerInfo" <?=$gameDisplay ?>>
        <div class="form-group">
            <label for="gameServerIP" class="col-lg-2 control-label">
                <?=$this->getTrans('gameServerIP') ?>:
            </label>
            <div class="col-lg-4">
                <input type="text"
                       class="form-control"
                       id="gameServerIP"
                       name="gameServerIP"
                       placeholder="IP:Port"
                       value="<?php if ($this->get('training') != '') { echo $this->escape($this->get('training')->getGameServerIP()); } ?>" />
            </div>
        </div>
        <div class="form-group">
            <label for="gameServerPW" class="col-lg-2 control-label">
                <?=$this->getTrans('gameServerPW') ?>:
            </label>
            <div class="col-lg-4">
                <input type="text"
                       class="form-control"
                       id="gameServerPW"
                       name="gameServerPW"
                       placeholder="********"
                       value="<?php if ($this->get('training') != '') { echo $this->escape($this->get('training')->getGameServerPW()); } ?>" />
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="ck_1" class="col-lg-2 control-label">
            <?=$this->getTrans('otherInfo') ?>:
        </label>
        <div class="col-lg-10">
            <textarea class="form-control ckeditor"
                      id="ck_1"
                      name="text"
                      toolbar="ilch_html"
                      rows="5"><?php if ($this->get('training') != '') { echo $this->escape($this->get('training')->getText()); } ?></textarea>
        </div>
    </div>
    <?php
    if ($this->get('training') != '') {
        echo $this->getSaveBar('updateButton');
    } else {
        echo $this->getSaveBar('addButton');
    }
    ?>
</form>

<?=$this->getDialog('mediaModal', $this->getTrans('media'), '<iframe frameborder="0"></iframe>'); ?>
<script type="text/javascript" src="<?=$this->getStaticUrl('js/datetimepicker/js/bootstrap-datetimepicker.min.js') ?>" charset="UTF-8"></script>
<?php if (substr($this->getTranslator()->getLocale(), 0, 2) != 'en'): ?>
    <script type="text/javascript" src="<?=$this->getStaticUrl('js/datetimepicker/js/locales/bootstrap-datetimepicker.'.substr($this->getTranslator()->getLocale(), 0, 2).'.js') ?>" charset="UTF-8"></script>
<?php endif; ?>
<script type="text/javascript">
$(document).ready(function() {
    $(".form_datetime").datetimepicker({
        format: "dd.mm.yyyy hh:ii",
        startDate: new Date(),
        autoclose: true,
        language: '<?=substr($this->getTranslator()->getLocale(), 0, 2) ?>',
        minuteStep: 15,
        todayHighlight: true
    });
});

function showMe (it, box) { 
    var vis = (box.checked) ? "block" : "none"; 
    document.getElementById(it).style.display = vis;
} 
</script>
