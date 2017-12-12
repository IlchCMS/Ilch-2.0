<?php
if ($this->get('dialogUnread') != '') {
    echo '<a href="'.$this->getUrl(['module' => 'user', 'controller' => 'panel', 'action' => 'dialog']).'" class="text-danger">'.$this->getTrans('new').' (<i class="fa fa-envelope-o"></i>)</a>';
}

