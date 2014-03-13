<?php 
    $profil = $this->get('profil'); 
    
    $groups = '';
    foreach($profil->getGroups() as $group) {
        if ($groups != '') {
            $groups .= ', ';
        }

        $groups .= $group->getName();
    } 
?>

<div class="profil">
    <div class="profil-header">
        <div class="row">
            <div class="col-lg-2">
                <img src="http://www.ilch.de/include/images/avatars/noavatar.jpg" title="<?php echo $this->escape($profil->getName()); ?>">
            </div>
            <div class="col-lg-5 col-xs-12">
                <h3><?php echo $this->escape($profil->getName()); ?> (00)</h3>
                <div class="detail">
                    <i class="fa fa-star" title="<?php echo $this->getTrans('rank'); ?>"></i> {Rangname]<br />
                    <i class="fa fa-sign-in" title="<?php echo $this->getTrans('regist'); ?>"></i> <?php echo $this->escape($profil->getDateCreated()) ?>
                </div>
            </div>
            <div class="col-lg-4 hidden-xs concatLinks-lg">
                <a class="fa fa-envelope" title="E-Mail"></a>
                <a class="fa fa-globe" title="<?php echo $this->getTrans('website'); ?>"></a>
                <a class="fa fa-facebook" title="Facebook"></a>
                <a class="fa fa-google-plus" title="Goggle+"></a>
                <a class="fa fa-twitter" title="Twitter"></a>
            </div>
        </div>
        
        <div class="row">
            <div class="col-xs-12 visible-xs hidden-lg concatLinks">
                <a class="fa fa-envelope" title="E-Mail"></a>
                <a class="fa fa-globe" title="<?php echo $this->getTrans('website'); ?>"></a>
                <a class="fa fa-facebook" title="Facebook"></a>
                <a class="fa fa-google-plus" title="Goggle+"></a>
                <a class="fa fa-twitter" title="Twitter"></a>
            </div>
            
        </div>
    </div>
    <br />
    <div class="profil-content">
        <legend><?php echo $this->getTrans('profileDetails'); ?></legend>
        <div class="row">
            <div class="col-lg-2 detail bold">
                First Name:
            </div>
            <div class="col-lg-8 detail">
                Max 
            </div>
        </div>
         <div class="row">
            <div class="col-lg-2 detail bold">
                Last Name:
            </div>
            <div class="col-lg-8 detail">
                Mustermann
            </div>
        </div>
        <div class="clearfix"></div>
        <br />
        <legend><?php echo $this->getTrans('others'); ?></legend>
        <div class="row">
            <div class="col-lg-2 detail bold">
                <?php echo $this->getTrans('groups'); ?>:
            </div>
            <div class="col-lg-8 detail">
                <?php echo $this->escape($groups) ?>
            </div>
        </div>
    </div>
</div>

