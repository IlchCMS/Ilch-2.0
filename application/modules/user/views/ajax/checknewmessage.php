<?php
if ($this->get('dialogUnread') != '') {
    echo '<a href="'.$this->getUrl(['module' => 'user', 'controller' => 'panel', 'action' => 'dialog']).'" style="color:red;">'.$this->getTrans('newMessage').' (<i class="fa fa-envelope-o"></i>)</a>';
}

