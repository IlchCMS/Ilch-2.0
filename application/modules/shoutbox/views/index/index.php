<?php

/** @var \Ilch\View $this */

$userMapper = new \Modules\User\Mappers\User();
?>
<h1><?=$this->getTrans('menuShoutbox') ?></h1>
<?php if ($this->get('shoutbox')) : ?>
    <table class="table table-striped table-responsive">
        <?php
        /** @var \Modules\Shoutbox\Models\Shoutbox $shoutbox */
        foreach ($this->get('shoutbox') as $shoutbox) : ?>
            <?php $date = new \Ilch\Date($shoutbox->getTime()) ?>
            <tr>
                <?php if ($shoutbox->getUid() == '0') : ?>
                    <td>
                        <b><?=$this->escape($shoutbox->getName()) ?>:</b> <span class="small"><?=$date->format("d.m.Y H:i", true) ?></span>
                    </td>
                <?php else : ?>
                    <?php $user = $userMapper->getUserById($shoutbox->getUid()) ?>
                    <td>
                        <b><a href="<?=$this->getUrl('user/profil/index/user/' . $user->getId()) ?>"><?=$this->escape($user ? $user->getName() : $userMapper->getDummyUser()->getName()) ?></a></b>: <span class="small"><?=$date->format("d.m.Y H:i", true) ?></span>
                    </td>
                <?php endif; ?>
            </tr>
            <tr>
                <td><?=$this->escape($shoutbox->getTextarea()) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php else : ?>
    <?=$this->getTrans('noEntrys') ?>
<?php endif; ?>
