<?php
    $adate = new \Ilch\Date(); 
    $history = $this->get('history');   

    if ($history != '') {
        $getDate = new \Ilch\Date($history->getDate());
        $date = $getDate->format('d-m-Y', true);
    } else {
        $date = $adate->format('d-m-Y');        
    }
?>

<link href="<?=$this->getStaticUrl('datepicker/css/datepicker.css')?>" rel="stylesheet">
<script type="text/javascript" src="<?=$this->getStaticUrl('datepicker/js/bootstrap-datepicker.js')?>"></script>
<script type="text/javascript" src="<?=$this->getStaticUrl('datepicker/js/locales/bootstrap-datepicker.de.js')?>"></script>

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
            <?php echo $this->getTrans('date'); ?>:
        </label>
        <div class="col-lg-4">
            <div class="input-group date" id="date" data-date="<?php echo $date; ?>">
                <input class="form-control"
                       type="text"
                       name="date"
                       id="date"
                       placeholder="<?php echo $date; ?>"
                       value="<?php echo $date; ?>" />
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
            </div>
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
        <div class="col-lg-4">
            <textarea class="form-control"
                   name="text" 
                   id="ilch_bbcode"
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

<script>
    $(function () {
        $('#date').datepicker({
            autoclose: true, 
            todayHighlight: true,
            language: "de",
            weekStart: "1",
            format: "dd-mm-yyyy"
        });
    });
</script>
