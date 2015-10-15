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
                <input type="radio" class="flipswitch-input" name="slider" value="1" id="slider-on" <?php if ($this->get('slider') == '1') { echo 'checked="checked"'; } ?> />
                <label for="slider-on" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('on') ?></label>
                <input type="radio" class="flipswitch-input" name="slider" value="0" id="slider-off" <?php if ($this->get('slider') != '1') { echo 'checked="checked"'; } ?> />
                <label for="slider-off" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('off') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
        </div>
    </div>
    <div id="contentHeight" class="<?php if($this->get('slider') != '1') { echo 'hidden'; } ?>">
        <div class="form-group">
            <label for="boxHeight" class="col-lg-2 control-label">
                <?=$this->getTrans('boxSliderHeight') ?>:
            </label>
            <div class="col-lg-2 input-group">
                <div class="container">
                    <div class="input-group spinner">
                        <input class="form-control"
                               type="text"
                               name="boxHeight"
                               value="<?=$this->get('boxHeight') ?>">
                        <div class="input-group-btn-vertical">
                            <span class="btn btn-default"><i class="fa fa-caret-up"></i></span>
                            <span class="btn btn-default"><i class="fa fa-caret-down"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="sliderSpeed" class="col-lg-2 control-label">
                <?=$this->getTrans('boxSliderSpeed') ?>:
            </label>
            <div class="col-lg-2 input-group">
                <div class="container">
                    <div class="input-group spinner">
                        <input class="form-control"
                               type="text"
                               name="sliderSpeed"
                               value="<?=$this->get('sliderSpeed') ?>">
                        <div class="input-group-btn-vertical">
                            <span class="btn btn-default"><i class="fa fa-caret-up"></i></span>
                            <span class="btn btn-default"><i class="fa fa-caret-down"></i></span>
                        </div>
                    </div>
                </div>
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

$(function() {
    $('.spinner .btn:first-of-type').on('click', function() {
        var btn = $(this);
        var input = btn.closest('.spinner').find('input');
        if (input.attr('max') == undefined || parseInt(input.val()) < parseInt(input.attr('max'))) {
            input.val(parseInt(input.val(), 10) + 1);
        } else {
            btn.next("disabled", true);
        }
    });
    $('.spinner .btn:last-of-type').on('click', function() {
        var btn = $(this);
        var input = btn.closest('.spinner').find('input');
        if (input.attr('min') == undefined || parseInt(input.val()) > parseInt(input.attr('min'))) {
            input.val(parseInt(input.val(), 10) - 1);
        } else {
            btn.prev("disabled", true);
        }
    });
})
</script>
