<?php
$event = $this->get('event');

$date = new \Ilch\Date($event->getDateCreated());
$userMapper = new \Modules\User\Mappers\User();
$user = $userMapper->getUserById($event->getEventUserId());
?>
<link href="<?=$this->getBaseUrl('application/modules/events/static/css/events.css') ?>" rel="stylesheet">
<legend><?=$this->getTrans('event') ?></legend>
<div class="form-horizontal">
    <div class="form-group">
        <div class="col-lg-6">
            <img src="http://suessmichael.de/wp-content/uploads/2015/01/2014-12-19_mjs_082-450x150.jpg" class="headPic">
            <div class="datePic">
                <div class="dateDayPic"><?=$date->format("d", true) ?></div>
                <div class="dateMonthPic"><?=$date->format("M", true) ?></div>
            </div>
            <div class="titlePic"><?=$event->getTitle() ?></div>
            <div class="naviPic">
                <div class="naviGast"><?=$this->getTrans('by') ?> <a href="<?=$this->getUrl('user/profil/index/user/'.$user->getId()) ?>" target="_blank"><?=$this->escape($user->getName()) ?></a></div>
                <div class="naviButtons">
                    <input type="hidden" name="id" value="<?=$this->escape($event->getId()) ?>">
                    <?php if ($this->getUser()): ?>
                    <?=$event->getStatus() ?>
                        <form class="form-horizontal" method="POST" action="">
                        <?=$this->getTokenField() ?>     
                            <input type="hidden" name="id" value="<?= $this->escape($event->getId()) ?>">
                            <?php if ($event->getEventUserId() != $this->getUser()->getId()): ?>
                                <?php if ($event->getUserId() != $this->getUser()->getId()): ?>
                                    <button type="submit" value="1" name="save" class="btn btn-sm btn-success">
                                        <?=$this->getTrans('join') ?>
                                    </button>
                                    <button type="submit" value="2" name="save" class="btn btn-sm btn-warning">
                                        <?=$this->getTrans('maybe') ?>
                                    </button>
                                <?php else: ?>      
                                    <?php if ($event->getStatus() == 1): ?>
                                        <button type="submit" value="2" name="save" class="btn btn-sm btn-warning">
                                            <?=$this->getTrans('maybe') ?>
                                        </button>
                                    <?php else: ?>
                                        <button type="submit" value="1" name="save" class="btn btn-sm btn-success">
                                            <?=$this->getTrans('agree') ?>
                                        </button>
                                    <?php endif; ?>
                                    <button type="submit" value="deleteUser" name="deleteUser" class="btn btn-sm btn-danger">
                                        <?=$this->getTrans('decline') ?>
                                    </button>    
                                <?php endif; ?>
                            <?php endif; ?>
                            <?php if ($event->getEventUserId() == $this->getUser()->getId()): ?>
                                <?=$this->getTrans('event') ?>
                                <button type="submit" value="deleteEvent" name="deleteEvent" class="btn btn-sm btn-danger">
                                    <?=$this->getTrans('decline') ?>
                                </button>
                            <?php endif; ?>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
            <br />
            <div class="eventBoxHead">
                <i class="fa fa-clock-o"></i> <?=$date->format("l, d. F Y", true) ?> um <?=$date->format("H:i", true) ?> <?=$this->getTrans('clock') ?>
            </div>
            <div class="eventBoxBottom">
                <i class="fa fa-map-marker"></i> <?=$event->getPlace() ?>
            </div>
            <br />
            <div class="eventBoxHead">
                <div style="width: 10%; float: left;">
                    <?=$this->getTrans('entrant') ?>
                </div>
                <div style="width: 45%; float: left;" align="right">
                    <?php $agree = 1; $maybe = 0; ?>
                    <?php if ($this->get('eventEntrantsCount') != ''): ?>
                        <?php foreach ($this->get('eventEntrants') as $eventEntrants): ?>
                            <?php if ($eventEntrants->getStatus() == 1): ?>
                                <?php $agree++; ?>
                            <?php elseif ($eventEntrants->getStatus() == 2): ?>
                                <?php $maybe++; ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <?=$agree ?> <?=$this->getTrans('agree') ?>
                </div>
                <div style="width: 45%; float: left;" align="right">
                    <?=$maybe ?> <?=$this->getTrans('maybe') ?>
                </div>
                <div style="clear: both;"></div>
            </div>
            <div class="eventBoxBottom">
                <div style="margin-left: 2px;">
                    <a href="<?=$this->getUrl('user/profil/index/user/'.$user->getId()) ?>" target="_blank"><img class="thumbnail" src="<?=$this->getStaticUrl().'../'.$this->escape($user->getAvatar()) ?>" title="<?=$this->escape($user->getName()) ?>"></a>
                    <?php if ($this->get('eventEntrantsCount') != ''): ?>
                        <?php foreach ($this->get('eventEntrants') as $eventEntrants): ?>
                        <?php $entrantsUser = $userMapper->getUserById($eventEntrants->getUserId()); ?>
                            <a href="<?=$this->getUrl('user/profil/index/user/'.$entrantsUser->getId()) ?>" target="_blank"><img class="thumbnail" src="<?=$this->getStaticUrl().'../'.$this->escape($entrantsUser->getAvatar()) ?>" title="<?=$this->escape($entrantsUser->getName()) ?>"></a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
            <br />
            <div class="eventBoxContent">
                <?=$event->getText() ?>
            </div>
        </div>

        <div class="col-lg-6">
            <?php if ($this->get('eventComments') != ''): ?>
                <?php foreach ($this->get('eventComments') as $eventComments): ?>
                <?php $commentUser = $userMapper->getUserById($eventComments->getUserId()); ?>
                <?php $commentDate = new \Ilch\Date($eventComments->getDateCreated()); ?>
                    <div class="eventBoxContent">
                        <a href="<?=$this->getUrl('user/profil/index/user/'.$commentUser->getId()) ?>" target="_blank"><?=$this->escape($commentUser->getName()) ?></a>: <?=$commentDate->format("Y.m.d H:i", true) ?>
                        <?php if ($this->getUser() AND $event->getEventUserId() == $this->getUser()->getId()): ?>
                            <div style="float: right;"><?=$this->getDeleteIcon(array('action' => 'del', 'id' => $eventComments->getId())) ?></div>
                        <?php endif; ?>
                        <br />
                        <?=$this->escape($eventComments->getText()) ?>
                    </div>
                    <br />
                <?php endforeach; ?>
            <?php endif; ?>
            <?php if ($this->getUser()): ?>
                <div class="form-group eventCommentSubmit">
                    <form action="" class="form-horizontal" method="POST">
                    <?=$this->getTokenField() ?>
                        <input type="hidden" name="id" value="<?= $this->escape($event->getId()) ?>">
                        <div style="margin-bottom: 10px; margin-top: 10px;">
                            <div class="col-lg-12">
                                <textarea class="eventTextarea" 
                                          name="commentEvent"
                                          placeholder="<?=$this->getTrans('writeToEvent') ?>"
                                          required></textarea>
                            </div>
                        </div>                        
                        <div class="col-lg-12 eventSubmit">
                            <button type="submit" name="saveEntry" class="pull-right btn btn-sm">
                                <?=$this->getTrans('submit') ?>
                            </button>
                        </div>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<script language="JavaScript" type="text/javascript">
// Textarea AutoResize
$('textarea').on('keyup', function() {
    $(this).css('height', 'auto');
    $(this).height(this.scrollHeight);
});
</script>
