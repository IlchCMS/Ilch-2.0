<?php

/** @var \Ilch\View $this */

/** @var string $searchUrl */
$searchUrl = $this->get('searchUrl');
?>
<form method="post" action="<?=$searchUrl ?>" class="search-box">
    <?=$this->getTokenField() ?>
    <div class="input-group">
        <input type="text" class="form-control" name="search" placeholder="<?=$this->getTrans('searchPlaceholder') ?>" value="" aria-label="<?=$this->getTrans('search') ?>">
        <button type="submit" class="btn btn-secondary btn-hover-success">
            <i class="fa-solid fa-magnifying-glass"></i>
        </button>
    </div>
</form>
