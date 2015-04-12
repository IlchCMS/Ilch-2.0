<table class="table table-striped table-responsive">
    <tr align="center">
        <td width="1%"><a class="list-group-item" href="<?=$this->getUrl(array('controller' => 'index', 'action' => 'index')); ?>"><i class="fa fa-home"></i></a></td>
        <td width="25%"><a class="list-group-item" href="<?=$this->getUrl(array('controller' => 'show', 'action' => 'upcoming')); ?>"><i class="fa fa-history fa-flip-horizontal"></i>&nbsp; <?=$this->getTrans('naviEventsUpcoming') ?></a></td>
        <td width="25%"><a class="list-group-item" href="<?=$this->getUrl(array('controller' => 'show', 'action' => 'past')); ?>"><i class="fa fa-history"></i>&nbsp; <?=$this->getTrans('naviEventsPast') ?></a></td>
        <?php if ($this->getUser()): ?>
            <td width="24%"><a class="list-group-item" href="<?=$this->getUrl(array('controller' => 'show', 'action' => 'my')); ?>"><i class="fa fa-cogs"></i>&nbsp; <?=$this->getTrans('naviEventsMy') ?></a></td>
            <td width="25%"><a class="list-group-item" href="<?=$this->getUrl(array('controller' => 'index', 'action' => 'treat')); ?>"><i class="fa fa-plus"></i>&nbsp; <?=$this->getTrans('naviEventsAdd') ?></a></td>
        <?php else: ?>
            <td width="25%"></td>
            <td width="24%"></td>
        <?php endif; ?>
    </tr>
</table>
