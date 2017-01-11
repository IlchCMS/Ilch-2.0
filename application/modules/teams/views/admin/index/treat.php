<link href="<?=$this->getModuleUrl('static/css/teams.css') ?>" rel="stylesheet">

<legend>
    <?php if ($this->get('team') != '') {
        echo $this->getTrans('edit');
    } else {
        echo $this->getTrans('add');
    }
    ?>
</legend>

<!-- Fehlerausgabe der Validation -->
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
<!-- Ende Fehlerausgabe der Validation -->

<form class="form-horizontal" method="POST" action="" enctype="multipart/form-data">
    <?=$this->getTokenField() ?>
    <div class="form-group <?=$this->validation()->hasError('name') ? 'has-error' : '' ?>">
        <label for="name" class="col-lg-2 control-label">
            <?=$this->getTrans('name') ?>
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
            <select class="form-control" id="leader" name="leader">
                <optgroup label="<?=$this->getTrans('users') ?>">
                    <?php foreach ($this->get('userList') as $userList): ?>
                        <?php $selected = ''; ?>
                        <?php if ($this->get('team') != ''): ?>
                            <?php if ($this->get('team')->getLeader() == $userList->getId()): ?>
                                <?php $selected = 'selected="selected"'; ?>
                            <?php endif; ?>
                        <?php endif; ?>
                        <option <?=$selected ?> value="<?=$userList->getId() ?>"><?=$userList->getName() ?></option>
                    <?php endforeach; ?>
                </optgroup>
            </select>
        </div>
    </div>
    <div class="form-group <?=$this->validation()->hasError('coLeader') ? 'has-error' : '' ?>">
        <label for="coLeader" class="col-lg-2 control-label">
            <?=$this->getTrans('coLeader') ?>
        </label>
        <div class="col-lg-4">
            <select class="form-control" id="coLeader" name="coLeader">
                <option <?php if ($this->get('team') != '' AND $this->get('team')->getCoLeader() == 0) { echo 'selected="selected"'; } ?> value="0"><?=$this->getTrans('noCoLeader') ?></option>
                <optgroup label="<?=$this->getTrans('users') ?>">
                    <?php foreach ($this->get('userList') as $userList): ?>
                        <?php $selected = ''; ?>
                        <?php if ($this->get('team') != ''): ?>
                            <?php if ($this->get('team')->getCoLeader() == $userList->getId()): ?>
                                <?php $selected = 'selected="selected"'; ?>
                            <?php endif; ?>
                        <?php endif; ?>
                        <option <?=$selected ?> value="<?=$userList->getId() ?>"><?=$userList->getName() ?></option>
                    <?php endforeach; ?>
                </optgroup>
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
                        <?php $selected = ''; ?>
                        <?php if ($this->get('team') != ''): ?>
                            <?php if ($this->get('team')->getGroupId() == $groupList->getId()): ?>
                                <?php $selected = 'selected="selected"'; ?>
                            <?php endif; ?>
                        <?php endif; ?>
                        <option <?=$selected ?> value="<?=$groupList->getId() ?>"><?=$groupList->getName() ?></option>
                    <?php endforeach; ?>
                </optgroup>
            </select>
        </div>
    </div>
    <?=($this->get('team') != '') ? $this->getSaveBar('edit') : $this->getSaveBar('add') ?>
</form>

<script>
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

