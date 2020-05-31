<?php
$faq = $this->get('faq'); ?>

<h1><?=$this->escape($faq->getQuestion()) ?></h1>
<?=$faq->getAnswer() ?>
