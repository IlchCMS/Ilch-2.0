<?php $teamsMapper = $this->get('teamsMapper'); ?>

<legend><?=$this->getTrans('application') ?></legend>
<?php if ($this->get('join')): ?>
    <?php $join = $this->get('join'); ?>
    <div class="form-horizontal">
        <div class="form-group">
            <label class="col-lg-2">
                <?=$this->getTrans('name') ?>:
            </label>
            <div class="col-lg-2">
                <?=$join->getName() ?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-2">
                <?=$this->getTrans('email') ?>:
            </label>
            <div class="col-lg-2">
                <?=$join->getEMail() ?>
            </div>
        </div>
        <?php if ($join->getAge()): ?>
            <div class="form-group">
                <label class="col-lg-2">
                    <?=$this->getTrans('age') ?>:
                </label>
                <div class="col-lg-2">
                    <?=$join->getAge() ?>
                </div>
            </div>
        <?php endif; ?>
        <?php if ($join->getPlace()): ?>
            <div class="form-group">
                <label class="col-lg-2">
                    <?=$this->getTrans('place') ?>:
                </label>
                <div class="col-lg-2">
                    <?=$join->getPlace() ?>
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
                <?=nl2br($this->getHtmlFromBBCode($join->getText())) ?>
            </div>
        </div>

        <div class="content_savebox">
            <a href="<?=$this->getUrl(['action' => 'accept', 'id' => $join->getId()], null, true) ?>" class="save_button btn btn-default">
                <?=$this->getTrans('accept') ?>
            </a>
            <a href="<?=$this->getUrl(['action' => 'reject', 'id' => $join->getId()], null, true) ?>" class="delete_button btn btn-default">
                <?=$this->getTrans('reject') ?>
            </a>
        </div>
    </div>
<?php endif; ?>
