<table class="table table-striped table-responsive">
    <tr>
        <th colspan="2"><?=$this->getTrans('menuEventList') ?></th>
    </tr>
    <?php if ($this->get('eventList') != ''): ?>
        <?php foreach ($this->get('eventList') as $eventlist): ?>
            <tr>
                <td width="167"><img src="http://placehold.it/150x100"></td>
                <td>
                    <form class="form-horizontal" action="<?=$this->getUrl(array('action' => $this->getRequest()->getActionName())); ?>" method="post">
                        <?=$this->getTokenField(); ?>
                        <div  style="margin-top: -3px;">
                            <a href="<?=$this->getUrl('events/index/show/id/' . $eventlist->getId()) ?>"><b><?=$this->escape($eventlist->getTitle()) ?></a>
                            <div class="small">
                                <?=$this->escape($eventlist->getDateCreated()) ?><br />
                                <?=$this->escape($eventlist->getPlace()) ?><br />
                                <?php $eventsMappers = new Modules\Events\Mappers\Events(); ?>
                                <?=count($eventsMappers->getEventEntrants($eventlist->getId()))+1 ?> <?= $this->getTrans('guest') ?>
                            </div>
                            <?php if ($this->getUser()): ?>
                                <input type="hidden" name="id" value="<?= $this->escape($eventlist->getId()) ?>">
                                <button type="submit" value="1" name="save" class="btn btn-sm btn-success">
                                    <?=$this->getTrans('join') ?>
                                </button>
                                <button type="submit" value="2" name="save" class="btn btn-sm btn-warning">
                                    <?=$this->getTrans('maybe') ?>
                                </button>
                                <button type="submit" value="delete" name="delete" class="btn btn-sm btn-danger">
                                    <?=$this->getTrans('decline') ?>
                                </button>
                            <?php endif; ?>
                        </div>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>            
        <tr>
            <td>
                <?=$this->getTrans('noEvent') ?>
            </td>
        </tr>
    <?php endif; ?>
</table>
