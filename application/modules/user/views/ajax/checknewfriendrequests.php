<?php
if (!empty($this->get('openFriendRequests'))) {
    echo '<a href="'.$this->getUrl(['module' => 'user', 'controller' => 'panel', 'action' => 'index']).'" class="text-danger">'.count($this->get('openFriendRequests')).' <i class="fa-solid fa-users"></i> </a>';
}
