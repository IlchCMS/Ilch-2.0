<?php $faq = $this->get('faq'); ?>

<h1><?=$this->escape($faq->question) ?></h1>
<?=$faq->answer ?>
