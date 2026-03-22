<?php

/** @var \Ilch\View $this */

/** @var \Modules\Training\Models\Training $training */
$training = $this->get('training');
$userMapper = new \Modules\User\Mappers\User();

/** @var \Modules\Calendar\Mappers\Calendar $calendarMapper */
$calendarMapper = $this->get('calendarMapper');

/** @var \Modules\Training\Models\Entrants[]|null $trainEntrantsUser */
$trainEntrantsUser = $this->get('trainEntrantsUser');

/** @var int $iteration */
$iteration = $this->get('iteration');

$periodDays = [
    '1' => $this->getTranslator()->trans('Monday'),
    '2' => $this->getTranslator()->trans('Tuesday'),
    '3' => $this->getTranslator()->trans('Wednesday'),
    '4' => $this->getTranslator()->trans('Thursday'),
    '5' => $this->getTranslator()->trans('Friday'),
    '6' => $this->getTranslator()->trans('Saturday'),
    '7' => $this->getTranslator()->trans('Sunday')
];
$periodTypes = [
    'daily' => $this->getTranslator()->trans('daily'),
    'weekly' => $this->getTranslator()->trans('weekly'),
    'monthly' => $this->getTranslator()->trans('monthly'),
    'quarterly' => $this->getTranslator()->trans('quarterly'),
    'yearly' => $this->getTranslator()->trans('yearly'),
    'days' => $this->getTranslator()->trans('days'),
];

$startDate = new \Ilch\Date($calendar->getStart());
$endDate = $calendar->getEnd() != '1000-01-01 00:00:00'
    ? new \Ilch\Date($calendar->getEnd())
    : new \Ilch\Date('9999-12-31 23:59:59');
$repeatUntil = $calendar->getRepeatUntil() && $calendar->getRepeatUntil() != '1000-01-01 00:00:00'
    ? new \Ilch\Date($calendar->getRepeatUntil())
    : new \Ilch\Date('9999-12-31 23:59:59');

if ($iteration != '') {
    $recurrence = $calendarMapper->repeat($training->getPeriodType(), $startDate, $endDate, $repeatUntil, $training->getPeriodDay())[$iteration];
    $startDate = $recurrence['start'];
    $endDate = $recurrence['end'];
}

$endDate = is_numeric($endDate) || $endDate == '9999-12-31 23:59:59' ? null : $endDate;
?>

<h1><?=$this->getTrans('trainDetails') ?></h1>
<div class="row mb-3">
    <div class="col-xl-3">
        <?=$this->getTrans('title') ?>:
    </div>
    <div class="col-xl-9"><?=$this->escape($training->getTitle()) ?></div>
</div>
<div class="row mb-3">
    <div class="col-xl-3">
        <?=$this->getTrans('start') ?>:
    </div>
    <div class="col-xl-9"><?=$this->getTrans($startDate->format('l')) . $startDate->format(', d. ') . $this->getTrans($startDate->format('F')) . $startDate->format(' Y') ?> <?=$this->getTrans('at') ?> <?=$startDate->format('H:i') ?> <?=$this->getTrans('clock') ?></div>
</div>
<div class="row mb-3">
    <div class="col-xl-3">
        <?=$this->getTrans('end') ?>:
    </div>
    <div class="col-xl-9"><?=$this->getTrans($endDate->format('l')) . $endDate->format(', d. ') . $this->getTrans($endDate->format('F')) . $endDate->format(' Y') ?> <?=$this->getTrans('at') ?> <?=$endDate->format('H:i') ?> <?=$this->getTrans('clock') ?></div>
</div>
<?php if ($training->getPeriodType()) : ?>
    <div class="row mb-3">
        <div class="col-xl-3"><?=$this->getTrans('periodEntry') ?></div>
        <div class="col-xl-9">
            <?php
            echo $periodTypes[$training->getPeriodType()];
            if ($training->getPeriodType() != 'days') {
                echo ' (x ' . $training->getPeriodDay() . ')';
            } else {
                echo ' (' . $periodDays[$training->getPeriodDay()] . ')';
            }
            ?>
        </div>
    </div>
<?php endif; ?>
<div class="row mb-3">
    <div class="col-xl-3">
        <?=$this->getTrans('place') ?>:
    </div>
    <div class="col-xl-9"><?=$this->escape($training->getPlace()) ?></div>
</div>
<div class="row mb-3">
    <div class="col-xl-3">
        <?=$this->getTrans('contactPerson') ?>:
    </div>
    <?php $contactUser = $userMapper->getUserById($training->getContact()); ?>
    <div class="col-xl-9"><a href="<?=$this->getUrl('user/profil/index/user/' . $contactUser->getId()) ?>" target="_blank"><?=$this->escape($contactUser->getName()) ?></a></div>
