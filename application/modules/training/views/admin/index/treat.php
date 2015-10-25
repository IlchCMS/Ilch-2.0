<link href="<?=$this->getModuleUrl('static/css/training.css') ?>" rel="stylesheet">
<link href="<?=$this->getStaticUrl('js/datetimepicker/css/bootstrap-datetimepicker.min.css') ?>" rel="stylesheet">

<legend>
    <?php if ($this->get('training') != ''): ?>
        <?=$this->getTrans('edit') ?>
    <?php else: ?>
        <?=$this->getTrans('add') ?>
    <?php endif; ?>
</legend>
<form class="form-horizontal" method="POST" action="">
    <div class="form-group">
        <label for="title" class="col-lg-2 control-label">
            <?=$this->getTrans('title') ?>:
        </label>
        <div class="col-lg-4">
            <input class="form-control"
                   type="text"
                   name="title"
                   id="title"
                   value="<?php if ($this->get('training') != '') { echo $this->escape($this->get('training')->getTitle()); } ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="date" class="col-md-2 control-label">
            <?=$this->getTrans('dateTime') ?>:
        </label>
        <div class="col-lg-2 input-group date form_datetime">
            <input class="form-control"
                   type="text"
                   name="date"
                   id="date"
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
        <div class="col-lg-2 input-group">
            <div class="container">
                <div class="input-group spinner">
                    <input class="form-control"
                           type="text"
                           id="time"
                           name="time"
                           min="0"
                           value="<?php if ($this->get('training') != '') { echo $this->escape($this->get('training')->getTime()); } else { echo '30'; } ?>">
                    <div class="input-group-btn-vertical">
                        <span class="btn btn-default"><i class="fa fa-caret-up"></i></span>
                        <span class="btn btn-default"><i class="fa fa-caret-down"></i></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="place" class="col-lg-2 control-label">
            <?=$this->getTrans('place') ?>:
        </label>
        <div class="col-lg-4">
            <input class="form-control"
                   type="text"
                   name="place"
                   id="place"
                   value="<?php if ($this->get('training') != '') { echo $this->escape($this->get('training')->getPlace()); } ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="contact" class="col-lg-2 control-label">
            <?=$this->getTrans('contactPerson') ?>:
        </label>
        <div class="col-lg-2">
            <select class="form-control" name="contact">
                <?php foreach ($this->get('users') as $user) {
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
                   name="voiceServer"
                   value="1"
                   onclick="showMe('voiceServerInfo', this)"
                   <?php if ($this->get('training') != '' AND $this->get('training')->getVoiceServer() == 1) { echo 'checked="checked"';} ?>>
        </div>
    </div>
    <?php if ($this->get('training') != ''): ?>
        <?php if ($this->get('training')->getVoiceServer() == 0): ?>
            <?php $voiceDisplay = 'style="display: none;"'; ?>
        <?php else: ?>
            <?php $voiceDisplay = ''; ?>
        <?php endif; ?>
    <?php else: ?>
        <?php $voiceDisplay = 'style="display: none;"'; ?>
    <?php endif; ?>
    <div id="voiceServerInfo" <?=$voiceDisplay ?>>
        <div class="form-group">
            <label for="voiceServerIP" class="col-lg-2 control-label">
                <?=$this->getTrans('voiceServerIP') ?>:
            </label>
            <div class="col-lg-4">
                <input class="form-control"
                       type="text"
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
                <input class="form-control"
                       type="text"
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
                   name="gameServer"
                   value="1"
                   onclick="showMe('gameServerInfo', this)"
                   <?php if ($this->get('training') != '' AND $this->get('training')->getGameServer() == 1) { echo 'checked="checked"';} ?>>
        </div>
    </div>
    <?php if ($this->get('training') != ''): ?>
        <?php if ($this->get('training')->getGameServer() == 0): ?>
            <?php $gameDisplay = 'style="display: none;"'; ?>
        <?php else: ?>
            <?php $gameDisplay = ''; ?>
        <?php endif; ?>
    <?php else: ?>
        <?php $gameDisplay = 'style="display: none;"'; ?>
    <?php endif; ?>
    <div id="gameServerInfo" <?=$gameDisplay ?>>
        <div class="form-group">
            <label for="gameServerIP" class="col-lg-2 control-label">
                <?=$this->getTrans('gameServerIP') ?>:
            </label>
            <div class="col-lg-4">
                <input class="form-control"
                       type="text"
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
                <input class="form-control"
                       type="text"
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
                   name="text"
                   id="ck_1"
                   toolbar="ilch_html"
                   rows="5"><?php if ($this->get('training') != '') { echo $this->escape($this->get('training')->getText()); } ?></textarea>
        </div>
    </div>
    <?php if ($this->get('training') != ''): ?>
        <?=$this->getSaveBar('updateButton') ?>
    <?php else: ?>
        <?=$this->getSaveBar('addButton') ?>
    <?php endif; ?>
</form>

<script type="text/javascript" src="<?=$this->getStaticUrl('js/datetimepicker/js/bootstrap-datetimepicker.js')?>" charset="UTF-8"></script>
<script type="text/javascript" src="<?=$this->getStaticUrl('js/datetimepicker/js/locales/bootstrap-datetimepicker.'.substr($this->getTranslator()->getLocale(), 0, 2).'.js')?>" charset="UTF-8"></script>
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

$(function() {
    $('.spinner .btn:first-of-type').on('click', function() {
        var btn = $(this);
        var input = btn.closest('.spinner').find('input');
        if (input.attr('max') == undefined || parseInt(input.val()) < parseInt(input.attr('max'))) {
            input.val(parseInt(input.val(), 10) + 1);
        } else {
            btn.next("disabled", true);
        }
    });
    $('.spinner .btn:last-of-type').on('click', function() {
        var btn = $(this);
        var input = btn.closest('.spinner').find('input');
        if (input.attr('min') == undefined || parseInt(input.val()) > parseInt(input.attr('min'))) {
            input.val(parseInt(input.val(), 10) - 1);
        } else {
            btn.prev("disabled", true);
        }
    });
})

function showMe (it, box) { 
    var vis = (box.checked) ? "block" : "none"; 
    document.getElementById(it).style.display = vis;
} 
</script>
