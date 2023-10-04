<?php

/** @var Items $itemsMapper */

use Modules\Shop\Mappers\Items;

$itemsMapper = $this->get('itemsMapper');

/* shopcart session */
$status = '';

if (!empty($_SESSION['shopping_cart']) && $this->getRequest()->isSecure()) {
    if (isset($_POST['action']) && $_POST['action'] == 'remove') {
        foreach ($_SESSION['shopping_cart'] as $key => $value) {
            if (isset($_POST['code']) && $_POST['code'] == $key) {
                unset($_SESSION['shopping_cart'][$key]);
                $status = '<div id="infobox" class="alert alert-danger" role="alert">' . $this->getTrans('theProduct') . ' <b>' . $this->escape($_POST['name']) . '</b> ' . $this->getTrans('removedFromCart') . '</div>';
            }
            if (empty($_SESSION['shopping_cart'])) {
                unset($_SESSION['shopping_cart']);
            }
        }
    }

    if (isset($_POST['action']) && $_POST['action'] == 'change') {
        foreach ($_SESSION['shopping_cart'] as &$value) {
            if (isset($_POST['code']) && $value['code'] === $_POST['code']) {
                $_POST['quantity'] = ($_POST['quantity'] <= 0) ? 1 : $_POST['quantity'];
                $value['quantity'] = $_POST['quantity'];
                break;
            }
        }

        $_SESSION['shopping_willCollect'] = $_POST['willCollect'];
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
    <?=$this->getTrans('menuCart') ?>
    <?=$cart_badge ?>
    <div id="reload" class="collapse"><a href="" title="<?=$this->getTrans('reloadCart') ?>"><i class="fa-solid fa-arrows-rotate"></i></a></div>
    <div id="shopAnker"></div>
</h1>

<div class="message_box">
    <?=$status; ?>
</div>
<div class="table cart">
    <?php
    if (isset($_SESSION['shopping_cart'])) {
        $arrayShippingCosts = [0];
        $subtotal_price = 0; ?>
        <table>
            <thead>
                <tr>
                    <th scope="col" width="10%"><?=$this->getTrans('productImage') ?><br />&nbsp;</th>
                    <th scope="col" width="7%"></th>
                    <th scope="col" width="25%"><?=$this->getTrans('productName') ?><br /><small><?=$this->getTrans('itemNumber') ?></small></th>
                    <th scope="col" width="20%"><?=$this->getTrans('singlePrice') ?><br /><small><?=$this->getTrans('withTax') ?></small></th>
                    <th scope="col" width="18%" class="text-center"><?=$this->getTrans('entries') ?><br />&nbsp;</th>
                    <th scope="col" width="20%" class="text-end"><?=$this->getTrans('total') ?><br /><small><?=$this->getTrans('withTax') ?></small></th>
                </tr>
            </thead>
            <tbody>
        <?php
        $itemIds = [];
        foreach ($_SESSION['shopping_cart'] as $product) {
            // Don't add entries where the product id and quantity is not an integer and bigger than 0.
            // Probably a corrupted shopping cart.
            if ((ctype_digit(strval($product['id'])) && $product['id'] > 0) && (ctype_digit(strval($product['quantity'])) && $product['quantity'] > 0)) {
                $itemIds[] = $product['id'];
            }
        }

        $itemsAssoc = [];
        $items = $itemsMapper->getShopItems(['id' => $itemIds]);
        foreach ($items as $item) {
            $itemsAssoc[$item->getId()] = $item;
        }

        foreach ($_SESSION['shopping_cart'] as $product) {
            $itemId = $product['id'];
            $item = $itemsAssoc[$itemId];
            $itemCode = '';
            $itemName = '';
            $itemPrice = 0;
            $itemNumber = '';
            $itemImg = '';
            $itemMaxStock = '';

            if ($item) {
                $itemCode = $item->getCode();
                $itemName = $item->getName();
                $itemPrice = $item->getPrice();
                $itemNumber = $item->getItemnumber();
                $itemImg = $item->getImage();
                $itemMaxStock = $item->getStock();
                $arrayShippingCosts[] = $item->getShippingCosts();
            }

            $shopImgPath = '/application/modules/shop/static/img/';
            if ($itemImg and file_exists(ROOT_PATH . '/' . $itemImg)) {
                $img = BASE_URL . '/' . $itemImg;
            } else {
                $img = BASE_URL . $shopImgPath . 'noimg.jpg';
            } ?>
                <tr>
                    <td data-label="<?=$this->getTrans('productImage') ?>">
                        <a href="<?=$this->getUrl('shop/index/show/id/' . $product['id']) ?>#shopAnker">
                            <img src="<?=$img ?>" alt="<?=$this->escape($itemName) ?>"/>
                        </a>
                    </td>
                    <td data-label="<?=$this->getTrans('remove') ?>" class="text-center">
                        <form method="post" action="#shopAnker">
                            <?=$this->getTokenField() ?>
                            <input type="hidden" name="code" value="<?=$this->escape($itemCode); ?>" />
                            <input type="hidden" name="name" value="<?=$this->escape($itemName); ?>" />
                            <input type="hidden" name="action" value="remove" />
                            <button type="submit" class="btn btn-sm btn-default fa-regular fa-trash-can remove"></button>
                        </form>
                    </td>
                    <td data-label="<?=$this->getTrans('productName') ?>">
                        <b><?=$this->escape($itemName); ?></b><br /><small><?=$this->escape($itemNumber); ?></small>
                    </td>
                    <td data-label="<?=$this->getTrans('singlePrice') ?> (<?=$this->getTrans('withTax') ?>)">
                        <?=number_format($itemPrice, 2, '.', '') ?> <?=$this->escape($this->get('currency')) ?>
                    </td>
                    <td data-label="<?=$this->getTrans('entries') ?>" class="text-center">
                        <form method="post" action="#shopAnker" class="quantity">
                            <?=$this->getTokenField() ?>
                            <input type="hidden" name="code" value="<?=$this->escape($itemCode); ?>" />
                            <input type="hidden" name="willCollect" value="<?=($this->get('allowWillCollect') && isset($_SESSION['shopping_willCollect'])) ? 'true' : '' ?>" />
                            <input type="hidden" name="action" value="change" />
                            <div class="input-group">
                                <input type="hidden" name="maxStock" value="<?=$itemMaxStock; ?>" />
                                    <button class="btn btn-sm btn-outline-secondary plus-btn" type="button" name="button"><i class="fa-solid fa-plus"></i></button>
                                <input class="form-control item-quantity input-sm"
                                    type="text"
                                    id="quantity"
                                    name="quantity"
                                    onchange="this.form.submit()"
                                    value="<?=$product['quantity'] ?>"
                                    readonly>
                                    <button class="btn btn-sm btn-outline-secondary minus-btn" type="button" name="button"><i class="fa-solid fa-minus"></i></button>
                            </div>
                        </form>
                    </td>
                    <td data-label="<?=$this->getTrans('total') ?> (<?=$this->getTrans('withTax') ?>)" class="text-end">
                        <b><?=number_format($itemPrice * $product['quantity'], 2, '.', '') ?> <?=$this->escape($this->get('currency')) ?></b>
                    </td>
                </tr>
                    <?php
                        $subtotal_price += round($itemPrice * $product['quantity'], 2);
        } ?>
            </tbody>
        </table>
        <table class="sum">
            <tr>
                <th>
                    <?=$this->getTrans('deliveryCosts') ?>
                </th>
                <td data-label="<?=$this->getTrans('deliveryCosts') ?>" class="text-end" id="deliveryCosts">
                    <?php $shipping_costs = max($arrayShippingCosts); ?>
                    <?php if (isset($_SESSION['shopping_willCollect'])) : ?>
                        <?=number_format(0, 2, '.', '') ?> <?=$this->escape($this->get('currency')) ?>
                    <?php else : ?>
                        <?=number_format($shipping_costs, 2, '.', '') ?> <?=$this->escape($this->get('currency')) ?>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <th>
                    <b><?=$this->getTrans('totalPrice') ?></b>
                </th>
                <td data-label="<?=$this->getTrans('totalPrice') ?>" class="text-end" id="totalPrice">
                    <?php $total_price = $subtotal_price + $shipping_costs; ?>
                    <?php if (isset($_SESSION['shopping_willCollect'])) : ?>
                        <b><?=number_format($total_price - $shipping_costs, 2, '.', '') ?> <?=$this->escape($this->get('currency')) ?></b>
                    <?php else : ?>
                        <b><?=number_format($total_price, 2, '.', '') ?> <?=$this->escape($this->get('currency')) ?></b>
                    <?php endif; ?>
                </td>
            </tr>
        </table>

        <?php if ($this->get('allowWillCollect')) : ?>
        <form method="post" action="#shopAnker">
            <?=$this->getTokenField() ?>
            <input type="hidden" name="action" value="change" />
            <input type="checkbox" id="willCollect" name="willCollect" <?=(isset($_SESSION['shopping_willCollect'])) ? ' checked' : '' ?> />
            <label for="willCollect"><?=$this->getTrans('willCollect') ?></label>
        </form>
        <?php else : ?>
            <?php unset($_SESSION['shopping_willCollect']) ?>
        <?php endif; ?>

        <form method="post" action="order#shopAnker" class="text-end">
            <div class="btn-group btn-group-sm">
                <a class="btn btn-outline-secondary" href="<?=$this->getUrl('shop/index') ?>#shopAnker"><i class="fa-solid fa-backward"></i> <?=$this->getTrans('back') ?></a>
                <button class="btn btn-warning"><?=$this->getTrans('completePurchase') ?> <i class="fa-solid fa-forward"></i></button>
            </div>
        </form>
        <?php
    } else { ?>
        <?=$this->getTrans('cartEmpty') ?>
    <div class="row space20"></div>
    <a href="<?=$this->getUrl('shop/index') ?>#shopAnker" class="btn btn-default">
        <i class="fa-solid fa-backward"></i> <?=$this->getTrans('back') ?>
    </a>
    <?php } ?>
</div>

<?php if (isset($_SESSION['shopping_cart'])) : ?>
<script>
let willCollect = $('#willCollect');

$(document).ready(function () {
    setTimeout(function() {
        $('#infobox').slideUp("slow");
    }, 5000);

    willCollect.change(function() {
        willCollect.val(this.checked);
        if (willCollect.is(':checked')) {
            $('#deliveryCosts').text(<?=json_encode(number_format(0, 2, '.', '') . ' ' . $this->escape($this->get('currency'))) ?>);
            $('#totalPrice').find('b').text(<?=json_encode(number_format($total_price - $shipping_costs, 2, '.', '') . ' ' . $this->escape($this->get('currency'))) ?>);
        } else {
            $('#deliveryCosts').text(<?=json_encode(number_format(max($arrayShippingCosts), 2, '.', '') . ' ' . $this->escape($this->get('currency'))) ?>);
            $('#totalPrice').find('b').text(<?=json_encode(number_format($total_price, 2, '.', '') . ' ' . $this->escape($this->get('currency'))) ?>);
        }

        const form = $(this).closest('form');
        const url = form.attr('action');
        $.ajax({
            type: "POST",
            url: url,
            data: form.serialize()
        });
    });
});
$('.minus-btn').on('click', function(e) {
    e.preventDefault();
    const $this = $(this);
    const $input = $this.closest('div').find('input[name="quantity"]');
    let value = parseInt($input.val());
    if (value > 2) {
        value = value - 1;
    } else {
        value = 1;
    }
    $input.val(value);
    const form = $this.closest('form');
    const url = form.attr('action');
    $.ajax({
        type: "POST",
        url: url,
        data: form.serialize()
    });
    $('#reload').css('display','inline-block');
});
$('.plus-btn').on('click', function(e) {
    e.preventDefault();
    const $this = $(this);
    const $input = $this.closest('div').find('input[name="quantity"]');
    let value = parseInt($input.val());
    const maxStock = $this.closest('div').find('input[name="maxStock"]').val();
    if (value < maxStock) {
        value = value + 1;
    } else {
        value = maxStock;
    }
    $input.val(value);
    const form = $this.closest('form');
    const url = form.attr('action');
    $.ajax({
        type: "POST",
        url: url,
        data: form.serialize()
    });
    $('#reload').css('display','inline-block');
});
</script>
<?php endif; ?>
