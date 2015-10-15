<link href="<?=$this->getModuleUrl('static/fullcalendar/fullcalendar.css') ?>" rel="stylesheet">
<link href="<?=$this->getModuleUrl('static/fullcalendar/fullcalendar.print.css') ?>" rel="stylesheet" media='print'>

<div id='calendar'></div>

<style>
#calendar {
    width: 100%;
    margin: 0 auto;
}
.fc-basic-view .fc-body .fc-row { min-height: 60px; }
@media (min-width: 1200px) {
    .fc-basic-view .fc-body .fc-row { height: 112px; }
}
.fc-today { background-color:#4295C9 !important; }
.fc-sat { color:gray; }
.fc-sun { color:red; }
</style>

<script src="<?=$this->getModuleUrl('static/fullcalendar/lib/moment.min.js') ?>"></script>
<script src="<?=$this->getModuleUrl('static/fullcalendar/fullcalendar.min.js') ?>"></script>
<script src="<?=$this->getModuleUrl('static/fullcalendar/lang-all.js') ?>"></script>
<script>    
    $(function() {
		var date = new Date();
		var d = date.getDate();
		var m = date.getMonth();
		var y = date.getFullYear();

		$('#calendar').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'agendaDay,agendaWeek,month'
            },
            defaultView: 'month',
            lang: 'de',
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
                                title  : '<?=$calendarList->getTitle() ?>',
                                start  : '<?=$calendarList->getStart() ?>',
                                end    : '<?=$calendarList->getEnd() ?>',
                                color  : '<?=$calendarList->getColor() ?>',
                                url    : '<?=$this->getUrl('calendar/index/show/id/' . $calendarList->getId()) ?>',
                            },
                        <?php endforeach; ?>
                    <?php endif; ?>

                    // birthday entries
                    <?php $yearBegin = date("Y") - 5; ?>
                    <?php $yearEnd = $yearBegin + 15; ?>
                    <?php $years = range($yearBegin, $yearEnd, 1); ?>
                    <?php foreach($years as $year): ?>
                        <?php foreach ($this->get('birthdayList') as $birthdayList): ?>
                            <?php if ($birthdayList->getBirthday() != '0000-00-00'): ?>
                                {
                                    title  : '<?=$birthdayList->getName() ?> (<?=floor(($year.date('md') - str_replace("-", "", $birthdayList->getBirthday())) / 10000 + 1) ?>)',
                                    start  : '<?=$year.'-'.date('m-d', strtotime($birthdayList->getBirthday())) ?>',
                                    color  : '#257e4a',
                                    url    : '<?=$this->getUrl('user/profil/index/user/' . $birthdayList->getId()) ?>'
                                },
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endforeach; ?>

                    // event entries
                    <?php if ($this->get('eventList') != ''): ?>
                        <?php foreach ($this->get('eventList') as $eventList): ?>
                            {
                                title  : '<?=$eventList->getTitle() ?>',
                                start  : '<?=$eventList->getDateCreated() ?>',
                                color  : '#C52C66',
                                url    : '<?=$this->getUrl('events/show/event/id/' . $eventList->getId()) ?>'
                            },
                        <?php endforeach; ?>
                    <?php endif; ?>
                    ],
		});
    });
</script>
