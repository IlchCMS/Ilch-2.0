<legend><?=$this->getTrans('settings') ?></legend>
<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <div class="form-group">
        <div class="col-lg-2 control-label">
            <?=$this->getTrans('showHtml') ?>:
        </div>
        <div class="col-lg-4">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" id="html-yes" name="html" value="1" <?php if ($this->get('linkus_html') == '1') { echo 'checked="checked"'; } ?> />
                <label for="html-yes" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('yes') ?></label>
                <input type="radio" class="flipswitch-input" id="html-no" name="html" value="0" <?php if ($this->get('linkus_html') != '1') { echo 'checked="checked"'; } ?> />
                <label for="html-no" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('no') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-2 control-label">
            <?=$this->getTrans('showBBBCode') ?>:
        </div>
        <div class="col-lg-4">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" id="bbcode-yes" name="bbcode" value="1" <?php if ($this->get('linkus_bbcode') == '1') { echo 'checked="checked"'; } ?> />
                <label for="bbcode-yes" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('yes') ?></label>
                <input type="radio" class="flipswitch-input" id="bbcode-no" name="bbcode" value="0" <?php if ($this->get('linkus_bbcode') != '1') { echo 'checked="checked"'; } ?> />
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
