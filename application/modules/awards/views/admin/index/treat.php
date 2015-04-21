<?php
    $awards = $this->get('awards');   

    if ($awards != '') {
        $getDate = new \Ilch\Date($awards->getDate());
        $date = $getDate->format('d.m.Y', true);
    }
?>

<link href="<?=$this->getModuleUrl('static/css/awards.css') ?>" rel="stylesheet">
<link href="<?=$this->getStaticUrl('datetimepicker/css/bootstrap-datetimepicker.min.css') ?>" rel="stylesheet">
<script type="text/javascript" src="<?=$this->getStaticUrl('datetimepicker/js/bootstrap-datetimepicker.js')?>" charset="UTF-8"></script>
<script type="text/javascript" src="<?=$this->getStaticUrl('datetimepicker/js/locales/bootstrap-datetimepicker.de.js')?>" charset="UTF-8"></script>

<form class="form-horizontal" method="POST" action="<?=$this->getUrl(array('action' => $this->getRequest()->getActionName(), 'id' => $this->getRequest()->getParam('id'))) ?>">
    <?=$this->getTokenField() ?>
    <legend>
    <?php
        if ($this->get('awards') != '') {
            echo $this->getTrans('menuActionEditAward');
        } else {
            echo $this->getTrans('menuActionNewAward');
        }
    ?>
    </legend>
    <div class="form-group">
        <label for="date" class="col-lg-2 control-label">
            <?=$this->getTrans('date') ?>:
        </label>
        <div class="col-lg-2 input-group date date form_datetime">
            <input class="form-control"
                   type="text"
                   name="date"
                   value="<?php if ($this->get('awards') != '') { echo $date; } ?>"
                   readonly>
            <span class="input-group-addon">
                <span class="fa fa-calendar"></span>
            </span>
        </div>
    </div>
    <div class="form-group">
        <label for="rank" class="col-lg-2 control-label">
            <?=$this->getTrans('rank') ?>:
        </label>
        <div class="col-lg-2 input-group">
            <div class="container">
                <div class="input-group spinner">
                    <input class="form-control"
                           type="text"
                           id="rank"
                           name="rank"
                           value="<?php if ($this->get('awards') != '') { echo $this->escape($this->get('awards')->getRank()); } else { echo '1'; } ?>">
                    <div class="input-group-btn-vertical">
                        <span class="btn btn-default"><i class="fa fa-caret-up"></i></span>
                        <span class="btn btn-default"><i class="fa fa-caret-down"></i></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="squad" class="col-lg-2 control-label">
            <?=$this->getTrans('squad') ?>:
        </label>
        <div class="col-lg-4">
            <input class="form-control"
                   type="text"
                   name="squad"
                   value="<?php if ($this->get('awards') != '') { echo $this->escape($this->get('awards')->getSquad()); } ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="event" class="col-lg-2 control-label">
            <?=$this->getTrans('event') ?>:
        </label>
        <div class="col-lg-4">
            <input class="form-control"
                   type="text"
                   name="event"
                   value="<?php if ($this->get('awards') != '') { echo $this->escape($this->get('awards')->getEvent()); } ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="page" class="col-lg-2 control-label">
            <?=$this->getTrans('page') ?>:
        </label>
        <div class="col-lg-4">
            <input class="form-control"
                   type="text"
                   name="page"
                   placeholder="http://"
                   value="<?php if ($this->get('awards') != '') { echo $this->escape($this->get('awards')->getPage()); } ?>" />
        </div>
    </div>
    <?php
    if ($this->get('awards') != '') {
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
    
    (function ($) {
      $('.spinner .btn:first-of-type').on('click', function() {
        $('.spinner input').val( parseInt($('.spinner input').val(), 10) + 1);
      });
      $('.spinner .btn:last-of-type').on('click', function() {
        $('.spinner input').val( parseInt($('.spinner input').val(), 10) - 1);
      });
    })(jQuery);
</script>
