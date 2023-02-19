<link href="<?=$this->getBoxUrl('static/css/calendar.css') ?>" rel="stylesheet">
<link href="<?=$this->getBoxUrl('static/js/fullcalendar_5_11_3/lib/main.min.css') ?>" rel="stylesheet">

<div class="calendar">
    <div id='calendarbox<?=$this->get('uniqid') ?>'></div>
</div>

<script src="<?=$this->getBoxUrl('static/js/fullcalendar_5_11_3/lib/main.min.js') ?>"></script>
<script src="<?=$this->getBoxUrl('static/js/fullcalendar_5_11_3/lib/locales-all.min.js') ?>"></script>
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
        let calendarEl = document.getElementById('calendarbox<?=$this->get('uniqid') ?>');
        let calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: "dayGridMonth",
            headerToolbar: {
              left: '',
              center: 'title',
              right: ''
            },
            locale: languagecalendar,
            nowIndicator: true,
            height: 450,
            eventSources: [
                <?php foreach ($this->get('events') ?? [] as $url): ?>
                {
                    url: '<?=$this->getUrl($url->getUrl()) ?>'
                },
                <?php endforeach; ?>
            ],
            eventDidMount: function (info) {
                $('#calendarbox<?=$this->get('uniqid') ?> .fc-daygrid-day.fc-day').each(function(i) {
                    let date = $(this).data('date'),
                    eventframe = $(this).find('.fc-daygrid-day-frame .fc-daygrid-day-events');
                    
                    let count = eventframe[0].childElementCount;
                    count--;
                    if (count > 0) {
                        $(this).find('.fc-daygrid-day-frame .fc-daygrid-day-events').html('<div class="fc-event-count">+' + count + '<div>');
                    }
                });
                info.event.remove();
            },
            loading: function( isLoading ) {
                if(isLoading) {// isLoading gives boolean value
                    //show your loader here 
                } else {
                    let titleframe = $('#calendarbox<?=$this->get('uniqid') ?> .fc-toolbar-title');
                    titleframe[0].innerHTML = '<a href="<?=$this->getUrl(['module' => 'calendar']) ?>">' + titleframe[0].innerHTML + '</a>';
                }
            }
        });
        calendar.render();
    });
</script>
