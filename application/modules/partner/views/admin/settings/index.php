<link href="<?=$this->getModuleUrl('static/css/partners.css') ?>" rel="stylesheet">

<h1><?=$this->getTrans('settings') ?></h1>
<?php
if (!$this->validation()->hasErrors()) {
    $slider = (bool)$this->get('slider') == '1';
} else {
    $slider = (bool)$this->originalInput('slider') == '1';
}
?>
<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <div class="form-group <?=$this->validation()->hasError('slider') ? 'has-error' : '' ?>">
        <div class="col-lg-2 control-label">
            <?=$this->getTrans('slider') ?>:
        </div>
        <div class="col-lg-2">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" id="slider-on" name="slider" value="1" <?php if ($slider) { echo 'checked="checked"'; } ?> />
                <label for="slider-on" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('on') ?></label>
                <input type="radio" class="flipswitch-input" id="slider-off" name="slider" value="0" <?php if (!$slider) { echo 'checked="checked"'; } ?> />
                <label for="slider-off" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('off') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
        </div>
    </div>
    <div id="contentHeight" class="<?php if (!$slider) { echo 'hidden'; } ?>">
        <div class="form-group <?=$this->validation()->hasError('boxSliderMode') ? 'has-error' : '' ?>">
            <label for="boxSliderMode" class="col-lg-2 control-label">
                <?=$this->getTrans('boxSliderMode') ?>:
            </label>
            <div class="col-lg-2">
                <select class="form-control" name="boxSliderMode">
                    <option <?php if ($this->get('boxSliderMode') == 'vertical') { echo 'selected="selected"'; } ?> value="vertical"><?=$this->getTrans('boxSliderModeVertical') ?></option>
                    <option <?php if ($this->get('boxSliderMode') == 'horizontal') { echo 'selected="selected"'; } ?> value="horizontal"><?=$this->getTrans('boxSliderModeHorizontal') ?></option>
                </select>
            </div>
        </div>
        <div class="form-group <?=$this->validation()->hasError('boxSliderHeight') ? 'has-error' : '' ?>">
            <label for="boxSliderHeight" class="col-lg-2 control-label">
                <?=$this->getTrans('boxSliderHeight') ?>:
            </label>
            <div class="col-lg-1">
                <input type="number"
                       class="form-control"
                       id="boxSliderHeight"
                       name="boxSliderHeight"
                       min="0"
                       value="<?=(!$this->validation()->hasErrors()) ? $this->get('boxSliderHeight') : $this->originalInput('boxSliderHeight') ?>">
            </div>
        </div>

        <div class="form-group <?=$this->validation()->hasError('boxSliderSpeed') ? 'has-error' : '' ?>">
            <label for="boxSliderSpeed" class="col-lg-2 control-label">
                <?=$this->getTrans('boxSliderSpeed') ?>:
            </label>
            <div class="col-lg-1">
                <input type="number"
                       class="form-control"
                       id="boxSliderSpeed"
                       name="boxSliderSpeed"
                       min="0"
                       value="<?=(!$this->validation()->hasErrors()) ? $this->get('boxSliderSpeed') : $this->originalInput('boxSliderSpeed') ?>">
            </div>
        </div>
    </div>
    <?=$this->getSaveBar() ?>
</form>

<script>
$('[name="slider"]').click(function () {
    if ($(this).val() == "1") {
        $('#contentHeight').removeClass('hidden');
    } else {
        $('#contentHeight').addClass('hidden');
    }
});
</script>
