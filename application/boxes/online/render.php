<?php $users = $this->get('usersOnline');?>
User: <?=count($users)?><br />
<hr />
<?php
if (!empty($users)) {
    echo '<ul class="list-unstyled">';
    foreach($users as $user) {
        echo '<li><a href="'.$this->getUrl(array('module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $user->getId())).'">'
                .$this->escape($user->getName())
                .'</a></li>';
    }
    echo '</ul><hr />';
}
?>
Gäste: <?=$this->get('guestOnline')?><br />