<?php
/**
 * @copyright Balthazar3k 2014
 * @package Eventplaner 2.0
 */
$errors = $this->get('errors');
?>

<form class="form-horizontal" method="POST" action="<?php echo $this->getUrl(array('action' => 'treat')); ?>">
    <?php echo $this->getTokenField(); ?>
    <legend>
    <?php
        if ($this->get('partner') != '') {
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
                    <option value="<?=$id;?>">
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
                    <option value="<?=$user->getId();?>" <?=( $user->getId() == $_SESSION['user_id'] ? 'checked="checked"' : '');?>>
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
                    <option value="<?=$eventName->getEvent();?>">
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
                   id="selectDateStart"
                   placeholder="<?php echo $this->getTrans('start'); ?>"
                   value="<?=date("Y-m-d")?>" />
        </div>
    </div>

    <div class="form-group">
        <label for="ends" class="col-lg-2 control-label">
            <?php echo $this->getTrans('ends'); ?>:
        </label>
        <div class="col-lg-4">
            <input class="form-control datepicker"
                   type="text"
                   id="selectDateEnds"
                   placeholder="<?php echo $this->getTrans('ends'); ?>"
                   value="<?=date("Y-m-d")?>" />
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

    <!-- IMPORTANT TIME, TO CREATE A EVENT -->
    <input type="hidden" id="start" name="start" value="">
    <input type="hidden" id="ends" name="ends" value="">

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
		
		$( ".datepicker" ).each(function(i){
			$(this).datepicker({ 
				dateFormat: "yy-mm-dd",
                onClose: function(date){
                    $('input#selectDateEnds').val(date);

                    
                }
			});
		});

        function compile(){
            var startDate = $("input#selectDateStart").val();
            var endsDate = $("input#selectDateEnds").val();

            var startTime = $("input#timeStart").val();
            var endsTime = $("input#timeStart").val();

            $('input#start').val(startDate + ' ' + startTime );
            $('input#ends').val(endsDate + ' ' + endsTime );
        }


        var thisSlider = $( "#slider" );
        var thisDiff = $( "#viewDiff" );

        thisSlider.slider({
            range: true,                 
            min: time2sec("10:00"),
            max: time2sec("23:59"),
            values: [time2sec("18:30"), time2sec("21:30")],
            step: time2sec("00:15"),
            slide: function( event, ui ) { 
                $('input#start').val($("input#selectDateStart").val() + ' ' + sec2time(ui.values[0]));
                $('input#ends').val($("input#selectDateStart").val() + ' ' + sec2time(ui.values[1]));
                
                thisDiff.html(sec2time(ui.values[0]) + ' - ' + sec2time(ui.values[1]) + ', ' + sec2diff(ui.values[0], ui.values[1]) );
            }
        });

        function sec2time(seconds)
        {    
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

        function sec2diff(time1, time2){
            return sec2time(time2-time1);
        }
	});
	
</script>

<?php
//Eventplaner\Controllers\Admin\Index::arPrint( $this->get('eventNames') );
?>
