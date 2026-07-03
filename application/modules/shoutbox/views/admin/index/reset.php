<?php

/** @var \Ilch\View $this */
?>
<h1><?=$this->getTrans('reset') ?></h1>
<div class="row mb-3">
    <div class="container">
        <a href="<?=$this->getUrl(['action' => 'reset'], null, true) ?>" class="btn btn-danger btn-xl active delete_button" role="button" aria-pressed="true"><?=$this->getTrans('reset') ?></a>
    </div>
</div>
