<legend>
    <?=$this->getTrans('menuGuestbook') ?>
    <div class="pull-right">
        <a href="<?=$this->getUrl(array('action' => 'newentry')) ?>"><?=$this->getTrans('entry') ?></a>
    </div>
</legend>
<?php foreach ($this->get('entries') as $entry): ?>
    <table class="table table-striped">
        <colgroup>
            <col class="col-lg-3">
            <col class="col-lg-2">
            <col />
        </colgroup>
        <tr>
            <td><?=$this->getTrans('from'); ?>: <?=$this->escape($entry->getName()) ?></td>
            <td>
                <?php if ($entry->getHomepage() != ''): ?>
                    <a target="_blank" href="<?=$this->escape($entry->getHomepage()) ?>">
                        <span class="glyphicon glyphicon-home"></span>
                    </a>
                <?php endif; ?>
                <a target="_blank" href="mailto:<?=$this->escape($entry->getEmail()) ?>">
                    <span class="glyphicon glyphicon-envelope"></span>
                </a>
            </td>
            <td><?=$this->getTrans('date') ?>: <?=$this->escape($entry->getDatetime()) ?></td>
        </tr>
        <tr>
            <td colspan="3"><?=nl2br($this->getHtmlFromBBCode($this->escape($entry->getText()))) ?></td>
        </tr>
    </table>
<?php endforeach; ?>

<?=$this->get('pagination')->getHtml($this, array('action' => 'index')) ?>
