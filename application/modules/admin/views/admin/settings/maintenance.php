<link href="<?=$this->getStaticUrl('js/datetimepicker/css/bootstrap-datetimepicker.min.css') ?>" rel="stylesheet">

<h1><?=$this->getTrans('menuMaintenance') ?></h1>
<form class="form-horizontal" method="POST" action="<?=$this->getUrl(['action' => $this->getRequest()->getActionName()]) ?>">
    <?=$this->getTokenField() ?>
    <div class="row form-group ilch-margin-b">
        <div class="col-lg-2 control-label">
            <?=$this->getTrans('maintenanceMode') ?>:
        </div>
        <div class="col-lg-2">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" id="maintenanceMode-on" name="maintenanceMode" value="1" <?php if ($this->get('maintenanceMode') == '1') { echo 'checked="checked"'; } ?> />
                <label for="maintenanceMode-on" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('on') ?></label>
                <input type="radio" class="flipswitch-input" id="maintenanceMode-off" name="maintenanceMode" value="0" <?php if ($this->get('maintenanceMode') != '1') { echo 'checked="checked"'; } ?> />
                <label for="maintenanceMode-off" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('off') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
        </div>
    </div>
    <div class="row form-group ilch-margin-b">
        <label for="maintenanceDateTime" class="col-md-2 control-label">
            <?=$this->getTrans('maintenanceEndDateTime') ?>:
        </label>
        <div class="col-lg-2 input-group ilch-date date form_datetime">
            <input type="text"
                   class="form-control"
                   name="maintenanceDateTime"
                   value="<?=date('d.m.Y H:i', strtotime($this->get('maintenanceDate'))) ?>"
                   readonly>
            <span class="input-group-addon">
                <span class="fa-solid fa-calendar"></span>
            </span>
        </div>
    </div>
    <div class="row form-group ilch-margin-b">
        <label for="maintenanceStatus" class="col-md-2 control-label">
            <?=$this->getTrans('maintenanceStatus') ?>:
        </label>
        <div class="col-lg-4">
            <div class="range">
                <input type="range" class="form-range" name="maintenanceStatus" min="0" max="100" value="<?=$this->escape($this->get('maintenanceStatus')) ?>" onchange="range.value=value">
                <output id="range"><?=$this->escape($this->get('maintenanceStatus')) ?></output>
            </div>
        </div>
    </div>
    <div class="row form-group ilch-margin-b">
        <label for="maintenanceText" class="col-lg-2 control-label">
            <?=$this->getTrans('maintenanceText') ?>:
        </label>
        <div class="col-lg-10">
            <textarea class="form-control ckeditor"
                      id="ck_1"
                      name="maintenanceText"
                      toolbar="ilch_html"
                      rows="5"><?=$this->escape($this->get('maintenanceText')) ?></textarea>
        </div>
    </div>
    <?=$this->getSaveBar('updateButton') ?>
</form>

<?=$this->getDialog('mediaModal', $this->getTrans('media'), '<iframe frameborder="0"></iframe>') ?>
<script src="<?=$this->getStaticUrl('js/datetimepicker/js/bootstrap-datetimepicker.min.js') ?>" charset="UTF-8"></script>
<?php if (strncmp($this->getTranslator()->getLocale(), 'en', 2) !== 0): ?>
    <script src="<?=$this->getStaticUrl('js/datetimepicker/js/locales/bootstrap-datetimepicker.'.substr($this->getTranslator()->getLocale(), 0, 2).'.js') ?>" charset="UTF-8"></script>
<?php endif; ?>
<script>
$(document).ready(function() {
    $(".form_datetime").datetimepicker({
        format: 'dd.mm.yyyy hh:ii',
        startDate: new Date(),
        autoclose: true,
        language: '<?=substr($this->getTranslator()->getLocale(), 0, 2) ?>',
        minuteStep: 15,
        todayHighlight: true
    });
});
</script>
