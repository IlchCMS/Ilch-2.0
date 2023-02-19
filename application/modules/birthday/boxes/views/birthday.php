<?php if ($this->get('birthdayListNOW') != ''): ?>
    <?php foreach ($this->get('birthdayListNOW') as $birthdaylist): ?>
        <i class="fa-solid fa-cake-candles"></i>
        <a href="<?=$this->getUrl('user/profil/index/user/' . $birthdaylist->getId()) ?>"><?=$this->escape($birthdaylist->getName()) ?></a> (<?=floor(($this->get('birthdayDateNowYMD') - str_replace("-", "", $this->escape($birthdaylist->getBirthday()))) / 10000) ?>)<br />
    <?php endforeach; ?>
<?php else: ?>
    <?=$this->getTrans('noBirthdayToday') ?>
<?php endif; ?>
<hr />
<div align="center"><a href="<?=$this->getUrl('birthday/index/index/') ?>"><?=$this->getTrans('otherBirthday') ?></a></div>
