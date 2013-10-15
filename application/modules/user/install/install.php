<?php
$config = array
(
	'key' => 'user',
	'name' => array
	(
		'en_EN' => 'User',
		'de_DE' => 'Benutzer',
	),
	'icon_small' => 'user.png',
);

$userMapper = new \User\UserMapper();
$user = new \User\UserModel();
$user->setName($_SESSION['install']['adminName']);
$user->setPassword(crypt($_SESSION['install']['adminPassword']));
$user->setEmail($_SESSION['install']['adminEmail']);
$user->setGroups(array(1));
$dateCreated = new \Ilch\Date();
$user->setDateCreated($dateCreated);
$userMapper->save($user);