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
 $entrie = $this->get('calendar');
 ?>

<link href="<?=$this->getStaticUrl('js/datetimepicker/css/bootstrap-datetimepicker.min.css') ?>" rel="stylesheet">

<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <h1>
        <?=($entrie->getId()) ? $this->getTrans('edit') : $this->getTrans('add') ?>
    </h1>
    <div class="form-group<?=$this->validation()->hasError('start') ? ' has-error' : '' ?>">
        <label for="start" class="col-lg-2 control-label">
            <?=$this->getTrans('start') ?>:
        </label>
        <div class="col-lg-4 input-group ilch-date date form_datetime">
            <input type="text"
                   class="form-control"
                   id="start"
                   name="start"
                   value="<?=$this->escape($this->originalInput('start', ($entrie->getId()?(new \Ilch\Date($entrie->getStart()))->format("d.m.Y H:i"):''))) ?>"
                   readonly>
            <span class="input-group-addon">
                <span class="fa fa-calendar"></span>
            </span>
        </div>
    </div>
    <div class="form-group<?=$this->validation()->hasError('end') ? ' has-error' : '' ?>">
        <label for="end" class="col-lg-2 control-label">
            <?=$this->getTrans('end') ?>:
        </label>
        <div class="col-lg-4 input-group ilch-date date form_datetime">
            <input type="text"
                   class="form-control"
                   id="end"
                   name="end"
                   value="<?=$this->escape($this->originalInput('end', ($entrie->getId()?(new \Ilch\Date($entrie->getEnd()))->format("d.m.Y H:i"):''))) ?>"
                   readonly>
            <span class="input-group-addon">
                <span class="fa fa-calendar"></span>
            </span>
        </div>
    </div>
    <div class="form-group<?=$this->validation()->hasError('title') ? ' has-error' : '' ?>">
        <label for="title" class="col-lg-2 control-label">
            <?=$this->getTrans('title') ?>:
        </label>
        <div class="col-lg-4">
            <input type="text"
                   class="form-control"
                   id="title"
                   name="title"
                   value="<?=$this->escape($this->originalInput('title', ($entrie->getId()?$entrie->getTitle():''))) ?>" />
        </div>
    </div>
    <div class="form-group<?=$this->validation()->hasError('place') ? ' has-error' : '' ?>">
        <label for="place" class="col-lg-2 control-label">
            <?=$this->getTrans('place') ?>:
        </label>
        <div class="col-lg-4">
            <input type="text"
                   class="form-control"
                   id="place"
                   name="place"
                   value="<?=$this->escape($this->originalInput('place', ($entrie->getId()?$entrie->getPlace():''))) ?>" />
        </div>
    </div>
    <div class="form-group<?=$this->validation()->hasError('periodDay') ? ' has-error' : '' ?>">
        <label for="place" class="col-lg-2 control-label">
            <?=$this->getTrans('periodEntry') ?>:
        </label>
        <div class="col-lg-4">
            <select class="form-control" name="periodDay" id="periodDay">
                <option value="0" <?=($this->originalInput('periodDay', ($entrie->getId()?$entrie->getPeriodDay():'0'))) == '0' ? 'selected=""' : '' ?>><?=$this->getTrans('noPeriodEntry') ?></option>
                <?php foreach ($periodDays as $key => $value): ?>
                    <option value="<?=$key ?>" <?=($this->originalInput('periodDay', ($entrie->getId()?$entrie->getPeriodDay():'0'))) == $key ? 'selected=""' : '' ?>><?=$value ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="form-group<?=$this->validation()->hasError('color') ? ' has-error' : '' ?>">
        <label for="color" class="col-lg-2 control-label">
            <?=$this->getTrans('color') ?>:
        </label>
        <div class="col-lg-4 input-group date">
            <input class="form-control color {hash:true}"
                   id="color"
                   name="color"
                   value="<?=$this->escape($this->originalInput('color', ($entrie->getId()?$entrie->getColor():'#32333B'))) ?>">
            <span class="input-group-addon">
                <span class="fa fa-undo" onclick="document.getElementById('color').color.fromString('32333B')"></span>
            </span>
        </div>
    </div>
    <div class="form-group<?=$this->validation()->hasError('groups') ? ' has-error' : '' ?>">
        <label for="access" class="col-lg-2 control-label">
            <?=$this->getTrans('visibleFor') ?>
        </label>
        <div class="col-lg-4">
            <select class="chosen-select form-control" id="access" name="groups[]" data-placeholder="<?=$this->getTrans('selectAssignedGroups') ?>" multiple>
                <option value="all" <?=in_array('all', $this->originalInput('groups', $this->get('groups'))) ? 'selected="selected"' : '' ?>><?=$this->getTrans('groupAll') ?></option>
                <?php foreach ($this->get('userGroupList') as $groupList): ?>
                    <?php if ($groupList->getId() != 1): ?>
                        <option value="<?=$groupList->getId() ?>" <?=in_array($groupList->getId(), $this->originalInput('groups', $this->get('groups'))) ? 'selected=""' : '' ?>><?=$groupList->getName() ?></option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="form-group<?=$this->validation()->hasError('text') ? ' has-error' : '' ?>">
        <label for="ck_1" class="col-lg-2 control-label">
            <?=$this->getTrans('text') ?>:
        </label>
        <div class="col-lg-10">
            <textarea class="form-control ckeditor"
                      id="ck_1"
                      name="text"
                      toolbar="ilch_html"
                      rows="5"><?=$this->escape($this->originalInput('text', ($entrie->getId()?$entrie->getText():''))) ?></textarea>
        </div>
    </div>
    <?=($entrie->getId()) ? $this->getSaveBar('updateButton') : $this->getSaveBar('addButton') ?>
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
