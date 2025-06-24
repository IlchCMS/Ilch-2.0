<?php

/** @var \Ilch\View $this */

/** @var null|Modules\User\Models\User[] $birthdayListNOW */
$birthdayListNOW = $this->get('birthdayListNOW');
?>
<?php if ($birthdayListNOW != ''): ?>
    <?php foreach ($birthdayListNOW as $user): ?>
        <i class="fa-solid fa-cake-candles"></i>
        <a href="<?=$this->getUrl('user/profil/index/user/' . $user->getId()) ?>"><?=$this->escape($user->getName()) ?></a> (<?=floor(($this->get('birthdayDateNowYMD') - str_replace("-", "", $this->escape($user->getBirthday()))) / 10000) ?>)<br />
    <?php endforeach; ?>
<?php else: ?>
    <?=$this->getTrans('noBirthdayToday') ?>
<?php endif; ?>
<hr />
<div align="center"><a href="<?=$this->getUrl('birthday/index/index/') ?>"><?=$this->getTrans('otherBirthday') ?></a></div>
