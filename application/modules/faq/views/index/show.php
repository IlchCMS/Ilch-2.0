<?php

/** @var \Ilch\View $this */

/** @var Modules\Faq\Models\Faq $faq */
$faq = $this->get('faq');
?>

<h1><?=$this->escape($faq->getQuestion()) ?></h1>
<?=$faq->getAnswer() ?>
