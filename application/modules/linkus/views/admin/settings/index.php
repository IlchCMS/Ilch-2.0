<?php
if (!$this->validation()->hasErrors()) {
    $showHtml = (bool)$this->get('linkus_html') == '1';
    $showBBCode = (bool)$this->get('linkus_bbcode') == '1';
} else {
    $showHtml = (bool)$this->get('post')['showHtml'] == '1';
    $showBBCode = (bool)$this->get('post')['showBBCode'] == '1';
}
?>

<h1><?=$this->getTrans('settings') ?></h1>
<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <div class="row form-group ilch-margin-b <?=$this->validation()->hasError('showHtml') ? 'has-error' : '' ?>">
        <div class="col-lg-2 control-label">
            <?=$this->getTrans('showHtml') ?>:
        </div>
        <div class="col-lg-4">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" id="html-yes" name="showHtml" value="1" <?=($showHtml) ? 'checked="checked"' : '' ?> />
                <label for="html-yes" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('yes') ?></label>
                <input type="radio" class="flipswitch-input" id="html-no" name="showHtml" value="0" <?=(!$showHtml) ? 'checked="checked"' : '' ?> />
                <label for="html-no" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('no') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
        </div>
    </div>
    <div class="row form-group ilch-margin-b <?=$this->validation()->hasError('showBBCode') ? 'has-error' : '' ?>">
        <div class="col-lg-2 control-label">
            <?=$this->getTrans('showBBCode') ?>:
        </div>
        <div class="col-lg-4">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" id="bbcode-yes" name="showBBCode" value="1" <?=($showBBCode) ? 'checked="checked"' : '' ?> />
                <label for="bbcode-yes" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('yes') ?></label>
                <input type="radio" class="flipswitch-input" id="bbcode-no" name="showBBCode" value="0" <?=(!$showBBCode) ? 'checked="checked"' : '' ?> />
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
