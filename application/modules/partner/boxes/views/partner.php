<link href="<?=$this->getBaseUrl('application/modules/partner/static/css/partners.css') ?>" rel="stylesheet">
<link href="<?=$this->getStaticUrl('js/jquery.bxslider/jquery.bxslider.css') ?>" rel="stylesheet">
<script type="text/javascript" src="<?=$this->getStaticUrl('js/jquery.bxslider/jquery.bxslider.js') ?>"></script>

<style>
.partnersslider .bx-viewport {
    height: <?=$this->get('boxHeight') ?>px !important;
}
</style>

<?php if ($this->get('slider') == 0): ?>
    <?php foreach ($this->get('partners') as $partner): ?>
        <div class="img-responsive" style="margin-bottom: 10px;">
            <?php $userMapper = new Modules\User\Mappers\User(); ?>
            <?php $link = $userMapper->getHomepage($partner->getLink()); ?>

            <?php if (substr($partner->getBanner(), 0, 11) == 'application'): ?>
                <?php $banner = $this->getBaseUrl($partner->getBanner()); ?>
            <?php else: ?>
                <?php $banner = $partner->getBanner(); ?>
            <?php endif; ?>

            <a href="<?=$link ?>" alt="<?=$partner->getName() ?>" title="<?=$partner->getName() ?>" target="_blank">
                <img src="<?=$banner ?>" alt="<?=$partner->getName() ?>" title="<?=$partner->getName() ?>">
            </a>
            <br />
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <div class="partnersslider">
        <ul class="bxslider">
            <?php foreach ($this->get('partners') as $partner): ?>
                <?php $userMapper = new Modules\User\Mappers\User(); ?>
                <?php $link = $userMapper->getHomepage($partner->getLink()); ?>

                <?php if (substr($partner->getBanner(), 0, 11) == 'application'): ?>
                    <?php $banner = $this->getBaseUrl($partner->getBanner()); ?>
                <?php else: ?>
                    <?php $banner = $partner->getBanner(); ?>
                <?php endif; ?>

                <li>
                    <a href="<?=$link ?>" alt="<?=$partner->getName() ?>" title="<?=$partner->getName() ?>" target="_blank">
                        <img src="<?=$banner ?>" alt="<?=$partner->getName() ?>" title="<?=$partner->getName() ?>">
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<script>
$('.bxslider').bxSlider({
    mode: 'vertical',
    ticker: true,
    slideMargin: 10,
    speed: <?=$this->get('sliderSpeed') ?>,
    useCSS: false,
    tickerHover: true
});
</script>
