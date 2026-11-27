<h1><?=$this->getTrans('search') ?></h1>

<?php
$term = $this->get('term');
$results = $this->get('results');
?>
<?php if ($term !== '') : ?>
    <p><?=$this->getTrans('searchingFor') ?>: <?=$this->escape($term) ?></p>
<?php endif; ?>

<?php if ($term !== '' && empty($results)) : ?>
    <p><?=$this->getTrans('noResultFound') ?></p>
<?php elseif (!empty($results)) : ?>
    <div class="list-group">
        <?php foreach ($results as $result) : ?>
            <a class="list-group-item list-group-item-action" href="<?=$result['url'] ?>"<?php if ($result['newTab']) : ?> target="_blank" rel="noopener"<?php endif; ?>>
                <i class="fa-solid fa-angles-right"></i> <?=$this->escape($result['label']) ?>
                <?php if ($result['hint'] !== '') : ?>
                    <small class="text-muted"> <?=$this->escape($result['hint']) ?></small>
                <?php endif; ?>
            </a>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
