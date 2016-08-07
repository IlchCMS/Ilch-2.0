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

<legend>
    <?php if ($history != '') {
        echo $this->getTrans('edit');
    } else {
        echo $this->getTrans('add');
    }
    ?>
</legend>
<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <div class="form-group">
        <label for="date" class="col-lg-2 control-label">
            <?=$this->getTrans('date') ?>:
        </label>
        <div class="col-lg-2 input-group date date form_datetime">
            <input type="text"
                   class="form-control"
                   name="date"
                   value="<?php if ($history != '') { echo $date; } ?>"
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
            <input type="text"
                   class="form-control"
                   id="title"
                   name="title"
                   value="<?php if ($history != '') { echo $this->escape($history->getTitle()); } ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="text" class="col-lg-2 control-label">
            <?=$this->getTrans('text') ?>:
        </label>
        <div class="col-lg-10">
            <textarea class="form-control ckeditor"
                      id="ck_1"
                      name="text"
                      toolbar="ilch_html"
                      rows="5"><?php if ($history != '') { echo $this->escape($history->getText()); } ?></textarea>
        </div>
    </div>
    <div class="form-group">
        <label for="type" class="col-lg-2 control-label">
            <?=$this->getTrans('symbol') ?>:
        </label>
        <div class="col-lg-2">
            <select class="form-control fontawesome-select" id="type" name="type">
                <option value="" <?php if ($history != '' AND $history->getType() == '') { echo 'selected="selected"'; } ?>><?=$this->getTrans('noSelect') ?></option>
                <option value="globe" <?php if ($history != '' AND $history->getType() == 'globe') { echo 'selected="selected"'; } ?>>&#xf0ac; <?=$this->getTrans('globeSelect') ?></option>
                <option value="idea" <?php if ($history != '' AND $history->getType() == 'idea') { echo 'selected="selected"'; } ?>>&#xf0eb; <?=$this->getTrans('ideaSelect') ?></option>
                <option value="cap" <?php if ($history != '' AND $history->getType() == 'cap') { echo 'selected="selected"'; } ?>>&#xf19d; <?=$this->getTrans('capSelect') ?></option>
                <option value="picture" <?php if ($history != '' AND $history->getType() == 'picture') { echo 'selected="selected"'; } ?>>&#xf030; <?=$this->getTrans('pictureSelect') ?></option>
                <option value="video" <?php if ($history != '' AND $history->getType() == 'video') { echo 'selected="selected"'; } ?>>&#xf03d; <?=$this->getTrans('videoSelect') ?></option>
                <option value="location" <?php if ($history != '' AND $history->getType() == 'location') { echo 'selected="selected"'; } ?>>&#xf041; <?=$this->getTrans('locationSelect') ?></option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label for="color" class="col-lg-2 control-label">
            <?=$this->getTrans('color') ?>:
        </label>
        <div class="col-lg-2 input-group date">
            <input class="form-control color {hash:true}"
                   id="color"
                   name="color"
                   value="<?php if ($history != '') { echo $history->getColor(); } else { echo '#75ce66'; } ?>">
            <span class="input-group-addon">
                <span class="fa fa-undo" onclick="document.getElementById('color').color.fromString('75ce66')"></span>
            </span>
        </div>
    </div>
    <?php if ($history != '') {
        echo $this->getSaveBar('updateButton');
    } else {
        echo $this->getSaveBar('addButton');
    }
    ?>
</form>

<?=$this->getDialog('mediaModal', $this->getTrans('media'), '<iframe frameborder="0"></iframe>'); ?>
<script type="text/javascript" src="<?=$this->getStaticUrl('js/jscolor/jscolor.js') ?>"></script>
<script type="text/javascript" src="<?=$this->getStaticUrl('js/datetimepicker/js/bootstrap-datetimepicker.js')?>" charset="UTF-8"></script>
<?php if (substr($this->getTranslator()->getLocale(), 0, 2) != 'en'): ?>
    <script type="text/javascript" src="<?=$this->getStaticUrl('js/datetimepicker/js/locales/bootstrap-datetimepicker.'.substr($this->getTranslator()->getLocale(), 0, 2).'.js')?>" charset="UTF-8"></script>
<?php endif; ?>
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
