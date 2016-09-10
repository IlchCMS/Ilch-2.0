<link href="<?=$this->getModuleUrl('static/css/calendar.css') ?>" rel="stylesheet">
<link href="<?=$this->getModuleUrl('static/js/fullcalendar/fullcalendar.css') ?>" rel="stylesheet">
<link href="<?=$this->getModuleUrl('static/js/fullcalendar/fullcalendar.print.css') ?>" rel="stylesheet" media='print'>

<div id='calendar'></div>

<script src="<?=$this->getModuleUrl('static/js/fullcalendar/lib/moment.min.js') ?>"></script>
<script src="<?=$this->getModuleUrl('static/js/fullcalendar/fullcalendar.min.js') ?>"></script>
<script src="<?=$this->getModuleUrl('static/js/fullcalendar/lang-all.js') ?>"></script>
<script>
$(function() {
    var date = new Date();
    var d = date.getDate();
    var m = date.getMonth();
    var y = date.getFullYear();
    var language = navigator.language || navigator.userLanguage;

    $('#calendar').fullCalendar({
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'agendaDay,agendaWeek,month'
        },
        defaultView: 'month',
        lang: language,
        firstDay: '1',
        eventColor: '#32333B',
        eventLimit: true,
        contentHeight: 'auto',

        dayClick: function(date, allDay, jsEvent, view) {
            $('#calendar').fullCalendar( 'changeView', 'agendaDay' );
            $('#calendar').fullCalendar( 'gotoDate', date );
        },

        // from database
        events: [
            // calendar entries
            <?php if ($this->get('calendarList') != ''): ?>
                <?php foreach ($this->get('calendarList') as $calendarList): ?>
                    {
                        title: '<?=$calendarList->getTitle() ?>',
                        start: '<?=$calendarList->getStart() ?>',
                        end  : '<?=$calendarList->getEnd() ?>',
                        color: '<?=$calendarList->getColor() ?>',
                        url  : '<?=$this->getUrl('calendar/index/show/id/' . $calendarList->getId()) ?>',
                    },
                <?php endforeach; ?>
            <?php endif; ?>

            // birthday entries
            <?php $yearBegin = date("Y") - 5; ?>
            <?php $yearEnd = $yearBegin + 15; ?>
            <?php $years = range($yearBegin, $yearEnd, 1); ?>
            <?php foreach ($years as $year): ?>
                <?php foreach ($this->get('birthdayList') as $birthdayList): ?>
                    <?php if ($birthdayList->getBirthday() != '0000-00-00'): ?>
                        {
                            title: '<?=$birthdayList->getName() ?> (<?=floor(($year.date('md') - str_replace("-", "", $birthdayList->getBirthday())) / 10000 + 1) ?>)',
                            start: '<?=$year.'-'.date('m-d', strtotime($birthdayList->getBirthday())) ?>',
                            color: '#257e4a',
                            url  : '<?=$this->getUrl('user/profil/index/user/' . $birthdayList->getId()) ?>'
                        },
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endforeach; ?>

            // away entries
            <?php if ($this->get('awayList') != ''): ?>
                <?php foreach ($this->get('awayList') as $awayList): ?>
                    {
                        title: '<?=$this->escape($awayList->getReason()) ?>',
                        start: '<?=$awayList->getStart() ?> 00:00:00',
                        end  : '<?=$awayList->getEnd() ?> 23:59:59',
                        <?php if ($awayList->getStatus() == 0 OR $awayList->getStatus() == 2): ?>
                            color  : '#DF0101',
                        <?php else: ?>
                            color  : '#04B404',
                        <?php endif; ?>
                        url  : '<?=$this->getUrl('away/index/index/#' . $awayList->getId()) ?>'
                    },
                <?php endforeach; ?>
            <?php endif; ?>

            // event entries
            <?php if ($this->get('eventList') != ''): ?>
                <?php foreach ($this->get('eventList') as $eventList): ?>
                    {
                        title: '<?=$eventList->getTitle() ?>',
                        start: '<?=$eventList->getStart() ?>',
                        end  : '<?=$eventList->getEnd() ?>',
                        color: '#C52C66',
                        url  : '<?=$this->getUrl('events/show/event/id/' . $eventList->getId()) ?>'
                    },
                <?php endforeach; ?>
            <?php endif; ?>
        ],
    });
});
</script>
