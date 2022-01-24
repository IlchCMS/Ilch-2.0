<?php
$periodDays = [
    '1' => $this->getTranslator()->trans('Monday'),
    '2' => $this->getTranslator()->trans('Tuesday'),
    '3' => $this->getTranslator()->trans('Wednesday'),
    '4' => $this->getTranslator()->trans('Thursday'),
    '5' => $this->getTranslator()->trans('Friday'),
    '6' => $this->getTranslator()->trans('Saturday'),
    '7' => $this->getTranslator()->trans('Sunday')
];
?>

<link href="<?=$this->getStaticUrl('js/datetimepicker/css/bootstrap-datetimepicker.min.css') ?>" rel="stylesheet">

<form class="form-horizontal" method="POST" action="<?=$this->getUrl(['action' => $this->getRequest()->getActionName(), 'id' => $this->getRequest()->getParam('id')]) ?>">
    <?=$this->getTokenField() ?>
    <h1>
        <?php if ($this->get('event') != ''): ?>
            <?=$this->getTrans('edit') ?>
        <?php else: ?>
            <?=$this->getTrans('add') ?>
        <?php endif; ?>
    </h1>
    <div class="form-group">
        <label for="start" class="col-lg-2 control-label">
            <?=$this->getTrans('start') ?>:
        </label>
        <div class="col-lg-2 input-group ilch-date date form_datetime">
            <input type="text"
                   class="form-control"
                   id="start"
                   name="start"
                   value="<?=($this->get('calendar') != '') ? date('d.m.Y H:i', strtotime($this->get('calendar')->getStart())) : '' ?>"
                   readonly>
            <span class="input-group-addon">
                <span class="fa fa-calendar"></span>
            </span>
        </div>
    </div>
    <div class="form-group">
        <label for="end" class="col-lg-2 control-label">
            <?=$this->getTrans('end') ?>:
        </label>
        <div class="col-lg-2 input-group ilch-date date form_datetime">
            <input type="text"
                   class="form-control"
                   id="end"
                   name="end"
                   value="<?=($this->get('calendar') != '') ? date('d.m.Y H:i', strtotime($this->get('calendar')->getEnd())) : '' ?>"
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
                   value="<?=($this->get('calendar') != '') ? $this->escape($this->get('calendar')->getTitle()) : '' ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="place" class="col-lg-2 control-label">
            <?=$this->getTrans('place') ?>:
        </label>
        <div class="col-lg-4">
            <input type="text"
                   class="form-control"
                   id="place"
                   name="place"
                   value="<?=($this->get('calendar') != '') ? $this->escape($this->get('calendar')->getPlace()) : '' ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="place" class="col-lg-2 control-label">
            <?=$this->getTrans('periodEntry') ?>:
        </label>
        <div class="col-lg-4">
            <select class="form-control" name="periodDay" id="periodDay">
                <option selected="selected"><?=$this->getTrans('noPeriodEntry') ?></option>
                <?php foreach ($periodDays as $key => $value): ?>
                    <?php $selected = ''; ?>
                    <?php if ($this->get('calendar') != '' and $this->get('calendar')->getPeriodDay() == $key or $this->originalInput('periodDay') == $key): ?>
                        <?php $selected = 'selected="selected"'; ?>
                    <?php endif; ?>

                    <option <?=$selected ?> value="<?=$key ?>"><?=$value ?></option>
                <?php endforeach; ?>
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
                   value="<?=($this->get('calendar') != '') ? $this->get('calendar')->getColor() : '#32333B' ?>">
            <span class="input-group-addon">
                <span class="fa fa-undo" onclick="document.getElementById('color').color.fromString('32333B')"></span>
            </span>
        </div>
    </div>
    <div class="form-group">
        <label for="access" class="col-lg-2 control-label">
            <?=$this->getTrans('visibleFor') ?>
        </label>
        <div class="col-lg-4">
            <select class="chosen-select form-control" id="access" name="groups[]" data-placeholder="<?=$this->getTrans('selectAssignedGroups') ?>" multiple>
                <?php foreach ($this->get('userGroupList') as $groupList): ?>
                    <option value="<?=$groupList->getId() ?>"<?=(in_array($groupList->getId(), $this->get('groups'))) ? ' selected' : '' ?>><?=$groupList->getName() ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label for="ck_1" class="col-lg-2 control-label">
            <?=$this->getTrans('text') ?>:
        </label>
        <div class="col-lg-10">
            <textarea class="form-control ckeditor"
                      id="ck_1"
                      name="text"
                      toolbar="ilch_html"
                      rows="5"><?=($this->get('calendar') != '') ? $this->escape($this->get('calendar')->getText()) : '' ?></textarea>
        </div>
    </div>
    <?php if ($this->get('event') != ''): ?>
        <?=$this->getSaveBar('updateButton') ?>
    <?php else: ?>
        <?=$this->getSaveBar('addButton') ?>
    <?php endif; ?>
</form>

<?=$this->getDialog('mediaModal', $this->getTrans('media'), '<iframe frameborder="0"></iframe>'); ?>
<script src="<?=$this->getStaticUrl('js/jscolor/jscolor.js') ?>"></script>
<script src="<?=$this->getStaticUrl('js/datetimepicker/js/bootstrap-datetimepicker.min.js') ?>" charset="UTF-8"></script>
<?php if (substr($this->getTranslator()->getLocale(), 0, 2) != 'en'): ?>
    <script src="<?=$this->getStaticUrl('js/datetimepicker/js/locales/bootstrap-datetimepicker.'.substr($this->getTranslator()->getLocale(), 0, 2).'.js') ?>" charset="UTF-8"></script>
<?php endif; ?>
<script>
<?=$this->getMedia()
    ->addMediaButton($this->getUrl('admin/media/iframe/index/type/single/'))
    ->addUploadController($this->getUrl('admin/media/index/upload'))
?>
$('#access').chosen();

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
