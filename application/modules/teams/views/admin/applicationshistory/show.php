<?php
$joinsMapper = $this->get('joinsMapper');
$teamsMapper = $this->get('teamsMapper');
?>

<h1><?=$this->getTrans('application') ?></h1>
<?php if ($this->get('join')): ?>
    <?php $join = $this->get('join'); ?>
    <?php $team = $teamsMapper->getTeamById($join->getTeamId()); ?>
    <?php $date = new Ilch\Date($join->getDateCreated()); ?>
    <?php $birthday = new Ilch\Date($join->getBirthday()); ?>
    <div class="form-horizontal">
        <div class="form-group">
            <label class="col-lg-2">
                <?=$this->getTrans('name') ?>:
            </label>
            <div class="col-lg-2">
                <?=$this->escape($join->getName()) ?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-2">
                <?=$this->getTrans('team') ?>:
            </label>
            <div class="col-lg-2">
                <?=(!empty($team)) ? $this->escape($team->getName()) : $this->getTrans('noTeam') ?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-2">
                <?=$this->getTrans('email') ?>:
            </label>
            <div class="col-lg-2">
                <?=$this->escape($join->getEMail()) ?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-2">
                <?=$this->getTrans('dateTime') ?>:
            </label>
            <div class="col-lg-2">
                <?=$date->format('d.m.Y H:i', true) ?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-2">
                <?=$this->getTrans('gender') ?>:
            </label>
            <div class="col-lg-2">
                <?php if ($join->getGender() == 1) {
                    echo $this->getTrans('genderMale');
                } elseif ($join->getGender() == 2) {
                    echo $this->getTrans('genderFemale');
                } else {
                    echo $this->getTrans('genderNonBinary');
                } ?>
            </div>
        </div>
        <?php if ($join->getBirthday()): ?>
            <div class="form-group">
                <label class="col-lg-2">
                    <?=$this->getTrans('birthday') ?>:
                </label>
                <div class="col-lg-2">
                    <?=$birthday->format('d.m.Y') ?> (<?=$joinsMapper->getAge($birthday) ?>)
                </div>
            </div>
        <?php endif; ?>
        <?php if ($join->getPlace()): ?>
            <div class="form-group">
                <label class="col-lg-2">
                    <?=$this->getTrans('place') ?>:
                </label>
                <div class="col-lg-2">
                    <?=$this->escape($join->getPlace()) ?>
                </div>
            </div>
        <?php endif; ?>
        <div class="form-group">
            <label class="col-lg-2">
                <?=$this->getTrans('skill') ?>:
            </label>
            <div class="col-lg-2">
                <?php
                if ($join->getSkill() == 0) {
                    echo $this->getTrans('beginner');
                } elseif ($join->getSkill() == 1) {
                    echo $this->getTrans('experience');
                } elseif ($join->getSkill() == 2) {
                    echo $this->getTrans('expert');
                } elseif ($join->getSkill() == 3) {
                    echo $this->getTrans('pro');
                }
                ?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-2">
                <?=$this->getTrans('text') ?>:
            </label>
            <div class="col-lg-12">
                <?=nl2br($this->getHtmlFromBBCode($this->escape($join->getText()))) ?>
            </div>
        </div>
    </div>
<?php endif; ?>
