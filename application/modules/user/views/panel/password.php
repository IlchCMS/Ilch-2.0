<?php $profil = $this->get('profil'); ?>

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
            <legend><?=$this->getTrans('settingsPassword'); ?></legend>
            <form action="" class="form-horizontal" method="POST">
                <?=$this->getTokenField(); ?>
                <div class="form-group">
                    <label class="col-lg-2 control-label">
                        <?=$this->getTrans('profileNewPassword'); ?>*
                    </label>
                    <div class="col-lg-8">
                        <input type="password"
                               class="form-control"
                               name="password"
                               id="password"
                               value=""
                               required />
                        <?=$this->getTrans('profilePasswordInfo'); ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-2 control-label">
                        <?=$this->getTrans('profileNewPasswordRetype'); ?>*
                    </label>
                    <div class="col-lg-8">
                        <input type="password"
                               class="form-control"
                               name="password2"
                               value=""
                               required />
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-lg-offset-2 col-lg-8">
                        <input type="submit"
                               name="saveEntry"
                               class="btn"
                               value="<?php echo $this->getTrans('profileSubmit'); ?>" />
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
