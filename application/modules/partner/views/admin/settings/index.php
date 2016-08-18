<link href="<?=$this->getModuleUrl('static/css/partners.css') ?>" rel="stylesheet">

<legend><?=$this->getTrans('settings') ?></legend>
<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <div class="form-group">
        <label for="slider" class="col-lg-2 control-label">
            <?=$this->getTrans('slider') ?>:
        </label>
        <div class="col-lg-2">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" id="slider-on" name="slider" value="1" <?php if ($this->get('slider') == '1') { echo 'checked="checked"'; } ?> />
                <label for="slider-on" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('on') ?></label>
                <input type="radio" class="flipswitch-input" id="slider-off" name="slider" value="0" <?php if ($this->get('slider') != '1') { echo 'checked="checked"'; } ?> />
                <label for="slider-off" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('off') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
        </div>
    </div>
    <div id="contentHeight" class="<?php if ($this->get('slider') != '1') { echo 'hidden'; } ?>">
        <div class="form-group">
            <label for="boxHeight" class="col-lg-2 control-label">
                <?=$this->getTrans('boxSliderHeight') ?>:
            </label>
            <div class="col-lg-1">
                <input type="number"
                       class="form-control"
                       id="boxHeight"
                       name="boxHeight"
                       min="0"
                       value="<?=$this->get('boxHeight') ?>">
            </div>
        </div>

        <div class="form-group">
            <label for="sliderSpeed" class="col-lg-2 control-label">
                <?=$this->getTrans('boxSliderSpeed') ?>:
            </label>
            <div class="col-lg-1">
                <input type="number"
                       class="form-control"
                       id="sliderSpeed"
                       name="sliderSpeed"
                       min="0"
                       value="<?=$this->get('sliderSpeed') ?>">
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
