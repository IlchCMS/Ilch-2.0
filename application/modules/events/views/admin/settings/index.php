<link href="<?=$this->getModuleUrl('static/css/events.css') ?>" rel="stylesheet">

<legend><?=$this->getTrans('menuSettings') ?></legend>
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
<form class="form-horizontal" method="POST" action="<?=$this->getUrl(['action' => $this->getRequest()->getActionName()]) ?>">
    <?=$this->getTokenField() ?>
    <div class="form-group <?=$this->validation()->hasError('event_height') ? 'has-error' : '' ?>">
        <label for="event_height" class="col-lg-2 control-label">
            <?=$this->getTrans('imageHeight') ?>:
        </label>
        <div class="col-lg-2">
            <input type="number"
                   class="form-control required"
                   id="event_height"
                   name="event_height"
                   min="1"
                   value="<?=$this->get('event_height') ?>" 
                   required />
        </div>
    </div>
    <div class="form-group <?=$this->validation()->hasError('event_width') ? 'has-error' : '' ?>">
        <label for="event_width" class="col-lg-2 control-label">
            <?=$this->getTrans('imageWidth') ?>:
        </label>
        <div class="col-lg-2">
            <input type="number"
                   class="form-control required"
                   id="event_width"
                   name="event_width"
                   min="1"
                   value="<?=$this->get('event_width') ?>"
                   required />
        </div>
    </div>
    <div class="form-group <?=$this->validation()->hasError('event_size') ? 'has-error' : '' ?>">
        <label for="event_size" class="col-lg-2 control-label">
            <?=$this->getTrans('imageSizeBytes') ?>:
        </label>
        <div class="col-lg-2">
            <input type="number"
                   class="form-control required"
                   id="event_size"
                   name="event_size"
                   min="1"
                   value="<?=$this->get('event_size') ?>"
                   required />
        </div>
    </div>
    <div class="form-group <?=$this->validation()->hasError('event_filetypes') ? 'has-error' : '' ?>">
        <label for="event_filetypes" class="col-lg-2 control-label">
            <?=$this->getTrans('imageAllowedFileExtensions') ?>:
        </label>
        <div class="col-lg-2">
            <input type="text"
                   class="form-control required"
                   id="event_filetypes"
                   name="event_filetypes"
                   value="<?=$this->get('event_filetypes') ?>"
                   required />
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
            <input type="text"
                   class="form-control"
                   id="event_google_maps_api_key"
                   name="event_google_maps_api_key"
                   value="<?=$this->get('event_google_maps_api_key') ?>" />
        </div>
    </div>
    <div class="form-group <?=$this->validation()->hasError('event_google_maps_map_typ') ? 'has-error' : '' ?>">
        <label for="event_google_maps_map_typ" class="col-lg-2 control-label">
            <?=$this->getTrans('googleMapsMapTyp') ?>:
        </label>
        <div class="col-lg-2">
            <select class="form-control" id="event_google_maps_map_typ" name="event_google_maps_map_typ">
                <option <?php if ($this->get('event_google_maps_map_typ') == 'ROADMAP') { echo 'selected="selected"'; } ?> value="ROADMAP">ROADMAP</option>
                <option <?php if ($this->get('event_google_maps_map_typ') == 'SATELLITE') { echo 'selected="selected"'; } ?> value="SATELLITE">SATELLITE</option>
                <option <?php if ($this->get('event_google_maps_map_typ') == 'HYBRID') { echo 'selected="selected"'; } ?> value="HYBRID">HYBRID</option>
                <option <?php if ($this->get('event_google_maps_map_typ') == 'TERRAIN') { echo 'selected="selected"'; } ?> value="TERRAIN">TERRAIN</option>
            </select>
        </div>
    </div>
    <div class="form-group <?=$this->validation()->hasError('event_google_maps_zoom') ? 'has-error' : '' ?>">
        <label for="event_google_maps_zoom" class="col-lg-2 control-label">
            <?=$this->getTrans('googleMapsZoom') ?>:
        </label>
        <div class="col-lg-1">
            <input type="number"
                   class="form-control"
                   id="event_google_maps_zoom"
                   name="event_google_maps_zoom"
                   min="1"
                   value="<?=$this->get('event_google_maps_zoom') ?>"
                   required />
        </div>
    </div>

    <legend><?=$this->getTrans('menuBoxes') ?></legend>
    <div class="form-group <?=$this->validation()->hasError('event_box_event_limit') ? 'has-error' : '' ?>">
        <label for="event_box_event_limit" class="col-lg-2 control-label">
            <?=$this->getTrans('boxEventLimit') ?>:
        </label>
        <div class="col-lg-1">
            <input type="number"
                   class="form-control"
                   id="event_box_event_limit"
                   name="event_box_event_limit"
                   min="1"
                   value="<?=$this->get('event_box_event_limit') ?>"
                   required />
        </div>
    </div>
    <?=$this->getSaveBar() ?>
</form>

<?=$this->getDialog('googleMapsAPIInfoModal', $this->getTrans('createGoogleMapsAPIKey'), $this->getTrans('googleMapsAPIKeyInfoText')); ?>
