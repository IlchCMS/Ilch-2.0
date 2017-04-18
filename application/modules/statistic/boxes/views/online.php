<?php $users = $this->get('usersOnline'); ?>

<?=$this->getTrans('onlineUser') ?>: <?=count($users) ?>
<hr />
<?php if (!empty($users)): ?>
    <ul class="list-unstyled">
        <?php foreach ($users as $user): ?>
            <li>
                <a href="<?=$this->getUrl(['module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $user->getId()]) ?>"><?=$this->escape($user->getName()) ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
    <hr />
<?php endif; ?>
<?=$this->getTrans('onlineGuests') ?>: <?=$this->get('guestOnline') ?>
