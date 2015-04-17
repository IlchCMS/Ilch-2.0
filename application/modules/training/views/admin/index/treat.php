<link href="<?=$this->getStaticUrl('datetimepicker/css/bootstrap-datetimepicker.min.css') ?>" rel="stylesheet">
<?php $training = $this->get('training'); ?>
<form class="form-horizontal" method="POST" action="<?php echo $this->getUrl(array('action' => $this->getRequest()->getActionName(), 'id' => $this->getRequest()->getParam('id'))); ?>">
    <?=$this->getTokenField() ?>
    <legend>
        <?php if ($this->get('training') != ''): ?>
            <?=$this->getTrans('menuEdit') ?>
        <?php else: ?>
            <?=$this->getTrans('menuAdd') ?>
        <?php endif; ?>
    </legend>

    <div class="form-group">
        <label for="title" class="col-lg-2 control-label">
            <?=$this->getTrans('title') ?>:
        </label>
        <div class="col-lg-4">
            <input class="form-control"
                   type="text"
                   name="title"
                   id="title"
                   value="<?php if ($this->get('training') != '') { echo $this->escape($this->get('training')->getTitle()); } ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="date" class="col-md-2 control-label">
            <?=$this->getTrans('date') ?>:
        </label>
        <div class="col-lg-2 input-group date form_datetime">
            <input class="form-control"
                   type="text"
                   name="date"
                   id="date"
                   value="<?php if ($this->get('training') != '') { echo date('d.m.Y H:i', strtotime($this->get('training')->getDate())); } ?>"
                   readonly>
            <span class="input-group-addon">
                <span class="fa fa-calendar"></span>
            </span>
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
                   value="<?php if ($this->get('training') != '') { echo $this->escape($this->get('training')->getPlace()); } ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="ilch_html" class="col-lg-2 control-label">
            <?=$this->getTrans('otherInfo') ?>:
        </label>
        <div class="col-lg-10">
            <textarea class="form-control"
                   name="text"
                   id="ilch_html"
                   rows="5"><?php if ($this->get('training') != '') { echo $this->escape($this->get('training')->getText()); } ?></textarea>
        </div>
    </div>
    <?php if ($this->get('training') != ''): ?>
        <?=$this->getSaveBar('updateButton') ?>
    <?php else: ?>
        <?=$this->getSaveBar('addButton') ?>
    <?php endif; ?>
</form>

<script type="text/javascript" src="<?=$this->getStaticUrl('datetimepicker/js/bootstrap-datetimepicker.js')?>" charset="UTF-8"></script>
<script type="text/javascript" src="<?=$this->getStaticUrl('datetimepicker/js/locales/bootstrap-datetimepicker.de.js')?>" charset="UTF-8"></script>
<script type="text/javascript">
    $( document ).ready(function()
    {
        $(".form_datetime").datetimepicker({
            format: "dd.mm.yyyy hh:ii",
            autoclose: true,
            language: 'de',
            minuteStep: 15
        });
    });
</script>
<style>
.date {
    padding-left: 15px !important;
    padding-right: 15px !important;
}
</style>
