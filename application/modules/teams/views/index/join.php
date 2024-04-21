<?php

/** @var \Ilch\View $this */

/** @var \Modules\User\Mappers\User $userMapper */
$userMapper = $this->get('userMapper');
/** @var \Modules\User\Mappers\Group $groupMapper */
$groupMapper = $this->get('groupMapper');

$userId = 0;
if ($this->getUser()) {
    $userId = $this->getUser()->getId();
}

/** @var \Modules\Teams\Models\Teams[]|null $teams */
$teams = $this->get('teams');
?>

<link href="<?=$this->getStaticUrl('js/tempus-dominus/dist/css/tempus-dominus.min.css') ?>" rel="stylesheet">

<h1><?=$this->getTrans('menuJoin') ?></h1>
<?php if ($teams) : ?>
    <form id="joinForm" name="joinForm" method="POST">
        <?=$this->getTokenField() ?>
        <div class="row mb-3 d-none">
            <label class="col-xl-2 col-form-label" for="bot">
                <?=$this->getTrans('bot') ?>*
            </label>
            <div class="col-xl-8">
                <input type="text"
                       class="form-control"
                       name="bot"
                       id="bot"
                       placeholder="Bot" />
            </div>
        </div>
        <div class="row mb-3 <?=$this->validation()->hasError('teamId') ? 'has-error' : '' ?>">
            <label for="teamId" class="col-xl-2 col-form-label">
                <?=$this->getTrans('team') ?>
            </label>
            <div class="col-xl-4">
                <select class="form-select" id="teamId" name="teamId">
                    <optgroup label="<?=$this->getTrans('teams') ?>">
                        <?php
                        foreach ($teams as $team) {
                            $groupList = $groupMapper->getUsersForGroup($team->getGroupId());
                            $leaderIds = explode(',', $team->getLeader());
                            $coLeaderIds = explode(',', $team->getCoLeader());
                            $groupList = array_unique(array_merge($leaderIds, $coLeaderIds, $groupList));

                            if ($team->getOptIn() == 1 && (!in_array($userId, $groupList) || $userId == 0)) {
                                $selected = '';
                                if ($this->originalInput('teamId') == $team->getGroupId() || $this->getRequest()->getParam('id') == $team->getId()) {
                                    $selected = 'selected="selected"';
                                }
                                echo '<option ' . $selected . ' value="' . $team->getId() . '">' . $team->getName() . '</option>';
                            }
                        }
                        ?>
                    </optgroup>
                </select>
            </div>
        </div>
        <div class="row mb-3 <?=$this->validation()->hasError('name') ? 'has-error' : '' ?>">
            <label for="name" class="col-lg-2 col-form-label">
                <?=$this->getTrans('name') ?>
            </label>
            <div class="col-xl-6">
            <?php if ($this->getUser()) : ?>
                <input type="text"
                       class="form-control"
                       id="name"
                       name="name"
                       value="<?=$this->getUser()->getName() ?>"
                       readonly />
            <?php else : ?>
                <input type="text"
                       class="form-control"
                       id="name"
                       name="name"
                       value="<?=$this->originalInput('name') ?>" />
            <?php endif; ?>
        </div>
        </div>
        <div class="row mb-3 <?=$this->validation()->hasError('email') ? 'has-error' : '' ?>">
            <label for="email" class="col-xl-2 col-form-label">
                <?=$this->getTrans('email') ?>
            </label>
            <div class="col-xl-6">
                <?php if ($this->getUser()) : ?>
                    <input type="text"
                           class="form-control"
                           id="email"
                           name="email"
                           value="<?=$this->getUser()->getEmail() ?>"
                           readonly />
                <?php else : ?>
                    <input type="text"
                           class="form-control"
                           id="email"
                           name="email"
                           value="<?=$this->originalInput('email') ?>" />
                <?php endif; ?>
            </div>
        </div>
        <div class="row mb-3">
            <label class="col-xl-2 col-form-label" for="gender">
                <?=$this->getTrans('gender') ?>
            </label>
            <div class="col-xl-2">
                <?php if ($this->getUser()) : ?>
                    <select class="form-select" id="gender" name="gender" <?=($this->getUser()->getGender() == 0) ? '' : 'disabled="disabled"' ?>>
                        <option value="1" <?=($this->getUser()->getGender() == 1) ? "selected='selected'" : '' ?>><?=$this->getTrans('genderMale') ?></option>
                        <option value="2" <?=($this->getUser()->getGender() == 2) ? "selected='selected'" : '' ?>><?=$this->getTrans('genderFemale') ?></option>
                        <option value="3" <?=($this->getUser()->getGender() == 3) ? "selected='selected'" : '' ?>><?=$this->getTrans('genderNonBinary') ?></option>
                    </select>
                <?php else : ?>
                    <select class="form-select" id="gender" name="gender">
                        <option value="1" <?=($this->originalInput('gender') != '' && $this->originalInput('gender') == 1) ? "selected='selected'" : '' ?>><?=$this->getTrans('genderMale') ?></option>
                        <option value="2" <?=($this->originalInput('gender') != '' && $this->originalInput('gender') == 2) ? "selected='selected'" : '' ?>><?=$this->getTrans('genderFemale') ?></option>
                        <option value="3" <?=($this->originalInput('gender') != '' && $this->originalInput('gender') == 3) ? "selected='selected'" : '' ?>><?=$this->getTrans('genderNonBinary') ?></option>
                    </select>
                <?php endif; ?>
            </div>
        </div>
        <div class="row mb-3 <?=$this->validation()->hasError('birthday') ? 'has-error' : '' ?>">
            <label for="age" class="col-xl-2 col-form-label" for="birthday">
                <?=$this->getTrans('birthday') ?>
            </label>
            <?php if ($this->getUser() && $this->getUser()->getBirthday() && $this->getUser()->getBirthday() != '0000-00-00') : ?>
                <div class="col-xl-2 input-group ilch-date">
                    <?php $birthday = new \Ilch\Date($this->getUser()->getBirthday()); ?>
                    <input type="text"
                           class="form-control"
                           id="birthday"
                           name="birthday"
                           value="<?=$birthday->format('d.m.Y') ?>"
                           readonly />
                    <span class="input-group-text">
                        <span class="fa-solid fa-calendar" disabled></span>
                    </span>
                </div>
            <?php else : ?>
                <div id="birthday" class="col-xl-2 input-group ilch-date date form_datetime">
                    <input type="text"
                           class="form-control"
                           id="birthday"
                           name="birthday"
                           value="<?=($this->originalInput('birthday') != '') ? $this->originalInput('birthday') : '' ?>" />
                    <span class="input-group-text">
                        <span class="fa-solid fa-calendar"></span>
                    </span>
                </div>
            <?php endif; ?>
        </div>
        <div class="row mb-3<?=$this->validation()->hasError('place') ? 'has-error' : '' ?>">
            <label for="place" class="col-xl-2 col-form-label">
                <?=$this->getTrans('place') ?>
            </label>
            <div class="col-xl-6">
                <input type="text"
                       class="form-control"
                       id="place"
                       name="place"
                       value="<?=$this->originalInput('place') ?>" />
            </div>
        </div>
        <div class="row mb-3 <?=$this->validation()->hasError('skill') ? 'has-error' : '' ?>">
            <label for="skill" class="col-xl-2 col-form-label">
                <?=$this->getTrans('skill') ?>
            </label>
            <div class="col-xl-2">
                <select class="form-select" id="skill" name="skill">
                    <option value="0" <?=($this->originalInput('skill') == 0) ? 'selected="selected"' : '' ?>><?=$this->getTrans('beginner') ?></option>
                    <option value="1" <?=($this->originalInput('skill') == 1) ? 'selected="selected"' : '' ?>><?=$this->getTrans('experience') ?></option>
                    <option value="2" <?=($this->originalInput('skill') == 2) ? 'selected="selected"' : '' ?>><?=$this->getTrans('expert') ?></option>
                    <option value="3" <?=($this->originalInput('skill') == 3) ? 'selected="selected"' : '' ?>><?=$this->getTrans('pro') ?></option>
                </select>
            </div>
        </div>
        <div class="row mb-3 <?=$this->validation()->hasError('text') ? 'has-error' : '' ?>">
            <label for="ck_1" class="col-xl-2 col-form-label">
                <?=$this->getTrans('text') ?>
            </label>
            <div class="col-xl-10">
                <textarea class="form-control ckeditor"
                          id="ck_1"
                          name="text"
                          toolbar="ilch_html_frontend"
                          rows="5"><?=$this->originalInput('text') ?></textarea>
            </div>
        </div>
        <?php if ($this->get('captchaNeeded') && $this->get('defaultcaptcha')) : ?>
            <?=$this->get('defaultcaptcha')->getCaptcha($this) ?>
        <?php endif; ?>
        <div class="row mb-3">
            <div class="offset-xl2 col-xl-8">
                <?php
                if ($this->get('captchaNeeded')) {
                    if ($this->get('googlecaptcha')) {
                        echo $this->get('googlecaptcha')->setForm('joinForm')->getCaptcha($this, 'apply', 'join');
                    } else {
                        echo $this->getSaveBar('apply', 'join');
                    }
                } else {
                    echo $this->getSaveBar('apply', 'join');
                }
                ?>
            </div>
        </div>
    </form>
    <?=$this->getDialog('mediaModal', $this->getTrans('media'), '<iframe style="border:0;"></iframe>') ?>
<?php else : ?>
    <?=$this->getTrans('noTeams') ?>
<?php endif; ?>

<script src="<?=$this->getStaticUrl('js/popper/dist/umd/popper.min.js') ?>" charset="UTF-8"></script>
<script src="<?=$this->getStaticUrl('js/tempus-dominus/dist/js/tempus-dominus.min.js') ?>" charset="UTF-8"></script>
<?php if (strncmp($this->getTranslator()->getLocale(), 'en', 2) !== 0): ?>
    <script src="<?=$this->getStaticUrl('js/tempus-dominus/dist/locales/' . substr($this->getTranslator()->getLocale(), 0, 2) . '.js') ?>" charset="UTF-8"></script>
<?php endif; ?>
<script>
$(document).ready(function() {
    new tempusDominus.TempusDominus(document.getElementById('birthday'), {
        restrictions: {
          maxDate: new Date()
        },
        display: {
            calendarWeeks: true,
            buttons: {
                today: true,
                close: true
            },
            components: {
                clock: false
            }
        },
        localization: {
            locale: "<?=substr($this->getTranslator()->getLocale(), 0, 2) ?>",
            startOfTheWeek: 1,
            format: "dd.MM.yyyy"
        }
    });
});
</script>
