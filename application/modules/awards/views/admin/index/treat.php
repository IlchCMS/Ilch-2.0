<?php
$awards = $this->get('awards');

if ($awards != '') {
    $getDate = new \Ilch\Date($awards->getDate());
    $date = $getDate->format('d.m.Y', true);
}
?>

<link href="<?=$this->getModuleUrl('static/css/awards.css') ?>" rel="stylesheet">
<link href="<?=$this->getStaticUrl('js/datetimepicker/css/bootstrap-datetimepicker.min.css') ?>" rel="stylesheet">

<h1>
    <?php if ($awards != '') {
        echo $this->getTrans('edit');
    } else {
        echo $this->getTrans('add');
    }
    ?>
</h1>

<form class="form-horizontal" method="POST" action="<?=$this->getUrl(['action' => $this->getRequest()->getActionName(), 'id' => $this->getRequest()->getParam('id')]) ?>">
    <?=$this->getTokenField() ?>
    <div class="form-group <?=$this->validation()->hasError('date') ? 'has-error' : '' ?>">
        <label for="date" class="col-lg-2 control-label">
            <?=$this->getTrans('date') ?>:
        </label>
        <div class="col-lg-2 input-group ilch-date date form_datetime">
            <input type="text"
                   class="form-control"
                   name="date"
                   id="date"
                   value="<?php if ($this->get('awards') != '') { echo $date; } ?>"
                   readonly>
            <span class="input-group-addon">
                <span class="fa fa-calendar"></span>
            </span>
        </div>
    </div>
    <div class="form-group <?=$this->validation()->hasError('rank') ? 'has-error' : '' ?>">
        <label for="rank" class="col-lg-2 control-label">
            <?=$this->getTrans('rank') ?>:
        </label>
        <div class="col-lg-1">
            <input type="number"
                   class="form-control"
                   id="rank"
                   name="rank"
                   min="1"
                   value="<?php if ($this->get('awards') != '') { echo $this->escape($this->get('awards')->getRank()); } else { echo '1'; } ?>">
        </div>
    </div>
    <div class="form-group <?=($this->validation()->hasError('typ') or $this->validation()->hasError('utId')) ? 'has-error' : '' ?>">
        <label for="user" class="col-lg-1 control-label">
            <?=$this->getTrans('user') ?>:
        </label>
        <div class="col-lg-1 userTeam">
            <input type="radio"
                   id="typ_user"
                   name="typ"
                   value="1"
                   onchange="toggleStatus()"
                   <?php if ($this->get('awards') != '' AND $this->get('awards')->getTyp() == 1) { echo 'checked="checked"';} ?>>
        </div>
        <div class="col-lg-2">
            <select class="form-control" id="user" name="utId" <?php if ($this->get('awards') == '' OR $this->get('awards')->getTyp() == 2) { echo 'disabled';} ?>>
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
    <div class="form-group <?=($this->validation()->hasError('typ') or $this->validation()->hasError('utId')) ? 'has-error' : '' ?>">
        <label for="team" class="col-lg-1 control-label">
            <?=$this->getTrans('team') ?>:
        </label>
        <div class="col-lg-1 userTeam">
            <input type="radio"
                   id="typ_team"
                   name="typ"
                   value="2"
                   onchange="toggleStatus()"
                   <?php if ($this->get('awards') != '' AND $this->get('awards')->getTyp() == 2) { echo 'checked="checked"';} ?>>
        </div>
        <div class="col-lg-2">
            <select class="form-control" id="team" name="utId" <?php if ($this->get('awards') == '' OR $this->get('awards')->getTyp() == 1) { echo 'disabled';} ?>>
                <?php foreach ($this->get('teams') as $team) {
                    $selected = '';
                    if ($this->get('awards') != '' AND $this->get('awards')->getUTId() == $team->getId()) {
                        $selected = 'selected="selected"';
                    }
                    echo '<option '.$selected.' value="'.$team->getId().'">'.$this->escape($team->getName()).'</option>';
                }
                ?>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label for="event" class="col-lg-2 control-label">
            <?=$this->getTrans('event') ?>:
        </label>
        <div class="col-lg-4">
            <input type="text"
                   class="form-control"
                   id="event"
                   name="event"
                   value="<?php if ($this->get('awards') != '') { echo $this->escape($this->get('awards')->getEvent()); } ?>" />
        </div>
    </div>
    <div class="form-group <?=$this->validation()->hasError('page') ? 'has-error' : '' ?>">
        <label for="page" class="col-lg-2 control-label">
            <?=$this->getTrans('page') ?>:
        </label>
        <div class="col-lg-4">
            <input type="text"
                   class="form-control"
                   name="page"
                   id="page"
                   placeholder="http://"
                   value="<?php if ($this->get('awards') != '') { echo $this->escape($this->get('awards')->getURL()); } else { echo $this->get('post')['page']; } ?>" />
        </div>
    </div>
    <?php if ($this->get('awards') != ''): ?>
        <?=$this->getSaveBar('updateButton') ?>
    <?php else: ?>
        <?=$this->getSaveBar('addButton') ?>
    <?php endif; ?>
</form>

<script src="<?=$this->getStaticUrl('js/datetimepicker/js/bootstrap-datetimepicker.min.js') ?>" charset="UTF-8"></script>
<?php if (substr($this->getTranslator()->getLocale(), 0, 2) != 'en'): ?>
    <script src="<?=$this->getStaticUrl('js/datetimepicker/js/locales/bootstrap-datetimepicker.'.substr($this->getTranslator()->getLocale(), 0, 2).'.js') ?>" charset="UTF-8"></script>
<?php endif; ?>
<script>
$(document).ready(function() {
    $(".form_datetime").datetimepicker({
        format: "dd.mm.yyyy",
        autoclose: true,
        language: '<?=substr($this->getTranslator()->getLocale(), 0, 2) ?>',
        minView: 2,
        todayHighlight: true
    });
});

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
