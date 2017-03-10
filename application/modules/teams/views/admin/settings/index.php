<legend><?=$this->getTrans('settings') ?></legend>
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
<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <div class="form-group <?=$this->validation()->hasError('image_height') ? 'has-error' : '' ?>">
        <label for="image_height" class="col-lg-2 control-label">
            <?=$this->getTrans('imageHeight') ?>
        </label>
        <div class="col-lg-2">
            <input type="number"
                   class="form-control required"
                   id="image_height"
                   name="image_height"
                   min="1"
                   value="<?=$this->get('teams_height') ?>"
                   required />
        </div>
    </div>
    <div class="form-group <?=$this->validation()->hasError('image_width') ? 'has-error' : '' ?>">
        <label for="image_width" class="col-lg-2 control-label">
            <?=$this->getTrans('imageWidth') ?>
        </label>
        <div class="col-lg-2">
            <input type="number"
                   class="form-control required"
                   id="image_width"
                   name="image_width"
                   min="1"
                   value="<?=$this->get('teams_width') ?>"
                   required />
        </div>
    </div>
    <div class="form-group">
        <label for="image_filetypes" class="col-lg-2 control-label">
            <?=$this->getTrans('allowedFileExtensions') ?>
        </label>
        <div class="col-lg-2">
            <input type="text"
                   class="form-control required"
                   id="image_filetypes"
                   name="image_filetypes"
                   value="<?=$this->get('teams_filetypes') ?>" />
        </div>
    </div>

    <legend><?=$this->getTrans('menuEMails') ?></legend>
    <div class="form-group">
        <label for="ck_1" class="col-lg-2 control-label">
            <?=$this->getTrans('mailForAccept') ?>:
            <br /><br />
            <div class="small">
                <b><?=$this->getTrans('variables') ?></b><br />
                <b>{name}</b> = <?=$this->getTrans('variablesName') ?><br />
                <b>{teamname}</b> = <?=$this->getTrans('variablesTeamname') ?><br />
                <b>{sitetitle}</b> = <?=$this->getTrans('variablesSitetitle') ?><br />
                <b>{confirm}</b> = <?=$this->getTrans('variablesConfirm') ?>
            </div>
        </label>
        <div class="col-lg-10">
            <textarea class="form-control ckeditor"
                      id="ck_1"
                      name="teams_accept_mail"
                      cols="60"
                      toolbar="ilch_html"
                      rows="5"><?=$this->get('teams_accept_mail') ?></textarea>
        </div>
    </div>
    <div class="form-group">
        <label for="ck_2" class="col-lg-2 control-label">
            <?=$this->getTrans('mailForUserAccept') ?>:
            <br /><br />
            <div class="small">
                <b><?=$this->getTrans('variables') ?></b><br />
                <b>{name}</b> = <?=$this->getTrans('variablesName') ?><br />
                <b>{teamname}</b> = <?=$this->getTrans('variablesTeamname') ?><br />
                <b>{sitetitle}</b> = <?=$this->getTrans('variablesSitetitle') ?>
            </div>
        </label>
        <div class="col-lg-10">
            <textarea class="form-control ckeditor"
                      id="ck_2"
                      name="teams_accept_user_mail"
                      cols="60"
                      toolbar="ilch_html"
                      rows="5"><?=$this->get('teams_accept_user_mail') ?></textarea>
        </div>
    </div>
    <div class="form-group">
        <label for="ck_3" class="col-lg-2 control-label">
            <?=$this->getTrans('mailForReject') ?>:
            <br /><br />
            <div class="small">
                <b><?=$this->getTrans('variables') ?></b><br />
                <b>{name}</b> = <?=$this->getTrans('variablesName') ?><br />
                <b>{teamname}</b> = <?=$this->getTrans('variablesTeamname') ?><br />
                <b>{sitetitle}</b> = <?=$this->getTrans('variablesSitetitle') ?>
            </div>
        </label>
        <div class="col-lg-10">
            <textarea class="form-control ckeditor"
                      id="ck_3"
                      name="teams_reject_mail"
                      cols="60"
                      toolbar="ilch_html"
                      rows="5"><?=$this->get('teams_reject_mail') ?></textarea>
        </div>
    </div>
    <?=$this->getSaveBar() ?>
</form>
