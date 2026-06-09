<?php

/** @var \Ilch\View $this */

/** @var string[] $userNames */
$userNames = $this->get('userNames');

/** @var string $dummyUserName */
$dummyUserName = $this->get('dummyUserName');

/** @var \Ilch\Pagination $pagination */
$pagination = $this->get('pagination');
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
                        <b><?=$this->escape($shoutbox->getName()) ?>:</b> <span class="small"><?=$date->format('d.m.Y H:i', true) ?></span>
                    </td>
                <?php else : ?>
                    <?php $userName = $userNames[$shoutbox->getUid()] ?? $dummyUserName ?>
                    <td>
                        <a href="<?=$this->getUrl('user/profil/index/user/' . $shoutbox->getUid()) ?>"><b><?=$this->escape($userName) ?></b></a>: <span class="small"><?=$date->format('d.m.Y H:i', true) ?></span>
                    </td>
                <?php endif; ?>
            </tr>
            <tr>
                <td><?=$this->escape($shoutbox->getTextarea()) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
    <?=$pagination->getHtml($this, ['action' => 'index']) ?>
<?php else : ?>
    <?=$this->getTrans('noEntries') ?>
<?php endif; ?>
