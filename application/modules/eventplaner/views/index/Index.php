<?php
/**
 * @copyright Balthazar3k 2014
 * @package Eventplaner 2.0
 */
 
namespace Eventplaner\Views\Admin;
use User\Mappers\User as UserMapper;
use Ilch\Database\Mysql\Pager2 as Pager;

$status = (array) json_decode($this->get('config')->get('event_status'), true);
$user = new UserMapper;
?>

<!-- TITLE -->

<h4><?=$this->getTrans('listView');?></h4>

<?php
if(empty($this->get('eventList'))){
    echo $this->getTrans('noEvents');
    return;
}
?>

<style type="text/css">
    .event {
        margin-bottom: 5px;
        border-radius: 10px;
        background-color: rgba( 50, 51 , 59, 0.6);
        border: 5px solid rgba( 255, 255, 255, 0.8);
    }
    
    .event h3 {
        color: #FFF;
        font-weight: bold;
        text-shadow: -1px -1px 0 rgba( 0, 0, 0, 0.3);
        
        padding: 0 20px 0 20px;
    }
    
    .event .singel-line{
        padding: 0 20px 0 20px;
    }
    
    .status{
        font-family: Trebuchet MS,Tahoma,Verdana,Arial,sans-serif;
        padding: 0 20px 0 20px;
        padding-top: 1px;
        width: 100%;
        height: 25px;
        line-height: 25px;
        box-shadow: inset 0 1px 2px rgba(0,0,0,0.9);
    }
    
    .status-string{
         font-size: 15px;
    }
    
    .ui-progressbar {
        position: relative;
    }
    
    .progress-label {
        position: absolute;
        left: 80%;
        top: 4px;
        font-weight: bold;
        text-shadow: 1px 1px 0 #fff;
    }
    
    .transparent{
        opacity: 0.5;
    }
</style>

<?php foreach( $this->get('eventList') as $event ): ?>
<a href="<?=$this->getURL(array('action' => 'details', 'id' => $event->getId() ));?>">
    <div class="event">
        <div>
            <h3 style="float: left;"><?=(!empty($event->getTitle()) ? $event->getTitle() : $event->getEvent())?></h3>
            <h3 style="float: right;"><?=(empty($event->getTitle()) ? $event->getTitle() : $event->getEvent())?> <?=$event->getRegistrations();?></h3>
            <br style="clear: both;" />
        </div>
        <div class="status status-string" style="<?=$status[$event->getStatus()]['style'];?>">
            <div style="float:left;">
                <strong>
                    <span class="transparent"><?=$this->getTrans(date('w', strtotime($event->getStart())));?></span> 
                    <?=$event->getStartDate('d.m.Y')?>
                </strong>
                <span class="transparent"> um </span>
                <strong>
                    <?=$event->getStartDate('H:i');?> - 
                    <?=$event->getEndsDate('H:i');?> 
                </strong>
                <span class="transparent">: <?=$event->getTimeDiff();?></span>
            </div>
            <div style="font-weight: bold; float:right;"><?=$this->getTrans($status[$event->getStatus()]['status']);?></div>
            <br style="clear: both;" />
        </div>
        <br>
        <div class="singel-line">
            <div id="progressbar" data-value="<?=rand(1, $event->getRegistrations());?>" data-max="<?=$event->getRegistrations();?>">
                <div class="progress-label">
                    <?=$this->getTrans('registrations')?>: 
                    2/<?=$event->getRegistrations();?>
                </div>
            </div>
        </div>
        <div>
            <div style="font-weight: bold; float:left;"></div>
            <div style="float:right;"></div>
            <br style="clear: both;" />
        </div>
    </div>
</a>
<?php endforeach; ?>


<script type="text/javascript">
$(function() {
    
    $( "div#progressbar" ).each(function(){
        var max = parseInt($(this).attr('data-max'));
        var value = parseInt($(this).attr('data-value'));
        $(this).progressbar({
            max: max,
            value: value
        });
    });
});
</script>