<link href="<?=$this->getStaticUrl('datetimepicker/css/bootstrap-datetimepicker.min.css') ?>" rel="stylesheet">
<link href="<?=$this->getModuleUrl('static/css/training.css') ?>" rel="stylesheet">
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
            <?=$this->getTrans('dateTime') ?>:
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
        <label for="time" class="col-lg-2 control-label">
            <?=$this->getTrans('time') ?>:
        </label>
        <div class="col-lg-6 input-group">
            <div class="container">
                <div class="input-group spinner">
                    <input class="form-control"
                           type="text"
                           id="time"
                           name="time"
                           value="<?php if ($this->get('training') != '') { echo $this->escape($this->get('training')->getTime()); } else { echo '30'; } ?>">
                    <div class="input-group-btn-vertical">
                        <span class="btn btn-default"><i class="fa fa-caret-up"></i></span>
                        <span class="btn btn-default"><i class="fa fa-caret-down"></i></span>
                    </div>
                    &nbsp;<?=$this->getTrans('min') ?>
                </div>
            </div>
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
        <label for="serverIP" class="col-lg-2 control-label">
            <?=$this->getTrans('serverIP') ?>:
        </label>
        <div class="col-lg-4">
            <input class="form-control"
                   type="text"
                   name="serverIP"
                   id="serverIP"
                   placeholder="IP:Port"
                   value="<?php if ($this->get('training') != '') { echo $this->escape($this->get('training')->getServerIP()); } ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="serverPW" class="col-lg-2 control-label">
            <?=$this->getTrans('serverPW') ?>:
        </label>
        <div class="col-lg-4">
            <input class="form-control"
                   type="text"
                   name="serverPW"
                   id="serverPW"
                   placeholder="********"
                   value="<?php if ($this->get('training') != '') { echo $this->escape($this->get('training')->getServerPW()); } ?>" />
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
 
    (function ($) {
      $('.spinner .btn:first-of-type').on('click', function() {
        $('.spinner input').val( parseInt($('.spinner input').val(), 10) + 1);
      });
      $('.spinner .btn:last-of-type').on('click', function() {
        $('.spinner input').val( parseInt($('.spinner input').val(), 10) - 1);
      });
    })(jQuery);
</script>