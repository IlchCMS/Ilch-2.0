<?php
$awardsMapper = $this->get('awardsMapper');
$awards = $this->get('awards');

if ($awards != '') {
    $getDate = new \Ilch\Date($awards->getDate());
    $date = $getDate->format('d.m.Y', true);
}
?>

<link href="<?=$this->getModuleUrl('static/css/awards.css') ?>" rel="stylesheet">
<link href="<?=$this->getStaticUrl('js/tempus-dominus/dist/css/tempus-dominus.min.css') ?>" rel="stylesheet">

<h1><?=($awards != '') ? $this->getTrans('edit') : $this->getTrans('add') ?></h1>
<form class="form-horizontal" method="POST" action="<?=$this->getUrl(['action' => $this->getRequest()->getActionName(), 'id' => $this->getRequest()->getParam('id')]) ?>">
    <?=$this->getTokenField() ?>
    <div class="row mb-3 <?=$this->validation()->hasError('date') ? 'has-error' : '' ?>">
        <label for="date" class="col-xl-2 control-label">
            <?=$this->getTrans('date') ?>:
        </label>
        <div id="date" class="col-xl-2 input-group ilch-date date form_datetime">
            <input type="text"
                   class="form-control"
                   name="date"
                   id="date"
                   value="<?=($this->get('awards') != '') ? $this->escape($date) : $this->escape($this->originalInput('date')) ?>"
                   readonly>
            <span class="input-group-text">
                <span class="fa-solid fa-calendar"></span>
            </span>
        </div>
    </div>
    <div class="row mb-3 <?=$this->validation()->hasError('rank') ? 'has-error' : '' ?>">
        <label for="rank" class="col-xl-2 control-label">
            <?=$this->getTrans('rank') ?>:
        </label>
        <div class="col-xl-1">
            <input type="number"
                   class="form-control"
                   id="rank"
                   name="rank"
                   min="1"
                   placeholder="1"
                   value="<?=($this->get('awards') != '') ? $this->escape($this->get('awards')->getRank()) : $this->escape($this->originalInput('rank')) ?>" />
        </div>
    </div>
    <div class="row mb-3 <?=$this->validation()->hasError('image') ? 'has-error' : '' ?>">
        <label for="selectedImage" class="col-xl-2 control-label">
            <?=$this->getTrans('image') ?>:
        </label>
        <div class="col-xl-4">
            <div class="input-group">
                <input type="text"
                       class="form-control"
                       id="selectedImage"
                       name="image"
                       placeholder="<?=$this->getTrans('httpOrMedia') ?>"
                       value="<?=($this->get('awards') != '') ? $this->escape($this->get('awards')->getImage()) : $this->escape($this->originalInput('image')) ?>" />
                <span class="input-group-text">
                    <span id="clearImage" class="fa-solid fa-xmark"></span>
                </span>
                <span class="input-group-text">
                    <a id="media" href="javascript:media()"><i class="fa-regular fa-image"></i></a>
                </span>
            </div>
        </div>
    </div>
    <div class="row mb-3 <?=($this->validation()->hasError('typ') || $this->validation()->hasError('utId')) ? 'has-error' : '' ?>">
        <label for="user" class="col-xl-2 control-label">
            <?=$this->getTrans('userTeam') ?>:
        </label>
        <div class="col-xl-2">
            <select class="form-control" id="user" name="utId[]" multiple>
                <optgroup label="<?=$this->getTrans('user') ?>">
                    <?php foreach ($this->get('users') as $user) {
                            $selected = '';
                            if ($this->validation()->hasErrors() && in_array('1_'.$user->getId(), $this->originalInput('utId'))) {
                                $selected = 'selected="selected"';
                            } elseif (!$this->validation()->hasErrors() && $awards) {
                                foreach($awards->getRecipients() as $recipient) {
                                    if ($recipient->getUtId() === $user->getId() && $recipient->getTyp() === 1) {
                                        $selected = 'selected="selected"';
                                        break;
                                    }
                                }
                            }

                            echo '<option '.$selected.' value="1_'.$user->getId().'">'.$this->escape($user->getName()).'</option>';
                        }
                    ?>
                </optgroup>
                <?php if ($awardsMapper->existsTable('teams') && $this->get('teams') != ''): ?>
                    <optgroup label="<?=$this->getTrans('team') ?>">
                        <?php foreach ($this->get('teams') as $team) {
                            $selected = '';
                            if ($this->validation()->hasErrors() && in_array('2_'.$team->getId(), $this->originalInput('utId'))) {
                                $selected = 'selected="selected"';
                            } elseif (!$this->validation()->hasErrors() && $awards) {
                                foreach($awards->getRecipients() as $recipient) {
                                    if ($recipient->getUtId() === $team->getId() && $recipient->getTyp() === 2) {
                                        $selected = 'selected="selected"';
                                        break;
                                    }
                                }
                            }

                            echo '<option '.$selected.' value="2_'.$team->getId().'">'.$this->escape($team->getName()).'</option>';
                        }
                        ?>
                    </optgroup>
                <?php endif; ?>
            </select>
        </div>
    </div>
    <div class="row mb-3 <?=($this->validation()->hasError('event')) ? 'has-error' : '' ?>">
        <label for="event" class="col-xl-2 control-label">
            <?=$this->getTrans('event') ?>:
        </label>
        <div class="col-xl-4">
            <input type="text"
                   class="form-control"
                   id="event"
                   name="event"
                   value="<?=($this->get('awards') != '') ? $this->escape($this->get('awards')->getEvent()) : $this->escape($this->originalInput('event')) ?>" />
        </div>
    </div>
    <div class="row mb-3 <?=$this->validation()->hasError('page') ? 'has-error' : '' ?>">
        <label for="page" class="col-xl-2 control-label">
            <?=$this->getTrans('page') ?>:
        </label>
        <div class="col-xl-4">
            <input type="text"
                   class="form-control"
                   name="page"
                   id="page"
                   placeholder="https://"
                   value="<?=($this->get('awards') != '') ? $this->escape($this->get('awards')->getURL()) : $this->escape($this->originalInput('page')) ?>" />
        </div>
    </div>
    <?php if ($this->get('awards') != ''): ?>
        <?=$this->getSaveBar('updateButton') ?>
    <?php else: ?>
        <?=$this->getSaveBar('addButton') ?>
    <?php endif; ?>
