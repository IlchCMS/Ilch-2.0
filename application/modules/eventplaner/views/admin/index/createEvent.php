<?php
$errors = $this->get('errors');
?>

<style>
.left-box{
	width: 40%;
	float: left;
}

.right-box{
	width: 60%;
	float: right;
}

br{
	clear: both;
}

label{
	width: 125px;
}

input, select, textarea{
	width: 350px;
}
</style>

<h4><?=$this->getTrans('createEvent');?></h4>
<form action="<?=$this->getUrl(array('action' => 'create'));?>" method="POST" id="standart">
    <?=$this->getTokenField()?>
    <div class="left-box">
	
        <div>
            <label for="title">
                <?php echo $this->getTrans('title'); ?>:
            </label>
            <input id="title" type="text" name="title" value="" />
        </div>
		
        <div>
            <label for="status">
                <?php echo $this->getTrans('status'); ?>:
            </label>
            <select id="status" name="status">
                <?php foreach($this->get('status') as $id => $status) :?>
                    <option value="<?=$id;?>">
                            <?=$status;?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
		
        <div>
            <label for="event">
                <?php echo $this->getTrans('event'); ?>:
            </label>
            <select id="event" name="event">
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
            <input id="change2input" type="button" value="+" style="width: 20px;"/>
        </div>
		
        <div>
            <label for="organizer">
                    <?php echo $this->getTrans('organizer'); ?>:
            </label>
            <select id="organizer" name="organizer">
                <?php foreach($this->get('users') as $user) :?>
                    <option value="<?=$user->getId();?>" <?=( $user->getId() == $_SESSION['user_id'] ? 'checked="checked"' : '');?>>
                            <?=$user->getName();?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
		
        <div>
            <label for="message">
                <?php echo $this->getTrans('message'); ?>:
            </label>
            <textarea id="message" name="message"></textarea>
        </div>
		
    </div>

    <div class="right-box">

        <div>
            <label for="startDate">
                <?php echo $this->getTrans('startDate'); ?>:
            </label>
            <input class="datepicker" type="text" name="startDate" value="<?=date('d.m.Y');?>" />
        </div>

        <div>
            <label for="start">
                <?php echo $this->getTrans('start'); ?>:
            </label>
            <input id="start" type="text" name="start" value="" />
        </div>

        <div>
            <label for="ends">
                <?php echo $this->getTrans('ends'); ?>:
            </label>
            <input id="ends" type="text" name="ends" value="" />
        </div>

    </div>
    <?=$this->getSaveBar()?>
</form>
<br />

<script>
	$(document).ready(function(){
		
		$( ".datepicker" ).each(function(i){
			$(this).datepicker({ 
				dateFormat: "dd.mm.yy",
				dayNames: 
				[ 
					"<?=$this->getTrans(0);?>", 
					"<?=$this->getTrans(1);?>", 
					"<?=$this->getTrans(2);?>", 
					"<?=$this->getTrans(3);?>", 
					"<?=$this->getTrans(4);?>", 
					"<?=$this->getTrans(5);?>", 
					"<?=$this->getTrans(6);?>"
				]
			});
		});
		
		$('input#start, input#ends').bind('keyup', function(){
			var string = $(this).val();
			var time = string.split(':');
			if( string.length == 2 &&  string.indexOf(':') ){
                            $(this).val( string + ':');
			}else if( string.indexOf('::') ){
                            $(this).val( string.replace('::', ':') );
			}
			
			if( time[0] >= 24 ){
				$(this).val( 23 + ':' + 59 );
			}else if( time[1] >= 59 ){
				$(this).val( (parseInt(time[0])+1) + ':' + '00' );
			}
		});
                
                $('#change2input').bind('click', function(){
                    var id = $(this).prev().attr('id');
                    var name = $(this).prev().attr('name');
                    $(this).prev().replaceWith('<input id="'+ id +'" name="'+ name +'" class="autocomplete" />').next();
                    $(this).fadeOut(1000, function(){
                        var thisTags = <?=json_encode($eventNames);?>;
                        $('input.autocomplete').autocomplete({
                           source: thisTags
                        });
                    });
                });
                
                
                
                
		
	});
	
</script>

<?php
Eventplaner\Controllers\Admin\Index::arPrint( $this->get('eventNames') );
?>
