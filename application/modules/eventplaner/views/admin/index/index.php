<?php

	

?>
<!-- TITLE -->
<h4><?=$this->getTrans('listView');?></h4>

<table class="table table-hover table-striped">
	<?php foreach( $this->get('eventList') as $event ): ?>
	<tr>
		<td>
			<div><b><?=$event->getTitle();?></b></div>
			<div>- <?=$event->getEvent();?> um <?=date('H:i', $event->getStart());?> bis <?=date('H:i', $event->getEnds());?> Uhr</div>
			<div>- <?=$this->getTrans(date('w', $event->getStart()));?> den <?=date('d.m.Y', $event->getStart());?></div>
		</td>
		
		<td align="center" valign="middle">
			<?=round((($event->getEnds()-$event->getStart())/60/60), 1) .' '. $this->getTrans('hours');?>
		</td>
		
		<td align="center" valign="middle">
			<?=$event->getMessage();?>
		</td>
	</tr>
	<?php endforeach; ?>
</table>