<?php
$profil = $this->get('profil');
?>

<div id="panel">
    <div class="row">
        <div class="col-lg-2">
            <img class="panel-profile-image" src="<?=$this->getStaticUrl().'../'.$this->escape($profil->getAvatar()) ?>" title="<?=$this->escape($profil->getName()) ?>">
            <ul class="nav">
            <?php foreach ($this->get('usermenu') as $usermenu): ?>
                <li><a class="" href="<?=$this->getUrl($usermenu->getKey()) ?>"><?=$usermenu->getTitle() ?></a></li>
            <?php endforeach; ?>
            </ul>
        </div>
        <div class="col-lg-10">
            <legend><?=$this->getTrans('settingsSetting') ?></legend>
            <form action="" class="form-horizontal" method="POST">
                <?=$this->getTokenField() ?>
                <div class="form-group">
                    <label for="opt_mail" class="col-lg-3 control-label">
                        <?=$this->getTrans('optMail') ?>:
                    </label>
                    <div class="col-lg-4">
                        <label class="checkbox-inline">
                            <input type="radio" 
                                   name="opt_mail" 
                                   id="opt_mail_yes" 
                                   value="1" 
                                   <?php if ($profil->getOptMail() == '1') { echo 'checked="checked"';} ?> />
                                   <label for="opt_mail_yes"><?=$this->getTrans('yes') ?></label>
                        </label>
                        <label class="checkbox-inline">
                            <input type="radio" 
                                   name="opt_mail" 
                                   id="opt_mail_no" 
                                   value="0" 
                                   <?php if ($profil->getOptMail() == '0') { echo 'checked="checked"';} ?> />
                                   <label for="opt_mail_no"><?=$this->getTrans('no') ?></label>
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-lg-offset-3 col-lg-12">
                        <input type="submit" 
                               name="saveEntry" 
                               class="btn"
                               value="<?=$this->getTrans('profileSubmit') ?>" />
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
