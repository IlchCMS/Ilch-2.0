<?php

/** @var \Ilch\View $this */
?>

<h1><?=$this->getTrans('settings') ?></h1>
<form method="POST" action="">
    <?=$this->getTokenField() ?>
    <div class="row mb-3<?=$this->validation()->hasError('showHtml') ? ' has-error' : '' ?>">
        <div class="col-xl-2 col-form-label">
            <?=$this->getTrans('showHtml') ?>:
        </div>
        <div class="col-xl-4">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" id="html-yes" name="showHtml" value="1" <?=$this->originalInput('showHtml', $this->get('linkus_html') == 1) ? 'checked="checked"' : '' ?> />
                <label for="html-yes" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('yes') ?></label>
                <input type="radio" class="flipswitch-input" id="html-no" name="showHtml" value="0" <?=$this->originalInput('showHtml', $this->get('linkus_html') != 1) ? 'checked="checked"' : '' ?> />
                <label for="html-no" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('no') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('showBBCode') ? ' has-error' : '' ?>">
        <div class="col-xl-2 col-form-label">
            <?=$this->getTrans('showBBCode') ?>:
        </div>
        <div class="col-xl-4">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" id="bbcode-yes" name="showBBCode" value="1" <?=$this->originalInput('showBBCode', $this->get('showBBCode') == 1) ? 'checked="checked"' : '' ?> />
                <label for="bbcode-yes" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('yes') ?></label>
                <input type="radio" class="flipswitch-input" id="bbcode-no" name="showBBCode" value="0" <?=$this->originalInput('showBBCode', $this->get('showBBCode') != 1) ? 'checked="checked"' : '' ?> />
                <label for="bbcode-no" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('no') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
        </div>
    </div>
    <?=$this->getSaveBar() ?>
</form>

<script>
$('form').submit(function( event ) {
    if (!$('#html-yes').is(':checked') && !$('#bbcode-yes').is(':checked')) {
        event.preventDefault();
        alert('<?=$this->getTrans('noneEnabled') ?>');
    }
});
</script>
