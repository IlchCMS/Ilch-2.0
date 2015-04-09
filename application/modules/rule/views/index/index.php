<?php $rules = $this->get('rules'); ?>
<?php if ($rules != ''): ?>
    <table class="table table-striped table-responsive">
        <?php foreach ($this->get('rules') as $rule): ?>
            <tr>
                <th>§<?=$this->escape($rule->getParagraph()).'. '.$this->escape($rule->getTitle()) ?></th>
            </tr>
            <tr>
                <td><?=nl2br($this->getHtmlFromBBCode($this->escape($rule->getText()))) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <?=$this->getTrans('noRules') ?>
<?php endif; ?>
