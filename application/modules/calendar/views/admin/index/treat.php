<?php
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

$entry = $this->get('calendar');
 ?>

<link href="<?=$this->getStaticUrl('js/tempus-dominus/dist/css/tempus-dominus.min.css') ?>" rel="stylesheet">

<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <h1>
        <?=($entry->getId()) ? $this->getTrans('edit') : $this->getTrans('add') ?>
    </h1>
    <div class="row mb-3 <?=$this->validation()->hasError('start') ? ' has-error' : '' ?>">
        <label for="start" class="col-xl-2 control-label">
            <?=$this->getTrans('start') ?>:
        </label>
        <div id="start" class="col-xl-4 input-group ilch-date date form_datetime_1">
            <input type="text"
                   class="form-control"
                   id="start"
                   name="start"
                   value="<?=$this->escape($this->originalInput('start', ($entry->getId()?(new \Ilch\Date($entry->getStart()))->format('d.m.Y H:i'):''))) ?>"
                   readonly>
            <span class="input-group-text">
                <span class="fa-solid fa-calendar"></span>
            </span>
        </div>
    </div>
    <div class="row mb-3 <?=$this->validation()->hasError('end') ? ' has-error' : '' ?>">
        <label for="end" class="col-xl-2 control-label">
            <?=$this->getTrans('end') ?>:
        </label>
        <div id="end" class="col-xl-4 input-group ilch-date date form_datetime_2">
            <input type="text"
                   class="form-control"
                   id="end"
                   name="end"
                   value="<?=$this->escape($this->originalInput('end', ($entry->getId()?($entry->getEnd() != '1000-01-01 00:00:00' ? (new \Ilch\Date($entry->getEnd()))->format('d.m.Y H:i') : ''):''))) ?>">
            <span class="input-group-text">
                <span class="fa-solid fa-calendar"></span>
            </span>
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('title') ? ' has-error' : '' ?>">
        <label for="title" class="col-xl-2 control-label">
            <?=$this->getTrans('title') ?>:
        </label>
        <div class="col-xl-4">
            <input type="text"
                   class="form-control"
                   id="title"
                   name="title"
                   value="<?=$this->escape($this->originalInput('title', ($entry->getId()?$entry->getTitle():''))) ?>" />
        </div>
    </div>
    <div class="row mb-3 <?=$this->validation()->hasError('place') ? ' has-error' : '' ?>">
        <label for="place" class="col-xl-2 control-label">
            <?=$this->getTrans('place') ?>:
        </label>
        <div class="col-xl-4">
            <input type="text"
                   class="form-control"
                   id="place"
                   name="place"
                   value="<?=$this->escape($this->originalInput('place', ($entry->getId()?$entry->getPlace():''))) ?>" />
        </div>
    </div>
    <div class="row mb-3 <?=$this->validation()->hasError('periodType') ? ' has-error' : '' ?>">
        <label for="periodType" class="col-xl-2 control-label">
            <?=$this->getTrans('periodEntry') ?>:
        </label>
        <div class="col-xl-4">
            <select class="form-control" name="periodType" id="periodType">
                <option value="" <?=($this->originalInput('periodType', ($entry->getId()?$entry->getPeriodType():''))) == '' ? 'selected=""' : '' ?>><?=$this->getTrans('noPeriodEntry') ?></option>
                <?php foreach ($periodTypes as $key => $value): ?>
                    <option value="<?=$key ?>" <?=($this->originalInput('periodType', ($entry->getId()?$entry->getPeriodType():''))) == $key ? 'selected=""' : '' ?>><?=$value ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="<?=$this->validation()->hasError('periodDays') ? ' has-error' : '' ?>" id="periodDays_div">
      <div class="row mb-3">
        <label for="periodDays" class="col-xl-2 control-label"></label>
        <div class="col-xl-4">
            <select class="form-control" name="periodDays" id="periodDays">
                <option value="0" <?=($this->originalInput('periodDay', ($entry->getId()?$entry->getPeriodDay():'0'))) == '0' ? 'selected=""' : '' ?>><?=$this->getTrans('noPeriodEntry') ?></option>
                <?php foreach ($periodDays as $key => $value): ?>
                    <option value="<?=$key ?>" <?=($this->originalInput('periodDay', ($entry->getId()?$entry->getPeriodDay():'0'))) == $key ? 'selected=""' : '' ?>><?=$value ?></option>
                <?php endforeach; ?>
            </select>
        </div>
      </div>
    </div>
    <div class="<?=$this->validation()->hasError('periodDay') ? ' has-error' : '' ?>" id="periodDay_div">
      <div class="row mb-3">
        <label for="periodDay" class="col-xl-2 control-label"></label>
        <div class="col-xl-4 input-group">
            <span class="input-group-text"><?=$this->getTrans('periodEvery') ?></span>
            <input type="text"
                   class="form-control"
                   id="periodDay"
                   name="periodDay"
                   value="<?=$this->escape($this->originalInput('periodDay', ($this->originalInput('periodType', ($entry->getId()?$entry->getPeriodType():'')) == 'days'?'0':($entry->getId()?$entry->getPeriodDay():'1')))) ?>" />
            <span class="input-group-text" id="periodDayAppendix"><?=(!empty($entry->getPeriodType())) ? $this->getTrans($periodAppendix[$entry->getPeriodType()]) : '' ?></span>
        </div>
      </div>
    </div>

    <div class="<?=$this->validation()->hasError('repeatUntil') ? ' has-error' : '' ?>" id="repeatUntil_div">
      <div class="row mb-3">
        <label for="repeatUntil" class="col-xl-2 control-label">
            <?=$this->getTrans('repeatUntil') ?>:
        </label>
        <div id="repeatUntil" class="col-xl-4 input-group ilch-date date form_datetime_3">
            <input type="text"
                   class="form-control"
                   id="repeatUntil"
                   name="repeatUntil"
                   value="<?=$this->escape($this->originalInput('repeatUntil', ($entry->getId()?($entry->getRepeatUntil() != '1000-01-01 00:00:00' ? (new \Ilch\Date($entry->getRepeatUntil()))->format('d.m.Y H:i') : ''):''))) ?>"
                   readonly>
            <span class="input-group-text">
                <span class="fa-solid fa-calendar"></span>
            </span>
        </div>
      </div>
    </div>

    <div class="row mb-3<?=$this->validation()->hasError('color') ? ' has-error' : '' ?>">
        <label for="color" class="col-xl-2 control-label">
            <?=$this->getTrans('color') ?>:
        </label>
        <div class="col-xl-4 input-group date">
            <input class="form-control color {hash:true}"
                   id="color"
                   name="color"
                   value="<?=$this->escape($this->originalInput('color', ($entry->getId()?$entry->getColor():'#32333B'))) ?>">
            <span class="input-group-text">
                <span class="fa-solid fa-arrow-rotate-left" onclick="document.getElementById('color').color.fromString('32333B')"></span>
            </span>
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('groups') ? ' has-error' : '' ?>">
        <label for="access" class="col-xl-2 control-label">
            <?=$this->getTrans('visibleFor') ?>
        </label>
        <div class="col-xl-4">
            <select class="chosen-select form-control" id="access" name="groups[]" data-placeholder="<?=$this->getTrans('selectAssignedGroups') ?>" multiple>
                <option value="all" <?=in_array('all', $this->originalInput('groups', $this->get('groups'))) ? 'selected="selected"' : '' ?>><?=$this->getTrans('groupAll') ?></option>
                <?php foreach ($this->get('userGroupList') as $groupList): ?>
                    <?php if ($groupList->getId() != 1): ?>
                        <option value="<?=$groupList->getId() ?>" <?=in_array($groupList->getId(), $this->originalInput('groups', $this->get('groups'))) ? 'selected=""' : '' ?>><?=$groupList->getName() ?></option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="row mb-3 <?=$this->validation()->hasError('text') ? ' has-error' : '' ?>">
        <label for="ck_1" class="col-xl-2 control-label">
            <?=$this->getTrans('text') ?>:
        </label>
        <div class="col-xl-10">
            <textarea class="form-control ckeditor"
                      id="ck_1"
                      name="text"
                      toolbar="ilch_html"
                      rows="5"><?=$this->escape($this->originalInput('text', ($entry->getId()?$entry->getText():''))) ?></textarea>
        </div>
    </div>
    <?=($entry->getId()) ? $this->getSaveBar('updateButton') : $this->getSaveBar('addButton') ?>
