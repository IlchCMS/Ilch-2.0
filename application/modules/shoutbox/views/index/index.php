<legend><?=$this->getTrans('menuShoutbox') ?></legend>
<?php if ($this->get('shoutbox') != ''): ?>
    <table class="table table-striped table-responsive">
        <?php foreach ($this->get('shoutbox') as $shoutbox): ?>
            <?php $userMapper = new \Modules\User\Mappers\User() ?>
            <?php $user = $userMapper->getUserById($shoutbox->getUid()) ?>
            <?php $date = new \Ilch\Date($shoutbox->getTime()) ?>
            <tr>
                <?php if ($shoutbox->getUid() == '0'): ?>
                    <td>
                        <b><?=$this->escape($shoutbox->getName()) ?>:</b> <span class="small"><?=$date->format("d.m.Y H:i", true) ?></span>
                    </td>
                <?php else: ?>
                    <td>
                        <b><a href="<?=$this->getUrl('user/profil/index/user/'.$user->getId()) ?>"><?=$user->getName() ?></a></b>: <span class="small"><?=$date->format("d.m.Y H:i", true) ?></span>
                    </td>
                <?php endif; ?>
            </tr>
            <tr>
                <td><?=$this->escape($shoutbox->getTextarea()) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <?=$this->getTrans('noEntrys') ?>
<?php endif; ?>
