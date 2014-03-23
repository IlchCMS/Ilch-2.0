<?php
/**
 * @copyright Balthazar3k 2014
 * @package Eventplaner 2.0
 */
 
namespace Eventplaner\Views\Admin;
use User\Mappers\User as UserMapper;
use Ilch\Database\Mysql\Pager2 as Pager;

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

<table class="table table-hover table-striped">
    <?php foreach( $this->get('eventList') as $event ): ?>
    <tr valign="middle">
	
        <td  width="5%" align="center" valign="middle">
            <div class="status status-<?=$event->getStatus();?>"><br /><br /><br /></div>
        </td>
		
        <td width="25%">
            <div><b><?=(!empty($event->getTitle()) ? $event->getTitle() : $event->getEvent())?></b></div>
            <span class="small"><?=$event->getEvent().' '.$event->getRegistrations()?></span><br />
            <span class="small"><b><?=$this->getTrans(date('w', strtotime($event->getStart())));?>, <?=date('d.m.Y', strtotime($event->getStart()));?></b></span>

        </td>
        
        <td width="30%" align="center">
            <h4>
                <?=date('H:i', strtotime($event->getStart()));?> - 
                <?=date('H:i', strtotime($event->getEnds()));?><br />
                <span class="small">(<?=round( (strtotime($event->getEnds())-strtotime($event->getStart()))/60/60 , 1) . $this->getTrans('hours');?>)</span>
            </h4>
        </td>
        
        <td  width="15%" align="center">
            <a href="<?=$this->getURL(array('action' => 'details', 'id' => $event->getId() ));?>"><i class="fa fa-info-circle"></i></a>
        </td>
		
        <td  width="15%" align="center">
            <h4><?='2/'.$event->getRegistrations()?></h4>
            <div class="small"><?=$this->getTrans('registrations')?></div>
        </td>

    </tr>
    <?php endforeach; ?>
</table>