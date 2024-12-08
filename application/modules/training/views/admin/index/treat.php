<?php

/** @var \Ilch\View $this */

/** @var \Modules\Training\Models\Training $training */
$training = $this->get('training');

$periodDays = [
    '1' => $this->getTranslator()->trans('Monday'),
    '2' => $this->getTranslator()->trans('Tuesday'),
    '3' => $this->getTranslator()->trans('Wednesday'),
    '4' => $this->getTranslator()->trans('Thursday'),
    '5' => $this->getTranslator()->trans('Friday'),
    '6' => $this->getTranslator()->trans('Saturday'),
    '7' => $this->getTranslator()->trans('Sunday')
];
$periodTypes = [
    'daily' => $this->getTranslator()->trans('daily'),
    'weekly' => $this->getTranslator()->trans('weekly'),
    'monthly' => $this->getTranslator()->trans('monthly'),
    'quarterly' => $this->getTranslator()->trans('quarterly'),
    'yearly' => $this->getTranslator()->trans('yearly'),
    'days' => $this->getTranslator()->trans('days'),
];

$periodAppendix = [
    'daily' => $this->getTranslator()->trans('daily'),
    'weekly' => $this->getTranslator()->trans('weeks'),
    'monthly' => $this->getTranslator()->trans('months'),
    'yearly' => $this->getTranslator()->trans('years'),
    'quarterly' => $this->getTranslator()->trans('quarter'),
    'days' => $this->getTranslator()->trans('days'),
];
?>
<link href="<?=$this->getModuleUrl('static/css/training.css') ?>" rel="stylesheet">
<link href="<?=$this->getStaticUrl('js/tempus-dominus/dist/css/tempus-dominus.min.css') ?>" rel="stylesheet">

<h1>
    <?=$this->getTrans($training->getId() ? 'edit' : 'add') ?>
