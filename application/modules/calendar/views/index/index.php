<?php

/** @var \Ilch\View $this */
?>
<link href="<?=$this->getModuleUrl('static/css/calendar.css') ?>" rel="stylesheet">

<div class="calendar">
    <div id="loading"></div>

    <div id='calendar'></div>
</div>

<script src="<?=$this->getModuleUrl('static/js/fullcalendar-6.1.15/dist/index.global.min.js') ?>"></script>
<script src="<?=$this->getModuleUrl('static/js/fullcalendar-6.1.15/packages/core/locales-all.global.min.js') ?>"></script>
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
            customButtons: {
                icalButton: {
                    text: 'iCal',
                    click: function() {
                        window.location = '<?=BASE_URL . '/index.php/calendar/events/iCal/' ?>';
                    }
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
                    },
                    buttonText: '<?=$this->getTrans('listweek') ?>'
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
