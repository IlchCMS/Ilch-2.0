<link href="<?=$this->getBaseUrl('application/modules/partner/static/css/partners.css') ?>" rel="stylesheet">
<link href="<?=$this->getStaticUrl('js/jquery.bxslider/jquery.bxslider.css') ?>" rel="stylesheet">
<style>
.partnersslider .bx-viewport {
    height: <?=$this->get('boxHeight') ?>px !important;
}
</style>

<?php if ($this->get('slider') == 0): ?>
    <?php foreach ($this->get('partners') as $partner): ?>
        <div class="img-responsive" style="margin-bottom: 10px;">
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
                <img src="<?=$banner ?>" alt="<?=$partner->getName() ?>" title="<?=$partner->getName() ?>">
            </a>
            <br />
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <?php if (!empty($this->get('partners'))) : ?>
        <div class="partnersslider">
            <ul class="bxslider">
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
                    <li>
                        <a href="<?=$link ?>" alt="<?=$partner->getName() ?>" title="<?=$partner->getName() ?>" target="_blank">
                            <img src="<?=$banner ?>" alt="<?=$partner->getName() ?>" title="<?=$partner->getName() ?>">
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
<?php endif; ?>

<script type="text/javascript" src="<?=$this->getStaticUrl('js/jquery.bxslider/jquery.bxslider.js') ?>"></script>
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
