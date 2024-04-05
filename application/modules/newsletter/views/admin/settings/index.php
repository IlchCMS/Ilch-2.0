<?php

/** @var \Ilch\View $this */
?>
<h1><?=$this->getTrans('settings') ?></h1>
<form method="POST">
    <?=$this->getTokenField() ?>
    <p><?=$this->getTrans('doubleOptInInfo') ?></p>
    <div class="row mb-3">
        <div class="col-lg-2 col-form-label">
            <?=$this->getTrans('doubleOptIn') ?>:
        </div>
        <div class="col-lg-4">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" id="doubleOptIn-on" name="doubleOptIn" value="1" <?=($this->get('doubleOptIn') == '1') ? 'checked="checked"' : '' ?> />
                <label for="doubleOptIn-on" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('on') ?></label>
                <input type="radio" class="flipswitch-input" id="doubleOptIn-off" name="doubleOptIn" value="0" <?=($this->get('doubleOptIn') != '1') ? 'checked="checked"' : '' ?> />
                <label for="doubleOptIn-off" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('off') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
        </div>
    </div>

    <?=$this->getSaveBar() ?>
</form>