</form>

<?=$this->getDialog('mediaModal', $this->getTrans('media'), '<iframe frameborder="0"></iframe>'); ?>
<script src="<?=$this->getStaticUrl('js/jscolor/jscolor.js') ?>"></script>
<script src="<?=$this->getStaticUrl('js/popper/dist/umd/popper.min.js') ?>" charset="UTF-8"></script>
<script src="<?=$this->getStaticUrl('js/tempus-dominus/dist/js/tempus-dominus.min.js') ?>" charset="UTF-8"></script>
<?php if (strncmp($this->getTranslator()->getLocale(), 'en', 2) !== 0) : ?>
    <script src="<?=$this->getStaticUrl('js/tempus-dominus/dist/locales/' . substr($this->getTranslator()->getLocale(), 0, 2) . '.js') ?>" charset="UTF-8"></script>
<?php endif; ?>
<script>
<?=$this->getMedia()
    ->addMediaButton($this->getUrl('admin/media/iframe/index/type/single/'))
    ->addUploadController($this->getUrl('admin/media/index/upload'))
?>
$('#access').chosen();

$(document).ready(function() {
    let jsPeriodAppendix = <?=json_encode($periodAppendix) ?>;

    const start = new tempusDominus.TempusDominus(document.getElementById('start'), {
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

    // $(".form_datetime_1").datetimepicker({
        // format: "dd.mm.yyyy hh:ii",
        // autoclose: true,
        // language: '<?=substr($this->getTranslator()->getLocale(), 0, 2) ?>',
        // minuteStep: 15,
        // todayHighlight: true,
    // }).on('change.datetimepicker', function (e) {
        // let mindate = $(".form_datetime_1").data("datetimepicker").getDate();
        // $('.form_datetime_2').datetimepicker('minDate', mindate);
    // });

    // $(".form_datetime_2").datetimepicker({
        // format: "dd.mm.yyyy hh:ii",
        // startDate: new Date(),
        // autoclose: true,
        // language: '<?=substr($this->getTranslator()->getLocale(), 0, 2) ?>',
        // minuteStep: 15,
        // todayHighlight: false,
        // useCurrent: false,
        // minDate: $(".form_datetime_1").data("datetimepicker").getDate()
    // }).on('change.datetimepicker', function (e) {
        // let mindate = $(".form_datetime_2").data("datetimepicker").getDate();
        // $('.form_datetime_3').datetimepicker('minDate', mindate);
    // });

    // $(".form_datetime_3").datetimepicker({
        // format: "dd.mm.yyyy hh:ii",
        // startDate: new Date(),
        // autoclose: true,
        // language: '<?=substr($this->getTranslator()->getLocale(), 0, 2) ?>',
        // minuteStep: 15,
        // todayHighlight: false,
        // useCurrent: false,
        // minDate: $(".form_datetime_2").data("datetimepicker").getDate()
    // });

    diasableDays();

    document.getElementById("periodType").onchange = function() {
        diasableDays();

        document.getElementById("periodDayAppendix").textContent = jsPeriodAppendix[document.getElementById('periodType').value];

        adjustRepeatUntilDate();
    };

    document.getElementById("start").onchange = function() {
        adjustRepeatUntilDate();
    }

    function adjustRepeatUntilDate() {
        let value = document.getElementById('periodType').value;

        if (value !== '') {
            let repeatUntilDate;
            let startValue = document.getElementById("start").value;

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

    function diasableDays() {
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
</script>
