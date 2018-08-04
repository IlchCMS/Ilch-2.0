<link href="<?=$this->getBaseUrl('application/modules/partner/static/css/partners.css') ?>" rel="stylesheet">
<link href="<?=$this->getStaticUrl('js/jquery.bxslider/jquery.bxslider.css') ?>" rel="stylesheet">
<style>
.partner-box .partnersslider .bx-viewport {
    height: <?=$this->get('boxHeight') ?>px !important;
}
</style>

<div class="partner-box">
    <?php if ($this->get('slider') == 0): ?>
        <?php foreach ($this->get('partners') as $partner): ?>
            <div class="partner-item">
                <?php
                $userMapper = new Modules\User\Mappers\User();
                $link = $userMapper->getHomepage($partner->getLink());
                if (substr($partner->getBanner(), 0, 11) == 'application') {
                    $banner = $this->getBaseUrl($partner->getBanner());
                } else {
                    $banner = $partner->getBanner();
                }
                ?>
                <a href="<?=$link ?>" alt="<?=$partner->getName() ?>" title="<?=$partner->getName() ?>" target="_blank">
                    <img src="<?=$banner ?>" alt="<?=$partner->getName() ?>" title="<?=$partner->getName() ?>" class="img-responsive">
                </a>
                <br />
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <?php if (!empty($this->get('partners'))) : ?>
            <div class="partnersslider">
                <div class="bxslider<?=($this->get('sliderMode') == 'horizontal' ? ' h-slide' : '') ?>">
                    <?php
                    foreach ($this->get('partners') as $partner):
                        $userMapper = new Modules\User\Mappers\User();
                        $link = $userMapper->getHomepage($partner->getLink());
                        if (substr($partner->getBanner(), 0, 11) == 'application') {
                            $banner = $this->getBaseUrl($partner->getBanner());
                        } else {
                            $banner = $partner->getBanner();
                        }
                        ?>

                        <div class="partner-item">
                            <a href="<?=$link ?>" alt="<?=$partner->getName() ?>" title="<?=$partner->getName() ?>" target="<?=($partner->getTarget() == 0 ? '_blank' : '_self') ?>">
                                <img src="<?=$banner ?>" alt="<?=$partner->getName() ?>" title="<?=$partner->getName() ?>" class="img-responsive">
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<script src="<?=$this->getStaticUrl('js/jquery.bxslider/jquery.bxslider.min.js') ?>"></script>
<script>
$('.bxslider').bxSlider({
    mode: '<?=$this->get('sliderMode') ?>',
    ticker: true,
    slideMargin: 10,
    speed: <?=$this->get('sliderSpeed') ?>,
    useCSS: false,
    tickerHover: true
});
</script>
