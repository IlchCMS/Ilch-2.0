<?php
$awards = $this->get('awards');

if ($awards != '') {
    $getDate = new \Ilch\Date($awards->getDate());
    $date = $getDate->format('d.m.Y', true);
}
?>

<link href="<?=$this->getModuleUrl('static/css/awards.css') ?>" rel="stylesheet">
<link href="<?=$this->getStaticUrl('js/datetimepicker/css/bootstrap-datetimepicker.min.css') ?>" rel="stylesheet">

<form class="form-horizontal" method="POST" action="<?=$this->getUrl(array('action' => $this->getRequest()->getActionName(), 'id' => $this->getRequest()->getParam('id'))) ?>">
    <?=$this->getTokenField() ?>
    <legend>
        <?php if ($this->get('awards') != ''): ?>
            <?=$this->getTrans('edit') ?>
        <?php else: ?>
            <?=$this->getTrans('add') ?>
        <?php endif; ?>
    </legend>
    <div class="form-group">
        <label for="date" class="col-lg-2 control-label">
            <?=$this->getTrans('date') ?>:
        </label>
        <div class="col-lg-2 input-group date form_datetime">
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
                           min="1"
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
        <label for="user" class="col-lg-1 control-label">
            <?=$this->getTrans('user') ?>:
        </label>
        <div class="col-lg-1 userTeam">
            <input type="radio"
                   name="typ"
                   id="typ_user"
                   value="1"
                   onchange="toggleStatus()"
                   <?php if ($this->get('awards') != '' AND $this->get('awards')->getTyp() == 1) { echo 'checked="checked"';} ?>>
        </div>
        <div class="col-lg-2">
            <select class="form-control" name="utId" id="user" <?php if ($this->get('awards') == '' OR $this->get('awards')->getTyp() == 2) { echo 'disabled';} ?>>
                <?php foreach ($this->get('users') as $user) {
                        $selected = '';

                        if ($this->get('awards') != '' AND $this->get('awards')->getUTId() == $user->getId()) {
                            $selected = 'selected="selected"';
                        }
                        echo '<option '.$selected.' value="'.$user->getId().'">'.$this->escape($user->getName()).'</option>';
                    }
                ?>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label for="team" class="col-lg-1 control-label">
            <?=$this->getTrans('team') ?>:
        </label>
        <div class="col-lg-1 userTeam">
            <input type="radio"
                   name="typ"
                   id="typ_team"
                   value="2"
                   onchange="toggleStatus()"
                   <?php if ($this->get('awards') != '' AND $this->get('awards')->getTyp() == 2) { echo 'checked="checked"';} ?>>
        </div>
        <div class="col-lg-2">
            <input class="form-control"
                   type="text"
                   name="utId"
                   id="team"
                   <?php if ($this->get('awards') == '' OR $this->get('awards')->getTyp() == 1) { echo 'disabled';} ?>
                   <?php if ($this->get('awards') != '' AND $this->get('awards')->getTyp() == 1) { echo 'disabled';} ?>
                   value="<?php if ($this->get('awards') != '' AND $this->get('awards')->getTyp() != 1) { echo $this->escape($this->get('awards')->getUTId()); } ?>" />
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
                   name="url"
                   placeholder="http://"
                   value="<?php if ($this->get('awards') != '') { echo $this->escape($this->get('awards')->getURL()); } ?>" />
        </div>
    </div>
    <?php if ($this->get('awards') != ''): ?>
        <?=$this->getSaveBar('updateButton') ?>
    <?php else: ?>
        <?=$this->getSaveBar('addButton') ?>
    <?php endif; ?>
</form>

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

$(function() {
    $('.spinner .btn:first-of-type').on('click', function() {
        var btn = $(this);
        var input = btn.closest('.spinner').find('input');
        if (input.attr('max') == undefined || parseInt(input.val()) < parseInt(input.attr('max'))) {
            input.val(parseInt(input.val(), 10) + 1);
        } else {
            btn.next("disabled", true);
        }
    });
    $('.spinner .btn:last-of-type').on('click', function() {
        var btn = $(this);
        var input = btn.closest('.spinner').find('input');
        if (input.attr('min') == undefined || parseInt(input.val()) > parseInt(input.attr('min'))) {
            input.val(parseInt(input.val(), 10) - 1);
        } else {
            btn.prev("disabled", true);
        }
    });
})

window.onload = function() {
    document.getElementById('typ_user').onchange = disablefield;
    document.getElementById('typ_team').onchange = disablefield;
}

function toggleStatus() {
    if ($('#typ_user').is(':checked')) {
        $('#user').removeAttr('disabled');
        $('#team').attr('disabled', true);
    } else if ($('#typ_team').is(':checked')) {
        $('#user').attr('disabled', true);
        $('#team').removeAttr('disabled');
    } else {
        $('#user').attr('disabled', true);
        $('#team').attr('disabled', true);
    }
}
</script>
