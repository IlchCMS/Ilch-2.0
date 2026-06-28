<?php

/** @var \Ilch\View $this */
?>
<link href="<?=$this->getModuleUrl('static/css/calendar.css') ?>" rel="stylesheet">
<link href="<?=$this->getModuleUrl('static/js/fullcalendar-7.0.0/dist/skeleton.css') ?>" rel="stylesheet">
<link href="<?=$this->getModuleUrl('static/js/fullcalendar-7.0.0/dist/themes/classic/theme.css') ?>" rel="stylesheet">
<link href="<?=$this->getModuleUrl('static/js/fullcalendar-7.0.0/dist/themes/classic/palette.css') ?>" rel="stylesheet">

<div class="calendar">
    <div id="loading"></div>

    <div id='calendar'></div>
</div>

<script src="<?=$this->getModuleUrl('static/js/fullcalendar-7.0.0/dist/fullcalendar.global.js') ?>"></script>
<script src="<?=$this->getModuleUrl('static/js/fullcalendar-7.0.0/dist/locales-all/global.js') ?>"></script>
<script src="<?=$this->getModuleUrl('static/js/fullcalendar-7.0.0/dist/themes/classic/global.js') ?>"></script>
<script>
    if (typeof languagecalendar === 'undefined') {
        var languagecalendar = '<?=substr($this->getTranslator()->getLocale(), 0, 2) ?>';
    }
    if (typeof timeFormat === 'undefined') {
        var timeFormat = '';
    }
    if (typeof labelTimeFormat === 'undefined') {
        var labelTimeFormat = '';
    }

    if (languagecalendar === 'de') {
        timeFormat = 'HH:mm';
        labelTimeFormat = 'HH:mm';
    } else if (languagecalendar === 'en') {
        timeFormat = 'hh:mm';
        labelTimeFormat = 'hh:mm A';
    }

    document.addEventListener('DOMContentLoaded', function() {
        let calendarEl = document.getElementById('calendar');
        let calendar = new FullCalendar.Calendar(calendarEl, {
            buttons: {
                icalButton: {
                    text: 'iCal',
                    click: function() {
                        window.location = '<?=BASE_URL . '/index.php/calendar/events/iCal/' ?>';
                    }
                },
                listSevenDay: {
                    text: '<?=$this->getTrans('listweek') ?>',
                }
            },
            headerToolbar: {
                left: 'prev,next today,list,listSevenDay',
                center: 'title',
                right: 'multiMonthYear,dayGridMonth,timeGridWeek,timeGridDay icalButton'
            },
            views: {
                listSevenDay: {
                    type: 'list',
                    duration: {
                        days: 7
                    }
                }
            },
            locale: languagecalendar,
            navLinks: true, // can click day/week names to navigate views
            dayMaxEvents: true, // allow "more" link when too many events
            nowIndicator: true,
            weekNumbers: true,
            weekNumberCalculation: 'ISO',
            eventSources: [
                <?php
                /** @var \Modules\Calendar\Models\Events $url */
                foreach ($this->get('events') ?? [] as $url) : ?>
                {
                    url: '<?=$this->getUrl($url->getUrl()) ?>'
                },
                <?php endforeach; ?>
            ],
            loading: function(bool) {
                document.getElementById('loading').style.display =
                    bool ? 'block' : 'none';
            }
        });
        calendar.render();
    });
</script>
