<?php
$date = new \Ilch\Date();
?>

<legend><?=$this->getTrans('menuBirthdayList') ?></legend>
<?php if ($this->get('birthdayListNOW') != ''): ?>
    <div class="panel panel-primary">
        <div class="panel-heading">
            <strong><?=$this->getTrans('birthdayToday') ?></strong>
            <span class="pull-right">
                <strong><?=date('d.m.Y', strtotime($date->format('Y-m-d'))) ?></strong>
            </span>
        </div>
        <?php foreach ($this->get('birthdayListNOW') as $birthdaylist): ?>
            <div class="panel-body">
                <div class="col-lg-12">
                    <div class="col-lg-2"><img class="thumbnail" style="margin-bottom: 0px;" src="<?=$this->getStaticUrl() . '../' . $this->escape($birthdaylist->getAvatar()); ?>" title="<?=$this->escape($birthdaylist->getName()) ?>" width="69" height="69"></div>
                    <div class="col-lg-10">
                        <a href="<?=$this->getUrl('user/profil/index/user/' . $birthdaylist->getId()) ?>"><b><?=$this->escape($birthdaylist->getName()) ?></b></a><br />
                        <?=$this->getTrans('will') ?> <?=floor(($date->format('Ymd') - str_replace("-", "", $this->escape($birthdaylist->getBirthday()))) / 10000) ?> <?=$this->getTrans('yearsOld') ?><br />
                        <?php if($this->getUser() AND $this->getUser()->getId() != $this->escape($birthdaylist->getID())) { ?><a href="<?=$this->getUrl('user/panel/dialognew/id/' . $birthdaylist->getId()) ?>"><?=$this->getTrans('writeCongratulations') ?></a><?php } ?>
                    </div>
                </div>
            </div>        
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php $monthsUserCount = 0; ?>
<?php for ($x = 1; $x < 13; $x++): ?>
    <?php foreach ($this->get('birthdayList') as $birthdaylist): ?>
        <?php if ($birthdaylist->getBirthday() != '0000-00-00' AND $this->escape(date('n', strtotime($birthdaylist->getBirthday()))) == $x): ?>
             <?php $monthsUserCount++; ?>
        <?php endif; ?>
    <?php endforeach; ?>

    <div class="panel panel-default">
        <div class="panel-heading">
            <?php $month = date('F', mktime(0, 0, 0, +$x, 1, 1));  ?>
            <strong><?=$month ?></strong>
            <span class="pull-right">
                <strong><?=$monthsUserCount; ?> <?=$this->getTrans('people') ?></strong>
            </span>
        </div>
        <div class="panel-body">
            <?php if ($monthsUserCount != ''): ?>
                <?php foreach ($this->get('birthdayList') as $birthdaylist): ?>
                    <?php if ($this->escape(date('n', strtotime($birthdaylist->getBirthday()))) == $x): ?>
                        <div style="padding: 0 2px 2px 0; float: left;"><a href="<?=$this->getUrl('user/profil/index/user/' . $birthdaylist->getId()) ?>"><img class="thumbnail" style="margin-bottom: 0px;" src="<?=$this->getStaticUrl() . '../' . $this->escape($birthdaylist->getAvatar()); ?>" title="<?=$this->escape($birthdaylist->getName()) ?> (<?=date("d.m", strtotime($this->escape($birthdaylist->getBirthday()))) ?>)" width="69" height="69"></a></div>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php else: ?>
                <?=$this->getTrans('noBirthdayMonth') ?>
            <?php endif; ?>
        </div>
    </div>
    <?php $monthsUserCount = 0; ?>
<?php endfor; ?>
