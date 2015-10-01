<?php foreach ($this->get('partners') as $partner): ?>
    <div class="img-responsive" style="margin-bottom: 15px;">
        <?php if (substr($partner->getBanner(), 0, 11) == 'application'): ?>
            <?php $banner = $this->getBaseUrl($partner->getBanner()); ?>
        <?php else: ?>
            <?php $banner = $partner->getBanner(); ?>
        <?php endif; ?>
        <a href="<?=$partner->getLink() ?>" title="<?=$partner->getName() ?>" target="_blank"><img src="<?=$banner ?>"></a><br />
    </div>
<?php endforeach; ?>
