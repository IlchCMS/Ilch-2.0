<?php
/**
 * @copyright Balthazar3k 2014
 * @package Eventplaner 2.0
 */

?>

<form class="form-horizontal" method="POST" action="<?php echo $this->getUrl(array('action' => 'treat')); ?>">
    <?php echo $this->getTokenField(); ?>
    <legend>
    <?php
        if ($this->get('event') != '') {
            echo $this->getTrans('menuActionEditEvent');
        } else {
            echo $this->getTrans('menuActionNewEvent');
        }
    ?>
    </legend>

    <div class="form-group">
        <label for="status" class="col-lg-2 control-label">
            <?php echo $this->getTrans('status'); ?>:
        </label>
        <div class="col-lg-4">
            <select 
                class="form-control"
                id="status"
                name="status">
                <option><?=$this->getTrans('choose')?> <?=$this->getTrans('status')?></option>
                <?php foreach($this->get('status') as $id => $status) :?>
                    <option value="<?=$id;?>" <?php if( $this->get('event') != '') { echo ( $id == $this->get('event')->getStatus() ? 'selected="selected"' : ''); }?>>
                            <?=$status;?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
     </div>

    <div class="form-group">
        <label for="title" class="col-lg-2 control-label">
            <?php echo $this->getTrans('title'); ?>:
        </label>
        <div class="col-lg-4">
            <input class="form-control"
                   type="text"
                   name="title"
                   id="title"
                   placeholder="<?php echo $this->getTrans('title'); ?>"
                   value="<?php if ($this->get('event') != '') { echo $this->escape($this->get('event')->getTitle()); } ?>" />
        </div>
    </div>

    <div class="form-group">
        <label for="organizer" class="col-lg-2 control-label">
            <?php echo $this->getTrans('organizer'); ?>:
        </label>
        <div class="col-lg-4">
            <select 
                class="form-control"
                id="organizer"
                name="organizer">
                <option><?=$this->getTrans('choose')?> <?=$this->getTrans('organizer')?></option>
                <?php foreach($this->get('users') as $user) :?>
                    <option value="<?=$user->getId();?>" <?php if($this->get('event') != ''){ if( $id == $this->get('event')->getOrganizer() || $user->getId() == $_SESSION['user_id'] ) { echo 'selected="selected"'; } } ?>>
                        <?=$user->getName();?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
     </div>

    <div class="form-group">
        <label for="event" class="col-lg-2 control-label">
            <?php echo $this->getTrans('event'); ?>:
        </label>
        <div class="col-lg-4">
            <select 
                class="form-control"
                id="event"
                name="event">
                <option><?=$this->getTrans('choose')?> <?=$this->getTrans('event')?></option>
                <?php 
                    $eventNames = array();
                    foreach($this->get('eventNames') as $eventName) :
                        $eventNames[] = $eventName->getEvent();
                ?>
                    <option value="<?=$eventName->getEvent();?>" <?php if( $this->get('event') != '') { echo ( $eventName->getEvent() == $this->get('event')->getEvent() ? 'selected="selected"' : ''); }?>>
                        <?=$eventName->getEvent();?>
                     </option>
                <?php endforeach; ?>
            </select>
        </div>
     </div>

     <div class="form-group">
        <label for="registrations" class="col-lg-2 control-label">
           max. <?php echo $this->getTrans('registrations'); ?>:
        </label>
        <div class="col-lg-4">
            <input class="form-control"
                   type="text"
                   name="registrations"
                   id="registrations"
                   placeholder="max. <?php echo $this->getTrans('registrations'); ?>"
                   value="<?php if ($this->get('event') != '') { echo $this->escape($this->get('event')->getRegistrations()); } ?>" />
        </div>
    </div>

    <div class="form-group">
        <label for="message" class="col-lg-2 control-label">
            <?php echo $this->getTrans('message'); ?>:
        </label>
        <div class="col-lg-4">
            <textarea class="form-control"
                type="text"
                name="message"
                id="message"
                placeholder="<?=$this->getTrans('message')?>"/><?php if ($this->get('event') != '') { echo $this->escape($this->get('event')->getMessage()); } ?></textarea>
        </div>
    </div>

    <div class="form-group">
        <label for="start" class="col-lg-2 control-label">
            <?php echo $this->getTrans('start'); ?>:
        </label>
        <div class="col-lg-4">
            <input class="form-control datepicker"
                   type="text"
                   id="start"
                   name="start"
                   placeholder="<?php echo $this->getTrans('start'); ?>, Format: YYYY-MM-TT HH:MM:SS"
                   value="<?php if( $this->get('event') != '') { echo ( empty($this->get('event')->getStart()) ? date("Y-m-d") : $this->get('event')->getStart() ); } ?>" />
        </div>
    </div>

    <div class="form-group">
        <label for="ends" class="col-lg-2 control-label">
            <?php echo $this->getTrans('ends'); ?>:
        </label>
        <div class="col-lg-4">
            <input class="form-control datepicker"
                   type="text"
                   id="ends"
                   name="ends"
                   placeholder="<?php echo $this->getTrans('ends'); ?>, Format: YYYY-MM-TT HH:MM:SS"
                   value="<?php if( $this->get('event') != '') { echo ( empty($this->get('event')->getEnds()) ? date("Y-m-d") : $this->get('event')->getEnds() ); } ?>" />
        </div>
    </div>

    <div class="form-group">
        <label for="ends" class="col-lg-2 control-label">
            <?php echo $this->getTrans('ends'); ?>:<br />
            <span class="small"><?=$this->getTrans('useSlider');?></span>
        </label>
        <div class="col-lg-4">
            <div id="slider"></div>
            <div class="inputWidth" align="center">
                <span id="viewDiff"></span>  
            </div>
        </div>
    </div>

    <?php
    if ($this->get('event') != '') {
        echo $this->getSaveBar('editButton');
    } else {
        echo $this->getSaveBar('addButton');
    }
    ?>
