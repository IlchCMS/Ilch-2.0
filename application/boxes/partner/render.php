<div class="img-responsive">
<?php foreach ($this->get('partners') as $partner): ?>
    <a href="<?php echo $partner->getLink(); ?>" title="<?php echo $partner->getName(); ?>" target="_blank"><img src="<?php echo $partner->getBanner(); ?>"></a><br />
<?php endforeach; ?>
</div>