<?php

$shopItem = $this->get('shopItem');
$status = '';

/* shopcart session */
if (isset($_POST['code']) && $_POST['code'] != '' && isset($_POST['quantity']) && $_POST['quantity'] != '' && is_numeric($_POST['quantity'])) {
    $code = $_POST['code'];
    $quantity = $_POST['quantity'];
    $id = $shopItem->getId();
    $name = $shopItem->getName();
    $itemnumber = $shopItem->getItemnumber();
    $price = $shopItem->getPrice();
    $image = $shopItem->getImage();

    $cartArray = [
        $code => [
        'id' => $id,
        'code' => $code,
        'quantity' => $quantity]
    ];

    if (empty($_SESSION['shopping_cart'])) {
        $_SESSION['shopping_cart'] = $cartArray;
        $status = '<div id="infobox" class="alert alert-success" role="alert">' . $this->getTrans('theProduct') . ' <b>' . $name . '</b> ' . $this->getTrans('addToCart') . '</div>';
    } else {
        $array_keys = array_keys($_SESSION['shopping_cart']);
        if (in_array($code, $array_keys)) {
            $status = '<div id="infobox" class="alert alert-danger" role="alert">' . $this->getTrans('theProduct') . ' <b>' . $name . '</b> ' . $this->getTrans('alreadyInCart') . '</div>';
        } else {
            $_SESSION['shopping_cart'] = array_merge($_SESSION['shopping_cart'], $cartArray);
            $status = '<div id="infobox" class="alert alert-success" role="alert">' . $this->getTrans('theProduct') . ' <b>' . $name . '</b> ' . $this->getTrans('addToCart') . '</div>';
        }
    }
}

/* show shopcart */
$cart_badge = '';
if (!empty($_SESSION['shopping_cart'])) {
    $cart_count = count(array_keys($_SESSION['shopping_cart']));
    $cart_badge = ($cart_count > 0) ? '<a class="activecart" href="' . $this->getUrl('shop/index/cart') . '#shopAnker">' . $this->getTrans('menuCart') . '<i class="fa-solid fa-shopping-cart"><span class="badge">' . $cart_count . '</span></i></a>' : '';
}
?>

<h1>
    <?=$this->escape($shopItem->getName()) ?>
    <?=$cart_badge ?>
    <div id="shopAnker"></div>
</h1>

<div class="message_box">
    <?=$status; ?>
</div>