</form>

<script>
	$(document).ready(function(){

        var thisSlider = $( "#slider" );
        var thisDiff = $( "#viewDiff" );
		
		$( ".datepicker" ).each(function(i){
			$(this).datepicker({ 
				dateFormat: "yy-mm-dd",
                onClose: function(date){
                    var start = $('input#start');
                    var ends = $('input#ends');

                    start.val(date + ' 18:00:00');
                    ends.val(date + ' 21:00:00');

                    thisSlider.slider({
                        values: [datetime2sec(start.val()), datetime2sec(ends.val())],
                        slide: function( event, ui ) { 

                            var startDate = start.val();
                            var endsDate = ends.val();

                            startDate = startDate.split(' ');
                            endsDate = endsDate.split(' ');

                            //console.log('StartDate:', startDate);
                            //console.log('EndsDate:', endsDate);

                            start.val(startDate[0] + ' ' + sec2time(ui.values[0]));
                            ends.val(endsDate[0] + ' ' + sec2time(ui.values[1]));
                        
                        
                            thisDiff.html( sec2diff(ui.values[0], ui.values[1]) );
                        }
                    });
                    
                }
			});
		});

        

        thisSlider.slider({
            range: true,                 
            min: time2sec("10:00"),
            max: time2sec("23:59"),
            step: time2sec("00:15"),
        });

        function sec2time(seconds) {    
            var hours = seconds / 3600;
            hours = Math.floor(hours);        
            seconds -= hours * 3600;

            var minutes = seconds / 60;
            minutes = Math.floor(minutes);
            seconds -= minutes * 60;

            if(hours < 10) {
                hours = '0' + hours;
            }
            if(minutes < 10) {
                minutes = '0' + minutes;
            }
            if(seconds < 10) {
                seconds = '0' + seconds;
            }
            return hours + ':' + minutes + ':00';
        }

        function time2sec(time){
            if(typeof time != "undefined"){
                var val = time.split(":");
                sec = (parseInt(val[1])*60);
                return sec + (parseInt(val[0])*60*60);
            }
        }

        function datetime2sec(datetime){
            if(typeof datetime != "undefined" && datetime.indexOf(' ') ){
                var val = datetime.split(" ");
                //console.log('Function datetime2sec:', val[1]);
                return time2sec(val[1]);
            }
        }

        function sec2diff(time1, time2){
            return sec2time(time2-time1);
        }
	});
	
</script>

<?php
    use Eventplaner\Controllers\Admin\Index as b3k;
    b3k::arPrint( $this->get('event') );
?>
