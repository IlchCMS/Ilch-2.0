<link href="<?=$this->getModuleUrl('static/css/training.css') ?>" rel="stylesheet">
<link href="<?=$this->getStaticUrl('js/tempus-dominus/dist/css/tempus-dominus.min.css') ?>" rel="stylesheet">

<h1>
    <?php
    if ($this->get('training') != '') {
        echo $this->getTrans('edit');
    } else {
        echo $this->getTrans('add');
    }
    ?>
</h1>
<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <div class="row mb-3">
        <label for="title" class="col-xl-2 control-label">
            <?=$this->getTrans('title') ?>:
        </label>
        <div class="col-xl-4">
            <input type="text"
                   class="form-control"
                   id="title"
                   name="title"
                   value="<?php if ($this->get('training') != '') { echo $this->escape($this->get('training')->getTitle()); } ?>" />
        </div>
    </div>
    <div class="row mb-3">
        <label for="date" class="col-lg-2 control-label">
            <?=$this->getTrans('dateTime') ?>:
        </label>
        <div id="date" class="col-xl-2 input-group ilch-date date form_datetime">
            <input type="text"
                   class="form-control"
                   id="date"
                   name="date"
                   value="<?php if ($this->get('training') != '') { echo date('d.m.Y H:i', strtotime($this->get('training')->getDate())); } ?>"
                   readonly>
            <span class="input-group-text">
                <span class="fa fa-calendar"></span>
            </span>
        </div>
    </div>
    <div class="row mb-3">
        <label for="time" class="col-xl-2 control-label">
            <?=$this->getTrans('time') ?>:
        </label>
        <div class="col-xl-1">
            <input type="number"
                   class="form-control"
                   id="time"
                   name="time"
                   min="0"
                   value="<?php if ($this->get('training') != '') { echo $this->escape($this->get('training')->getTime()); } else { echo '30'; } ?>">
        </div>
    </div>
    <div class="row mb-3">
        <label for="place" class="col-xl-2 control-label">
            <?=$this->getTrans('place') ?>:
        </label>
        <div class="col-xl-4">
            <input type="text"
                   class="form-control"
                   id="place"
                   name="place"
                   value="<?php if ($this->get('training') != '') { echo $this->escape($this->get('training')->getPlace()); } ?>" />
        </div>
    </div>
    <div class="row mb-3">
        <label for="contact" class="col-xl-2 control-label">
            <?=$this->getTrans('contactPerson') ?>:
        </label>
        <div class="col-xl-2">
            <select class="form-control" id="contact" name="contact">
                <?php
                foreach ($this->get('users') as $user) {
                    $selected = '';
                    if ($this->get('training') != '' && $this->get('training')->getContact() == $user->getId()) {
                        $selected = 'selected="selected"';
                    }
                    echo '<option '.$selected.' value="'.$user->getId().'">'.$this->escape($user->getName()).'</option>';
                }
                ?>
            </select>
        </div>
    </div>
    <div class="row mb-3">
        <label for="voiceServer" class="col-xl-2 control-label">
            <?=$this->getTrans('voiceServer') ?>:
        </label>
        <div class="col-xl-2">
            <input type="checkbox"
                   id="voiceServer"
                   name="voiceServer"
                   value="1"
                   onclick="showMe('voiceServerInfo', this)"
                   <?php if ($this->get('training') != '' && $this->get('training')->getVoiceServer() == 1) { echo 'checked="checked"';} ?>>
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
        <div class="row mb-3">
            <label for="voiceServerIP" class="col-xl-2 control-label">
                <?=$this->getTrans('voiceServerIP') ?>:
            </label>
            <div class="col-xl-4">
                <input type="text"
                       class="form-control"
                       id="voiceServerIP"
                       name="voiceServerIP"
                       placeholder="IP:Port"
                       value="<?php if ($this->get('training') != '') { echo $this->escape($this->get('training')->getVoiceServerIP()); } ?>" />
            </div>
        </div>
        <div class="row mb-3">
            <label for="voiceServerPW" class="col-xl-2 control-label">
                <?=$this->getTrans('voiceServerPW') ?>:
            </label>
            <div class="col-xl-4">
                <input type="text"
                       class="form-control"
                       id="voiceServerPW"
                       name="voiceServerPW"
                       placeholder="********"
                       value="<?php if ($this->get('training') != '') { echo $this->escape($this->get('training')->getVoiceServerPW()); } ?>" />
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <label for="gameServer" class="col-xl-2 control-label">
            <?=$this->getTrans('gameServer') ?>:
        </label>
        <div class="col-xl-2">
            <input type="checkbox"
                   id="gameServer"
                   name="gameServer"
                   value="1"
                   onclick="showMe('gameServerInfo', this)"
                   <?php if ($this->get('training') != '' && $this->get('training')->getGameServer() == 1) { echo 'checked="checked"';} ?>>
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
        <div class="row mb-3">
            <label for="gameServerIP" class="col-xl-2 control-label">
                <?=$this->getTrans('gameServerIP') ?>:
            </label>
            <div class="col-xl-4">
                <input type="text"
                       class="form-control"
                       id="gameServerIP"
                       name="gameServerIP"
                       placeholder="IP:Port"
                       value="<?php if ($this->get('training') != '') { echo $this->escape($this->get('training')->getGameServerIP()); } ?>" />
            </div>
        </div>
        <div class="row mb-3">
            <label for="gameServerPW" class="col-xl-2 control-label">
                <?=$this->getTrans('gameServerPW') ?>:
            </label>
            <div class="col-xl-4">
                <input type="text"
                       class="form-control"
                       id="gameServerPW"
                       name="gameServerPW"
                       placeholder="********"
                       value="<?php if ($this->get('training') != '') { echo $this->escape($this->get('training')->getGameServerPW()); } ?>" />
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <label for="ck_1" class="col-xl-2 control-label">
            <?=$this->getTrans('otherInfo') ?>:
        </label>
        <div class="col-xl-10">
            <textarea class="form-control ckeditor"
                      id="ck_1"
                      name="text"
                      toolbar="ilch_html"
                      rows="5"><?php if ($this->get('training') != '') { echo $this->escape($this->get('training')->getText()); } ?></textarea>
        </div>
    </div>
    <div class="row mb-3">
        <label for="access" class="col-xl-2 control-label">
            <?=$this->getTrans('visibleFor') ?>
        </label>
        <div class="col-xl-6">
            <select class="chosen-select form-control" id="access" name="groups[]" data-placeholder="<?=$this->getTrans('selectAssignedGroups') ?>" multiple>
                <?php foreach ($this->get('userGroupList') as $groupList): ?>
                    <?php if ($groupList->getId() != 1): ?>
                        <option value="<?=$groupList->getId() ?>"<?=(in_array($groupList->getId(), $this->get('groups'))) ? ' selected' : '' ?>><?=$groupList->getName() ?></option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <?php if ($this->get('calendarShow') == 1): ?>
        <div class="row mb-3">
            <div class="offset-xl-2 col-xl-10">
                <input type="checkbox"
                       id="calendarShow"
                       name="calendarShow"
                       value="1"
                    <?php if (($this->get('training') != '' && $this->get('training')->getShow() == 1) || $this->originalInput('calendarShow') == 1) { echo 'checked'; } ?> />
                <label for="calendarShow">
                    <?=$this->getTrans('calendarShow') ?>
                </label>
            </div>
        </div>
    <?php endif; ?>
    <?php
    if ($this->get('training') != '') {
        echo $this->getSaveBar('updateButton');
    } else {
        echo $this->getSaveBar('addButton');
    }
    ?>
