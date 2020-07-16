<?php
$training = $this->get('training');
$userMapper = new \Modules\User\Mappers\User();
?>

<?php if ($this->get('hasReadAccess')) : ?>
    <h1><?=$this->getTrans('trainDetails') ?></h1>
    <div class="form-horizontal">
        <div class="form-group">
            <div class="col-lg-3">
                <?=$this->getTrans('title') ?>:
            </div>
            <div class="col-lg-9"><?=$this->escape($training->getTitle()) ?></div>
        </div>
        <div class="form-group">
            <div class="col-lg-3">
                <?=$this->getTrans('dateTime') ?>:
            </div>
            <div class="col-lg-9"><?=date('d.m.Y', strtotime($training->getDate())) ?> <?=$this->getTrans('at') ?> <?=date('H:i', strtotime($training->getDate())) ?> <?=$this->getTrans('clock') ?></div>
        </div>
        <div class="form-group">
            <div class="col-lg-3">
                <?=$this->getTrans('time') ?>:
            </div>
            <div class="col-lg-9">~ <?=$this->escape($training->getTime()) ?></div>
        </div>
        <div class="form-group">
            <div class="col-lg-3">
                <?=$this->getTrans('place') ?>:
            </div>
            <div class="col-lg-9"><?=$this->escape($training->getPlace()) ?></div>
        </div>
        <div class="form-group">
            <div class="col-lg-3">
                <?=$this->getTrans('contactPerson') ?>:
            </div>
            <?php $contactUser = $userMapper->getUserById($training->getContact()); ?>
            <div class="col-lg-9"><a href="<?=$this->getUrl('user/profil/index/user/'.$contactUser->getId()) ?>" target="_blank"><?=$this->escape($contactUser->getName()) ?></a></div>
        </div>
        <?php if ($training->getVoiceServer() != ''): ?>
            <?php if ($training->getVoiceServerIP() != ''): ?>
                <div class="form-group">
                    <div class="col-lg-3">
                        <?=$this->getTrans('voiceServerIP') ?>:
                    </div>
                    <div class="col-lg-9"><?=$this->escape($training->getVoiceServerIP()) ?></div>
                </div>
            <?php endif; ?>
            <?php if ($training->getVoiceServerPW() != ''): ?>
                <div class="form-group">
                    <div class="col-lg-3">
                        <?=$this->getTrans('voiceServerPW') ?>:
                    </div>
                    <div class="col-lg-9">
                        <?php if ($this->getUser() && $this->get('trainEntrantUser') != ''): ?>
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
                    <div class="col-lg-3">
                        <?=$this->getTrans('gameServerIP') ?>:
                    </div>
                    <div class="col-lg-9"><?=$this->escape($training->getGameServerIP()) ?></div>
                </div>
            <?php endif; ?>
            <?php if ($training->getGameServerPW() != ''): ?>
                <div class="form-group">
                    <div class="col-lg-3">
                        <?=$this->getTrans('gameServerPW') ?>:
                    </div>
                    <div class="col-lg-9">
                        <?php if ($this->getUser() && $this->get('trainEntrantUser') != ''): ?>
                            <?=$this->escape($training->getGameServerPW()) ?>
                        <?php else: ?>
                            <?=str_repeat('&bull;', strlen($this->escape($training->getGameServerPW()))) ?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>
        <div class="form-group">
            <div class="col-lg-3">
                <?=$this->getTrans('entrant') ?>:
            </div>
            <div class="col-lg-9">
                <?=$this->getTrans('entrys') ?> <?=$this->get('trainEntrantsUserCount') ?>
                <?php if ($this->get('trainEntrantsUserCount') != 0): ?>
                    <br />
                    <?php foreach ($this->get('trainEntrantsUser') as $trainEntrantsUser): ?>
                        <?php $entrantsUser = $userMapper->getUserById($trainEntrantsUser->getUserId()); ?>
                        <a href="<?=$this->getUrl('user/profil/index/user/'.$entrantsUser->getId()) ?>" target="_blank"><?=$this->escape($entrantsUser->getName()) ?></a>
                        <?php if ($trainEntrantsUser->getNote() != ''): ?>
                            <i class="fa fa-arrow-right"></i> <?=$this->escape($trainEntrantsUser->getNote()) ?>
                        <?php endif; ?>
                        <br />
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
        <div class="form-group">
            <div class="col-lg-12">
                <?=$this->getTrans('otherInfo') ?>:
            </div>
            <div class="col-lg-12">
                <?php if ($training->getText()!= ''): ?>
                    <?=$this->purify($training->getText()) ?>
                <?php else: ?>
                    <?=$this->getTrans('noOtherInfo') ?>
                <?php endif; ?>
            </div>
        </div>
        <?php if ($this->getUser()): ?>
            <br />
            <h1><?=$this->getTrans('options') ?></h1>
            <form class="form-horizontal" method="POST" action="">
                <?=$this->getTokenField() ?>
                <?php if ($this->get('trainEntrantUser') != ''): ?>
                    <button type="submit" class="btn btn-sm btn-danger" name="del" value="del">
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
                                  id="otherInfo"
                                  name="train_textarea"
                                  cols="10"
                                  rows="1"></textarea>
                        </div>
                        <div class="col-lg-2" style="top: 2px;">
                            <button type="submit" class="btn btn-sm btn-success" name="save" value="save">
                                <?=$this->getTrans('join') ?>
                            </button>
                        </div>
                    </div>
                <?php endif; ?>
            </form>
        <?php endif; ?>
    </div>
<?php else : ?>
    <?=$this->getTrans('noTraining') ?>
<?php endif; ?>
