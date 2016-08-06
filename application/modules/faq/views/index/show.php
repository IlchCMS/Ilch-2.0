<?php $faq = $this->get('faq'); ?>

<legend><?=$this->escape($faq->getQuestion()) ?></legend>
<?=$faq->getAnswer() ?>