</h1>
<form method="POST" action="">
    <?=$this->getTokenField() ?>
    <div class="row mb-3<?=$this->validation()->hasError('title') ? ' has-error' : '' ?>">
        <label for="title" class="col-xl-2 col-form-label">
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
    <div class="row mb-3<?=$this->validation()->hasError('date') ? ' has-error' : '' ?>">
        <label for="start" class="col-md-2 col-form-label">
            <?=$this->getTrans('start') ?>:
        </label>
        <div class="col-xl-2 input-group ilch-date date form_datetime" id="date">
            <?php
            $datecreate = '';
            if ($training->getDate()) {
                $date = new \Ilch\Date($training->getDate());
            } else {
                $date = new \Ilch\Date();
            }
            $datecreate = $date->format('d.m.Y H:i');
            ?>
            <input type="text"
                   class="form-control"
                   id="start"
                   name="date"
                   value="<?=$this->escape($this->originalInput('date', $datecreate)) ?>"
                   readonly>
            <span class="input-group-text">
                <span class="fa-solid fa-calendar"></span>
            </span>
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('end') ? ' has-error' : '' ?>">
        <label for="end" class="col-xl-2 col-form-label">
            <?=$this->getTrans('end') ?>:
        </label>
        <div id="end" class="col-xl-4 input-group ilch-date date form_datetime_2">
            <input type="text"
                   class="form-control"
                   id="end"
                   name="end"
                   value="<?=$this->escape($this->originalInput('end', ($training->getId() ? ($training->getEnd() != '1000-01-01 00:00:00' ? (new \Ilch\Date($training->getEnd()))->format('d.m.Y H:i') : '') : ''))) ?>"
                   readonly>
            <span class="input-group-text">
                <span class="fa-solid fa-calendar"></span>
            </span>
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('periodType') ? ' has-error' : '' ?>">
        <label for="periodType" class="col-xl-2 col-form-label">
            <?=$this->getTrans('periodEntry') ?>:
        </label>
        <div class="col-xl-4">
            <select class="form-select" name="periodType" id="periodType">
                <option value="" <?=($this->originalInput('periodType', ($training->getId() ? $training->getPeriodType() : ''))) == '' ? 'selected=""' : '' ?>><?=$this->getTrans('noPeriodEntry') ?></option>
                <?php foreach ($periodTypes as $key => $value) : ?>
                    <option value="<?=$key ?>" <?=($this->originalInput('periodType', ($training->getId() ? $training->getPeriodType() : ''))) == $key ? 'selected=""' : '' ?>><?=$value ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="<?=$this->validation()->hasError('periodDays') ? ' has-error' : '' ?>" id="periodDays_div">
        <div class="row mb-3">
            <label for="periodDays" class="col-xl-2 col-form-label"></label>
            <div class="col-xl-4">
                <select class="form-select" name="periodDays" id="periodDays">
                    <option value="0" <?=($this->originalInput('periodDay', ($training->getId() ? $training->getPeriodDay() : '0'))) == '0' ? 'selected=""' : '' ?>><?=$this->getTrans('noPeriodEntry') ?></option>
                    <?php foreach ($periodDays as $key => $value) : ?>
                        <option value="<?=$key ?>" <?=($this->originalInput('periodDay', ($training->getId() ? $training->getPeriodDay() : '0'))) == $key ? 'selected=""' : '' ?>><?=$value ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>
    <div class="<?=$this->validation()->hasError('periodDay') ? ' has-error' : '' ?>" id="periodDay_div">
        <div class="row mb-3">
            <label for="periodDay" class="col-xl-2 col-form-label"></label>
            <div class="col-xl-4 input-group">
                <span class="input-group-text"><?=$this->getTrans('periodEvery') ?></span>
                <input type="text"
                       class="form-control"
                       id="periodDay"
                       name="periodDay"
                       value="<?=$this->escape($this->originalInput('periodDay', ($this->originalInput('periodType', ($training->getId() ? $training->getPeriodType() : '')) == 'days' ? '0' : ($training->getId() ? $training->getPeriodDay() : '1')))) ?>" />
                <span class="input-group-text" id="periodDayAppendix"><?=(!empty($training->getPeriodType())) ? $this->getTrans($periodAppendix[$training->getPeriodType()]) : '' ?></span>
            </div>
        </div>
    </div>
    <div class="<?=$this->validation()->hasError('repeatUntil') ? ' has-error' : '' ?>" id="repeatUntil_div">
        <div class="row mb-3">
            <label for="repeatUntil" class="col-xl-2 col-form-label">
                <?=$this->getTrans('repeatUntil') ?>:
            </label>
            <div id="repeatUntil" class="col-xl-4 input-group ilch-date date form_datetime_3">
                <input type="text"
                       class="form-control"
                       id="repeatUntil"
                       name="repeatUntil"
                       value="<?=$this->escape($this->originalInput('repeatUntil', ($training->getId() ? ($training->getRepeatUntil() != '1000-01-01 00:00:00' ? (new \Ilch\Date($training->getRepeatUntil()))->format('d.m.Y H:i') : '') : ''))) ?>"
                       readonly>
                <span class="input-group-text">
                <span class="fa-solid fa-calendar"></span>
            </span>
            </div>
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('place') ? ' has-error' : '' ?>">
        <label for="place" class="col-xl-2 col-form-label">
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
    <div class="row mb-3<?=$this->validation()->hasError('contact') ? ' has-error' : '' ?>">
        <label for="contact" class="col-xl-2 col-form-label">
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
    <div class="row mb-3<?=$this->validation()->hasError('voiceServer') ? ' has-error' : '' ?>">
        <label for="voiceServer" class="col-xl-2 col-form-label">
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
        <div class="row mb-3<?=$this->validation()->hasError('voiceServerIP') ? ' has-error' : '' ?>">
            <label for="voiceServerIP" class="col-xl-2 col-form-label">
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
        <div class="row mb-3<?=$this->validation()->hasError('voiceServerPW') ? ' has-error' : '' ?>">
            <label for="voiceServerPW" class="col-xl-2 col-form-label">
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
    <div class="row mb-3<?=$this->validation()->hasError('gameServer') ? ' has-error' : '' ?>">
        <label for="gameServer" class="col-xl-2 col-form-label">
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
        <div class="row mb-3<?=$this->validation()->hasError('gameServerIP') ? ' has-error' : '' ?>">
            <label for="gameServerIP" class="col-xl-2 col-form-label">
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
        <div class="row mb-3<?=$this->validation()->hasError('gameServerPW') ? ' has-error' : '' ?>">
            <label for="gameServerPW" class="col-xl-2 col-form-label">
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
    <div class="row mb-3<?=$this->validation()->hasError('text') ? ' has-error' : '' ?>">
        <label for="ck_1" class="col-xl-2 col-form-label">
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
    <div class="row mb-3<?=$this->validation()->hasError('access') ? ' has-error' : '' ?>">
        <label for="access" class="col-xl-2 col-form-label">
            <?=$this->getTrans('visibleFor') ?>
        </label>
        <div class="col-xl-6">
            <select class="choices-select form-control" id="access" name="groups[]" data-placeholder="<?=$this->getTrans('selectAssignedGroups') ?>" multiple>
                <?php foreach ($this->get('userGroupList') as $groupList) : ?>
                    <?php if ($groupList->getId() != 1) : ?>
                        <option value="<?=$groupList->getId() ?>"<?=(in_array($groupList->getId(), $this->get('groups'))) ? ' selected' : '' ?>><?=$groupList->getName() ?></option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <?php if ($this->get('calendarShow')) : ?>
        <div class="row mb-3<?=$this->validation()->hasError('calendarShow') ? ' has-error' : '' ?>">
            <label for="calendarShow" class="col-xl-2 col-form-label">
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

