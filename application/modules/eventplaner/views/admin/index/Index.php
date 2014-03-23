<?php
/**
 * @copyright Balthazar3k 2014
 * @package Eventplaner 2.0
 */
namespace Eventplaner\Views\Admin;
use User\Mappers\User as UserMapper;
$user = new UserMapper;

$config = $this->get('config');
$status = (array) json_decode($config->get('event_status'), true);
?>
<h4><?=$this->getTrans('listView');?></h4>

<?php
if(empty($this->get('eventList'))){
    echo $this->getTrans('noEvents');
    return;
}
?>

<style type="text/css">
.status{
    width: 100%;
    height: 100%;
    border-radius: 3px;
    box-shadow: inset 1px 1px 2px rgba(0,0,0,0.9);
}
</style>

<table class="table table-hover table-striped">
    <?php foreach( $this->get('eventList') as $event ): ?>
    <tr valign="middle">
	
        <td  width="3%" align="center" valign="middle">
            <div style="<?=$status[$event->getStatus()]['style'];?>" class="status"><br /><br /><br /></div>
        </td>
		
        <td  width="3%" align="center" valign="middle" class="td-border">
            <!--<center class="small">Options</center><br />-->
            <a href=""><i class="fa fa-info-circle"></i></a><br />
            <a href="<?=$this->getURL(array('action' => 'treat', 'id' => $event->getId() ));?>"><i class="fa fa-cogs"></i></a><br />
            <a href=""><i class="fa fa-th-list"></i></a>
        </td>
		
        <td width="15%">
            <b><?=$event->getTitle();?></b><br />
            - <?=$event->getEvent();?>, <b><?=date('H:i', strtotime($event->getStart()));?> - <?=date('H:i', strtotime($event->getEnds()));?> </b>

              (<?=round( (strtotime($event->getEnds())-strtotime($event->getStart()))/60/60 , 1) . $this->getTrans('hours');?>)<br />

            - <?=$this->getTrans(date('w', strtotime($event->getStart())));?>, <?=date('d.m.Y', strtotime($event->getStart()));?>
        </td>
		
        <td  width="40%" align="center">
            <h4><?='2/'.$event->getRegistrations()?></h4>
            <div class="small"><?=$this->getTrans('registrations')?></div>
        </td>

        <td  width="20%" align="right" valign="middle" >
            <?=$this->getTrans('organizer');?> <b><?=$user->getUserById($event->getOrganizer())->getName();?></b><br />
            <span class="small">
                <?=$this->getTrans('created');?> <?=date('d.m.Y H:i', strtotime($event->getCreated()));?><br />
                <?=$this->getTrans('changed');?> <?=date('d.m.Y H:i', strtotime($event->getChanged()));?>
            </span>
        </td>
    </tr>
	<?php endforeach; ?>
</table>

<?php
echo $this->get('pagination')->getHtml($this, array('action' => 'index'));
    //use Eventplaner\Controllers\Admin\Index as b3k;
    //b3k::arPrint( $this->get('event') );
?>