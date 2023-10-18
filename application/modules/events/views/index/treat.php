<?php
$settingMapper = $this->get('settingMapper');
$userMapper = $this->get('userMapper');
$config = \Ilch\Registry::get('config');
$groupAccesses = explode(',', $config->get('event_add_entries_accesses'));
$users = $userMapper->getUserList();
$types = $this->get('types');
?>

<link href="<?=$this->getStaticUrl('js/datetimepicker/css/bootstrap-datetimepicker.min.css') ?>" rel="stylesheet">
<link href="<?=$this->getStaticUrl('css/chosen/bootstrap-chosen.css') ?>" rel="stylesheet">
<link href="<?=$this->getVendorUrl('harvesthq/chosen/chosen.min.css') ?>" rel="stylesheet">

<?php include APPLICATION_PATH.'/modules/events/views/index/navi.php'; ?>

<h1><?=($this->get('event') != '' ? $this->getTrans('edit') : $this->getTrans('add')) ?></h1>
<?php if ($this->getUser() && (in_array($this->getUser()->getId(), $groupAccesses) || $this->getUser()->hasAccess('module_events'))): ?>
    <form class="form-horizontal" method="POST" enctype="multipart/form-data" action="">
        <?=$this->getTokenField() ?>
        <div class="row mb-3">
            <div class="col-lg-2 control-label"><?=$this->getTrans('image') ?></div>
            <div class="col-lg-10">
                <?php if ($this->get('event') != '' && $this->escape($this->get('event')->getImage()) != ''): ?>
                    <div class="col-lg-7 col-sm-7 col-7">
                        <div class="row">
                            <img src="<?=$this->getBaseUrl().$this->escape($this->get('event')->getImage()) ?>" title="<?=$this->escape($this->get('event')->getTitle()) ?>" alt="<?=$this->escape($this->get('event')->getTitle()) ?>">
                        </div>
                    </div>
                <?php endif; ?>
                <div class="col-lg-7">
                    <div class="row">
                        <?php if ($this->get('event') != '' && $this->get('event')->getImage() != ''): ?>
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
                <div class="input-group col-lg-7">
                    <span class="input-group-btn">
                        <span class="btn btn-primary btn-file">
                            <?=$this->getTrans('browse') ?>&hellip; <input type="file" name="image" accept="image/*">
                        </span>
                    </span>
                    <input type="text"
                           class="form-control"
                           readonly />
                </div>
            </div>
        </div>
        <div class="row mb-3 <?=$this->validation()->hasError('creator') ? 'has-error' : '' ?>">
            <label for="creator" class="col-lg-2 control-label">
                <?=$this->getTrans('by') ?>
            </label>
            <div class="col-lg-4">
                <select class="form-control" name="creator" id="creator">
                    <option selected="selected"><?=$this->getTrans('noSelection') ?></option>
                    <?php foreach ($users as $user): ?>
                        <option value="<?=$user->getId() ?>" <?php if (($this->get('event') != '' && $this->get('event')->getUserId() == $user->getId()) || $this->originalInput('creator') == $user->getId()) { echo 'selected="selected"'; } ?>><?=$user->getName() ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="row mb-3 <?=$this->validation()->hasError('start') ? 'has-error' : '' ?>">
            <label for="start" class="col-md-2 control-label">
                <?=$this->getTrans('startTime') ?>
            </label>
            <div class="col-lg-4 input-group ilch-date date form_datetime">
                <input type="text"
                       class="form-control"
                       id="start"
                       name="start"
                       size="16"
                       value="<?php if ($this->get('event') != '') { echo date('d.m.Y H:i', strtotime($this->get('event')->getStart())); } elseif ($this->originalInput('start') != '') { echo date('d.m.Y H:i', strtotime($this->originalInput('start'))); } ?>"
                       readonly>
                <span class="input-group-text">
                    <span class="fa-regular fa-calendar"></span>
                </span>
            </div>
        </div>
        <div class="row mb-3 <?=$this->validation()->hasError('end') ? 'has-error' : '' ?>">
            <label for="end" class="col-md-2 control-label">
                <?=$this->getTrans('endTime') ?>
            </label>
            <div class="col-lg-4 input-group ilch-date date form_datetime">
                <input type="text"
                       class="form-control"
                       id="end"
                       name="end"
                       size="16"
                       value="<?php if ($this->get('event') != '') { echo date('d.m.Y H:i', strtotime($this->get('event')->getEnd())); } elseif ($this->originalInput('end') != '') { echo date('d.m.Y H:i', strtotime($this->originalInput('end'))); } ?>"
                       readonly>
                <span class="input-group-text">
                    <span class="fa-solid fa-xmark"></span>
                </span>
                <span class="input-group-text">
                    <span class="fa-regular fa-calendar"></span>
                </span>
            </div>
        </div>
        <div class="row mb-3 <?=$this->validation()->hasError('title') ? 'has-error' : '' ?>">
            <label for="title" class="col-lg-2 control-label">
                <?=$this->getTrans('title') ?>
            </label>
            <div class="col-lg-6">
                <input type="text"
                       class="form-control"
                       id="title"
                       name="title"
                       value="<?=($this->get('event') != '') ? $this->escape($this->get('event')->getTitle()) : $this->escape($this->originalInput('title')) ?>" />
            </div>
        </div>
        <div class="row mb-3 <?=$this->validation()->hasError('place') ? 'has-error' : '' ?>">
            <label for="place" class="col-lg-2 control-label">
                <?=$this->getTrans('place') ?>
            </label>
            <div class="col-lg-6">
                <input type="text"
                       class="form-control"
                       id="place"
                       name="place"
                       value="<?=($this->get('event') != '') ? $this->escape($this->get('event')->getPlace()) : $this->escape($this->originalInput('place')) ?>" />
            </div>
        </div>
        <div class="row mb-3 <?=$this->validation()->hasError('type') ? 'has-error' : '' ?>">
            <label for="place" class="col-lg-2 control-label">
                <?=$this->getTrans('type') ?>
            </label>
            <div class="col-lg-6">
                <select class="form-control typeselection mb-3" name="typeselection" id="typeselection">
                    <option value=""><?=$this->getTrans('otherOrNone') ?></option>
                    <?php foreach ($types as $type) : ?>
                        <?php if (empty($type)) {
                            continue;
                        } ?>
                        <option value="<?=$this->escape($type) ?>"<?=(($this->get('event') != '' && $this->get('event')->getType() == $type) || $this->originalInput('type') == $type) ? ' selected="selected"' : '' ?>><?=$this->escape($type) ?></option>
                    <?php endforeach; ?>
                </select>
                <input type="text"
                       class="form-control type"
                       id="type"
                       name="type"
                       placeholder="<?=$this->getTrans('typePlaceholder') ?>"
                       value="<?=($this->get('event') != '') ? $this->escape($this->get('event')->getType()) : $this->escape($this->originalInput('type')) ?>" />
            </div>
        </div>
        <div class="row mb-3 <?=$this->validation()->hasError('website') ? 'has-error' : '' ?>">
            <label for="website" class="col-lg-2 control-label">
                <?=$this->getTrans('website') ?>
            </label>
            <div class="col-lg-6">
                <input type="text"
                       class="form-control"
                       id="website"
                       name="website"
                       placeholder="https://"
                       value="<?=($this->get('event') != '') ? $this->escape($this->get('event')->getWebsite()) : $this->escape($this->originalInput('website')) ?>" />
            </div>
        </div>
        <div class="row mb-3 <?=$this->validation()->hasError('text') ? 'has-error' : '' ?>">
            <label for="ck_1" class="col-lg-2 control-label">
                <?=$this->getTrans('text') ?>
            </label>
            <div class="col-lg-10">
                <textarea class="form-control ckeditor"
                          id="ck_1"
                          name="text"
                          toolbar="ilch_html_frontend"
                          rows="5"><?=($this->get('event') != '') ? $this->escape($this->get('event')->getText()) : $this->escape($this->originalInput('text')) ?></textarea>
            </div>
        </div>
        <div class="row mb-3 <?=$this->validation()->hasError('price') ? 'has-error' : '' ?>">
            <label for="price" class="col-lg-2 control-label">
                <?=$this->getTrans('price') ?>
            </label>
            <div class="col-lg-2">
                <select class="form-control" id="priceArt" name="priceArt">
                    <option <?php if ($this->get('event') != '' && $this->get('event')->getPriceArt() == 0) { echo 'selected="selected"'; } ?> value="0"><?=$this->getTrans('select') ?></option>
                    <option <?php if ($this->get('event') != '' && $this->get('event')->getPriceArt() == 1) { echo 'selected="selected"'; } ?> value="1"><?=$this->getTrans('ticket') ?></option>
                    <option <?php if ($this->get('event') != '' && $this->get('event')->getPriceArt() == 2) { echo 'selected="selected"'; } ?> value="2"><?=$this->getTrans('entry') ?></option>
                </select>
            </div>
            <div class="col-lg-4">
                <input type="number"
                       class="form-control"
                       id="price"
                       name="price"
                       step="0.01"
                       min="0"
                       value="<?=($this->get('event') != '') ? $this->escape($this->get('event')->getPrice()) : $this->originalInput('price') ?>" />
            </div>
            <div class="col-lg-2">
                <select class="form-control" id="currency" name="currency">
                    <option <?php if ($this->get('event') != '' && $this->get('event')->getPriceArt() == 0) { echo 'selected="selected"'; } ?> value="0"><?=$this->getTrans('select') ?></option>
                    <?php foreach ($this->get('currencies') as $currency) {
                        if ($this->get('event') != '' && $this->get('event')->getCurrency() == $currency->getId()) {
                            echo '<option value="'.$currency->getId().'" selected="selected">'.$this->escape($currency->getName()).'</option>';
                        } else {
                            echo '<option value="'.$currency->getId().'">'.$this->escape($currency->getName()).'</option>';
                        }
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="row mb-3 <?=$this->validation()->hasError('userLimit') ? 'has-error' : '' ?>">
            <label for="userLimit" class="col-lg-2 control-label">
                <?=$this->getTrans('userLimit') ?> <div class="badge" data-toggle="event-popover" title="<?=$this->getTrans('popoverInfo') ?>" data-content="<?=$this->getTrans('userLimitInfo') ?>"><i class="fa-solid fa-info"></i></div>
            </label>
            <div class="col-lg-2">
                <input type="number"
                       class="form-control"
                       id="userLimit"
                       name="userLimit"
                       step="1"
                       min="0"
                       value="<?=($this->get('event') != '') ? $this->get('event')->getUserLimit() : $this->originalInput('userLimit') ?>" />
            </div>
        </div>
        <div class="row mb-3">
            <label for="access" class="col-lg-2 control-label">
                <?=$this->getTrans('visibleFor') ?>
            </label>
            <div class="col-lg-6">
                <select class="chosen-select form-control" id="access" name="groups[]" data-placeholder="<?=$this->getTrans('selectAssignedGroups') ?>" multiple>
                    <?php foreach ($this->get('userGroupList') as $groupList): ?>
                        <?php if ($groupList->getId() != 1): ?>
                            <option value="<?=$groupList->getId() ?>"<?=(in_array($groupList->getId(), $this->get('groups'))) ? ' selected' : '' ?>><?=$groupList->getName() ?></option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <?php if ($this->get('calendarShow') == 1): ?>
            <div class="row mb-3">
                <div class="col-lg-offset-2 col-lg-10">
                    <input type="checkbox"
                           id="calendarShow"
                           name="calendarShow"
                           value="1"
                           <?php if (($this->get('event') != '' && $this->get('event')->getShow() == 1) || $this->originalInput('calendarShow') == 1) { echo 'checked'; } ?> />
                    <label for="calendarShow">
                        <?=$this->getTrans('calendarShow') ?>
                    </label>
                </div>
            </div>
        <?php endif; ?>
        <div style="float: right;">
            <?php
            if ($this->get('event') != '') {
                echo $this->getSaveBar('edit');
            } else {
                echo $this->getSaveBar('add');
            }
            ?>
        </div>
    </form>
<?php else: ?>
    <?=$this->getTrans('noAccess') ?>
<?php endif; ?>

<script src="<?=$this->getVendorUrl('harvesthq/chosen/chosen.jquery.min.js') ?>"></script>
<script src="<?=$this->getStaticUrl('js/datetimepicker/js/bootstrap-datetimepicker.min.js') ?>" charset="UTF-8"></script>
<?php if (strncmp($this->getTranslator()->getLocale(), 'en', 2) !== 0): ?>
    <script src="<?=$this->getStaticUrl('js/datetimepicker/js/locales/bootstrap-datetimepicker.'.substr($this->getTranslator()->getLocale(), 0, 2).'.js') ?>" charset="UTF-8"></script>
<?php endif; ?>
<?php if ($this->get('event_google_maps_api_key') != ''): ?>
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&key=<?=$this->get('event_google_maps_api_key') ?>&libraries=places&region=<?=substr($this->getTranslator()->getLocale(), 0, 2) ?>"></script>
<?php endif; ?>
<script>
$('#access').chosen();

$(document).ready(function() {
    $(".form_datetime").datetimepicker({
        format: "dd.mm.yyyy hh:ii",
        startDate: new Date(),
        autoclose: true,
        language: '<?=substr($this->getTranslator()->getLocale(), 0, 2) ?>',
        minuteStep: 15,
        todayHighlight: true,
        linkField: "end",
        linkFormat: "dd.mm.yyyy hh:ii"
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

$(function () {
    $('[data-toggle="event-popover"]').popover({
        container: 'body',
        trigger: 'hover'
    })
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

<?php if ($this->get('event_google_maps_api_key') != ''): ?>
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
