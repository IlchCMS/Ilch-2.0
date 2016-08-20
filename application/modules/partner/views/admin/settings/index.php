<link href="<?=$this->getModuleUrl('static/css/partners.css') ?>" rel="stylesheet">

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
    $slider = (bool)$this->get('slider') == '1';
} else {
    $slider = (bool)$this->get('post')['slider'] == '1';
}
?>
<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <div class="form-group<?=in_array('slider', $this->get('errorFields')) ? ' has-error' : '' ?>">
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
        <div class="form-group<?=in_array('boxSliderHeight', $this->get('errorFields')) ? ' has-error' : '' ?>">
            <label for="boxSliderHeight" class="col-lg-2 control-label">
                <?=$this->getTrans('boxSliderHeight') ?>:
            </label>
            <div class="col-lg-1">
                <input type="number"
                       class="form-control"
                       id="boxSliderHeight"
                       name="boxSliderHeight"
                       min="0"
                       value="<?=(empty($this->get('errorFields'))) ? $this->get('boxSliderHeight') : $this->get('post')['boxSliderHeight'] ?>">
            </div>
        </div>

        <div class="form-group<?=in_array('boxSliderSpeed', $this->get('errorFields')) ? ' has-error' : '' ?>">
            <label for="boxSliderSpeed" class="col-lg-2 control-label">
                <?=$this->getTrans('boxSliderSpeed') ?>:
            </label>
            <div class="col-lg-1">
                <input type="number"
                       class="form-control"
                       id="boxSliderSpeed"
                       name="boxSliderSpeed"
                       min="0"
                       value="<?=(empty($this->get('errorFields'))) ? $this->get('boxSliderSpeed') : $this->get('post')['boxSliderSpeed'] ?>">
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
