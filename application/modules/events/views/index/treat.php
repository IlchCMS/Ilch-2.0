<link href="<?=$this->getStaticUrl('datetimepicker/css/bootstrap-datetimepicker.min.css') ?>" rel="stylesheet">
<table class="table table-striped table-responsive">
    <tr align="center">
        <td width="20%"><a class="list-group-item" href="<?=$this->getUrl(array('action' => 'upcoming')); ?>"><i class="fa fa-history fa-flip-horizontal"></i>&nbsp; <?=$this->getTrans('naviEventsUpcoming') ?></a></td>
        <td width="20%"><a class="list-group-item" href="<?=$this->getUrl(array('action' => 'all')); ?>"><i class="fa fa-list-ul"></i>&nbsp; <?=$this->getTrans('naviEventsAll') ?></a></td>
        <td width="20%"><a class="list-group-item" href="<?=$this->getUrl(array('action' => 'past')); ?>"><i class="fa fa-history"></i>&nbsp; <?=$this->getTrans('naviEventsPast') ?></a></td>
        <td width="20%"><a class="list-group-item" href="<?=$this->getUrl(array('action' => 'treat')); ?>"><i class="fa fa-plus"></i>&nbsp; <?=$this->getTrans('naviEventsAdd') ?></a></td>
        <td width="20%"><a class="list-group-item" href="<?=$this->getUrl(array('action' => 'my')); ?>"><i class="fa fa-cogs"></i>&nbsp; <?=$this->getTrans('naviEventsMy') ?></a></td>
    </tr>
</table>
<form class="form-horizontal" method="POST" action="<?=$this->getUrl(array('action' => $this->getRequest()->getActionName(), 'id' => $this->getRequest()->getParam('id'))) ?>">
    <?=$this->getTokenField() ?>
    <legend>
    <?php
        if ($this->get('event') != '') {
            echo $this->getTrans('menuEditEvent');
        } else {
            echo $this->getTrans('menuNewEvent');
        }
    ?>
    </legend>
    <div class="form-group">
        <label for="dtp_input1" class="col-md-2 control-label">
            <?=$this->getTrans('time') ?>:
        </label>
        <div class="col-lg-3 input-group date form_datetime">
            <input class="form-control"
                   size="16"
                   type="text"
                   name="dateCreated"
                   value="<?php if ($this->get('event') != '') { echo date('d.m.Y H:i', strtotime($this->get('event')->getdateCreated())); } ?>"
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
        <div class="col-lg-6">
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
        <div class="col-lg-6">
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
            <textarea class="form-control"
                      name="text"
                      id="ilch_html"
                      rows="5"><?php if ($this->get('event') != '') { echo $this->escape($this->get('event')->getText()); } ?></textarea>
        </div>
    </div>
    <div style="float: right;">
        <?php
        if ($this->get('event') != '') {
            echo $this->getSaveBar('updateButton');
        } else {
            echo $this->getSaveBar('addButton');
        }
        ?>
    </div>
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