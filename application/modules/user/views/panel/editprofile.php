<?php
    $profil = $this->get('profil');
    $birthday = new \Ilch\Date($profil->getBirthday());
?>
<link href="<?=$this->getStaticUrl('datepicker/css/datepicker.css')?>" rel="stylesheet">
<script type="text/javascript" src="<?=$this->getStaticUrl('datepicker/js/bootstrap-datepicker.js')?>"></script>
<div id="panel">
    <div class="row">
        <div class="col-lg-2">
            <img class="panel-profile-image" src="<?php echo $this->getStaticUrl().'../'.$this->escape($profil->getAvatar()); ?>" title="<?php echo $this->escape($profil->getName()); ?>">
            <ul class="nav">
            <?php foreach ($this->get('usermenu') as $usermenu): ?>
                <li><a class="" href="<?php echo $this->getUrl($usermenu->getKey()); ?>"><?php echo $usermenu->getTitle(); ?></a></li>
            <?php endforeach; ?>
            </ul>
        </div>
        <div class="col-lg-10">
            <legend><?php echo $this->getTrans('profileSettings'); ?></legend>
            <form action="" class="form-horizontal" method="POST">
                <?php echo $this->getTokenField(); ?>
                <div class="form-group">
                    <label class="col-lg-2 control-label">
                        <?=$this->getTrans('profileEmail'); ?>*
                    </label>
                    <div class="col-lg-8">
                        <input type="text"
                               class="form-control"
                               name="email"
                               placeholder="<?php echo $this->escape($profil->getEmail()); ?>"
                               value="<?php echo $this->escape($profil->getEmail()); ?>"
                               required />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-2 control-label">
                        <?=$this->getTrans('profileFirstName'); ?>
                    </label>
                    <div class="col-lg-8">
                        <input type="text"
                               class="form-control"
                               name="first-name"
                               placeholder="<?php echo $this->escape($profil->getFirstName()); ?>"
                               value="<?php echo $this->escape($profil->getFirstName()); ?>"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-2 control-label">
                        <?=$this->getTrans('profileLastName'); ?>
                    </label>
                    <div class="col-lg-8">
                        <input type="text"
                               class="form-control"
                               name="last-name"
                               placeholder="<?php echo $this->escape($profil->getLastName()); ?>"
                               value="<?php echo $this->escape($profil->getLastName()); ?>"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-2 control-label">
                        <?=$this->getTrans('profileHomepage'); ?>
                    </label>
                    <div class="col-lg-8">
                       <input type="text"
                              class="form-control"
                              name="homepage"
                              placeholder="<?php echo $this->escape($profil->getHomepage()); ?>"
                              value="<?php echo $this->escape($profil->getHomepage()); ?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-2 control-label">
                        <?=$this->getTrans('profileCity'); ?>
                    </label>
                    <div class="col-lg-8">
                       <input type="text"
                              class="form-control"
                              name="city"
                              placeholder="<?php echo $this->escape($profil->getCity()); ?>"
                              value="<?php echo $this->escape($profil->getCity()); ?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-2 control-label">
                        <?=$this->getTrans('profileBirthday'); ?>
                    </label>
                    <div class="col-lg-4 input-group date birthday" id="dp1" data-date="01-01-1980" data-date-format="dd-mm-yyyy">
                        <input class="form-control"
                               type="text"
                               name="birthday"
                               placeholder="<?=$birthday->format('d-m-Y', true)?>"
                               value="<?=$birthday->format('d-m-Y', true)?>">
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
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
<script>
    $(".input-group.date").datepicker({});
</script>