<?=$this->getDialog('mediaModal', $this->getTrans('media'), '<iframe style="border:0;"></iframe>') ?>
<script src="<?=$this->getStaticUrl('js/popper/dist/umd/popper.min.js') ?>" charset="UTF-8"></script>
<script src="<?=$this->getStaticUrl('js/tempus-dominus/dist/js/tempus-dominus.min.js') ?>" charset="UTF-8"></script>
<?php if (strncmp($this->getTranslator()->getLocale(), 'en', 2) !== 0): ?>
    <script src="<?=$this->getStaticUrl('js/tempus-dominus/dist/locales/' . substr($this->getTranslator()->getLocale(), 0, 2) . '.js') ?>" charset="UTF-8"></script>
<?php endif; ?>
<script>
    let jsPeriodAppendix = <?=json_encode($periodAppendix) ?>;

    $(document).ready(function() {
        new Choices('#access', {
            ...choicesOptions,
            searchEnabled: true
        })
    });

    if ("<?=substr($this->getTranslator()->getLocale(), 0, 2) ?>" !== 'en') {
        tempusDominus.loadLocale(tempusDominus.locales.<?=substr($this->getTranslator()->getLocale(), 0, 2) ?>);
        tempusDominus.locale(tempusDominus.locales.<?=substr($this->getTranslator()->getLocale(), 0, 2) ?>.name);
    }

    $(document).ready(function() {
        const start = new tempusDominus.TempusDominus(document.getElementById('date'), {
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

        const end = new tempusDominus.TempusDominus(document.getElementById('end'), {
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

        const repeatUntil = new tempusDominus.TempusDominus(document.getElementById('repeatUntil'), {
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
            promptTimeOnDateChange: true,
            stepping: 15
        });

        start.subscribe('change.td', (e) => {
            end.updateOptions({
                restrictions: {
                    minDate: e.date,
                },
            });
        });

        end.subscribe('change.td', (e) => {
            repeatUntil.updateOptions({
                restrictions: {
                    minDate: e.date,
                },
            });
            start.updateOptions({
                restrictions: {
                    maxDate: e.date,
                },
            });
        });


        disableDays();

        document.getElementById("periodType").onchange = function() {
            disableDays();

            document.getElementById("periodDayAppendix").textContent = jsPeriodAppendix[document.getElementById('periodType').value];

            adjustRepeatUntilDate();
        };

        document.getElementById("startDate").onchange = function() {
            adjustRepeatUntilDate();
        }

        function adjustRepeatUntilDate() {
            let value = document.getElementById('periodType').value;

            if (value !== '') {
                let repeatUntilDate;
                let startValue = document.getElementById("startDate").value;

                if (startValue !== '') {
                    // d.m.Y H:i
                    let startdateArray = startValue.split(' ');
                    let startDateArrayDateParts = startdateArray[0].split('.');
                    let startDateArrayTimeParts = startdateArray[1].split(':');

                    // new Date(year, monthIndex, day, hours, minutes)
                    repeatUntilDate = new Date(startDateArrayDateParts[2], startDateArrayDateParts[1] - 1, startDateArrayDateParts[0], startDateArrayTimeParts[0], startDateArrayTimeParts[1]);
                } else {
                    repeatUntilDate = new Date();
                }

                switch (value) {
                    case 'daily':
                        repeatUntilDate.setMonth(repeatUntilDate.getMonth() + 1);
                        break;
                    case 'weekly':
                    case 'days':
                        repeatUntilDate.setMonth(repeatUntilDate.getMonth() + 6);
                        break;
                    case 'monthly':
                        repeatUntilDate.setFullYear(repeatUntilDate.getFullYear() + 1);
                        break;
                    case 'quarterly':
                    case 'yearly':
                        repeatUntilDate.setFullYear(repeatUntilDate.getFullYear() + 5);
                        break;
                }

                document.getElementById("repeatUntil").value = [repeatUntilDate.getDate().toString().padStart(2, '0'), (repeatUntilDate.getMonth() + 1).toString().padStart(2, '0'), repeatUntilDate.getFullYear()].join('.')
                    + ' ' + [repeatUntilDate.getHours().toString().padStart(2, '0'), repeatUntilDate.getMinutes().toString().padStart(2, '0')].join(':');
            }
        }

        function disableDays() {
            if (document.getElementById('periodType').value !== '') {
                if (document.getElementById("periodType").value === 'days') {
                    document.getElementById("periodDays_div").style.display = "block";
                    document.getElementById("periodDay_div").style.display = "none";
                } else {
                    document.getElementById("periodDays_div").style.display = "none";
                    document.getElementById("periodDay_div").style.display = "block";
                }
                document.getElementById("repeatUntil_div").style.display = "block";
            } else {
                document.getElementById("periodDays_div").style.display = "none";
                document.getElementById("periodDay_div").style.display = "none";
                document.getElementById("repeatUntil_div").style.display = "none";
            }
        }
    });

    function showMe (it, box) {
        document.getElementById(it).style.display = (box.value === "1") ? "block" : "none";
    }
</script>
