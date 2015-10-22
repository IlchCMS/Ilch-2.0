<?php
$adate = new \Ilch\Date(); 
$history = $this->get('history');

if ($history != '') {
    $getDate = new \Ilch\Date($history->getDate());
    $date = $getDate->format('d.m.Y', true);
}
?>

<link rel="stylesheet" href="<?=$this->getModuleUrl('static/css/history.css') ?>">
<link href="<?=$this->getStaticUrl('js/datetimepicker/css/bootstrap-datetimepicker.min.css') ?>" rel="stylesheet">

<form class="form-horizontal" method="POST" action="<?=$this->getUrl(array('action' => $this->getRequest()->getActionName(), 'id' => $this->getRequest()->getParam('id'))) ?>">
    <?=$this->getTokenField() ?>
    <legend>
        <?php if ($this->get('history') != ''): ?>
            <?=$this->getTrans('edit') ?>
        <?php else: ?>
            <?=$this->getTrans('add') ?>
        <?php endif; ?>
    </legend>
    <div class="form-group">
        <label for="date" class="col-lg-2 control-label">
            <?=$this->getTrans('date') ?>:
        </label>
        <div class="col-lg-2 input-group date date form_datetime">
            <input class="form-control"
                   type="text"
                   name="date"
                   value="<?php if ($this->get('history') != '') { echo $date; } ?>"
                   readonly>
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
                   value="<?php if ($this->get('history') != '') { echo $this->escape($this->get('history')->getTitle()); } ?>" />
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
                   rows="5"><?php if ($this->get('history') != '') { echo $this->escape($this->get('history')->getText()); } ?></textarea>
        </div>
    </div>
    <div class="form-group">
        <label for="typ" class="col-lg-2 control-label">
            <?=$this->getTrans('symbol') ?>:
        </label>
        <div class="col-lg-2">
            <select class="form-control fontawesome-select" name="typ" id="typ">
                <option value="" <?php if ($this->get('history') != '' AND $this->get('history')->getTyp() == '') { echo 'selected="selected"'; } ?>><?=$this->getTrans('noSelect') ?></option>
                <option value="globe" <?php if ($this->get('history') != '' AND $this->get('history')->getTyp() == 'globe') { echo 'selected="selected"'; } ?>>&#xf0ac; <?=$this->getTrans('globeSelect') ?></option>
                <option value="idea" <?php if ($this->get('history') != '' AND $this->get('history')->getTyp() == 'idea') { echo 'selected="selected"'; } ?>>&#xf0eb; <?=$this->getTrans('ideaSelect') ?></option>
                <option value="cap" <?php if ($this->get('history') != '' AND $this->get('history')->getTyp() == 'cap') { echo 'selected="selected"'; } ?>>&#xf19d; <?=$this->getTrans('capSelect') ?></option>
                <option value="picture" <?php if ($this->get('history') != '' AND $this->get('history')->getTyp() == 'picture') { echo 'selected="selected"'; } ?>>&#xf030; <?=$this->getTrans('pictureSelect') ?></option>
                <option value="video" <?php if ($this->get('history') != '' AND $this->get('history')->getTyp() == 'video') { echo 'selected="selected"'; } ?>>&#xf03d; <?=$this->getTrans('videoSelect') ?></option>
                <option value="location" <?php if ($this->get('history') != '' AND $this->get('history')->getTyp() == 'location') { echo 'selected="selected"'; } ?>>&#xf041; <?=$this->getTrans('locationSelect') ?></option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label for="color" class="col-lg-2 control-label">
            <?=$this->getTrans('color') ?>:
        </label>
        <div class="col-lg-2 input-group date">
            <input class="form-control color {hash:true}"
                   name="color"
                   id="color"
                   value="<?php if ($this->get('history') != '') { echo $this->get('history')->getColor(); } else { echo '#75ce66'; } ?>">
            <span class="input-group-addon">
                <span class="fa fa-undo" onclick="document.getElementById('color').color.fromString('75ce66')"></span>
            </span>
        </div>
    </div>
    <?php if ($this->get('history') != ''): ?>
        <?=$this->getSaveBar('updateButton') ?>
    <?php else: ?>
        <?=$this->getSaveBar('addButton') ?>
    <?php endif; ?>
</form>

<script type="text/javascript" src="<?=$this->getStaticUrl('js/jscolor/jscolor.js') ?>"></script>
<script type="text/javascript" src="<?=$this->getStaticUrl('js/datetimepicker/js/bootstrap-datetimepicker.js')?>" charset="UTF-8"></script>
<script type="text/javascript" src="<?=$this->getStaticUrl('js/datetimepicker/js/locales/bootstrap-datetimepicker.'.substr($this->getTranslator()->getLocale(), 0, 2).'.js')?>" charset="UTF-8"></script>
<script type="text/javascript">
$(document).ready(function() {
    $(".form_datetime").datetimepicker({
        format: "dd.mm.yyyy",
        autoclose: true,
        language: '<?=substr($this->getTranslator()->getLocale(), 0, 2) ?>',
        minView: 2,
        todayHighlight: true
    });
});
</script>
