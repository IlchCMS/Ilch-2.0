<legend><?=$this->getTrans('settings') ?></legend>
<?php if (!empty($this->get('errors'))): ?>
    <div class="alert alert-danger" role="alert">
        <strong> <?=$this->getTrans('errorsOccured') ?>:</strong>
        <ul>
            <?php foreach ($this->get('errors') as $error): ?>
                <li><?= $error; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
<?php
if (empty($this->get('errorFields'))) {
    $showHtml = (bool)$this->get('linkus_html') == '1';
    $showBBCode = (bool)$this->get('linkus_bbcode') == '1';
} else {
    $showHtml = (bool)$this->get('post')['showHtml'] == '1';
    $showBBCode = (bool)$this->get('post')['showBBCode'] == '1';
}
?>
<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <div class="form-group<?=in_array('showHtml', $this->get('errorFields')) ? ' has-error' : '' ?>">
        <div class="col-lg-2 control-label">
            <?=$this->getTrans('showHtml') ?>:
        </div>
        <div class="col-lg-4">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" id="html-yes" name="showHtml" value="1" <?php if ($showHtml) { echo 'checked="checked"'; } ?> />
                <label for="html-yes" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('yes') ?></label>
                <input type="radio" class="flipswitch-input" id="html-no" name="showHtml" value="0" <?php if (!$showHtml) { echo 'checked="checked"'; } ?> />
                <label for="html-no" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('no') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
        </div>
    </div>
    <div class="form-group<?=in_array('showBBCode', $this->get('errorFields')) ? ' has-error' : '' ?>">
        <div class="col-lg-2 control-label">
            <?=$this->getTrans('showBBCode') ?>:
        </div>
        <div class="col-lg-4">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" id="bbcode-yes" name="showBBCode" value="1" <?php if ($showBBCode) { echo 'checked="checked"'; } ?> />
                <label for="bbcode-yes" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('yes') ?></label>
                <input type="radio" class="flipswitch-input" id="bbcode-no" name="showBBCode" value="0" <?php if (!$showBBCode) { echo 'checked="checked"'; } ?> />
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
