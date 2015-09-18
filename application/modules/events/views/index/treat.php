<?php 
$settingMapper = new \Modules\User\Mappers\Setting();
?>

<link href="<?=$this->getStaticUrl('js/datetimepicker/css/bootstrap-datetimepicker.min.css') ?>" rel="stylesheet">

<?php include APPLICATION_PATH.'/modules/events/views/index/navi.php'; ?>
<form class="form-horizontal" method="POST" enctype="multipart/form-data" action="">
    <?=$this->getTokenField() ?>
    <legend>
        <?php if ($this->get('event') != ''): ?>
            <?=$this->getTrans('edit') ?>
        <?php else: ?>
            <?=$this->getTrans('add') ?>
        <?php endif; ?>
    </legend>
    <div class="form-group">
        <label for="place" class="col-lg-2 control-label">
            <?=$this->getTrans('image') ?>:
        </label>
        <div class="col-lg-10">
            <?php if ($this->get('event') != ''): ?>
                <div class="col-lg-7 col-sm-7 col-7">
                    <?php if ($this->escape($this->get('event')->getImage()) != ''): ?>
                        <img src="<?=$this->getBaseUrl().$this->escape($this->get('event')->getImage()) ?>" title="<?=$this->escape($this->get('event')->getTitle()) ?>">
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            <div class="col-lg-7">
                <p><?=$this->getTrans('imageSize') ?>: <?=$this->get('image_width') ?> Pixel <?=$this->getTrans('width') ?>, <?=$this->get('image_height') ?> Pixel <?=$this->getTrans('height') ?>.</p>
                <p><?=$this->getTrans('maxFilesize') ?>: <?=$settingMapper->getNicebytes($this->get('image_size')) ?>.</p>
                <p><?=$this->getTrans('imageAllowedFileExtensions') ?>: <?=str_replace(' ', ', ', $this->get('image_filetypes')) ?></p>
            </div>
            <div class="input-group col-lg-7">
                <span class="input-group-btn">
                    <span class="btn btn-primary btn-file">
                        Browse&hellip; <input type="file" name="image" accept="image/*">
                    </span>
                </span>
                <input type="text" 
                       class="form-control" 
                       readonly />
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="dtp_input1" class="col-md-2 control-label">
            <?=$this->getTrans('time') ?>:
        </label>
        <div class="col-lg-3 input-group date form_datetime">
            <input class="form-control"
                   size="16"
                   type="text"
                   name="dateCreated"
                   value="<?php if ($this->get('event') != '') { echo date('d.m.Y H:i', strtotime($this->get('event')->getdateCreated())); } ?>"
                   readonly>
            <span class="input-group-addon">
                <span class="fa fa-calendar"></span>
            </span>
        </div>
    </div>
    <div class="form-group">
        <label for="title" class="col-lg-2 control-label">
            <?=$this->getTrans('title') ?>:
        </label>
        <div class="col-lg-6">
            <input class="form-control"
                   type="text"
                   name="title"
                   id="title"
                   value="<?php if ($this->get('event') != '') { echo $this->escape($this->get('event')->getTitle()); } ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="place" class="col-lg-2 control-label">
            <?=$this->getTrans('place') ?>:
        </label>
        <div class="col-lg-6">
            <input class="form-control"
                   type="text"
                   name="place"
                   id="place"
                   value="<?php if ($this->get('event') != '') { echo $this->escape($this->get('event')->getPlace()); } ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="text" class="col-lg-2 control-label">
            <?=$this->getTrans('text') ?>:
        </label>
        <div class="col-lg-10">
            <textarea class="form-control ckeditor"
                      name="text"
                      id="ck_1"
                      toolbar="ilch_html"
                      rows="5"><?php if ($this->get('event') != '') { echo $this->escape($this->get('event')->getText()); } ?></textarea>
        </div>
    </div>
    <?php if ($this->get('calendarTable') == 1): ?>
        <div class="form-group">
            <div class="col-lg-offset-2 col-lg-10">
                <input value="1" type="checkbox" name="calendarShow" id="calendarShow" <?php if ($this->get('event') == '' OR $this->get('event')->getShow() == 1) { echo 'checked'; } ?> />
                <label for="calendarShow">
                    <?=$this->getTrans('calendarShow') ?>
                </label>            
            </div>
        </div>
    <?php endif; ?>
    <div style="float: right;">
        <?php if ($this->get('event') != ''): ?>
            <?=$this->getSaveBar('edit') ?>
        <?php else: ?>
            <?=$this->getSaveBar('add') ?>
        <?php endif; ?>
    </div>
</form>

<script type="text/javascript" src="<?=$this->getStaticUrl('js/datetimepicker/js/bootstrap-datetimepicker.js')?>" charset="UTF-8"></script>
<script type="text/javascript" src="<?=$this->getStaticUrl('js/datetimepicker/js/locales/bootstrap-datetimepicker.de.js')?>" charset="UTF-8"></script>
<script type="text/javascript">
    $( document ).ready(function() {
        $(".form_datetime").datetimepicker({
            format: "dd.mm.yyyy hh:ii",
            startDate: new Date(),
            autoclose: true,
            language: 'de',
            minuteStep: 15
        });
    });

    $(document).on('change', '.btn-file :file', function() {
        var input = $(this),
            numFiles = input.get(0).files ? input.get(0).files.length : 1,
            label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
        input.trigger('fileselect', [numFiles, label]);
    });

    $(document).ready( function() {
        $('.btn-file :file').on('fileselect', function(event, numFiles, label) {

            var input = $(this).parents('.input-group').find(':text'),
                log = numFiles > 1 ? numFiles + ' files selected' : label;

            if( input.length ) {
                input.val(log);
            } else {
                if( log ) alert(log);
            }

        });
    });
</script>
