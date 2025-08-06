<?php

/** @var \Ilch\View $this */

/** @var \Ilch\Config\Database $config */
$config = \Ilch\Registry::get('config');

/** @var \Modules\User\Mappers\Setting $settingMapper */
$settingMapper = $this->get('settingMapper');
/** @var \Modules\User\Mappers\User $userMapper */
$userMapper = $this->get('userMapper');
$users = $userMapper->getUserList();

$groupAccesses = explode(',', $config->get('event_add_entries_accesses'));
/** @var string[] $types */
$types = $this->get('types');

/** @var \Modules\Events\Models\Events $event */
$event = $this->get('event');
?>

<link href="<?=$this->getStaticUrl('js/tempus-dominus/dist/css/tempus-dominus.min.css') ?>" rel="stylesheet">
<link href="<?=$this->getStaticUrl('css/bootstrap-choices.css') ?>" rel="stylesheet">
<link href="<?=$this->getStaticUrl('js/choices/build/choices.min.css') ?>" rel="stylesheet">

<?php include APPLICATION_PATH . '/modules/events/views/index/navi.php'; ?>

<h1><?=$this->getTrans($event->getId() ? 'edit' : 'add') ?></h1>
<?php if ($this->getUser() && (in_array($this->getUser()->getId(), $groupAccesses) || $this->getUser()->hasAccess('module_events'))) : ?>
    <form method="POST" enctype="multipart/form-data" action="">
        <?=$this->getTokenField() ?>
        <div class="row mb-3">
            <div class="col-xl-2 col-form-label"><?=$this->getTrans('image') ?></div>
            <div class="col-xl-10">
                <?php if ($event->getId() && $this->escape($event->getImage()) != '') : ?>
                    <div class="col-xl-7 col-md-7 col-7">
                        <div class="row">
                            <img src="<?=$this->getBaseUrl() . $this->escape($event->getImage()) ?>" title="<?=$this->escape($event->getTitle()) ?>" alt="<?=$this->escape($event->getTitle()) ?>">
                        </div>
                    </div>
                <?php endif; ?>
                <div class="col-xl-7">
                    <div class="row">
                        <?php if ($event->getId() && $event->getImage() != '') : ?>
                            <label style="margin-left: 10px; margin-top: 10px;">
                                <input type="checkbox" id="image_delete" name="image_delete"> <?=$this->getTrans('deleteImage') ?>
                            </label>
                        <?php endif; ?>

                        <p>
                            <?=$this->getTrans('imageSize') ?>: <?=$this->get('image_width') ?> Pixel <?=$this->getTrans('width') ?>, <?=$this->get('image_height') ?> Pixel <?=$this->getTrans('height') ?>.<br />
                            <?=$this->getTrans('maxFilesize') ?>: <?=$settingMapper->getNicebytes($this->get('image_size')) ?>.<br />
                            <?=$this->getTrans('imageAllowedFileExtensions') ?>: <?=str_replace(' ', ', ', $this->get('image_filetypes')) ?>
                        </p>
                    </div>
                </div>
                <div class="input-group col-xl-7">
                    <span class="btn btn-outline-secondary btn-file">
                        <?=$this->getTrans('browse') ?> <input type="file" name="image" accept="image/*">
                    </span>
                    <input type="text"
                           class="form-control"
                           readonly />
                </div>
            </div>
        </div>
        <div class="row mb-3<?=$this->validation()->hasError('creator') ? ' has-error' : '' ?>">
            <label for="creator" class="col-xl-2 col-form-label">
                <?=$this->getTrans('by') ?>
            </label>
            <div class="col-xl-4">
                <select class="form-select" name="creator" id="creator">
                    <option selected="selected" value="0"><?=$this->getTrans('noSelection') ?></option>
                    <?php foreach ($users as $user) : ?>
                        <option value="<?=$user->getId() ?>" <?=($this->originalInput('creator', $event->getUserId()) == $user->getId()) ? 'selected="selected"' : '' ?>><?=$user->getName() ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <?php
            $startDate = new \Ilch\Date($this->originalInput('start', $event->getStart()));
            $endDate = new \Ilch\Date($this->originalInput('end', $event->getEnd()));
        ?>
        <div class="row mb-3<?=$this->validation()->hasError('start') ? ' has-error' : '' ?>">
            <label for="start" class="col-lg-2 col-form-label">
                <?=$this->getTrans('startTime') ?>
            </label>
            <div id="start" class="col-xl-4 input-group date form_datetime">
                <input type="text"
                       class="form-control"
                       id="start"
                       name="start"
                       size="16"
                       value="<?=$startDate->format('d.m.Y H:i') ?>"
                       readonly>
                <span class="input-group-text">
                    <span class="fa-regular fa-calendar"></span>
                </span>
            </div>
        </div>
        <div class="row mb-3<?=$this->validation()->hasError('end') ? ' has-error' : '' ?>">
            <label for="end" class="col-lg-2 col-form-label">
                <?=$this->getTrans('endTime') ?>
            </label>
            <div id="end" class="col-xl-4 input-group date form_datetime">
                <input type="text"
                       class="form-control"
                       id="end"
                       name="end"
                       size="16"
                       value="<?=$endDate->format('d.m.Y H:i') ?>"
                       readonly>
                <span class="input-group-text">
                    <span class="fa-solid fa-xmark"></span>
                </span>
                <span class="input-group-text">
                    <span class="fa-regular fa-calendar"></span>
                </span>
            </div>
        </div>
        <div class="row mb-3<?=$this->validation()->hasError('title') ? ' has-error' : '' ?>">
            <label for="title" class="col-xl-2 col-form-label">
                <?=$this->getTrans('title') ?>
            </label>
            <div class="col-xl-6">
                <input type="text"
                       class="form-control"
                       id="title"
                       name="title"
                       value="<?=$this->originalInput('title', $event->getTitle(), true) ?>" />
            </div>
        </div>
        <div class="row mb-3<?=$this->validation()->hasError('place') ? ' has-error' : '' ?>">
            <label for="place" class="col-xl-2 col-form-label">
                <?=$this->getTrans('place') ?>
            </label>
            <div class="col-xl-6">
                <input type="text"
                       class="form-control"
                       id="place"
                       name="place"
                       value="<?=$this->originalInput('place', $event->getPlace(), true) ?>" />
            </div>
        </div>
        <div class="row mb-3<?=$this->validation()->hasError('type') ? ' has-error' : '' ?>">
            <label for="type" class="col-xl-2 col-form-label">
                <label for="typeselection">
                    <?=$this->getTrans('type') ?>
                </label>
            </label>
            <div class="col-xl-6">
                <select class="form-select typeselection mb-3" name="typeselection" id="typeselection">
                    <option value=""><?=$this->getTrans('otherOrNone') ?></option>
                    <?php foreach ($types as $type) : ?>
                        <?php if (empty($type)) {
                            continue;
                        } ?>
                        <option value="<?=$this->escape($type) ?>"<?=($this->originalInput('type', $event->getType()) == $type) ? ' selected="selected"' : '' ?>><?=$this->escape($type) ?></option>
                    <?php endforeach; ?>
                </select>
                <input type="text"
                       class="form-control type"
                       id="type"
                       name="type"
                       placeholder="<?=$this->getTrans('typePlaceholder') ?>"
                       value="<?=$this->originalInput('type', $event->getType(), true) ?>" />
            </div>
        </div>
        <div class="row mb-3<?=$this->validation()->hasError('website') ? ' has-error' : '' ?>">
            <label for="website" class="col-xl-2 col-form-label">
                <?=$this->getTrans('website') ?>
            </label>
            <div class="col-xl-6">
                <input type="text"
                       class="form-control"
                       id="website"
                       name="website"
                       placeholder="https://"
                       value="<?=$this->originalInput('website', $event->getWebsite(), true) ?>" />
            </div>
        </div>
        <div class="row mb-3<?=$this->validation()->hasError('text') ? ' has-error' : '' ?>">
            <label for="ck_1" class="col-xl-2 col-form-label">
                <?=$this->getTrans('text') ?>
            </label>
            <div class="col-xl-10">
                <textarea class="form-control ckeditor"
                          id="ck_1"
                          name="text"
                          toolbar="ilch_html_frontend"
                          rows="5"><?=$this->originalInput('text', $event->getText(), true) ?></textarea>
            </div>
        </div>
        <div class="row mb-3<?=$this->validation()->hasError('price') ? ' has-error' : '' ?>">
            <label for="price" class="col-xl-2 col-form-label">
                <label for="priceArt">
                    <label for="currency">
                        <?=$this->getTrans('price') ?>
                    </label>
                </label>
            </label>
            <div class="col-xl-2">
                <select class="form-select" id="priceArt" name="priceArt">
                    <option <?=($this->originalInput('priceArt', $event->getPriceArt()) == 0) ? 'selected="selected"' : '' ?> value="0"><?=$this->getTrans('select') ?></option>
                    <option <?=($this->originalInput('priceArt', $event->getPriceArt()) == 1) ? 'selected="selected"' : '' ?> value="1"><?=$this->getTrans('ticket') ?></option>
                </select>
            </div>
            <div class="col-xl-4">
                <input type="number"
                       class="form-control"
                       id="price"
                       name="price"
                       step="0.01"
                       min="0"
                       value="<?=$this->originalInput('price', $event->getPrice()) ?>" />
            </div>
            <div class="col-xl-2">
                <select class="form-select" id="currency" name="currency">
                    <option <?=($event != '' && $event->getPriceArt() == 0) ? 'selected="selected"' : '' ?> value="0"><?=$this->getTrans('select') ?></option>
                    <?php foreach ($this->get('currencies') as $currency) { ?>
                    <option <?=($this->originalInput('currency', $event->getCurrency()) == $currency->getId()) ? 'selected="selected"' : '' ?> value="<?=$currency->getId() ?>"><?=$this->escape($currency->getName()) ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
        <div class="row mb-3<?=$this->validation()->hasError('userLimit') ? ' has-error' : '' ?>">
            <label for="userLimit" class="col-xl-2 col-form-label">
                <?=$this->getTrans('userLimit') ?> <div class="badge rounded-pill bg-secondary" data-bs-toggle="tooltip" data-bs-custom-class="custom-tooltip" data-bs-title="<?=$this->getTrans('userLimitInfo') ?>"><i class="fa-solid fa-info"></i></div>
            </label>
            <div class="col-xl-2">
                <input type="number"
                       class="form-control"
                       id="userLimit"
                       name="userLimit"
                       step="1"
                       min="0"
                       value="<?=$this->originalInput('userLimit', $event->getUserLimit()) ?>" />
            </div>
        </div>
        <div class="row mb-3<?=$this->validation()->hasError('groups') ? ' has-error' : '' ?>">
            <label for="access" class="col-xl-2 col-form-label">
                <?=$this->getTrans('visibleFor') ?>
            </label>
            <div class="col-xl-6">
                <select class="choices-select form-control" id="access" name="groups[]" data-placeholder="<?=$this->getTrans('selectAssignedGroups') ?>" multiple>
                    <?php foreach ($this->get('userGroupList') as $groupList) : ?>
                        <?php if ($groupList->getId() != 1) : ?>
                            <option value="<?=$groupList->getId() ?>"<?=(in_array($groupList->getId(), $this->originalInput('groups', $this->get('groups')))) ? ' selected' : '' ?>><?=$groupList->getName() ?></option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <?php if ($this->get('calendarShow') == 1) : ?>
            <div class="row mb-3<?=$this->validation()->hasError('calendarShow') ? ' has-error' : '' ?>">
                <div class="offset-xl-2 col-xl-10">
                    <input type="checkbox"
                           id="calendarShow"
                           name="calendarShow"
                           value="1"
                           <?=($this->originalInput('calendarShow', $event->getShow()) == 1) ? 'checked' : '' ?> />
                    <label for="calendarShow">
                        <?=$this->getTrans('calendarShow') ?>
                    </label>
                </div>
            </div>
        <?php endif; ?>
        <div class="float-end">
            <?=$this->getSaveBar($event->getId() ? 'edit' : 'add') ?>
        </div>
    </form>
<?php else : ?>
    <?=$this->getTrans('noAccess') ?>
<?php endif; ?>

<?=$this->getDialog('mediaModal', $this->getTrans('media'), '<iframe frameborder="0"></iframe>'); ?>
<script src="<?=$this->getStaticUrl('js/choices/build/choices.min.js') ?>"></script>
<script src="<?=$this->getStaticUrl('js/popper/dist/umd/popper.min.js') ?>" charset="UTF-8"></script>
<script src="<?=$this->getStaticUrl('js/tempus-dominus/dist/js/tempus-dominus.min.js') ?>" charset="UTF-8"></script>
<?php if (strncmp($this->getTranslator()->getLocale(), 'en', 2) !== 0) : ?>
    <script src="<?=$this->getStaticUrl('js/tempus-dominus/dist/locales/' . substr($this->getTranslator()->getLocale(), 0, 2) . '.js') ?>" charset="UTF-8"></script>
<?php endif; ?>
<?php if ($this->get('event_google_maps_api_key') != '') : ?>
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&key=<?=$this->get('event_google_maps_api_key') ?>&libraries=places&region=<?=substr($this->getTranslator()->getLocale(), 0, 2) ?>"></script>
<?php endif; ?>
<script>
    $(document).ready(function() {
        new Choices('#access', {
            removeItemButton: true,
            searchEnabled: true,
            shouldSort: false,
            loadingText: '<?=$this->getTranslator()->trans('choicesLoadingText') ?>',
            noResultsText: '<?=$this->getTranslator()->trans('choicesNoResultsText') ?>',
            noChoicesText: '<?=$this->getTranslator()->trans('choicesNoChoicesText') ?>',
            itemSelectText: '<?=$this->getTranslator()->trans('choicesItemSelectText') ?>',
            uniqueItemText: '<?=$this->getTranslator()->trans('choicesUniqueItemText') ?>',
            customAddItemText: '<?=$this->getTranslator()->trans('choicesCustomAddItemText') ?>',
            addItemText: (value) => {
                return '<?=$this->getTranslator()->trans('choicesAddItemText') ?>'.replace(/\${value}/g, value);
            },
            removeItemIconText: '<?=$this->getTranslator()->trans('choicesRemoveItemIconText') ?>',
            removeItemLabelText: (value) => {
                return '<?=$this->getTranslator()->trans('choicesRemoveItemLabelText') ?>'.replace(/\${value}/g, value);
            },
            maxItemCount: (maxItemCount) => {
                return '<?=$this->getTranslator()->trans('choicesMaxItemText') ?>'.replace(/\${maxItemCount}/g, maxItemCount);
            },
        });
    });

$(document).ready(function() {
    if ("<?=substr($this->getTranslator()->getLocale(), 0, 2) ?>" !== 'en') {
        tempusDominus.loadLocale(tempusDominus.locales.<?=substr($this->getTranslator()->getLocale(), 0, 2) ?>);
        tempusDominus.locale(tempusDominus.locales.<?=substr($this->getTranslator()->getLocale(), 0, 2) ?>.name);
    }

    const start = new tempusDominus.TempusDominus(document.getElementById('start'), {
        restrictions: {
          minDate: new Date()
        },
        display: {
            sideBySide: true,
            calendarWeeks: true,
            buttons: {
                today: true,
                close: true
            }
        },
        localization: {
            locale: "<?=substr($this->getTranslator()->getLocale(), 0, 2) ?>",
            startOfTheWeek: 1,
            format: "dd.MM.yyyy HH:mm"
        },
        stepping: 15
    });

    const end = new tempusDominus.TempusDominus(document.getElementById('end'), {
        restrictions: {
          minDate: new Date()
        },
        display: {
            sideBySide: true,
            calendarWeeks: true,
            buttons: {
                today: true,
                close: true
            }
        },
        localization: {
            locale: "<?=substr($this->getTranslator()->getLocale(), 0, 2) ?>",
            startOfTheWeek: 1,
            format: "dd.MM.yyyy HH:mm"
        },
        stepping: 15
    });

    start.subscribe('change.td', (e) => {
        end.updateOptions({
            restrictions: {
                minDate: e.date,
            },
        });
    });

    end.subscribe('change.td', (e) => {
        start.updateOptions({
            restrictions: {
                maxDate: e.date,
            },
        });
    });
});

$(document).on('change', '.btn-file :file', function() {
    var input = $(this),
        numFiles = input.get(0).files ? input.get(0).files.length : 1,
        label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
    input.trigger('fileselect', [numFiles, label]);
});

$(document).ready(function() {
    $('.btn-file :file').on('fileselect', function(event, numFiles, label) {
        var input = $(this).parents('.input-group').find(':text'),
            log = numFiles > 1 ? numFiles + ' files selected' : label;

        if (input.length) {
            input.val(log);
        } else {
            if (log) alert(log);
        }
    });
});

$(document).ready(function() {
    $( ".typeselection" ).change(function() {
        $( ".type" ).val(this.value);
    });

    $( ".type" ).change(function() {
        let found = false;
        let typeselection = $(".typeselection");

        for (var i = 0; i < typeselection[0].options.length; ++i) {
            if (this.value === typeselection[0].options[i].value) {
                found = true;
                typeselection.val(this.value);
                break;
            }
        }

        if (!found) {
            typeselection.val($("#typeselection option:first").val());
        }
    });
});

<?php if ($this->get('event_google_maps_api_key') != '') : ?>
    // Google Maps Place
    var pac_input = document.getElementById('place');

    (function pacSelectFirst(input){
        // store the original event binding function
        var _addEventListener = (input.addEventListener) ? input.addEventListener : input.attachEvent;

        function addEventListenerWrapper(type, listener) {
        // Simulate a 'down arrow' keypress on hitting 'return' when no pac suggestion is selected,
        // and then trigger the original listener.

        if (type == "keydown") {
          var orig_listener = listener;
          listener = function (event) {
            var suggestion_selected = $(".pac-item-selected").length > 0;
            if (event.which == 13 && !suggestion_selected) {
              var simulated_downarrow = $.Event("keydown", {keyCode:40, which:40});
              orig_listener.apply(input, [simulated_downarrow]);
            }

            orig_listener.apply(input, [event]);
          };
        }

        // add the modified listener
        _addEventListener.apply(input, [type, listener]);
      }

      if (input.addEventListener)
        input.addEventListener = addEventListenerWrapper;
      else if (input.attachEvent)
        input.attachEvent = addEventListenerWrapper;

    })(pac_input);

    $(function() {
      var autocomplete = new google.maps.places.Autocomplete(pac_input);
    });
<?php endif; ?>
</script>
