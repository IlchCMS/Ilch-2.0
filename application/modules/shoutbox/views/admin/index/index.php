<?php

/** @var \Ilch\View $this */

/** @var string[] $userNames */
$userNames = $this->get('userNames');

/** @var string $dummyUserName */
$dummyUserName = $this->get('dummyUserName');

/** @var string $search */
$search = $this->get('search');

/** @var \Ilch\Pagination $pagination */
$pagination = $this->get('pagination');

$paginationParams = ['action' => 'index'];
if ($search !== '') {
    $paginationParams['search'] = urlencode($search);
}
?>
<h1><?=$this->getTrans('manage') ?></h1>
<form method="GET" action="<?=$this->getUrl(['action' => 'index']) ?>" class="mb-3">
    <div class="input-group" style="max-width: 400px;">
        <input type="text"
               class="form-control"
               name="search"
               value="<?=$this->escape($search) ?>"
               placeholder="<?=$this->getTrans('search') ?>">
        <button type="submit" class="btn btn-outline-secondary">
            <span class="fa-solid fa-magnifying-glass"></span> <?=$this->getTrans('search') ?>
        </button>
        <?php if ($search !== '') : ?>
            <a href="<?=$this->getUrl(['action' => 'index']) ?>" class="btn btn-outline-secondary" title="<?=$this->getTrans('reset') ?>">
                <span class="fa-solid fa-xmark"></span>
            </a>
        <?php endif; ?>
    </div>
</form>
<?php if (count($this->get('shoutbox'))) : ?>
    <form method="POST">
        <?=$this->getTokenField() ?>
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <colgroup>
                    <col class="icon_width">
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
                            <td><?=$this->getEditIcon(['action' => 'treat', 'id' => $shoutbox->getId()]) ?></td>
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
    <?=$pagination->getHtml($this, $paginationParams) ?>
<?php else : ?>
    <?=$this->getTrans('noEntries') ?>
<?php endif; ?>
