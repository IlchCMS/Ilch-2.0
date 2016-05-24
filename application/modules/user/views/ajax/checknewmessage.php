<?php
if ($this->get('dialogUnread') != '') {
    $dialogUnread = $this->get('dialogUnread');
    echo '<a href="'.$this->getUrl(['module' => 'user', 'controller' => 'panel', 'action' => 'dialog']).'" style="color:red;">Neu (<i class="fa fa-envelope-o"></i>)</a>';
}
?>
