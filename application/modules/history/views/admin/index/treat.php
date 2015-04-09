<?php
    $adate = new \Ilch\Date(); 
    $history = $this->get('history');   

    if ($history != '') {
        $getDate = new \Ilch\Date($history->getDate());
        $date = $getDate->format('d.m.Y', true);
    }
?>

<link href="<?=$this->getStaticUrl('datetimepicker/css/bootstrap-datetimepicker.min.css') ?>" rel="stylesheet">
<script type="text/javascript" src="<?=$this->getStaticUrl('datetimepicker/js/bootstrap-datetimepicker.js')?>" charset="UTF-8"></script>
<script type="text/javascript" src="<?=$this->getStaticUrl('datetimepicker/js/locales/bootstrap-datetimepicker.de.js')?>" charset="UTF-8"></script>

<form class="form-horizontal" method="POST" action="<?php echo $this->getUrl(array('action' => $this->getRequest()->getActionName(), 'id' => $this->getRequest()->getParam('id'))); ?>">
    <?php echo $this->getTokenField(); ?>
    <legend>
    <?php
        if ($this->get('history') != '') {
            echo $this->getTrans('menuActionEditHistory');
        } else {
            echo $this->getTrans('menuActionNewHistory');
        }
    ?>
    </legend>
    <div class="form-group">
        <label for="date" class="col-lg-2 control-label">
            <?=$this->getTrans('date') ?>:
        </label>
        <div class="col-lg-4 input-group date date form_datetime">
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
            <?php echo $this->getTrans('title'); ?>:
        </label>
        <div class="col-lg-4">
            <input class="form-control"
                   type="text"
                   name="title"
                   id="title"
                   placeholder="Title"
                   value="<?php if ($this->get('history') != '') { echo $this->escape($this->get('history')->getTitle()); } ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="text" class="col-lg-2 control-label">
            <?php echo $this->getTrans('text'); ?>:
        </label>
        <div class="col-lg-10">
            <textarea class="form-control"
                   name="text" 
                   id="ilch_html"
                   rows="5"><?php if ($this->get('history') != '') { echo $this->escape($this->get('history')->getText()); } ?></textarea>
        </div>
    </div>
    <?php
    if ($this->get('history') != '') {
        echo $this->getSaveBar('updateButton');
    } else {
        echo $this->getSaveBar('addButton');
    }
    ?>
</form>

<script type="text/javascript">
    $( document ).ready(function()
    {
        $(".form_datetime").datetimepicker({
            format: "dd.mm.yyyy",
            autoclose: true,
            language: 'de',
            minView: 2
        });
    });
</script>

<style>
    .date {
        padding-left: 15px !important;
        padding-right: 15px !important;
    }
</style>
