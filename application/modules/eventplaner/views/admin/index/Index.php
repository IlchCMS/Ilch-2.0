<?php
/**
 * @copyright Balthazar3k 2014
 * @package Eventplaner 2.0
 */
namespace Eventplaner\Views\Admin;
use User\Mappers\User as UserMapper;
$user = new UserMapper;
?>
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
	
        <td  width="3%" align="center" valign="middle">
            <div class="status status-<?=$event->getStatus();?>"><br /><br /><br /></div>
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
		
            <td  width="40%">
                <div>
                    <span class="small"><?=$this->getTrans('registrations');?></span><br />
                    <?php $end = (integer) rand( 1, 10 );
                    for( $i = 1; $i<$event->getRegistrations()+1; $i++): ?>
                        <div class="status registrations <?php if( $i <= $end ){ echo 'registrations-aktiv'; } ?>"><b class="small"><?=$i?></b></div>
                    <?php endfor; ?>
                </div>
            </td>
		
            <td  width="20%" align="right" valign="middle">
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