<?php
$result = [];
foreach ($this->get('users') as $user) {
    $result[] = ['id' => $user->getId(), 'name' => $user->getName()];
}
echo json_encode($result);
