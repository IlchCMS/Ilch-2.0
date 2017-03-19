<?php
$userMapper = $this->get('userMapper');

if ($this->getUser()) {
    $user = $userMapper->getUserById($this->getUser()->getId());
}
?>

<h1><?=$this->getTrans('menuJoin') ?></h1>
<?php if ($this->get('teams') != ''): ?>
    <form class="form-horizontal" method="POST" action="">
        <?=$this->getTokenField() ?>
        <div class="form-group <?=$this->validation()->hasError('teamId') ? 'has-error' : '' ?>">
            <label for="teamId" class="col-lg-2 control-label">
                <?=$this->getTrans('team') ?>:
            </label>
            <div class="col-lg-4">
                <select class="form-control" id="teamId" name="teamId">
                    <optgroup label="<?=$this->getTrans('teams') ?>">
                        <?php foreach ($this->get('teams') as $teamList): ?>
                            <?php if ($teamList->getOptIn() == 1): ?>
                                <?php $selected = ''; ?>
                                <?php if ($this->originalInput('teamId') == $teamList->getId() OR $this->getRequest()->getParam('id') == $teamList->getId()): ?>
                                    <?php $selected = 'selected="selected"'; ?>
                                <?php endif; ?>
                                <option <?=$selected ?> value="<?=$teamList->getId() ?>"><?=$teamList->getName() ?></option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </optgroup>
                </select>
            </div>
        </div>
        <div class="form-group <?=$this->validation()->hasError('name') ? 'has-error' : '' ?>">
            <label for="name" class="col-md-2 control-label">
                <?=$this->getTrans('name') ?>:
            </label>
            <div class="col-lg-6">
            <?php if ($this->getUser()): ?>
                <input type="text"
                       class="form-control"
                       id="name"
                       name="name"
                       value="<?=$user->getName() ?>"
                       readonly />
            <?php else: ?>
                <input type="text"
                       class="form-control"
                       id="name"
                       name="name"
                       value="<?=$this->originalInput('name') ?>" />
            <?php endif; ?>
        </div>
        </div>
        <div class="form-group <?=$this->validation()->hasError('email') ? 'has-error' : '' ?>">
            <label for="email" class="col-lg-2 control-label">
                <?=$this->getTrans('email') ?>:
            </label>
            <div class="col-lg-6">
                <?php if ($this->getUser()): ?>
                    <input type="text"
                           class="form-control"
                           id="email"
                           name="email"
                           value="<?=$user->getEmail() ?>"
                           readonly />
                <?php else: ?>
                    <input type="text"
                           class="form-control"
                           id="email"
                           name="email"
                           value="<?=$this->originalInput('email') ?>" />
                <?php endif; ?>
            </div>
        </div>
        <div class="form-group <?=$this->validation()->hasError('age') ? 'has-error' : '' ?>">
            <label for="age" class="col-lg-2 control-label">
                <?=$this->getTrans('age') ?>:
            </label>
            <div class="col-lg-6">
                <input type="number"
                       class="form-control"
                       id="age"
                       name="age"
                       value="<?=$this->originalInput('age') ?>" />
            </div>
        </div>
        <div class="form-group <?=$this->validation()->hasError('place') ? 'has-error' : '' ?>">
            <label for="place" class="col-lg-2 control-label">
                <?=$this->getTrans('place') ?>:
            </label>
            <div class="col-lg-6">
                <input type="text"
                       class="form-control"
                       id="place"
                       name="place"
                       value="<?=$this->originalInput('place') ?>" />
            </div>
        </div>
        <div class="form-group <?=$this->validation()->hasError('skill') ? 'has-error' : '' ?>">
            <label for="skill" class="col-lg-2 control-label">
                <?=$this->getTrans('skill') ?>:
            </label>
            <div class="col-lg-2">
                <select class="form-control" id="skill" name="skill">
                    <option value="0" <?=($this->originalInput('skill') == 0) ? 'selected="selected"' : '' ?>><?=$this->getTrans('beginner') ?></option>
                    <option value="1" <?=($this->originalInput('skill') == 1) ? 'selected="selected"' : '' ?>><?=$this->getTrans('experience') ?></option>
                    <option value="2" <?=($this->originalInput('skill') == 2) ? 'selected="selected"' : '' ?>><?=$this->getTrans('expert') ?></option>
                    <option value="3" <?=($this->originalInput('skill') == 3) ? 'selected="selected"' : '' ?>><?=$this->getTrans('pro') ?></option>
                </select>
            </div>
        </div>
        <div class="form-group <?=$this->validation()->hasError('text') ? 'has-error' : '' ?>">
            <label for="ck_1" class="col-lg-2 control-label">
                <?=$this->getTrans('text') ?>:
            </label>
            <div class="col-lg-10">
                <textarea class="form-control ckeditor"
                          id="ck_1"
                          name="text"
                          toolbar="ilch_bbcode"
                          rows="5"><?=$this->originalInput('text') ?></textarea>
            </div>
        </div>
        <?php if (!$this->getUser()): ?>
            <div class="form-group <?=$this->validation()->hasError('captcha') ? 'has-error' : '' ?>">
                <label class="col-lg-2 control-label">
                    <?=$this->getTrans('captcha') ?>:
                </label>
                <div class="col-lg-8">
                    <?=$this->getCaptchaField() ?>
                </div>
            </div>
            <div class="form-group <?=$this->validation()->hasError('captcha') ? 'has-error' : '' ?>">
                <div class="col-lg-offset-2 col-lg-8 input-group captcha">
                    <input type="text"
                           class="form-control"
                           id="captcha-form"
                           name="captcha"
                           autocomplete="off"
                           placeholder="<?=$this->getTrans('captcha') ?>" />
                    <span class="input-group-addon">
                            <a href="javascript:void(0)" onclick="
                                    document.getElementById('captcha').src='<?=$this->getUrl() ?>/application/libraries/Captcha/Captcha.php?'+Math.random();
                                    document.getElementById('captcha-form').focus();"
                               id="change-image">
                                <i class="fa fa-refresh"></i>
                            </a>
                        </span>
                </div>
            </div>
        <?php endif; ?>
        <div class="form-group">
            <div class="col-lg-offset-2 col-lg-8">
                <?=$this->getSaveBar('apply', 'join') ?>
            </div>
        </div>
    </form>
<?php else: ?>
    <?=$this->getTrans('noTeams') ?>
<?php endif; ?>

<?=$this->getDialog("smiliesModal", $this->getTrans('smilies'), "<iframe frameborder='0'></iframe>"); ?>
