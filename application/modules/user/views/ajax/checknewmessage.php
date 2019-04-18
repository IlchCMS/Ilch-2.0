<?php
if ($this->get('dialogUnread') != 0) {
    echo '<a href="'.$this->getUrl(['module' => 'user', 'controller' => 'panel', 'action' => 'dialog']).'" class="text-danger">'.$this->get('dialogUnread').' <i class="fa fa-envelope-o"></i></a>';
}
