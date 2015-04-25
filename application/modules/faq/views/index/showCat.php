<?php
    $categories = $this->get('categorys');
    $faqs = $this->get('faqs');

    $faqMappers = new Modules\Faq\Mappers\Faq();
?>

<link href="<?=$this->getModuleUrl('static/css/faq.css') ?>" rel="stylesheet">

<legend><?=$this->getTrans('faqs') ?></legend>
<?php if ($categories != '' OR $faqs != ''): ?>
    <?php if ($categories != ''): ?>
        <ul class="list-unstyled">
            <?php foreach ($categories as $category): ?>
                <?php $catFaqs = $faqMappers->getFaqsByCatId($category->getId()); ?>
                <li><a href="<?=$this->getUrl('faq/index/showCat/catId/'.$category->getId()) ?>"><b><?=$category->getTitle() ?></b></a>
                    <?php if ($catFaqs != ''): ?>
                        <?php foreach ($catFaqs as $faq): ?>
                            <ul>
                                <li class="trigger"><div></div> &nbsp;<?=$faq->getQuestion() ?></li>
                                <div class="toggle_container"><?=$faq->getAnswer() ?></div>
                            </ul>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </li>
                <br />
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    <?php if ($faqs != ''): ?>
        <ul style="padding-left:21px;">
            <?php $faqs = $faqMappers->getFaqsByCatId($this->getRequest()->getParam('catId')); ?>
            <?php foreach ($faqs as $faq): ?>
                <li class="trigger"><div></div> &nbsp;<?=$faq->getQuestion() ?></li>
                <div class="toggle_container"><?=$faq->getAnswer() ?></div>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
<?php else: ?>
    <?=$this->getTrans('noFaqs') ?>
<?php endif; ?>

<script>
    $(document).ready( function() {
        $('.trigger').not('.trigger_active').next('.toggle_container').hide();
        $('.trigger').click( function() {
            var trig = $(this);
            if ( trig.hasClass('trigger_active') ) {
                trig.next('.toggle_container').slideToggle('slow');
                trig.removeClass('trigger_active');
            } else {
                $('.trigger_active').next('.toggle_container').slideToggle('slow');
                $('.trigger_active').removeClass('trigger_active');
                trig.next('.toggle_container').slideToggle('slow');
                trig.addClass('trigger_active');
            };
            return false;
        });
    });
</script>