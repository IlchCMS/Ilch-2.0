<?php

/** @var \Ilch\View $this */

use Modules\Forum\Models\Prefix;

?>
<h1><?=$this->getTrans('prefixes') ?></h1>
<?php if (!empty($this->get('prefixes'))) : ?>
    <form method="POST">
        <?=$this->getTokenField() ?>
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <colgroup>
                    <col class="icon_width" />
                    <col class="icon_width" />
                    <col class="icon_width" />
                    <col>
                </colgroup>
                <thead>
                    <tr>
                        <th><?=$this->getCheckAllCheckbox('check_forumReports') ?></th>
                        <th></th>
                        <th></th>
                        <th><?=$this->getTrans('prefix') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    /** @var Prefix $prefix */
                    foreach ($this->get('prefixes') as $prefix) : ?>
                        <tr>
                            <td><?=$this->getDeleteCheckbox('check_forumPrefixes', $prefix->getId()) ?></td>
                            <td><?=$this->getEditIcon(['action' => 'treat', 'id' => $prefix->getId()]) ?></td>
                            <td><?=$this->getDeleteIcon(['action' => 'delete', 'id' => $prefix->getId()]) ?></td>
                            <td><?=$this->escape($prefix->getPrefix()) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?=$this->getListBar(['delete' => 'delete']) ?>
    </form>
<?php else : ?>
    <tr>
        <td colspan="5"><?=$this->getTrans('noPrefixes') ?></td>
    </tr>
<?php endif; ?>
