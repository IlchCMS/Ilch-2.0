<?php
/**
 * @copyright Balthazar3k 2014
 * @package Eventplaner 2.0
 */
$config = $this->get('config');
?>

<form class="form-horizontal" method="POST" action="<?php echo $this->getUrl(array('action' => 'treat', 'id' => $this->getRequest()->getParam('id'))); ?>">
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
                            <?=$this->getTrans($status['status']);?>
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
        <?php if( !empty($this->get('eventNames')) ) : ?>
        <div class="col-lg-4">
            <select 
                class="form-control"
                id="event"
                name="event">
                <option><?=$this->getTrans('choose')?> <?=$this->getTrans('event')?></option>
                <option value="newEvent"><?=$this->getTrans('newEventName');?></option>
                <optgroup>Events</optgroup>
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
        <?php endif; ?>
        <div id="setNewEvent" class="col-lg-4" <?php if( !empty($this->get('eventNames')) ) : ?>style="display:none;"<?php endif; ?>>
            <input class="form-control "
                   type="text"
                   name="newEvent"
                   placeholder="<?php echo $this->getTrans('newEventName'); ?>" />
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
                id="ilch_html"
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
            
        var sel = $('select#event');
        
        
            sel.change(function(){
                if( sel.val() === 'newEvent'){
                    $(this).animate({visibility:'hidden'}, function(){
                        $('div#setNewEvent').fadeIn();
                    }).parent().remove();
                }
            });
      
            
        var start = $('input#start');
        var ends = $('input#ends');
            
        var slideNow = function( event, ui ) { 

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

        var thisSlider = $( "#slider" );
        var thisDiff = $( "#viewDiff" );
		
            $( ".datepicker" ).each(function(i){
                $(this).datepicker({ 
                    dateFormat: "yy-mm-dd",
                    onClose: function(date){
                        

                        start.val(date + ' <?=$config->get('event_start_time');?>');
                        ends.val(date + ' <?=$config->get('event_ends_time');?>');

                        thisSlider.slider({
                            values: [datetime2sec(start.val()), datetime2sec(ends.val())],
                            slide: slideNow
                        });
                    
                }
			});
		});

        

        thisSlider.slider({
            range: true, 
            values: [
                datetime2sec('<?=($this->get('event') == '' ? $config->get('event_start_time') : $this->get('event')->getStart() )?>'), 
                datetime2sec('<?=($this->get('event') == '' ? $config->get('event_ends_time') : $this->get('event')->getEnds() )?>')
            ],
            min: time2sec("<?=$config->get('event_starting_time');?>"),
            max: time2sec("<?=$config->get('event_ending_time');?>"),
            step: time2sec("<?=$config->get('event_steps_time');?>"),
            slide:  slideNow
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