</div>
<?php if ($training->getVoiceServer() != '') : ?>
    <?php if ($training->getVoiceServerIP() != '') : ?>
        <div class="row mb-3">
            <div class="col-xl-3">
                <?=$this->getTrans('voiceServerIP') ?>:
            </div>
            <div class="col-xl-9"><?=$this->escape($training->getVoiceServerIP()) ?></div>
        </div>
    <?php endif; ?>
    <?php if ($training->getVoiceServerPW() != '') : ?>
        <div class="row mb-3">
            <div class="col-xl-3">
                <?=$this->getTrans('voiceServerPW') ?>:
            </div>
            <div class="col-xl-9">
                <?php if ($this->getUser() && $this->get('trainEntrantUser') != '') : ?>
                    <?=$this->escape($training->getVoiceServerPW()) ?>
                <?php else : ?>
                    <?=str_repeat('&bull;', strlen($this->escape($training->getVoiceServerPW()))) ?>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
<?php endif; ?>
<?php if ($training->getGameServer() != '') : ?>
    <?php if ($training->getGameServerIP() != '') : ?>
        <div class="row mb-3">
            <div class="col-xl-3">
                <?=$this->getTrans('gameServerIP') ?>:
            </div>
            <div class="col-xl-9"><?=$this->escape($training->getGameServerIP()) ?></div>
        </div>
    <?php endif; ?>
    <?php if ($training->getGameServerPW() != '') : ?>
        <div class="row mb-3">
            <div class="col-xl-3">
                <?=$this->getTrans('gameServerPW') ?>:
            </div>
            <div class="col-xl-9">
                <?php if ($this->getUser() && $this->get('trainEntrantUser') != '') : ?>
                    <?=$this->escape($training->getGameServerPW()) ?>
                <?php else : ?>
                    <?=str_repeat('&bull;', strlen($this->escape($training->getGameServerPW()))) ?>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
<?php endif; ?>
<div class="row mb-3">
    <div class="col-xl-3">
        <?=$this->getTrans('entrant') ?>:
    </div>
    <div class="col-xl-9">
        <?=$this->getTrans('entrys') ?> <?=$trainEntrantsUserCount = count($trainEntrantsUser ?? []) ?>
        <?php if ($trainEntrantsUserCount) : ?>
            <br />
            <?php foreach ($trainEntrantsUser as $model) : ?>
                <?php $entrantsUser = $userMapper->getUserById($model->getUserId()); ?>
                <a href="<?=$this->getUrl('user/profil/index/user/' . $entrantsUser->getId()) ?>" target="_blank"><?=$this->escape($entrantsUser->getName()) ?></a>
                <?php if ($model->getNote() != '') : ?>
                    <i class="fa-solid fa-arrow-right"></i> <?=$this->escape($model->getNote()) ?>
                <?php endif; ?>
                <br />
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
<div class="row mb-3">
    <div class="col-xl-12">
        <?=$this->getTrans('otherInfo') ?>:
    </div>
    <div class="col-xl-12">
        <?php if ($training->getText() != '') : ?>
            <div class="ck-content">
                <?=$this->purify($training->getText()) ?>
            </div>
        <?php else : ?>
            <?=$this->getTrans('noOtherInfo') ?>
        <?php endif; ?>
    </div>
</div>
<?php if ($this->getUser()) : ?>
    <br />
    <h1><?=$this->getTrans('options') ?></h1>
    <form method="POST" action="">
        <?=$this->getTokenField() ?>
        <?php if ($this->get('trainEntrantUser') != '') : ?>
            <button type="submit" class="btn btn-sm btn-danger" name="del" value="del">
                <?=$this->getTrans('decline') ?>
            </button>
        <?php else : ?>
            <div class="row mb-3<?=$this->validation()->hasError('train_textarea') ? ' has-error' : '' ?>">
                <label for="otherInfo" class="col-lg-2" style="top: 7px;">
                    <?=$this->getTrans('note') ?>:
                </label>
                <div class="col-xl-4">
                <textarea class="form-control"
                          style="resize: none;"
                          id="otherInfo"
                          name="train_textarea"
                          cols="10"
                          rows="1"><?=$this->originalInput('train_textarea') ?></textarea>
                </div>
                <div class="col-xl-2" style="top: 2px;">
                    <button type="submit" class="btn btn-sm btn-success" name="save" value="save">
                        <?=$this->getTrans('join') ?>
                    </button>
                </div>
            </div>
        <?php endif; ?>
    </form>
<?php endif; ?>
