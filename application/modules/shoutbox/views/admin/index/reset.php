<?php

/** @var \Ilch\View $this */

$userMapper = $this->get('userMapper');
?>
<h1><?=$this->getTrans('reset') ?></h1>
<div class="row mb-3">
    <a href="<?=$this->getUrl(['action' => 'reset', 'reset' => true], null, true) ?>" class="btn btn-danger btn-lg active delete_button" role="button" aria-pressed="true"><?=$this->getTrans('reset') ?></a>
</div>
