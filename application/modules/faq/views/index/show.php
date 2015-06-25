<?php
$faq = $this->get('faq');
?>

<legend><?=$faq->getQuestion() ?></legend>
<?=$faq->getAnswer() ?>
