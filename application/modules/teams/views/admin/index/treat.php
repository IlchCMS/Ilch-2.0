<link href="<?=$this->getModuleUrl('static/css/teams.css') ?>" rel="stylesheet">

<h1>
    <?php if ($this->get('team') != '') {
        echo $this->getTrans('edit');
    } else {
        echo $this->getTrans('add');
    }
    ?>
    <a class="badge" data-toggle="modal" data-target="#infoModal">
        <i class="fa fa-info"></i>
    </a>
</h1>
<form class="form-horizontal" method="POST" enctype="multipart/form-data">
    <?=$this->getTokenField() ?>
    <div class="form-group <?=$this->validation()->hasError('name') ? 'has-error' : '' ?>">
        <label for="name" class="col-lg-2 control-label">
            <?=$this->getTrans('teamName') ?>
        </label>
        <div class="col-lg-4">
            <input type="text"
                   class="form-control"
                   id="name"
                   name="name"
                   value="<?=($this->get('team') != '') ? $this->escape($this->get('team')->getName()) : $this->originalInput('name') ?>" />
        </div>
    </div>
    <div class="form-group <?=$this->validation()->hasError('img') ? 'has-error' : '' ?>">
        <label for="img" class="col-lg-2 control-label">
            <?=$this->getTrans('img') ?>:
        </label>
        <div class="col-lg-4">
            <div class="row">
                <?php if ($this->get('team') != '' AND $this->get('team')->getImg() != ''): ?>
                    <div class="col-lg-12">
                        <img src="<?=$this->getBaseUrl().$this->get('team')->getImg() ?>">

                        <label for="image_delete" style="margin-left: 10px; margin-top: 10px;">
                            <input type="checkbox" id="image_delete" name="image_delete"> <?=$this->getTrans('imageDelete') ?>
                        </label>
                    </div>
                <?php endif; ?>
                <div class="col-lg-12 input-group">
                    <span class="input-group-btn">
                        <span class="btn btn-primary btn-file">
                            Browse&hellip; <input type="file" name="img" accept="image/*">
                        </span>
                    </span>
                    <input type="text"
                           name="img"
                           class="form-control"
                           readonly />
                </div>
            </div>
        </div>
    </div>
    <div class="form-group <?=$this->validation()->hasError('leader') ? 'has-error' : '' ?>">
        <label for="leader" class="col-lg-2 control-label">
            <?=$this->getTrans('leader') ?>
        </label>
        <div class="col-lg-4">
            <select class="chosen-select form-control"
                    id="leader"
                    name="leader[]"
                    data-placeholder="<?=$this->getTrans('selectLeader') ?>"
                    multiple>
                <?php foreach ($this->get('userList') as $userList): ?>
                    <option value="<?=$userList->getId() ?>"
                        <?php if ($this->get('team') != '') {
                            $leaderIds = explode(',', $this->get('team')->getLeader());
                            foreach ($leaderIds as $leaderId) {
                                if ($userList->getId() == $leaderId) {
                                    echo 'selected="selected"';
                                    break;
                                }
                            }
                        }
                        ?>>
                        <?=$this->escape($userList->getName()) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="form-group <?=$this->validation()->hasError('coLeader') ? 'has-error' : '' ?>">
        <label for="coLeader" class="col-lg-2 control-label">
            <?=$this->getTrans('coLeader') ?>
        </label>
        <div class="col-lg-4">
            <select class="chosen-select form-control"
                    id="coLeader"
                    name="coLeader[]"
                    data-placeholder="<?=$this->getTrans('selectCoLeader') ?>"
                    multiple>
                <?php foreach ($this->get('userList') as $userList): ?>
                    <option value="<?=$userList->getId() ?>"
                        <?php if ($this->get('team') != '') {
                            $coLeaderIds = explode(',', $this->get('team')->getCoLeader());
                            foreach ($coLeaderIds as $coLeaderId) {
                                if ($userList->getId() == $coLeaderId) {
                                    echo 'selected="selected"';
                                    break;
                                }
                            }
                        }
                        ?>>
                        <?=$this->escape($userList->getName()) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="form-group <?=$this->validation()->hasError('members') ? 'has-error' : '' ?>">
        <label for="groupId" class="col-lg-2 control-label">
            <?=$this->getTrans('group') ?>
        </label>
        <div class="col-lg-4">
            <select class="form-control" id="groupId" name="groupId">
                <optgroup label="<?=$this->getTrans('groups') ?>">
                    <?php foreach ($this->get('userGroupList') as $groupList): ?>
                        <?php if ($groupList->getId() != 3): ?>
                            <?php $selected = ''; ?>
                            <?php if ($this->get('team') != ''): ?>
                                <?php if ($this->get('team')->getGroupId() == $groupList->getId()): ?>
                                    <?php $selected = 'selected="selected"'; ?>
                                <?php endif; ?>
                            <?php endif; ?>
                            <option <?=$selected ?> value="<?=$groupList->getId() ?>"><?=$groupList->getName() ?></option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </optgroup>
            </select>
        </div>
    </div>
    <div class="form-group <?=$this->validation()->hasError('optShow') ? 'has-error' : '' ?>">
        <label for="optShow" class="col-lg-2 control-label">
            <?=$this->getTrans('optShow') ?>:
        </label>
        <div class="col-lg-4">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" id="optShow-on" name="optShow" value="1" <?=($this->get('team') != '' AND $this->get('team')->getOptShow() == '1') ? 'checked="checked"' : ($this->get('team') == '' AND $this->originalInput('optShow') == 1) ? 'checked="checked"' : '' ?> />
                <label for="optShow-on" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('yes') ?></label>
                <input type="radio" class="flipswitch-input" id="optShow-off" name="optShow" value="0" <?=($this->get('team') != '' AND $this->get('team')->getOptShow() == '0') ? 'checked="checked"' : ($this->get('team') == '' AND $this->originalInput('optShow') == 0) ? 'checked="checked"' : '' ?> />
                <label for="optShow-off" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('no') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
        </div>
    </div>
    <div class="form-group <?=$this->validation()->hasError('optIn') ? 'has-error' : '' ?>">
        <label for="optIn" class="col-lg-2 control-label">
            <?=$this->getTrans('optIn') ?>:
        </label>
        <div class="col-lg-4">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" id="optIn-on" name="optIn" value="1" <?=($this->get('team') != '' AND $this->get('team')->getOptIn() == '1') ? 'checked="checked"' : ($this->get('team') == '' AND $this->originalInput('optIn') == 1) ? 'checked="checked"' : '' ?> />
                <label for="optIn-on" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('yes') ?></label>
                <input type="radio" class="flipswitch-input" id="optIn-off" name="optIn" value="0" <?=($this->get('team') != '' AND $this->get('team')->getOptIn() == '0') ? 'checked="checked"' : ($this->get('team') == '' AND $this->originalInput('optIn') == 0) ? 'checked="checked"' : '' ?> />
                <label for="optIn-off" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('no') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
        </div>
    </div>
    <?=($this->get('team') != '') ? $this->getSaveBar('edit') : $this->getSaveBar('add') ?>
</form>

<?=$this->getDialog("infoModal", $this->getTrans('info'), $this->getTrans('teamUsersInfoText')); ?>

<script>
$('#leader').chosen();
$('#coLeader').chosen();

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

        if (input.length) {
            input.val(log);
        } else {
            if (log) alert(log);
        }
    });
});
</script>

