<?php $historys = $this->get('historys'); ?>
<?php if ($historys != ''): ?>
    <table class="table table-striped table-responsive">
        <?php foreach ($this->get('historys') as $history): ?>
            <tr>
                <th><?=$this->escape($history->getTitle()).' am '.$this->escape($history->getDate()) ?></th>
            </tr>
            <tr>
                <td><?=$history->getText() ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <?=$this->getTrans('noHistorys') ?>
<?php endif; ?>
