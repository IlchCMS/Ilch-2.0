<?php

/** @var \Ilch\View $this */

/** @var string $searchUrl */
$searchUrl = $this->get('searchUrl');
?>
<form method="post" action="<?=$searchUrl ?>" class="search-box">
    <?=$this->getTokenField() ?>
    <input type="text" name="search" placeholder="<?=$this->getTrans('searchPlaceholder') ?>" value="">
    <button type="submit" class="btn btn-sm btn-secondary btn-hover-success">
        <i class="fa-solid fa-magnifying-glass"></i> <?=$this->getTrans('search') ?>
    </button>
</form>
