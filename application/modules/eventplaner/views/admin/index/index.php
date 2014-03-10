<?php
namespace Eventplaner\Views\Admin;
use User\Mappers\User as UserMapper;
$user = new UserMapper;
?>

<!-- TITLE -->
<style>
.status{
	width: 100%;
	height: 100%;
	border-radius: 3px;
	box-shadow: inset 1px 1px 2px rgba(0,0,0,0.9);
}
.status-1{
	background-color: lime;
}

.status-2{
	background-color: blue;
}

.td-border{
	border-left: 1px solid rgba( 0, 0, 0, 0.1);
	border-right: 1px solid rgba( 0, 0, 0, 0.1);
}

.registrations {
	float: left;
	text-align: center; 
	width: 20px; 
	height: 20px; 
	line-height: 19px; 
	margin-left: 2px; 
	border: 1px solid #FFF;
	font-size: 10px;
}

.registrations-aktiv {
	color: #FFF;
	text-shadow: 1px 1px 0 rgba( 0, 0, 0, 0.75);
	font-size: 20px;
	line-height: 16px;
	background-color: lime;
	overfolw: hidden;
	 
}

</style>

<h4><?=$this->getTrans('listView');?></h4>

<table class="table table-hover table-striped">
	<?php foreach( $this->get('eventList') as $event ): ?>
	<tr valign="middle">
	
		<td  width="3%" align="center" valign="middle">
			<div class="status status-<?=$event->getStatus();?>"><br /><br /><br /></div>
		</td>
		
		<td  width="3%" align="center" valign="middle" class="td-border">
			<!--<center class="small">Options</center><br />-->
			<a href="http://localhost/Ilch-2.0/index.php/admin/eventplaner/index/calender"><i class="fa fa-info-circle"></i></a><br />
			<a href="http://localhost/Ilch-2.0/index.php/admin/eventplaner/index/calender"><i class="fa fa-cogs"></i></a><br />
			<a href="http://localhost/Ilch-2.0/index.php/admin/eventplaner/index/calender"><i class="fa fa-th-list"></i></a>
		</td>
		
		<td width="15%">
			<b><?=$event->getTitle();?></b><br />
			- <?=$event->getEvent();?>, <b><?=date('H:i', $event->getStart());?> - <?=date('H:i', $event->getEnds());?> </b>
			  (<?=round((($event->getEnds()-$event->getStart())/60/60), 1) . $this->getTrans('hours');?>)<br />
			- <?=$this->getTrans(date('w', $event->getStart()));?>, <?=date('d.m.Y', $event->getStart());?>
		</td>
		
		<td  width="40%">
			<div>
				<span class="small"><?=$this->getTrans('registrations');?></span><br />
				<?php 
					$end = (integer) rand( 1, 10 );
					for( $i = 1; $i<$event->getRegistrations()+1; $i++):
				?>
					<div class="status registrations <?php if( $i <= $end ){ echo 'registrations-aktiv'; } ?>"><b class="small"><?=$i?></b></div>
				<?php endfor; ?>
			</div>
		</td>
		
		<td  width="20%" align="right" valign="middle">
			<?=$this->getTrans('organizer');?> <?=$user->getUserById($event->getOrganizer())->getName();?><br />
			<span class="small">
				<?=$this->getTrans('created');?> <?=date('d.m.Y H:i', $event->getCreated());?><br />
				<?=$this->getTrans('changed');?> <?=date('d.m.Y H:i', $event->getChanged());?>
			</span>
		</td>
	</tr>
	<?php endforeach; ?>
</table>

<pre>
<?php
print_r( $event );
?>
</pre>