</form>

<?=$this->getDialog('mediaModal', $this->getTrans('media'), '<iframe frameborder="0"></iframe>') ?>
<script src="<?=$this->getStaticUrl('js/popper/dist/umd/popper.min.js') ?>" charset="UTF-8"></script>
<script src="<?=$this->getStaticUrl('js/tempus-dominus/dist/js/tempus-dominus.min.js') ?>" charset="UTF-8"></script>
<?php if (strncmp($this->getTranslator()->getLocale(), 'en', 2) !== 0): ?>
    <script src="<?=$this->getStaticUrl('js/tempus-dominus/dist/locales/' . substr($this->getTranslator()->getLocale(), 0, 2) . '.js') ?>" charset="UTF-8"></script>
<?php endif; ?>
<script>
$('#access').chosen();

$(document).ready(function() {
    new tempusDominus.TempusDominus(document.getElementById('date'), {
        restrictions: {
          minDate: new Date()
        },
        display: {
            sideBySide: true,
            calendarWeeks: true,
            buttons: {
                today: true,
                close: true
            }
        },
        localization: {
            locale: "<?=substr($this->getTranslator()->getLocale(), 0, 2) ?>",
            startOfTheWeek: 1,
            format: "dd.MM.yyyy HH:mm"
        },
        stepping: 15
    });
});

function showMe (it, box) {
    var vis = (box.checked) ? "block" : "none";
    document.getElementById(it).style.display = vis;
}
</script>
