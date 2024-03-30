<?php

/** @var \Ilch\View $this */

/** @var \Modules\Training\Models\Training $training */
$training = $this->get('training');
?>
<link href="<?=$this->getModuleUrl('static/css/training.css') ?>" rel="stylesheet">
<link href="<?=$this->getStaticUrl('js/tempus-dominus/dist/css/tempus-dominus.min.css') ?>" rel="stylesheet">

<h1>
    <?=$this->getTrans($training->getId() ? 'edit' : 'add') ?>
</h1>
<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <div class="row mb-3<?=$this->validation()->hasError('title') ? ' has-error' : '' ?>">
        <label for="title" class="col-xl-2 control-label">
            <?=$this->getTrans('title') ?>:
        </label>
        <div class="col-xl-4">
            <input type="text"
                   class="form-control"
                   id="title"
                   name="title"
                   value="<?=$this->escape($this->originalInput('title', $training->getTitle())) ?>" />
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('title') ? ' has-error' : '' ?>">
        <label for="date" class="col-md-2 control-label">
            <?=$this->getTrans('dateTime') ?>:
        </label>
        <div class="col-xl-2 input-group ilch-date date form_datetime" id="date">
            <?php
            $datecreate = '';
            if ($training->getDate()) {
                $date = new \Ilch\Date($training->getDate());
            } else {
                $date = new \Ilch\Date();
            }
            $datecreate = $date->format('d.m.Y H:i', true);
            ?>
            <input type="text"
                   class="form-control"
                   id="date"
                   name="date"
                   value="<?=$this->escape($this->originalInput('date', $datecreate)) ?>"
                   readonly>
            <span class="input-group-text">
                <span class="fa-solid fa-calendar"></span>
            </span>
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('title') ? ' has-error' : '' ?>">
        <label for="time" class="col-xl-2 control-label">
            <?=$this->getTrans('time') ?>:
        </label>
        <div class="col-xl-1">
            <input type="number"
                   class="form-control"
                   id="time"
                   name="time"
                   min="0"
                   value="<?=$this->escape($this->originalInput('time', $training->getTime())) ?>">
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('title') ? ' has-error' : '' ?>">
        <label for="place" class="col-xl-2 control-label">
            <?=$this->getTrans('place') ?>:
        </label>
        <div class="col-xl-4">
            <input type="text"
                   class="form-control"
                   id="place"
                   name="place"
                   value="<?=$this->escape($this->originalInput('place', $training->getPlace())) ?>" />
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('title') ? ' has-error' : '' ?>">
        <label for="contact" class="col-xl-2 control-label">
            <?=$this->getTrans('contactPerson') ?>:
        </label>
        <div class="col-xl-2">
            <select class="form-select" id="contact" name="contact">
                <?php
                foreach ($this->get('users') as $user) {
                    $selected = '';
                    if ($this->get('training') != '' && $this->get('training')->getContact() == $user->getId()) {
                        $selected = 'selected="selected"';
                    }
                    echo '<option ' . $selected . ' value="' . $user->getId() . '">' . $this->escape($user->getName()) . '</option>';
                }
                ?>
            </select>
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('title') ? ' has-error' : '' ?>">
        <label for="voiceServer" class="col-xl-2 control-label">
            <?=$this->getTrans('voiceServer') ?>:
        </label>
        <div class="col-xl-6">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" id="voiceServer-on" name="voiceServer" value="1" onclick="showMe('voiceServerInfo', this)" <?=$this->originalInput('voiceServer', $training->getVoiceServer()) ? 'checked="checked"' : '' ?> />
                <label for="voiceServer-on" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('on') ?></label>
                <input type="radio" class="flipswitch-input" id="voiceServer-off" name="voiceServer" value="0" onclick="showMe('voiceServerInfo', this)" <?=!$this->originalInput('voiceServer', $training->getVoiceServer()) ? 'checked="checked"' : '' ?> />
                <label for="voiceServer-off" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('off') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
        </div>
    </div>
    <?php
    if (!$training->getVoiceServer()) {
        $voiceDisplay = 'style="display: none;"';
    } else {
        $voiceDisplay = '';
    }
    ?>
    <div id="voiceServerInfo" <?=$voiceDisplay ?>>
        <div class="row mb-3<?=$this->validation()->hasError('title') ? ' has-error' : '' ?>">
            <label for="voiceServerIP" class="col-xl-2 control-label">
                <?=$this->getTrans('voiceServerIP') ?>:
            </label>
            <div class="col-xl-4">
                <input type="text"
                       class="form-control"
                       id="voiceServerIP"
                       name="voiceServerIP"
                       placeholder="IP:Port"
                       value="<?=$this->escape($this->originalInput('voiceServerIP', $training->getVoiceServerIP())) ?>" />
            </div>
        </div>
        <div class="row mb-3<?=$this->validation()->hasError('title') ? ' has-error' : '' ?>">
            <label for="voiceServerPW" class="col-xl-2 control-label">
                <?=$this->getTrans('voiceServerPW') ?>:
            </label>
            <div class="col-xl-4">
                <input type="text"
                       class="form-control"
                       id="voiceServerPW"
                       name="voiceServerPW"
                       placeholder="********"
                       value="<?=$this->escape($this->originalInput('voiceServerPW', $training->getVoiceServerPW())) ?>" />
            </div>
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('title') ? ' has-error' : '' ?>">
        <label for="gameServer" class="col-xl-2 control-label">
            <?=$this->getTrans('gameServer') ?>:
        </label>
        <div class="col-xl-6">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" id="gameServer-on" name="gameServer" value="1" onclick="showMe('gameServerInfo', this)" <?=$this->originalInput('gameServer', $training->getGameServer()) ? 'checked="checked"' : '' ?> />
                <label for="gameServer-on" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('on') ?></label>
                <input type="radio" class="flipswitch-input" id="gameServer-off" name="gameServer" value="0" onclick="showMe('gameServerInfo', this)" <?=!$this->originalInput('gameServer', $training->getGameServer()) ? 'checked="checked"' : '' ?> />
                <label for="gameServer-off" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('off') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
        </div>
    </div>
    <?php
    if (!$training->getGameServer()) {
        $gameDisplay = 'style="display: none;"';
    } else {
        $gameDisplay = '';
    }
    ?>
    <div id="gameServerInfo" <?=$gameDisplay ?>>
        <div class="row mb-3<?=$this->validation()->hasError('title') ? ' has-error' : '' ?>">
            <label for="gameServerIP" class="col-xl-2 control-label">
                <?=$this->getTrans('gameServerIP') ?>:
            </label>
            <div class="col-xl-4">
                <input type="text"
                       class="form-control"
                       id="gameServerIP"
                       name="gameServerIP"
                       placeholder="IP:Port"
                       value="<?=$this->escape($this->originalInput('gameServerIP', $training->getGameServerIP())) ?>" />
            </div>
        </div>
        <div class="row mb-3<?=$this->validation()->hasError('title') ? ' has-error' : '' ?>">
            <label for="gameServerPW" class="col-xl-2 control-label">
                <?=$this->getTrans('gameServerPW') ?>:
            </label>
            <div class="col-xl-4">
                <input type="text"
                       class="form-control"
                       id="gameServerPW"
                       name="gameServerPW"
                       placeholder="********"
                       value="<?=$this->escape($this->originalInput('gameServerPW', $training->getGameServerPW())) ?>" />
            </div>
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('title') ? ' has-error' : '' ?>">
        <label for="ck_1" class="col-xl-2 control-label">
            <?=$this->getTrans('otherInfo') ?>:
        </label>
        <div class="col-xl-10">
            <textarea class="form-control ckeditor"
                      id="ck_1"
                      name="text"
                      toolbar="ilch_html"
                      rows="5"><?=$this->escape($this->originalInput('text', $training->getText())) ?></textarea>
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('title') ? ' has-error' : '' ?>">
        <label for="access" class="col-xl-2 control-label">
            <?=$this->getTrans('visibleFor') ?>
        </label>
        <div class="col-xl-6">
            <select class="chosen-select form-control" id="access" name="groups[]" data-placeholder="<?=$this->getTrans('selectAssignedGroups') ?>" multiple>
                <?php foreach ($this->get('userGroupList') as $groupList) : ?>
                    <?php if ($groupList->getId() != 1) : ?>
                        <option value="<?=$groupList->getId() ?>"<?=(in_array($groupList->getId(), $this->get('groups'))) ? ' selected' : '' ?>><?=$groupList->getName() ?></option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <?php if ($this->get('calendarShow')) : ?>
        <div class="row mb-3<?=$this->validation()->hasError('title') ? ' has-error' : '' ?>">
            <label for="calendarShow" class="col-xl-2 control-label">
                <?=$this->getTrans('calendarShow') ?>
            </label>
            <div class="col-xl-6">
                <div class="flipswitch">
                    <input type="radio" class="flipswitch-input" id="calendarShow-on" name="calendarShow" value="1" <?=$this->originalInput('calendarShow', $training->getShow()) ? 'checked="checked"' : '' ?> />
                    <label for="calendarShow-on" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('on') ?></label>
                    <input type="radio" class="flipswitch-input" id="calendarShow-off" name="calendarShow" value="0" <?=!$this->originalInput('calendarShow', $training->getShow()) ? 'checked="checked"' : '' ?> />
                    <label for="calendarShow-off" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('off') ?></label>
                    <span class="flipswitch-selection"></span>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <?=$this->getSaveBar($training->getId() ? 'updateButton' : 'addButton') ?>
</form>

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
        document.getElementById(it).style.display = (box.value === "1") ? "block" : "none";
    }
</script>
