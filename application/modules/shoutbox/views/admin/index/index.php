<?php

/** @var \Ilch\View $this */

/** @var string[] $userNames */
$userNames = $this->get('userNames');

/** @var string $dummyUserName */
$dummyUserName = $this->get('dummyUserName');

/** @var \Ilch\Pagination $pagination */
$pagination = $this->get('pagination');
?>
<h1><?=$this->getTrans('manage') ?></h1>
<?php if ($this->get('shoutbox') != '') : ?>
    <form method="POST">
        <?=$this->getTokenField() ?>
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <colgroup>
                    <col class="icon_width">
                    <col class="icon_width">
                    <col class="col-xl-2">
                    <col class="col-xl-2">
                    <col>
                </colgroup>
                <thead>
                    <tr>
                        <th><?=$this->getCheckAllCheckbox('check_entries') ?></th>
                        <th></th>
                        <th><?=$this->getTrans('from') ?></th>
                        <th><?=$this->getTrans('date') ?></th>
                        <th><?=$this->getTrans('message') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    /** @var \Modules\Shoutbox\Models\Shoutbox $shoutbox */
                    foreach ($this->get('shoutbox') as $shoutbox) : ?>
                        <?php $date = new \Ilch\Date($shoutbox->getTime()) ?>
                        <tr>
                            <td><?=$this->getDeleteCheckbox('check_entries', $shoutbox->getId()) ?></td>
                            <td><?=$this->getDeleteIcon(['action' => 'delete', 'id' => $shoutbox->getId()]) ?></td>
                            <?php if ($shoutbox->getUid() == '0') : ?>
                                <td><?=$this->escape($shoutbox->getName()) ?></td>
                            <?php else : ?>
                                <?php $userName = $userNames[$shoutbox->getUid()] ?? $dummyUserName ?>
                                <td><a href="<?=$this->getUrl('user/profil/index/user/' . $shoutbox->getUid()) ?>" target="_blank"><?=$this->escape($userName) ?></a></td>
                            <?php endif; ?>
                            <td><?=$date->format('d.m.Y H:i', true) ?></td>
                            <td><?=$this->escape($shoutbox->getTextarea()) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?=$this->getListBar(['delete' => 'delete']) ?>
    </form>
    <?=$pagination->getHtml($this, ['action' => 'index']) ?>
<?php else : ?>
    <?=$this->getTrans('noEntries') ?>
<?php endif; ?>
