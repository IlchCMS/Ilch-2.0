<?php
/* delete session */
unset($_SESSION['shopping_cart']); ?>

<h1>
    <?=$this->getTrans('menuOrdersSuccess') ?>
    <div id="shopAnker"></div>
</h1>

<div class="alert alert-success">
    <?=$this->getTrans('orderSuccessText') ?>
</div>

<div class="row space20"></div>
<a href="<?=$this->getUrl('shop/index') ?>#shopAnker" class="btn btn-default">
    <i class="fa-solid fa-backward"></i> <?=$this->getTrans('back') ?>
</a>
