<link href="<?=$this->getModuleUrl('static/css/events.css') ?>" rel="stylesheet">

<legend><?=$this->getTrans('menuSettings') ?></legend>
<form class="form-horizontal" method="POST" action="<?=$this->getUrl(['action' => $this->getRequest()->getActionName()]) ?>">
    <?=$this->getTokenField() ?>
    <div class="form-group">
        <label for="event_height" class="col-lg-2 control-label">
            <?=$this->getTrans('imageHeight') ?>:
        </label>
        <div class="col-lg-2">
            <input name="event_height"
                   type="text"
                   id="event_height"
                   class="form-control required"
                   value="<?=$this->get('event_height') ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="event_width" class="col-lg-2 control-label">
            <?=$this->getTrans('imageWidth') ?>:
        </label>
        <div class="col-lg-2">
            <input name="event_width"
                   type="text"
                   id="event_width"
                   class="form-control required"
                   value="<?=$this->get('event_width') ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="event_size" class="col-lg-2 control-label">
            <?=$this->getTrans('imageSizeBytes') ?>:
        </label>
        <div class="col-lg-2">
            <input name="event_size"
                   type="text"
                   id="event_size"
                   class="form-control required"
                   value="<?=$this->get('event_size') ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="event_filetypes" class="col-lg-2 control-label">
            <?=$this->getTrans('imageAllowedFileExtensions') ?>:
        </label>
        <div class="col-lg-2">
            <input name="event_filetypes"
                   type="text"
                   id="event_filetypes"
                   class="form-control required"
                   value="<?=$this->get('event_filetypes') ?>" />
        </div>
    </div>

    <legend><?=$this->getTrans('menuGoogleMaps') ?></legend>
    <div class="form-group">
        <label for="event_google_maps_api_key" class="col-lg-2 control-label">
            <?=$this->getTrans('googleMapsAPIKey') ?>:
            <a class="badge" data-toggle="modal" data-target="#googleMapsAPIInfoModal">
                <i class="fa fa-info" ></i>
            </a>
        </label>
        <div class="col-lg-3">
            <input name="event_google_maps_api_key"
                   type="text"
                   id="event_google_maps_api_key"
                   class="form-control"
                   value="<?=$this->get('event_google_maps_api_key') ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="event_google_maps_map_typ" class="col-lg-2 control-label">
            <?=$this->getTrans('googleMapsMapTyp') ?>:
        </label>
        <div class="col-lg-2">
            <select class="form-control" name="event_google_maps_map_typ" id="event_google_maps_map_typ">
                <option <?php if ($this->get('event_google_maps_map_typ') == 'ROADMAP') { echo 'selected="selected"'; } ?> value="ROADMAP">ROADMAP</option>
                <option <?php if ($this->get('event_google_maps_map_typ') == 'SATELLITE') { echo 'selected="selected"'; } ?> value="SATELLITE">SATELLITE</option>
                <option <?php if ($this->get('event_google_maps_map_typ') == 'HYBRID') { echo 'selected="selected"'; } ?> value="HYBRID">HYBRID</option>
                <option <?php if ($this->get('event_google_maps_map_typ') == 'TERRAIN') { echo 'selected="selected"'; } ?> value="TERRAIN">TERRAIN</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label for="event_google_maps_zoom" class="col-lg-2 control-label">
            <?=$this->getTrans('googleMapsZoom') ?>:
        </label>
        <div class="col-lg-2">
            <div class="input-group spinner">
                <input name="event_google_maps_zoom"
                       type="text"
                       id="event_google_maps_zoom"
                       class="form-control"
                       min="1"
                       value="<?=$this->get('event_google_maps_zoom') ?>" />
                <div class="input-group-btn-vertical">
                    <span class="btn btn-default"><i class="fa fa-caret-up"></i></span>
                    <span class="btn btn-default"><i class="fa fa-caret-down"></i></span>
                </div>
            </div>
        </div>
    </div>
    <?=$this->getSaveBar() ?>
</form>

<?=$this->getDialog('googleMapsAPIInfoModal', $this->getTrans('createGoogleMapsAPIKey'), $this->getTrans('googleMapsAPIKeyInfoText')); ?>

<script language="JavaScript" type="text/javascript">
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
</script>
