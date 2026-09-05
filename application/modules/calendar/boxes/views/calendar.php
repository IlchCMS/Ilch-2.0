<?php

/** @var \Ilch\View $this */
?>
<link href="<?=$this->getBoxUrl('static/css/calendar.css') ?>" rel="stylesheet">
<link href="<?=$this->getBoxUrl('static/js/fullcalendar-7.1.0/dist/skeleton.css') ?>" rel="stylesheet">
<link href="<?=$this->getBoxUrl('static/js/fullcalendar-7.1.0/dist/themes/classic/theme.css') ?>" rel="stylesheet">
<link href="<?=$this->getBoxUrl('static/js/fullcalendar-7.1.0/dist/themes/classic/palette.css') ?>" rel="stylesheet">

<div class="calendar">
    <div class="calendarbox-title" id='calendarboxTitle<?=$this->get('uniqid') ?>'></div>

    <div id='calendarbox<?=$this->get('uniqid') ?>'></div>
</div>

<script src="<?=$this->getBoxUrl('static/js/fullcalendar-7.1.0/dist/fullcalendar.global.js') ?>"></script>
<script src="<?=$this->getBoxUrl('static/js/fullcalendar-7.1.0/dist/locales-all/global.js') ?>"></script>
<script src="<?=$this->getBoxUrl('static/js/fullcalendar-7.1.0/dist/themes/classic/global.js') ?>"></script>
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
            // Title is rendered via datesSet so that it can be linked.
            headerToolbar: {
              left: '',
              center: '',
              right: ''
            },
            locale: languagecalendar,
            nowIndicator: true,
            height: 450,
            // In the box there is no space for event titles: all events of a day
            // are summarized as a "+N" counter.
            dayMaxEvents: 0,
            moreLinkClass: 'calendarbox-event-count',
            moreLinkContent: function (arg) {
                return '+' + arg.num;
            },
            moreLinkClick: function () {
                window.location = calendarUrl;

                // A truthy return value suppresses FullCalendar's popover.
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
