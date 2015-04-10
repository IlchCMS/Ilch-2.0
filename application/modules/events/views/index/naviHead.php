<table class="table table-striped table-responsive">
    <tr align="center">
        <td width="20%"><a class="list-group-item" href="<?=$this->getUrl(array('action' => 'upcoming')); ?>"><i class="fa fa-history fa-flip-horizontal"></i>&nbsp; <?=$this->getTrans('naviEventsUpcoming') ?></a></td>
        <td width="20%"><a class="list-group-item" href="<?=$this->getUrl(array('action' => 'all')); ?>"><i class="fa fa-list-ul"></i>&nbsp; <?=$this->getTrans('naviEventsAll') ?></a></td>
        <td width="20%"><a class="list-group-item" href="<?=$this->getUrl(array('action' => 'past')); ?>"><i class="fa fa-history"></i>&nbsp; <?=$this->getTrans('naviEventsPast') ?></a></td>
        <td width="20%"><a class="list-group-item" href="<?=$this->getUrl(array('action' => 'treat')); ?>"><i class="fa fa-plus"></i>&nbsp; <?=$this->getTrans('naviEventsAdd') ?></a></td>
        <td width="20%"><a class="list-group-item" href="<?=$this->getUrl(array('action' => 'my')); ?>"><i class="fa fa-cogs"></i>&nbsp; <?=$this->getTrans('naviEventsMy') ?></a></td>
    </tr>
</table>