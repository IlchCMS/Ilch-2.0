<?php

/** @var \Ilch\View $this */
?>
<link href="<?=$this->getBoxUrl('static/css/calendar.css') ?>" rel="stylesheet">
<link href="<?=$this->getBoxUrl('static/js/fullcalendar-7.0.0/dist/skeleton.css') ?>" rel="stylesheet">
<link href="<?=$this->getBoxUrl('static/js/fullcalendar-7.0.0/dist/themes/classic/theme.css') ?>" rel="stylesheet">
<link href="<?=$this->getBoxUrl('static/js/fullcalendar-7.0.0/dist/themes/classic/palette.css') ?>" rel="stylesheet">

<div class="calendar">
    <div class="calendarbox-title" id='calendarboxTitle<?=$this->get('uniqid') ?>'></div>

    <div id='calendarbox<?=$this->get('uniqid') ?>'></div>
</div>

<script src="<?=$this->getBoxUrl('static/js/fullcalendar-7.0.0/dist/fullcalendar.global.js') ?>"></script>
<script src="<?=$this->getBoxUrl('static/js/fullcalendar-7.0.0/dist/locales-all/global.js') ?>"></script>
<script src="<?=$this->getBoxUrl('static/js/fullcalendar-7.0.0/dist/themes/classic/global.js') ?>"></script>
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
        let titleEl = document.getElementById('calendarboxTitle<?=$this->get('uniqid') ?>');
        let calendarUrl = '<?=$this->getUrl(['module' => 'calendar']) ?>';

        if (!calendarEl) {
            return;
        }

        let calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: "dayGridMonth",
            // Der Titel wird ueber datesSet selbst gerendert, damit er verlinkt werden kann.
            headerToolbar: {
              left: '',
              center: '',
              right: ''
            },
            locale: languagecalendar,
            nowIndicator: true,
            height: 450,
            // In der Box ist kein Platz fuer Termintitel: alle Termine eines Tages
            // werden zu einem "+N"-Zaehler zusammengefasst.
            dayMaxEvents: 0,
            moreLinkClass: 'calendarbox-event-count',
            moreLinkContent: function (arg) {
                return '+' + arg.num;
            },
            moreLinkClick: function () {
                window.location = calendarUrl;

                // Ein truthy Rueckgabewert unterdrueckt das Popover von FullCalendar.
                return true;
            },
            eventSources: [
                <?php foreach ($this->get('events') ?? [] as $url) : ?>
                {
                    url: '<?=$this->getUrl($url->getUrl()) ?>'
                },
                <?php endforeach; ?>
            ],
            datesSet: function (info) {
                if (!titleEl) {
                    return;
                }

                let link = document.createElement('a');
                link.href = calendarUrl;
                link.textContent = info.view.title;

                titleEl.textContent = '';
                titleEl.appendChild(link);
            }
        });
        calendar.render();
    });
</script>
