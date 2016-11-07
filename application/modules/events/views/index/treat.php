<?php $settingMapper = $this->get('settingMapper'); ?>

<link href="<?=$this->getStaticUrl('js/datetimepicker/css/bootstrap-datetimepicker.min.css') ?>" rel="stylesheet">

<?php include APPLICATION_PATH.'/modules/events/views/index/navi.php'; ?>
<legend>
    <?php
    if ($this->get('event') != '') {
        echo $this->getTrans('edit');
    } else {
        echo $this->getTrans('add');
    }
    ?>
</legend>
<?php if ($this->validation()->hasErrors()): ?>
    <div class="alert alert-danger" role="alert">
        <strong> <?=$this->getTrans('errorsOccured') ?>:</strong>
        <ul>
            <?php foreach ($this->validation()->getErrorMessages() as $error): ?>
                <li><?= $error; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
<form class="form-horizontal" method="POST" enctype="multipart/form-data" action="">
    <?=$this->getTokenField() ?>
    <div class="form-group">
        <div class="col-lg-2 control-label"><?=$this->getTrans('image') ?></div>
        <div class="col-lg-10">
            <?php if ($this->get('event') != '' AND $this->escape($this->get('event')->getImage()) != ''): ?>
                <div class="col-lg-7 col-sm-7 col-7">
                    <div class="row">
                        <img src="<?=$this->getBaseUrl().$this->escape($this->get('event')->getImage()) ?>" title="<?=$this->escape($this->get('event')->getTitle()) ?>">
                    </div>
                </div>
            <?php endif; ?>
            <div class="col-lg-7">
                <div class="row">
                    <?php if ($this->get('event') != '' AND $this->get('event')->getImage() != ''): ?>
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
    <div class="form-group">
        <label for="start" class="col-md-2 control-label">
            <?=$this->getTrans('startTime') ?>:
        </label>
        <div class="col-lg-4 input-group date form_datetime">
            <input type="text"
                   class="form-control"
                   id="start"
                   name="start"
                   size="16"
                   value="<?php if ($this->get('event') != '') { echo date('d.m.Y H:i', strtotime($this->get('event')->getStart())); } ?>"
                   readonly>
            <span class="input-group-addon">
                <span class="fa fa-calendar"></span>
            </span>
        </div>
    </div>
    <div class="form-group">
        <label for="end" class="col-md-2 control-label">
            <?=$this->getTrans('endTime') ?>:
        </label>
        <div class="col-lg-4 input-group date form_datetime">
            <input type="text"
                   class="form-control"
                   id="end"
                   name="end"
                   size="16"
                   value="<?php if ($this->get('event') != '' AND $this->get('event')->getEnd() != '0000-00-00 00:00:00') { echo date('d.m.Y H:i', strtotime($this->get('event')->getEnd())); } ?>"
                   readonly>
            <span class="input-group-addon">
                <span class="fa fa-times"></span>
            </span>
            <span class="input-group-addon">
                <span class="fa fa-calendar"></span>
            </span>
        </div>
    </div>
    <div class="form-group <?=$this->validation()->hasError('title') ? 'has-error' : '' ?>">
        <label for="title" class="col-lg-2 control-label">
            <?=$this->getTrans('title') ?>:
        </label>
        <div class="col-lg-6">
            <input type="text"
                   class="form-control"
                   id="title"
                   name="title"
                   value="<?=($this->get('event') != '') ? $this->escape($this->get('event')->getTitle()) : $this->originalInput('title') ?>" />
        </div>
    </div>
    <div class="form-group <?=$this->validation()->hasError('place') ? 'has-error' : '' ?>">
        <label for="place" class="col-lg-2 control-label">
            <?=$this->getTrans('place') ?>:
        </label>
        <div class="col-lg-6">
            <input type="text"
                   class="form-control"
                   id="place"
                   name="place"
                   value="<?=($this->get('event') != '') ? $this->escape($this->get('event')->getPlace()) : $this->originalInput('place') ?>" />
        </div>
    </div>
    <div class="form-group <?=$this->validation()->hasError('text') ? 'has-error' : '' ?>">
        <label for="ck_1" class="col-lg-2 control-label">
            <?=$this->getTrans('text') ?>:
        </label>
        <div class="col-lg-10">
            <textarea class="form-control ckeditor"
                      id="ck_1"
                      name="text"
                      toolbar="ilch_bbcode"
                      rows="5"><?=($this->get('event') != '') ? $this->escape($this->get('event')->getText()) : $this->originalInput('text') ?></textarea>
        </div>
    </div>
    <?php if ($this->get('calendarShow') == 1): ?>
        <div class="form-group">
            <div class="col-lg-offset-2 col-lg-10">
                <input type="checkbox"
                       id="calendarShow"
                       name="calendarShow"
                       value="1"
                       <?php if (($this->get('event') != '' AND $this->get('event')->getShow() == 1) OR $this->originalInput('calendarShow') == 1) { echo 'checked'; } ?> />
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

<?=$this->getDialog("smiliesModal", $this->getTrans('smilies'), "<iframe frameborder='0'></iframe>"); ?>
<script type="text/javascript" src="<?=$this->getStaticUrl('js/datetimepicker/js/bootstrap-datetimepicker.min.js') ?>" charset="UTF-8"></script>
<?php if (substr($this->getTranslator()->getLocale(), 0, 2) != 'en'): ?>
    <script type="text/javascript" src="<?=$this->getStaticUrl('js/datetimepicker/js/locales/bootstrap-datetimepicker.'.substr($this->getTranslator()->getLocale(), 0, 2).'.js') ?>" charset="UTF-8"></script>
<?php endif; ?>
<?php if ($this->get('event_google_maps_api_key') != ''): ?>
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&key=<?=$this->get('event_google_maps_api_key') ?>&libraries=places&region=<?=substr($this->getTranslator()->getLocale(), 0, 2) ?>"></script>
<?php endif; ?>
<script type="text/javascript">
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

// Google Maps Place
<?php if ($this->get('event_google_maps_api_key') != ''): ?>
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
