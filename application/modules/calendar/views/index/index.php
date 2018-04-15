<link href="<?=$this->getModuleUrl('static/css/calendar.css') ?>" rel="stylesheet">
<link href="<?=$this->getModuleUrl('static/js/fullcalendar/fullcalendar.min.css') ?>" rel="stylesheet">
<link href="<?=$this->getModuleUrl('static/js/fullcalendar/fullcalendar.print.min.css') ?>" rel="stylesheet" media='print'>

<div class="calendar">
    <div id="loading"></div>

    <div id='calendar'></div>
</div>

<script src="<?=$this->getModuleUrl('static/js/fullcalendar/lib/moment.min.js') ?>"></script>
<script src="<?=$this->getModuleUrl('static/js/fullcalendar/fullcalendar.min.js') ?>"></script>
<script src="<?=$this->getModuleUrl('static/js/fullcalendar/locale-all.js') ?>"></script>
<script>
$(document).ready(function() {
    var date = new Date();
    var language = '<?=substr($this->getTranslator()->getLocale(), 0, 2) ?>';

    $('#calendar').fullCalendar({
        header: {
            left: 'prev',
            center: 'title',
            right: 'next'
        },
        defaultView: 'month',
        locale: language,
        firstDay: '1',
        eventColor: '#32333B',
        contentHeight: 'auto',
        timeFormat: 'HH:mm',
        axisFormat: 'HH:mm',
        slotLabelFormat: "HH:mm",
        allDaySlot: false,
        eventLimit: true,
        eventLimitClick: 'listWeek',
        eventLimitText: '<?=$this->getTrans('calendarLimitText') ?>',

        dayClick: function(date) {
            $('#calendar').fullCalendar('changeView', 'agendaDay');
            $('#calendar').fullCalendar('gotoDate', date);
        },
        eventClick: function(event, jsEvent, view) {
            var currentView = view.name;

            if (event.url && currentView == 'agendaDay') {
                window.open(event.url, "_self");
                return false;
            } else {
                $('#calendar').fullCalendar('changeView', 'agendaDay');
                $('#calendar').fullCalendar('gotoDate', event.start);
            }
        },

        // from database
        eventSources: [
            <?php foreach ($this->get('events') as $url): ?>
                {
                    url: '<?=$this->getUrl($url->getUrl()) ?>'
                },
            <?php endforeach; ?>
        ],

        loading: function(bool) {
            $('#loading').toggle(bool);
        }
    });

    addButtons();
    bindButtonActions();

    function addButtons() {
        var month = $("<button/>")
            .addClass("fc-month-button fc-button fc-state-default fc-corner-left fc-state-active")
            .attr({
                type: "button"
            })
            .text("<?=$this->getTrans('calendarMonth') ?>");

        var week = $("<button/>")
            .addClass("fc-agendaWeek-button fc-button fc-state-default")
            .attr({
                type: "button"
            })
            .text("<?=$this->getTrans('calendarWeek') ?>");

        var day = $("<button/>")
            .addClass("fc-agendaDay-button fc-button fc-state-default fc-corner-right")
            .attr({
                type: "button"
            })
            .text("<?=$this->getTrans('calendarDay') ?>");

        var today = $("<button/>")
            .addClass("fc-today-button fc-button fc-state-default fc-corner-left fc-state-disabled")
            .attr({
                type: "button",
                disabled: ""
            })
            .text("<?=$this->getTrans('calendarToday') ?>");

        var listWeek = $("<button/>")
            .addClass("fc-listWeek-button fc-button fc-state-default")
            .attr({
                type: "button"
            })
            .text("<?=$this->getTrans('calendarWeek') ?>");

        var list = $("<button/>")
            .addClass("fc-listWeek-button fc-button fc-state-default fc-corner-right fc-listWeek-button-desk")
            .attr({
                type: "button"
            })
            .text("<?=$this->getTrans('calendarList') ?>");

        var iCal = $("<button/>")
            .addClass("fc-iCal-button fc-button fc-state-default fc-corner-left fc-corner-right")
            .attr({
                type: "button"
            })
            .text("<?=$this->getTrans('calendarICal') ?>");

        var btn = $("<div class='fc-head'/>").append(
            $("<div/>")
                .addClass("fc-button-group")
                .append(month)
                .append(week)
                .append(listWeek)
                .append(day),
            $("<div/>")
                .addClass("fc-button-group fc-right")
                .append(iCal),
            $("<div/>")
                .addClass("fc-button-group fc-right")
                .append(today)
                .append(list)
        );

        var clear = $("<div/>").addClass("fc-clear");

        $(".fc-toolbar").find(".fc-left").before(btn);
        $(".fc-toolbar").find(".fc-head").after(clear);
    }

    function bindButtonActions(){
        $(".fc-month-button, .fc-agendaWeek-button, .fc-agendaDay-button, .fc-listWeek-button, .fc-iCal-button").on('click', function() {
            var view = "month";
            if ($(this).hasClass("fc-agendaWeek-button")) {
                view = "agendaWeek";
            } else if ($(this).hasClass("fc-agendaDay-button")) {
                view = "agendaDay";
            } else if ($(this).hasClass("fc-listWeek-button")) {
                view = "listWeek";
            } else if ($(this).hasClass("fc-iCal-button")) {
                var currentView = $('#calendar').fullCalendar('getView');

                view = currentView.name;
                window.location = '<?=$this->getUrl('calendar/events/iCal/') ?>';
            }

            $('#calendar').fullCalendar('changeView', view);
        });

        $(".fc-today-button").on('click', function() {
            $('#calendar').fullCalendar('today');
        });
    }
});
</script>
