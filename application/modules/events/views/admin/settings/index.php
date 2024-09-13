<?php

/** @var \Ilch\View $this */
?>
<link href="<?=$this->getModuleUrl('static/css/events.css') ?>" rel="stylesheet">

<h1><?=$this->getTrans('menuSettings') ?></h1>
<form method="POST" action="">
    <?=$this->getTokenField() ?>
    <div class="row mb-3">
        <label for="event_add_entries_accesses" class="col-xl-2 col-form-label">
            <?=$this->getTrans('addEntriesGroupAccesses') ?>
        </label>
        <div class="col-xl-3">
            <select class="form-control choices-select"
                    id="event_add_entries_accesses"
                    name="event_add_entries_accesses[]"
                    data-placeholder="<?=$this->getTrans('selectGroupAccesses') ?>"
                    multiple>
                <?php
                /** @var \Modules\User\Models\Group $group */
                foreach ($this->get('userGroupList') as $group) : ?>
                    <?php if ($group->getId() != 1 && $group->getId() != 3) : ?>
                        <option value="<?=$group->getId() ?>"
                            <?php $addEntriesAccessesIds = explode(',', $this->get('event_add_entries_accesses'));
                            foreach ($addEntriesAccessesIds as $addEntriesAccessesId) {
                                if ($group->getId() == $addEntriesAccessesId) {
                                    echo 'selected="selected"';
                                    break;
                                }
                            }
                            ?>>
                            <?=$group->getName() ?>
                        </option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="row mb-3">
        <label for="event_show_members_accesses" class="col-xl-2 col-form-label">
            <?=$this->getTrans('showEntryMembersAccesses') ?>
        </label>
        <div class="col-xl-3">
            <select class="form-control choices-select"
                    id="event_show_members_accesses"
                    name="event_show_members_accesses[]"
                    data-placeholder="<?=$this->getTrans('selectGroupAccesses') ?>"
                    multiple>
                <?php
                /** @var \Modules\User\Models\Group $group */
                foreach ($this->get('userGroupList') as $group) : ?>
                    <?php if ($group->getId() != 1) : ?>
                        <option value="<?=$group->getId() ?>"
                            <?php $addEntriesAccessesIds = explode(',', $this->get('event_show_members_accesses'));
                            foreach ($addEntriesAccessesIds as $addEntriesAccessesId) {
                                if ($group->getId() == $addEntriesAccessesId) {
                                    echo 'selected="selected"';
                                    break;
                                }
                            }
                            ?>>
                            <?=$group->getName() ?>
                        </option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('event_upcoming_event_limit') ? ' has-error' : '' ?>">
        <label for="event_upcoming_event_limit" class="col-xl-2 col-form-label">
            <?=$this->getTrans('upcomingEventLimit') ?>:
        </label>
        <div class="col-xl-1">
            <input type="number"
                   class="form-control"
                   id="event_upcoming_event_limit"
                   name="event_upcoming_event_limit"
                   min="1"
                   value="<?=$this->get('event_upcoming_event_limit') ?>"
                   required />
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('event_current_event_limit') ? ' has-error' : '' ?>">
        <label for="event_current_event_limit" class="col-xl-2 col-form-label">
            <?=$this->getTrans('currentEventLimit') ?>:
        </label>
        <div class="col-xl-1">
            <input type="number"
                   class="form-control"
                   id="event_current_event_limit"
                   name="event_current_event_limit"
                   min="1"
                   value="<?=$this->get('event_current_event_limit') ?>"
                   required />
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('event_past_event_limit') ? ' has-error' : '' ?>">
        <label for="event_past_event_limit" class="col-xl-2 col-form-label">
            <?=$this->getTrans('pastEventLimit') ?>:
        </label>
        <div class="col-xl-1">
            <input type="number"
                   class="form-control"
                   id="event_past_event_limit"
                   name="event_past_event_limit"
                   min="1"
                   value="<?=$this->get('event_past_event_limit') ?>"
                   required />
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('event_height') ? ' has-error' : '' ?>">
        <label for="event_height" class="col-xl-2 col-form-label">
            <?=$this->getTrans('imageHeight') ?>:
        </label>
        <div class="col-xl-2">
            <input type="number"
                   class="form-control required"
                   id="event_height"
                   name="event_height"
                   min="1"
                   value="<?=$this->get('event_height') ?>"
                   required />
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('event_width') ? ' has-error' : '' ?>">
        <label for="event_width" class="col-xl-2 col-form-label">
            <?=$this->getTrans('imageWidth') ?>:
        </label>
        <div class="col-xl-2">
            <input type="number"
                   class="form-control required"
                   id="event_width"
                   name="event_width"
                   min="1"
                   value="<?=$this->get('event_width') ?>"
                   required />
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('event_size') ? ' has-error' : '' ?>">
        <label for="event_size" class="col-xl-2 col-form-label">
            <?=$this->getTrans('imageSizeBytes') ?>:
        </label>
        <div class="col-xl-2">
            <input type="number"
                   class="form-control required"
                   id="event_size"
                   name="event_size"
                   min="1"
                   value="<?=$this->get('event_size') ?>"
                   required />
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('event_filetypes') ? ' has-error' : '' ?>">
        <label for="event_filetypes" class="col-xl-2 col-form-label">
            <?=$this->getTrans('imageAllowedFileExtensions') ?>:
        </label>
        <div class="col-xl-2">
            <input type="text"
                   class="form-control required"
                   id="event_filetypes"
                   name="event_filetypes"
                   value="<?=$this->get('event_filetypes') ?>"
                   required />
        </div>
    </div>

    <h1><?=$this->getTrans('menuGoogleMaps') ?></h1>
    <div class="row mb-3">
        <label for="event_google_maps_api_key" class="col-xl-2 col-form-label">
            <?=$this->getTrans('googleMapsAPIKey') ?>:
            <a class="badge rounded-pill bg-secondary" data-bs-toggle="modal" data-bs-target="#googleMapsAPIInfoModal">
                <i class="fa-solid fa-info"></i>
            </a>
        </label>
        <div class="col-xl-3">
            <input type="text"
                   class="form-control"
                   id="event_google_maps_api_key"
                   name="event_google_maps_api_key"
                   value="<?=$this->get('event_google_maps_api_key') ?>" />
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('event_google_maps_map_typ') ? ' has-error' : '' ?>">
        <label for="event_google_maps_map_typ" class="col-xl-2 col-form-label">
            <?=$this->getTrans('googleMapsMapTyp') ?>:
        </label>
        <div class="col-xl-2">
            <select class="form-select" id="event_google_maps_map_typ" name="event_google_maps_map_typ">
                <?php foreach (['ROADMAP', 'SATELLITE', 'HYBRID', 'TERRAIN'] as $type) : ?>
                    <option<?=($this->get('event_google_maps_map_typ') === $type) ? ' selected="selected"' : '' ?> value="<?=$type ?>>"><?=$type ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('event_google_maps_zoom') ? ' has-error' : '' ?>">
        <label for="event_google_maps_zoom" class="col-xl-2 col-form-label">
            <?=$this->getTrans('googleMapsZoom') ?>:
        </label>
        <div class="col-xl-1">
            <input type="number"
                   class="form-control"
                   id="event_google_maps_zoom"
                   name="event_google_maps_zoom"
                   min="1"
                   value="<?=$this->get('event_google_maps_zoom') ?>"
                   required />
        </div>
    </div>

    <h1><?=$this->getTrans('menuBoxes') ?></h1>
    <div class="row mb-3<?=$this->validation()->hasError('event_box_event_limit') ? ' has-error' : '' ?>">
        <label for="event_box_event_limit" class="col-xl-2 col-form-label">
            <?=$this->getTrans('boxEventLimit') ?>:
        </label>
        <div class="col-xl-1">
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

<?=$this->getDialog('googleMapsAPIInfoModal', $this->getTrans('createGoogleMapsAPIKey'), $this->getTrans('googleMapsAPIKeyInfoText')) ?>

<script>
    $(document).ready(function() {
        new Choices('#event_add_entries_accesses', {
            removeItemButton: true,
            searchEnabled: true,
            shouldSort: false,
            itemSelectText: ''
        })
        new Choices('#event_show_members_accesses', {
            removeItemButton: true,
            searchEnabled: true,
            shouldSort: false,
            itemSelectText: ''
        })
    });
</script>
