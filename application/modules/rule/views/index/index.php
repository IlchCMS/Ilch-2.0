<?php $rules = $this->get('rules'); ?>

<h1><?=$this->getTrans('menuRules') ?></h1>
<?php if ($rules != ''): ?>
    <table class="table table-striped table-responsive">
        <?php foreach ($this->get('rules') as $rule): ?>
            <tr id="paragraph<?=$this->escape($rule->getParagraph()) ?>" tabindex="-1">
                <th>§<?=$this->escape($rule->getParagraph()).'. '.$this->escape($rule->getTitle()) ?></th>
            </tr>
            <tr>
                <td><?=$rule->getText() ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <?=$this->getTrans('noRules') ?>
<?php endif; ?>
