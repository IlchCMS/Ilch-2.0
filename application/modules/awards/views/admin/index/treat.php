<?php

/** @var \Ilch\View $this */

/** @var \Modules\Awards\Mapper\Awards $awardsMapper */
$awardsMapper = $this->get('awardsMapper');
/** @var \Modules\Awards\Models\Awards $award */
$award = $this->get('award');

/** @var \Modules\Teams\Models\Teams[]|null $teams */
$teams = $this->get('teams');
/** @var \Modules\Users\Models\User[] $users */
$users = $this->get('users');
?>

<link href="<?=$this->getModuleUrl('static/css/awards.css') ?>" rel="stylesheet">
<link href="<?=$this->getStaticUrl('js/tempus-dominus/dist/css/tempus-dominus.min.css') ?>" rel="stylesheet">

<h1><?=$this->getTrans($award->getId() ? 'edit' : 'add') ?></h1>
<form method="POST" action="<?=$this->getUrl(['action' => $this->getRequest()->getActionName(), 'id' => $this->getRequest()->getParam('id')]) ?>">
    <?=$this->getTokenField() ?>
    <div class="row mb-3<?=$this->validation()->hasError('date') ? ' has-error' : '' ?>">
        <label for="date" class="col-xl-2 col-form-label">
            <?=$this->getTrans('date') ?>:
        </label>
        <div id="date" class="col-xl-2 input-group date form_datetime">
            <input type="text"
                   class="form-control"
                   name="date"
                   id="date"
                   value="<?=$this->originalInput('date', ($award->getId() ? (new \Ilch\Date($award->getDate()))->format('d.m.Y') : (new \Ilch\Date())->format('d.m.Y')), true) ?>"
                   readonly>
            <span class="input-group-text">
                <span class="fa-solid fa-calendar"></span>
            </span>
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('rank') ? ' has-error' : '' ?>">
        <label for="rank" class="col-xl-2 col-form-label">
            <?=$this->getTrans('rank') ?>:
        </label>
        <div class="col-xl-1">
            <input type="number"
                   class="form-control"
                   id="rank"
                   name="rank"
                   min="1"
                   placeholder="1"
                   value="<?=$this->originalInput('rank', $award->getRank(), true) ?>" />
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('image') ? ' has-error' : '' ?>">
        <label for="selectedImage" class="col-xl-2 col-form-label">
            <?=$this->getTrans('image') ?>:
        </label>
        <div class="col-xl-4">
            <div class="input-group">
                <input type="text"
                       class="form-control"
                       id="selectedImage"
                       name="image"
                       placeholder="<?=$this->getTrans('httpOrMedia') ?>"
                       value="<?=$this->originalInput('image', $award->getImage(), true) ?>" />
                <span class="input-group-text">
                    <span id="clearImage" class="fa-solid fa-xmark"></span>
                </span>
                <span class="input-group-text">
                    <a id="media" href="javascript:media()"><i class="fa-regular fa-image"></i></a>
                </span>
            </div>
        </div>
    </div>
    <div class="row mb-3<?=($this->validation()->hasError('typ') || $this->validation()->hasError('utId')) ? ' has-error' : '' ?>">
        <label for="user" class="col-xl-2 col-form-label">
            <?=$this->getTrans('userTeam') ?>:
        </label>
        <div class="col-xl-2">
            <select class="form-select" id="user" name="utId[]" multiple>
                <optgroup label="<?=$this->getTrans('user') ?>">
                    <?php
                    foreach ($users as $user) {
                        $selected = '';
                        if ($this->originalInput('utId') && in_array('1_' . $user->getId(), $this->originalInput('utId'))) {
                            $selected = 'selected="selected"';
                        } else {
                            foreach ($award->getRecipients() as $recipient) {
                                if ($recipient->getUtId() === $user->getId() && $recipient->getTyp() === 1) {
                                    $selected = 'selected="selected"';
                                    break;
                                }
                            }
                        }

                        echo '<option ' . $selected . ' value="1_' . $user->getId() . '">' . $this->escape($user->getName()) . '</option>';
                    } ?>
                </optgroup>
            <?php if ($awardsMapper->existsTable('teams') && count($teams)) : ?>
                <optgroup label="<?=$this->getTrans('team') ?>">
                    <?php
                    foreach ($teams as $team) {
                        $selected = '';
                        if ($this->originalInput('utId') && in_array('2_' . $team->getId(), $this->originalInput('utId'))) {
                            $selected = 'selected="selected"';
                        } else {
                            foreach ($award->getRecipients() as $recipient) {
                                if ($recipient->getUtId() === $team->getId() && $recipient->getTyp() === 2) {
                                    $selected = 'selected="selected"';
                                    break;
                                }
                            }
                        }

                        echo '<option ' . $selected . ' value="2_' . $team->getId() . '">' . $this->escape($team->getName()) . '</option>';
                    } ?>
                </optgroup>
            <?php endif; ?>
            </select>
        </div>
    </div>
    <div class="row mb-3<?=($this->validation()->hasError('event')) ? ' has-error' : '' ?>">
        <label for="event" class="col-xl-2 col-form-label">
            <?=$this->getTrans('event') ?>:
        </label>
        <div class="col-xl-4">
            <input type="text"
                   class="form-control"
                   id="event"
                   name="event"
                   value="<?=$this->originalInput('event', $award->getEvent(), true) ?>" />
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('page') ? ' has-error' : '' ?>">
        <label for="page" class="col-xl-2 col-form-label">
            <?=$this->getTrans('page') ?>:
        </label>
        <div class="col-xl-4">
            <input type="text"
                   class="form-control"
                   name="page"
                   id="page"
                   placeholder="https://"
                   value="<?=$this->originalInput('page', $award->getURL(), true) ?>" />
        </div>
    </div>
    <?=$this->getSaveBar($award->getId() ? 'updateButton' : 'addButton') ?>
</form>

<?=$this->getDialog('mediaModal', $this->getTrans('media'), '<iframe frameborder="0"></iframe>') ?>
<script src="<?=$this->getStaticUrl('js/popper/dist/umd/popper.min.js') ?>" charset="UTF-8"></script>
<script src="<?=$this->getStaticUrl('js/tempus-dominus/dist/js/tempus-dominus.min.js') ?>" charset="UTF-8"></script>
<?php if (strncmp($this->getTranslator()->getLocale(), 'en', 2) !== 0) : ?>
    <script src="<?=$this->getStaticUrl('js/tempus-dominus/dist/locales/' . substr($this->getTranslator()->getLocale(), 0, 2) . '.js') ?>" charset="UTF-8"></script>
<?php endif; ?>
<script>
$(document).ready(function() {
    if ("<?=substr($this->getTranslator()->getLocale(), 0, 2) ?>" !== 'en') {
        tempusDominus.loadLocale(tempusDominus.locales.<?=substr($this->getTranslator()->getLocale(), 0, 2) ?>);
        tempusDominus.locale(tempusDominus.locales.<?=substr($this->getTranslator()->getLocale(), 0, 2) ?>.name);
    }

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

    $("#clearImage").click(function(){
            $("#selectedImage").val('');
    });
});

<?=$this->getMedia()
    ->addMediaButton($this->getUrl('admin/media/iframe/index/type/single/'))
    ->addUploadController($this->getUrl('admin/media/index/upload'))
?>
</script>
