<?php
    $training = $this->get('training');

    $userMapper = new \Modules\User\Mappers\User();
?>

<legend><?=$this->getTrans('trainDetails') ?></legend>
<div class="form-horizontal">
    <div class="form-group">
        <label for="receiver" class="col-lg-3">
            <?=$this->getTrans('title') ?>:
        </label>
        <div class="col-lg-9"><?=$this->escape($training->getTitle()) ?></div>
    </div>
    <div class="form-group">
        <label for="date" class="col-lg-3">
            <?=$this->getTrans('dateTime') ?>:
        </label>
        <div class="col-lg-9"><?=date('d.m.Y', strtotime($training->getDate())) ?> <?=$this->getTrans('at') ?> <?=date('H:i', strtotime($training->getDate())) ?> <?=$this->getTrans('clock') ?></div>
    </div>
    <div class="form-group">
        <label for="time" class="col-lg-3">
            <?=$this->getTrans('time') ?>:
        </label>
        <div class="col-lg-9">~ <?=$this->escape($training->getTime()) ?></div>
    </div>
    <div class="form-group">
        <label for="place" class="col-lg-3">
            <?=$this->getTrans('place') ?>:
        </label>
        <div class="col-lg-9"><?=$this->escape($training->getPlace()) ?></div>
    </div>
    <div class="form-group">
        <label for="contactPerson" class="col-lg-3">
            <?=$this->getTrans('contactPerson') ?>:
        </label>        
        <?php $contactUser = $userMapper->getUserById($training->getContact()); ?>
        <div class="col-lg-9"><a href="<?=$this->getUrl('user/profil/index/user/'.$contactUser->getId()) ?>" target="_blank"><?=$this->escape($contactUser->getName()) ?></a></div>
    </div>
    <?php if ($training->getVoiceServer() != ''): ?>
        <?php if ($training->getVoiceServerIP() != ''): ?>
        <div class="form-group">
            <label for="voiceServerIP" class="col-lg-3">
                <?=$this->getTrans('voiceServerIP') ?>:
            </label>
            <div class="col-lg-9"><?=$this->escape($training->getVoiceServerIP()) ?></div>
        </div>
        <?php endif; ?>
        <?php if ($training->getVoiceServerPW() != ''): ?>
            <div class="form-group">
                <label for="voiceServerPW" class="col-lg-3">
                    <?=$this->getTrans('voiceServerPW') ?>:
                </label>
                <div class="col-lg-9">                
                    <?php if ($this->getUser() AND $this->get('trainEntrantUser') != ''): ?>
                        <?=$this->escape($training->getVoiceServerPW()) ?>
                    <?php else: ?>
                        <?=str_repeat('&bull;', strlen($this->escape($training->getVoiceServerPW()))) ?>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>
    <?php if ($training->getGameServer() != ''): ?>
        <?php if ($training->getGameServerIP() != ''): ?>
        <div class="form-group">
            <label for="gameServerIP" class="col-lg-3">
                <?=$this->getTrans('gameServerIP') ?>:
            </label>
            <div class="col-lg-9"><?=$this->escape($training->getGameServerIP()) ?></div>
        </div>
        <?php endif; ?>
        <?php if ($training->getGameServerPW() != ''): ?>
            <div class="form-group">
                <label for="gameServerPW" class="col-lg-3">
                    <?=$this->getTrans('gameServerPW') ?>:
                </label>
                <div class="col-lg-9">                
                    <?php if ($this->getUser() AND $this->get('trainEntrantUser') != ''): ?>
                        <?=$this->escape($training->getGameServerPW()) ?>
                    <?php else: ?>
                        <?=str_repeat('&bull;', strlen($this->escape($training->getGameServerPW()))) ?>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>
    <div class="form-group">
        <label for="entrant" class="col-lg-3">
            <?=$this->getTrans('entrant') ?>:
        </label>
        <div class="col-lg-9">
            <?=$this->getTrans('entrys') ?> <?=$this->get('trainEntrantsUserCount') ?>
            <?php if ($this->get('trainEntrantsUserCount') != 0): ?>
                <br />
                <?php foreach ($this->get('trainEntrantsUser') as $trainEntrantsUser): ?>
                <?php $entrantsUser = $userMapper->getUserById($trainEntrantsUser->getUserId()); ?>
                    <a href="<?=$this->getUrl('user/profil/index/user/'.$entrantsUser->getId()) ?>" target="_blank"><?=$this->escape($entrantsUser->getName()) ?></a> 
                    <?php if ($trainEntrantsUser->getNote() != ''): ?>
                        <i class="fa fa-arrow-right"></i> <?=$trainEntrantsUser->getNote() ?>
                    <?php endif; ?>
                    <br />
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    <div class="form-group">
        <label for="otherInfo" class="col-lg-12">
            <?=$this->getTrans('otherInfo') ?>:
        </label>
        <div class="col-lg-12">
            <?php if ($training->getText()!= ''): ?>
                <?=$training->getText() ?>
            <?php else: ?>
                <?=$this->getTrans('noOtherInfo') ?>
            <?php endif; ?>
        </div>
    </div>
    <?php  if ($this->getUser()): ?>
        <br />
        <legend><?=$this->getTrans('options') ?></legend>
        <form class="form-horizontal" method="POST" action="">
        <?=$this->getTokenField() ?>
            <?php if ($this->get('trainEntrantUser') != ''): ?>
                <button type="submit" value="del" name="del" class="btn btn-sm btn-danger">
                    <?=$this->getTrans('decline') ?>
                </button>
            <?php else: ?>
                <div class="form-group">
                    <label for="otherInfo" class="col-lg-2" style="top: 7px;">
                        <?=$this->getTrans('note') ?>:
                    </label>
                    <div class="col-lg-4">
                        <textarea class="form-control"
                                  style="resize: none;"
                                  name="train_textarea" 
                                  cols="10" 
                                  rows="1"></textarea>
                    </div>
                    <div class="col-lg-2" style="top: 2px;">
                        <button type="submit" value="save" name="save" class="btn btn-sm btn-success">
                            <?=$this->getTrans('join') ?>
                        </button>
                    </div>
                </div>
            <?php endif; ?>
        </form>
    <?php endif; ?>
</div>
