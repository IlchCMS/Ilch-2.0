<?php

/** @var \Ilch\View $this */

/** @var \Modules\User\Models\User[] $users */
$users = $this->get('users');

/** @var \Modules\User\Models\User $dummyUser */
$dummyUser = $this->get('dummyUser');

/** @var \Ilch\Pagination $pagination */
$pagination = $this->get('pagination');

/** @var \Ilch\Config\Database $config */
$config = \Ilch\Registry::get('config');
?>
<link href="<?=$this->getModuleUrl('../shoutbox/static/css/shoutbox.css') ?>" rel="stylesheet">
<?=\Modules\Shoutbox\Libs\DesignCss::render() ?>
<h1><?=$this->getTrans('menuShoutbox') ?></h1>
<?php if ($this->get('shoutbox')) : ?>
    <?php $currentUser = $this->getUser(); ?>
    <table class="table table-striped table-responsive shoutbox-messages">
        <?php
        /** @var \Modules\Shoutbox\Models\Shoutbox $shoutbox */
        foreach ($this->get('shoutbox') as $shoutbox) : ?>
            <?php $date = new \Ilch\Date($shoutbox->getTime()) ?>
            <?php $canDelete = $currentUser !== null && ($shoutbox->getUid() === $currentUser->getId() || $currentUser->isAdmin()); ?>
            <?php
            $deleteIcon = '';
            if ($canDelete) {
                $deleteIcon = '<a href="' . $this->getUrl(['module' => 'shoutbox', 'controller' => 'index', 'action' => 'delete', 'id' => $shoutbox->getId()], null, true) . '"'
                    . ' class="float-end shoutbox-delete text-danger" title="' . $this->getTrans('deleteOwnPost') . '"'
                    . ' onclick="return confirm(' . $this->escape(json_encode($this->getTrans('confirmDelete'))) . ')">'
                    . '<i class="fa-solid fa-trash-can"></i></a>';
            }
            ?>
            <tr>
                <?php if ($shoutbox->getUid() == '0') : ?>
                    <td>
                        <?=$deleteIcon ?>
                        <b><?=$this->escape($shoutbox->getName()) ?>:</b> <span class="small"><?=$date->format('d.m.Y H:i', true) ?></span>
                    </td>
                <?php else : ?>
                    <?php $userName = $this->escape(isset($users[$shoutbox->getUid()]) ? $users[$shoutbox->getUid()]->getName() : $dummyUser->getName()) ?>
                    <?php $avatar = isset($users[$shoutbox->getUid()]) ? $users[$shoutbox->getUid()]->getAvatar() : $dummyUser->getAvatar() ?>
                    <td>
                        <?=$deleteIcon ?>
                        <?php if ($config->get('shoutbox_showAvatars') !== '0') : ?>
                            <img class="avatar" src="<?=$this->getStaticUrl() . '../' . $avatar ?>" alt="<?=$userName ?>">
                        <?php endif; ?>
                        <a href="<?=$this->getUrl('user/profil/index/user/' . $shoutbox->getUid()) ?>"><b><?=$userName ?></b></a>: <span class="small"><?=$date->format('d.m.Y H:i', true) ?></span>
                    </td>
                <?php endif; ?>
            </tr>
            <tr>
                <td class="shoutbox-text"><?=\Modules\Shoutbox\Libs\TextFormatter::format($shoutbox->getTextarea()) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
    <?=$pagination->getHtml($this, ['action' => 'index']) ?>
<?php else : ?>
    <?=$this->getTrans('noEntries') ?>
<?php endif; ?>
