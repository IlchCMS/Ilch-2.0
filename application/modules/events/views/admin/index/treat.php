<link href="<?=$this->getModuleUrl('static/css/events.css') ?>" rel="stylesheet">
<link href="<?=$this->getStaticUrl('js/datetimepicker/css/bootstrap-datetimepicker.min.css') ?>" rel="stylesheet">
<style>
.input-group > .input-group-addon:not(:last-child):not(:nth-last-child(2)) {
    border-right-width: 0;
}
.input-group > :not(.input-group-addon):not(.input-group-btn) + .input-group-addon {
    border-left-width: 0;
}
.input-group > .input-group-btn:not(:first-child) > .btn,
.input-group > .input-group-btn:not(:first-child) > .btn-group > .btn {
    margin-left: -1px;
    margin-right: -1px;
}
.input-group > .input-group-btn:not(:first-child):not(:last-child) > .btn,
.input-group > .input-group-btn:not(:first-child):not(:last-child) > .btn-group > .btn {
    border-radius: 0;
}
</style>

<form class="form-horizontal" method="POST" action="<?=$this->getUrl(array('action' => $this->getRequest()->getActionName(), 'id' => $this->getRequest()->getParam('id'))) ?>">
    <?=$this->getTokenField() ?>
    <legend>
        <?php if ($this->get('event') != ''): ?>
            <?=$this->getTrans('edit') ?>
        <?php else: ?>
            <?=$this->getTrans('add') ?>
        <?php endif; ?>
    </legend>
    <div class="form-group">
        <label for="start" class="col-md-2 control-label">
            <?=$this->getTrans('startTime') ?>:
        </label>
        <div class="col-lg-3 input-group date form_datetime">
            <input class="form-control"
                   type="text"
                   id="start"
                   name="start"
                   value="<?php if ($this->get('event') != '') { echo date('d.m.Y H:i', strtotime($this->get('event')->getStart())); } ?>"
                   readonly>
            <span class="input-group-addon">
                <span class="fa fa-calendar"></span>
            </span>
        </div>
    </div>
    <div class="form-group">
        <label for="end" class="col-md-2 control-label">
            <?=$this->getTrans('endTime') ?>:
        </label>
        <div class="col-lg-3 input-group date form_datetime">
            <input class="form-control"
                   type="text"
                   id="end"
                   name="end"
                   value="<?php if ($this->get('event') != '' AND $this->get('event')->getEnd() != '0000-00-00 00:00:00') { echo date('d.m.Y H:i', strtotime($this->get('event')->getEnd())); } ?>"
                   readonly>
            <span class="input-group-addon">
                <span class="fa fa-times"></span>
            </span>
            <span class="input-group-addon">
                <span class="fa fa-calendar"></span>
            </span>
        </div>
    </div>
    <div class="form-group">
        <label for="title" class="col-lg-2 control-label">
            <?=$this->getTrans('title') ?>:
        </label>
        <div class="col-lg-4">
            <input class="form-control"
                   type="text"
                   name="title"
                   id="title"
                   value="<?php if ($this->get('event') != '') { echo $this->escape($this->get('event')->getTitle()); } ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="place" class="col-lg-2 control-label">
            <?=$this->getTrans('place') ?>:
        </label>
        <div class="col-lg-4">
            <input class="form-control"
                   type="text"
                   name="place"
                   id="place"
                   value="<?php if ($this->get('event') != '') { echo $this->escape($this->get('event')->getPlace()); } ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="text" class="col-lg-2 control-label">
            <?=$this->getTrans('text') ?>:
        </label>
        <div class="col-lg-10">
            <textarea class="form-control ckeditor"
                      name="text"
                      id="ck_1"
                      toolbar="ilch_html"
                      rows="5"><?php if ($this->get('event') != '') { echo $this->escape($this->get('event')->getText()); } ?></textarea>
        </div>
    </div>
    <?php if ($this->get('calendarShow') == 1): ?>
        <div class="form-group">
            <div class="col-lg-offset-2 col-lg-10">
                <input value="1" type="checkbox" name="calendarShow" id="calendarShow" <?php if ($this->get('event') != '' AND $this->get('event')->getShow() == 1) { echo 'checked'; } ?> />
                <label for="calendarShow">
                    <?=$this->getTrans('calendarShow') ?>
                </label>
            </div>
        </div>
    <?php endif; ?>
    <?php if ($this->get('event') != ''): ?>
        <?=$this->getSaveBar('updateButton') ?>
    <?php else: ?>
        <?=$this->getSaveBar('addButton') ?>
    <?php endif; ?>
</form>

<script type="text/javascript" src="<?=$this->getStaticUrl('js/datetimepicker/js/bootstrap-datetimepicker.js')?>" charset="UTF-8"></script>
<script type="text/javascript" src="<?=$this->getStaticUrl('js/datetimepicker/js/locales/bootstrap-datetimepicker.'.substr($this->getTranslator()->getLocale(), 0, 2).'.js')?>" charset="UTF-8"></script>
<script type="text/javascript">
$(document).ready(function() {
    $(".form_datetime").datetimepicker({
        format: "dd.mm.yyyy hh:ii",
        startDate: new Date(),
        autoclose: true,
        language: '<?=substr($this->getTranslator()->getLocale(), 0, 2) ?>',
        minuteStep: 15,
        todayHighlight: true,
        linkField: "end",
        linkFormat: "dd.mm.yyyy hh:ii"
    });
});
</script>
