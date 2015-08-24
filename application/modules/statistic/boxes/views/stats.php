<?php 
$date = new \Ilch\Date();
?>

<a href="<?=$this->getUrl('statistic/index/online') ?>"><?=$this->getTrans('statOnline') ?>: <?=$this->get('visitsOnline')?></a><br />
<?=$this->getTrans('statToday') ?>: <?=$this->get('visitsToday')?><br />
<?=$this->getTrans('statYesterday') ?>: <?=$this->get('visitsYesterday')?><br />
<a href="<?=$this->getUrl(array('controller' => 'index', 'action' => 'show', 'year' => $date->format("Y", true), 'month' => $date->format("m", true)))?>"><?=$this->getTrans('statMonth') ?>: <?=$this->get('visitsMonth')?></a><br />
<a href="<?=$this->getUrl(array('controller' => 'index', 'action' => 'show', 'year' => $date->format("Y", true)))?>"><?=$this->getTrans('statYear') ?>: <?=$this->get('visitsYear')?></a><br />
<?=$this->getTrans('statUserRegist') ?>: <?=$this->get('visitsRegistUser')?><br />
<a href="<?=$this->getUrl('statistic/index/index') ?>"><?=$this->getTrans('statTotal') ?>: <?=$this->get('visitsTotal')?></a><br />
