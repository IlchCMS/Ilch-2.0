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

$groupMapper = new \User\Mappers\Group();
$group = new \User\Models\Group();
$group->setName('Admin');
$group->setId(1);
$groupMapper->save($group);
$userMapper = new \User\Mappers\User();
$user = new \User\Models\User();
$user->setName($_SESSION['install']['adminName']);
$user->setPassword(crypt($_SESSION['install']['adminPassword']));
$user->setEmail($_SESSION['install']['adminEmail']);
$user->setGroups($group);
$dateCreated = new \Ilch\Date();
$user->setDateCreated($dateCreated);
$userMapper->save($user);
