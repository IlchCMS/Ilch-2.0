<link href="<?=$this->getModuleUrl('static/css/calendar.css') ?>" rel="stylesheet">
<link href="<?=$this->getModuleUrl('static/js/fullcalendar_5_11_3/lib/main.min.css') ?>" rel="stylesheet">

<div class="calendar">
    <div id="loading"></div>

    <div id='calendar'></div>
</div>

<script src="<?=$this->getModuleUrl('static/js/fullcalendar_5_11_3/lib/main.min.js') ?>"></script>
<script src="<?=$this->getModuleUrl('static/js/fullcalendar_5_11_3/lib/locales-all.js') ?>"></script>
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
                        window.location = '<?=BASE_URL.'/index.php/calendar/events/iCal/' ?>';
                    }
                }
            },
            headerToolbar: {
                left: 'prev,next today,list,listSevenDay',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay icalButton'
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
                <?php foreach ($this->get('events') ?? [] as $url): ?>
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