</form>

<?=$this->getDialog('mediaModal', $this->getTrans('media'), '<iframe frameborder="0"></iframe>') ?>
<script src="<?=$this->getStaticUrl('js/popper/dist/umd/popper.min.js') ?>" charset="UTF-8"></script>
<script src="<?=$this->getStaticUrl('js/tempus-dominus/dist/js/tempus-dominus.min.js') ?>" charset="UTF-8"></script>
<?php if (strncmp($this->getTranslator()->getLocale(), 'en', 2) !== 0): ?>
    <script src="<?=$this->getStaticUrl('js/tempus-dominus/dist/locales/'.substr($this->getTranslator()->getLocale(), 0, 2).'.js') ?>" charset="UTF-8"></script>
<?php endif; ?>
<script>
$(document).ready(function() {
    new tempusDominus.TempusDominus(document.getElementById('date'), {
        display: {
            calendarWeeks: true,
            buttons: {
                today: true,
                close: true
            },
            components: {
                clock: false
            }
        },
        localization: {
            locale: "<?=substr($this->getTranslator()->getLocale(), 0, 2) ?>",
            startOfTheWeek: 1,
            format: "dd.MM.yyyy"
        }
    });

    // $(".form_datetime").datetimepicker({
        // format: "dd.mm.yyyy",
        // autoclose: true,
        // language: '<?=substr($this->getTranslator()->getLocale(), 0, 2) ?>',
        // minView: 2,
        // todayHighlight: true
    // });

    $("#clearImage").click(function(){
            $("#selectedImage").val('');
    });
});

<?=$this->getMedia()
    ->addMediaButton($this->getUrl('admin/media/iframe/index/type/single/'))
    ->addUploadController($this->getUrl('admin/media/index/upload'))
?>
</script>
