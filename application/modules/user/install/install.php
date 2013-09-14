<?php
$config = array
(
	'key' => 'user',
	'name' => array
	(
		'en_EN' => 'User',
		'de_DE' => 'Benutzer',
	)
);

$userMapper = new User_UserMapper();
$user = new User_UserModel();
$user->setName($_SESSION['install']['adminName']);
$user->setPassword(crypt($_SESSION['install']['adminPassword']));
$user->setEmail($_SESSION['install']['adminEmail']);
$user->setGroups(array(1));
$userMapper->save($user);