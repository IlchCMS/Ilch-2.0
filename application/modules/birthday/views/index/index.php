<?php
$date = new \Ilch\Date();
$months = array(1=>"Januar",
                2=>"Februar",
                3=>"M&auml;rz",
                4=>"April",
                5=>"Mai",
                6=>"Juni",
                7=>"Juli",
                8=>"August",
                9=>"September",
                10=>"Oktober",
                11=>"November",
                12=>"Dezember");
?>

<legend><?=$this->getTrans('menuBirthdayList') ?></legend>
<?php if ($this->get('birthdayListNOW') != ''): ?>
<table class="table table-striped table-responsive">
    <tr>
        <th colspan="2"><div align="left" style="float: left;">Geburtstage von heute</div><div align="right"><?=date('d.m.Y', strtotime($this->get('birthdayDateNow'))) ?></div></th>
    </tr>
    <?php foreach ($this->get('birthdayListNOW') as $birthdaylist): ?>
        <tr>
            <td width="85"><img class="thumbnail" style="margin-bottom: 0px;" src="<?=$this->getStaticUrl() . '../' . $this->escape($birthdaylist->getAvatar()); ?>" title="<?=$this->escape($birthdaylist->getName()) ?>" width="69" height="69"></td>
            <td>
                <div style="margin-top: 3px;">
                    <a href="<?=$this->getUrl('user/profil/index/user/' . $birthdaylist->getId()) ?>"><b><?= $this->escape($birthdaylist->getName()) ?></b></a><br />
                    <?=$this->getTrans('will') ?> <?=floor(($this->get('birthdayDateNowYMD') - str_replace("-", "", $this->escape($birthdaylist->getBirthday()))) / 10000) ?> <?=$this->getTrans('yearsOld') ?><br />
                    <?php if($this->getUser() AND $this->getUser()->getId() != $this->escape($birthdaylist->getID())) { ?><a href="<?=$this->getUrl('user/panel/dialognew/id/' . $birthdaylist->getId()) ?>"><?=$this->getTrans('writeCongratulations') ?></a><?php } ?>
                </div>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<?php endif; ?>

<?php $monthsUserCount = 0; ?>
<?php for ($x = 0; $x < 12; $x++): ?>
<?php foreach ($this->get('birthdayList') as $birthdaylist): ?>
    <?php if ($this->escape(date('n', strtotime("+$x months", strtotime($date->format('Y-m-d'))))) == $this->escape(date('n', strtotime($birthdaylist->getBirthday()))) AND $this->escape(date('n-d', strtotime($birthdaylist->getBirthday()))) > $this->escape(date('n-d', strtotime($date->format('Y-m-d'))))): ?>
        <?php $monthsUserCount++; ?>
    <?php endif; ?>
<?php endforeach; ?>
    <table class="table table-striped table-responsive">
    <tr>
        <th>
            <div align="left" style="float: left;">
                <?php if ($x == 0): ?>
                    <?=$this->getTrans('laterIn') ?>
                <?php endif; ?>
                <?=$months[date('n', strtotime("+$x months", strtotime($date->format('Y-m-d'))))] ?>
            </div>
            <div align="right">
                <?=$monthsUserCount; ?> <?=$this->getTrans('people') ?>
            </div>
        </th>
    </tr>
    <tr>
        <td>
            <?php if ($monthsUserCount != ''): ?>
                <?php foreach ($this->get('birthdayList') as $birthdaylist): ?>
                    <?php if ($this->escape(date('n', strtotime("+$x months", strtotime($date->format('Y-m-d'))))) == $this->escape(date('n', strtotime($birthdaylist->getBirthday()))) AND $this->escape(date('n-d', strtotime($birthdaylist->getBirthday()))) > $this->escape(date('n-d', strtotime($date->format('Y-m-d'))))): ?>
                        <div style="padding: 0 2px 2px 0; float: left;"><a href="<?=$this->getUrl('user/profil/index/user/' . $birthdaylist->getId()) ?>"><img class="thumbnail" style="margin-bottom: 0px;" src="<?=$this->getStaticUrl() . '../' . $this->escape($birthdaylist->getAvatar()); ?>" title="<?=$this->escape($birthdaylist->getName()) ?> (<?=date("d-m", strtotime($this->escape($birthdaylist->getBirthday()))) ?>)" width="69" height="69"></a></div>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php else: ?>
                <?=$this->getTrans('noBirthdayMonth') ?>
            <?php endif; ?>
        </td>
    </tr>
</table>
<?php $monthsUserCount = 0; ?>
<?php endfor; ?>

<?php for ($x = 12; $x < 13; $x++): ?>
<?php foreach ($this->get('birthdayList') as $birthdaylist): ?>
    <?php if ($this->escape(date('n', strtotime("+12 months", strtotime($date->format('Y-m-d'))))) == $this->escape(date('n', strtotime($birthdaylist->getBirthday())))): ?>
        <?php $monthsUserCount++; ?>
    <?php endif; ?>
<?php endforeach; ?>
    <table class="table table-striped table-responsive">
    <tr>
        <th>
            <div align="left" style="float: left;"><?=$months[date('n', strtotime("+$x months", strtotime($date->format('Y-m-d'))))] ?></div>
            <div align="right">
                <?=$monthsUserCount; ?> <?=$this->getTrans('people') ?>
            </div>
        </th>
    </tr>
    <tr>
        <td>
            <?php if ($monthsUserCount != ''): ?>
                <?php foreach ($this->get('birthdayList') as $birthdaylist): ?>
                    <?php if ($this->escape(date('m', strtotime("+$x months", strtotime($date->format('Y-m-d'))))) == $this->escape(date('m', strtotime($birthdaylist->getBirthday())))): ?>
                        <div style="padding: 0 2px 2px 0; float: left;"><a href="<?=$this->getUrl('user/profil/index/user/' . $birthdaylist->getId()) ?>"><img class="thumbnail" style="margin-bottom: 0px;" src="<?=$this->getStaticUrl() . '../' . $this->escape($birthdaylist->getAvatar()); ?>" title="<?=$this->escape($birthdaylist->getName()) ?> (<?=date("d-m", strtotime($this->escape($birthdaylist->getBirthday()))) ?>)" width="69" height="69"></a></div>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php else: ?>
                <?=$this->getTrans('noBirthdayMonth') ?>
            <?php endif; ?>
        </td>
    </tr>
</table>
<?php $monthsUserCount = 0; ?>
<?php endfor; ?>
