<div class="img-responsive">
    <?php foreach ($this->get('partners') as $partner): ?>
        <a href="<?=$partner->getLink() ?>" title="<?=$partner->getName() ?>" target="_blank"><img src="<?=$partner->getBanner() ?>"></a><br />
    <?php endforeach; ?>
</div>
