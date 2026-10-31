<?php
/**
 * JSON response for the AJAX user search of the group treat page.
 * {total: int, users: [{id: int, name: string}, ...]}  — max 50 users.
 */
$users = [];
foreach ($this->get('users') as $user) {
    $users[] = ['id' => $user->getId(), 'name' => $user->getName()];
}

echo json_encode(['total' => (int)$this->get('total'), 'users' => $users]);