<?php $shopImgPath = '/application/modules/shop/static/img/';
if ($shopItem->getImage() && file_exists(ROOT_PATH . '/' . $shopItem->getImage())) {
    $img = BASE_URL . '/' . $shopItem->getImage();
} else {
    $img = BASE_URL . $shopImgPath . 'noimg.jpg';
} ?>
<div class="col show row">
    <div class="col-lg-6">
        <table class="table noborder">
            <tr>
                <td class="big">
                    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title"><?=$this->escape($shopItem->getName()) ?></h4>
                                </div>
                                <div id="carousel-shop" class="carousel carousel-dark slide" data-bs-touch="false" data-bs-interval="false">
                                    <!-- Indicators -->
                                    <!-- Wrapper for slides -->
                                    <div class="carousel-inner" role="listbox">
                                        <div class="carousel-item active"><img src="<?=$img ?>" alt="<?=$this->escape($shopItem->getName()) ?>"></div>
                                        <?php
                                        if ($shopItem->getImage1() && file_exists(ROOT_PATH . '/' . $shopItem->getImage1())) {
                                            echo '<div class="carousel-item"><img src="' . BASE_URL . '/' . $shopItem->getImage1() . '"></div>';
                                        }
                                        if ($shopItem->getImage2() && file_exists(ROOT_PATH . '/' . $shopItem->getImage2())) {
                                            echo '<div class="carousel-item"><img src="' . BASE_URL . '/' . $shopItem->getImage2() . '"></div>';
                                        }
                                        if ($shopItem->getImage3() && file_exists(ROOT_PATH . '/' . $shopItem->getImage3())) {
                                            echo '<div class="carousel-item"><img src="' . BASE_URL . '/' . $shopItem->getImage3() . '"></div>';
                                        } ?>
                                    </div>
                                    <!-- Controls -->
                                    <a class="carousel-control-prev" role="button" data-bs-target="#carousel-shop" data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Previous</span>
                                    </a>
                                    <a class="carousel-control-next" role="button" data-bs-target="#carousel-shop" data-bs-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Next</span>
                                    </a>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"><i class="fa-solid fa-xmark"></i> Close</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php if ($shopItem->getCordon() && $shopItem->getCordon() == 1) { ?>
                        <div class="cordon-wrapper">
                            <div class="cordon <?=$this->escape($shopItem->getCordonColor()) ?>"><?=$this->escape($shopItem->getCordonText()) ?></div>
                        </div>
                    <?php } ?>

                    <img id="productimage" class="productimage" src="<?=$img ?>" alt="<?=$this->escape($shopItem->getName()) ?>" data-bs-toggle="modal" data-bs-target="#myModal">
                </td>
            </tr>
            <tr>
                <td class="thumb text-center">
                    <a href="javascript:void(0)" class="thumbimg"><img src="<?=$img ?>" class="selected" alt="<?=$this->escape($shopItem->getName()) ?>"></a>
                    <?php
                    if ($shopItem->getImage1() && file_exists(ROOT_PATH . '/' . $shopItem->getImage1())) {
                        echo '<a href="javascript:void(0)" class="thumbimg"><img src="' . BASE_URL . '/' . $shopItem->getImage1() . '" class=""></a>';
                    }
                    if ($shopItem->getImage2() && file_exists(ROOT_PATH . '/' . $shopItem->getImage2())) {
                        echo '<a href="javascript:void(0)" class="thumbimg"><img src="' . BASE_URL . '/' . $shopItem->getImage2() . '" class=""></a>';
                    }
                    if ($shopItem->getImage3() && file_exists(ROOT_PATH . '/' . $shopItem->getImage3())) {
                        echo '<a href="javascript:void(0)" class="thumbimg"><img src="' . BASE_URL . '/' . $shopItem->getImage3() . '" class=""></a>';
                    } ?>
                </td>
            </tr>
        </table>
    </div>
    <div class="col-lg-6">
        <table class="table table-striped border">
            <colgroup>
                <col>
                <col>
            </colgroup>
            <tbody>
                <tr>
                    <th><?=$this->getTrans('productName') ?></th>
                    <td><?=$this->escape($shopItem->getName()) ?></td>
                </tr>
                <tr>
                    <th><?=$this->getTrans('itemNumber') ?></th>
                    <td><?=$this->escape($shopItem->getItemnumber()) ?></td>
                </tr>
                <tr>
                    <th><?=$this->getTrans('price') ?> <small>(<?=$this->getTrans('withTax') ?> <?=$this->escape($shopItem->getTax()) ?>%)</small></th>
                    <td><?=number_format($shopItem->getPrice(), 2, '.', '') ?> <?=$this->escape($this->get('currency')) ?></td>
                </tr>
                <tr>
                    <th><?=$this->getTrans('shippingCosts') ?></th>
                    <td><?=($shopItem->getShippingCosts() == '0.00') ? $this->getTrans('free') : number_format($shopItem->getShippingCosts(), 2, '.', '') . ' ' . $this->escape($this->get('currency')) ?></td>
                </tr>
                <tr>
                    <th><?=$this->getTrans('shippingTime') ?></th>
                    <td><?=$this->getTrans('approx') ?> <?=$this->escape($shopItem->getShippingTime()) ?> <?=$this->getTrans('days') ?></td>
                </tr>
                <tr>
                    <td colspan="2"><b><?=$this->getTrans('shortInfo') ?></b><br /><br /><small><?=$shopItem->getInfo() ?></small></td>
                </tr>
                <tr>
                    <td colspan="2" class="text-center">
                        <?php if ($shopItem->getStock() >= 1) { ?>
                            <form method="post" action="#shopAnker">
                            <?=$this->getTokenField() ?>
                            <input type="hidden" name="code" value="<?=$shopItem->getCode() ?>" />
                                <div class="input-group input-group-sm quantity">
                                    <span class="input-group-btn">
                                        <button class="btn btn-outline-secondary plus-btn" type="button" name="button"><i class="fa-solid fa-plus"></i></button>
                                    </span>
                                    <input class="form-control item-quantity"
                                        type="text"
                                        id="quantity"
                                        name="quantity"
                                        value="1"
                                        readonly>
                                    <span class="input-group-btn">
                                        <button class="btn btn-outline-secondary minus-btn" type="button" name="button"><i class="fa-solid fa-minus"></i></button>
                                    </span>
                                </div>
                                <button type="submit" class="btn btn-sm btn-warning"><i class="fa-solid fa-shopping-cart"></i> <?=$this->getTrans('inToCart') ?></button>
                            </form>
                        <?php } else { ?>
                            <button class="btn btn-outline-secondary">
                                <i class="fa-solid fa-store-slash"></i> <b><?=$this->getTrans('currentlySoldOut') ?></b>
                            </button>
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="text-center">
                        <div class="btn-group btn-group-sm">
                            <a class="btn btn-outline-secondary" href="<?=$this->getUrl('shop/index') ?>#shopAnker"><i class="fa-solid fa-backward"></i> <?=$this->getTrans('back') ?></a>
                            <a class="btn btn-outline-secondary" href="<?=$this->getUrl('shop/index/agb') ?>#shopAnker"><i class="fa-solid fa-gavel"></i> <?=$this->getTrans('menuAGB') ?></a>
                            <a class="btn btn-outline-secondary" href="<?=$this->getUrl('shop/index/cart') ?>#shopAnker"><i class="fa-solid fa-shopping-basket"></i> <?=$this->getTrans('menuCart') ?></a>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="col-lg-12 desc">
        <table class="table table-striped border">
            <tr>
                <td>
                    <?=$shopItem->getDesc() ?>
                </td>
            </tr>
        </table>
    </div>
</div>
<div style="clear:both;"></div>

<script src="<?=BASE_URL ?>/application/modules/shop/static/js/jquery.touchSwipe.min.js"></script>
<script>
$(document).ready(function () {
    setTimeout(function() {
        $('#infobox').slideUp("slow");
    }, 5000);
    $('a.thumbimg').click(function () {
        $('#productimage').attr('src',$(this).children('img').attr('src'));
        $('.thumbimg img').removeClass('selected');
        $(this).children('img').addClass('selected');
    });
});
$('.minus-btn').on('click', function(e) {
    e.preventDefault();
    const $this = $(this);
    const $input = $this.closest('div').find('input');
    let value = parseInt($input.val());
    if (value > 2) {
        value = value - 1;
    } else {
        value = 1;
    }
    $input.val(value);
});
$('.plus-btn').on('click', function(e) {
    e.preventDefault();
    const $this = $(this);
    const $input = $this.closest('div').find('input');
    let value = parseInt($input.val());
    const maxStock = '<?=$shopItem->getStock() ?>';
    if (value < maxStock) {
        value = value + 1;
    } else {
        value = maxStock;
    }
    $input.val(value);
});
$(".carousel").swipe({
    swipe: function(event, direction) {
        if (direction === 'left') $(this).carousel('next');
        if (direction === 'right') $(this).carousel('prev');
    },
    allowPageScroll:"vertical"
});
</script>
