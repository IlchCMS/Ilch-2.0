<a href="<?=$this->getUrl('statistic/index/online') ?>"><?=$this->getTrans('statOnline') ?>: <?=$this->get('visitsOnline')?></a><br />
<?=$this->getTrans('statToday') ?>: <?=$this->get('visitsToday')?><br />
<?=$this->getTrans('statYesterday') ?>: <?=$this->get('visitsYesterday')?><br />
<?=$this->getTrans('statMonth') ?>: <?=$this->get('visitsMonth')?><br />
<?=$this->getTrans('statYear') ?>: <?=$this->get('visitsYear')?><br />
<?=$this->getTrans('statUserRegist') ?>: <?=$this->get('visitsRegistUser')?><br />
<a href="<?=$this->getUrl('statistic/index/index') ?>"><?=$this->getTrans('statTotal') ?>: <?=$this->get('visitsTotal')?></a><br />
