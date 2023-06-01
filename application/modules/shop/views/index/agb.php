<?php

$shopSettings = $this->get('shopSettings');
$shopAGB = $shopSettings->getAGB();

/* show shopcart */
$cart_badge = '';
if (!empty($_SESSION['shopping_cart'])) {
    $cart_count = count(array_keys($_SESSION['shopping_cart']));
    $cart_badge = ($cart_count > 0) ? '<a class="activecart" href="' . $this->getUrl('shop/index/cart') . '#shopAnker">' . $this->getTrans('menuCart') . '<i class="fa-solid fa-shopping-cart"><span class="badge">' . $cart_count . '</span></i></a>' : '';
}
?>

<h1>
    <?=$this->getTrans('AGB') ?>
    <?=$cart_badge ?>
    <div id="shopAnker"></div>
</h1>

<?php if (!empty($shopAGB)) : ?>
    <div class="col-lg-12"><?=$shopAGB ?></div>
<?php else : ?>
    <?=$this->getTrans('noAGB') ?>
<?php endif; ?>
