<?php
$config['name'] = 'Ilch-Clan';
$config['version'] = '1.0';
$config['author'] = 'Ilch.de';
$config['link'] = 'http://ilch.de';
$config['desc'] = '2 Spalten Clan Layout';
$config['layouts']['index_full'] = [['module' => 'user', 'controller' => 'panel']]; //only for example
$config['layouts']['index_full'] = [['module' => 'forum']]; //only for example
//$config['layouts']['index_full'] = array(array('module' => 'guestbook', 'controller' => 'index', 'action' => 'newentry'));
//$config['modulekey'] = 'Name of Module';