<?php
$itemsMapper = $this->get('itemsMapper');
?>

<h1>
    <?=$this->getTrans('menuCustomerArea') ?>
</h1>

<?php if (!empty($this->get('order'))) : ?>
    <?php
    $order = $this->get('order');
    $ilchDate = new Ilch\Date($this->escape($order->getDatetime()));
    $orderTime = $ilchDate->format(' H:i ', true);
    $orderDate = $ilchDate->format('d.m.Y ', true);
    $invoiceNr = $ilchDate->format('ymd').'-'.$order->getId();

    $invoiceFilename = '';
    $nameInvoice = utf8_decode($this->getTrans('invoice'));
    $shopInvoicePath = '/application/modules/shop/static/invoice/';

    if (empty($order->getInvoiceFIlename())) {
        $hash = bin2hex(random_bytes(32));
        $invoiceFilename = $nameInvoice.'_'.$invoiceNr.'_'.$hash;
    } else {
        $invoiceFilename = $order->getInvoiceFIlename();
    }
    $file_location = ROOT_PATH.$shopInvoicePath.$invoiceFilename.'.pdf';
    ?>

    <h4><?=$this->getTrans('customerAreaInfoBuyer') ?></h4>
    <div class="table-responsive">
        <table class="table">
            <colgroup>
                <col class="col-lg-1">
                <col>
            </colgroup>
            <tbody>
            <tr>
                <th><?=$this->getTrans('invoiceAddress') ?></th>
                <td><?=$this->escape($order->getInvoiceAddress()->getPrename()) ?> <?=$this->escape($order->getInvoiceAddress()->getLastname()) ?>, <?=$this->escape($order->getInvoiceAddress()->getStreet()) ?>, <?=$this->escape($order->getInvoiceAddress()->getPostcode()) ?> <?=$this->escape($order->getInvoiceAddress()->getCity()) ?>, <?=$this->escape($order->getInvoiceAddress()->getCountry()) ?></td>
            </tr>
            <tr>
                <th><?=$this->getTrans('deliveryAddress') ?></th>
                <td><?=$this->escape($order->getDeliveryAddress()->getPrename()) ?> <?=$this->escape($order->getDeliveryAddress()->getLastname()) ?>, <?=$this->escape($order->getDeliveryAddress()->getStreet()) ?>, <?=$this->escape($order->getDeliveryAddress()->getPostcode()) ?> <?=$this->escape($order->getDeliveryAddress()->getCity()) ?>, <?=$this->escape($order->getDeliveryAddress()->getCountry()) ?></td>
            </tr>
            <tr>
                <th><?=$this->getTrans('emailAddress') ?></th>
                <td><a href="mailto:<?=$this->escape($order->getEmail()) ?>"><?=$this->escape($order->getEmail()) ?></a></td>
            </tr>
            <tr>
                <th><?=$this->getTrans('date') ?></th>
                <td><?=$orderDate . $this->getTrans('dateTimeAt') . $orderTime .$this->getTrans('dateTimeoClock') ?></td>
            </tr>
            <tr>
                <th><?=$this->getTrans('invoice') ?></th>
                <td>
                    <?=$this->getTrans('invoice') ?> - <?=utf8_decode($this->getTrans('numberShort')) ?> <?=$invoiceNr ?>
                    <?php if (file_exists($file_location)) : ?>
                    <a href="<?=$this->getUrl(['action' => 'download', 'id' => $order->getId()], null, true) ?>" target="_blank" class="btn btn-sm alert-success">
                        <i class="fa-solid fa-file-pdf" aria-hidden="true"></i>&nbsp;<?=$this->getTrans('showPDF') ?>
                    </a>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <th><?=$this->getTrans('status') ?></th>
                <td>
                    <?php if ($order->getStatus() == 0) : ?>
                        <div class="btn btn-sm alert-danger"><i class="fa-solid fa-plus-square" aria-hidden="true"></i>&nbsp;<b><?=$this->getTrans('newBIG') ?></b></div>
                    <?php elseif ($order->getStatus() == 1) : ?>
                        <div class="btn btn-sm alert-warning"><i class="fa-solid fa-pencil-square" aria-hidden="true"></i>&nbsp;<b><?= $this->getTrans('processingBIG') ?></b></div>
                    <?php elseif ($order->getStatus() == 2) : ?>
                        <div class="btn btn-sm alert-info"><i class="fa-solid fa-exclamation-triangle" aria-hidden="true"></i>&nbsp;<b><?= $this->getTrans('canceledBIG') ?></b></div>
                    <?php else : ?>
                        <div class="btn btn-sm alert-success"><i class="fa-solid fa-check-square" aria-hidden="true"></i>&nbsp;<b><?= $this->getTrans('completedBIG') ?></b></div>
                    <?php endif; ?>
                </td>
            </tr>
            </tbody>
        </table>
    </div>

    <h4><?=$this->getTrans('orderedItems') ?></h4>
    <div class="table-responsive cart">
        <table class="table table-striped">
            <thead>
            <tr>
                <th><?=$this->getTrans('productImage') ?><br />&nbsp;</th>
                <th><?=$this->getTrans('productName') ?><br /><small><?=$this->getTrans('itemNumber') ?></small></th>
                <th><?=$this->getTrans('shippingTime') ?><br />&nbsp;</th>
                <th><?=$this->getTrans('singlePrice') ?><br /><small><?=$this->getTrans('withoutTax') ?></small></th>
                <th><?=$this->getTrans('taxShort') ?><br />&nbsp;</th>
                <th><?=$this->getTrans('singlePrice') ?><br /><small><?=$this->getTrans('withTax') ?></small></th>
                <th class="text-center"><?=$this->getTrans('entries') ?><br />&nbsp;</th>
                <th class="text-right"><?=$this->getTrans('total') ?><br /><small><?=$this->getTrans('withTax') ?></small></th>
            </tr>
            </thead>
            <tbody>
            <?php
            $orderItems = $order->getOrderdetails();
            $subtotal_price = 0;
            $pdfOrderNr = 1;
            foreach ($orderItems as $orderItem):
                $itemId = $orderItem->getItemId();
                $item = $itemsMapper->getShopItemById($itemId);
                $itemImg = $item->getImage();
                $itemName = $item->getName();
                $itemNumber = $item->getItemnumber();
                $itemPrice = $orderItem->getPrice();
                $itemTax = $orderItem->getTax();
                $itemPriceWithoutTax = round(($itemPrice / (100 + $itemTax)) * 100, 2);
                $arrayShippingCosts[] = $orderItem->getShippingCosts();
                $itemShippingTime = $item->getShippingTime();
                $arrayShippingTime[] = $itemShippingTime;
                $arrayTaxes[] = $itemTax;
                $arrayPrices[] = $itemPrice * $orderItem->getQuantity();
                $arrayPricesWithoutTax[] = $itemPriceWithoutTax * $orderItem->getQuantity();
                $shopImgPath = '/application/modules/shop/static/img/';
                if ($itemImg AND file_exists(ROOT_PATH.'/'.$itemImg)) {
                    $img = BASE_URL.'/'.$itemImg;
                } else {
                    $img = BASE_URL.$shopImgPath.'noimg.jpg';
                }
                $currency = iconv('UTF-8', 'windows-1252', $this->escape($this->get('currency')));
                $pdfOrderData[] = [
                    $pdfOrderNr++,
                    utf8_decode($itemName),
                    number_format($itemPriceWithoutTax, 2, '.', '').' '.$currency,
                    $itemTax.' %',
                    number_format($itemPrice, 2, '.', '').' '.$currency,
                    $orderItem->getQuantity(),
                    number_format($itemPrice * $orderItem->getQuantity(), 2, '.', '').' '.$currency,
                    utf8_decode($this->getTrans('itemNumberShort')).' '.$itemNumber];
                ?>
                <tr>
                    <td><img src="<?=$img ?>" class="item_image" alt="<?=$this->escape($itemName) ?>"> </td>
                    <td>
                        <b><?=$this->escape($itemName); ?></b><br /><small><?=$this->escape($itemNumber); ?></small>
                    </td>
                    <td><?=$itemShippingTime ?> <?=$this->getTrans('days') ?></td>
                    <td>
                        <?=number_format($itemPriceWithoutTax, 2, '.', '') ?> <?=$this->escape($this->get('currency')) ?>
                    </td>
                    <td><?=$itemTax ?> %</td>
                    <td>
                        <b><?=number_format($itemPrice, 2, '.', '') ?> <?=$this->escape($this->get('currency')) ?></b>
                    </td>
                    <td class="text-center">
                        <b><?=$orderItem->getQuantity() ?></b>
                    </td>
                    <td class="text-right">
                        <b><?=number_format($itemPrice * $orderItem->getQuantity(), 2, '.', '') ?> <?=$this->escape($this->get('currency')) ?></b>
                    </td>
                </tr>
                <?php $subtotal_price += round($itemPrice * $orderItem->getQuantity(), 2); ?>
            <?php endforeach; ?>
            <tr>
                <td colspan="7" class="text-right finished">
                    <b><?=$this->getTrans('deliveryCosts') ?>:</b>
                </td>
                <td colspan="1" class="text-right finished">
                    <?php $shipping_costs = max($arrayShippingCosts); ?>
                    <b><?=number_format($shipping_costs, 2, '.', '') ?> <?=$this->escape($this->get('currency')) ?></b>
                </td>
            </tr>
            <tr>
                <td colspan="7" class="text-right finish">
                    <?=$this->getTrans('subtotal') ?> <?=$this->getTrans('withTax') ?>:
                </td>
                <td colspan="1" class="text-right finish">
                    <?php $total_price = array_sum($arrayPrices) + $shipping_costs; ?>
                    <?=number_format($total_price, 2, '.', '') ?> <?=$this->escape($this->get('currency')) ?>
                </td>
            </tr>
            <tr>
                <td colspan="7" class="text-right finish">
                    <?=$this->getTrans('subtotal') ?> <?=$this->getTrans('withoutTax') ?>:
                </td>
                <td colspan="1" class="text-right finish">
                    <?php $sumPricewithoutTax = array_sum($arrayPricesWithoutTax) + round(($shipping_costs / (100 + max($arrayTaxes))) * 100, 2); ?>
                    <?=number_format($sumPricewithoutTax, 2, '.', '') ?> <?=$this->escape($this->get('currency')) ?>
                </td>
            </tr>
            <tr>
                <td colspan="7" class="text-right finish">
                    <?=$this->getTrans('tax') ?>:
                </td>
                <td colspan="1" class="text-right finish">
                    <?php $differenzTax = round($total_price - $sumPricewithoutTax, 2); ?>
                    <?=number_format($differenzTax, 2, '.', '') ?> <?=$this->escape($this->get('currency')) ?>
                </td>
            </tr>
            <tr>
                <td colspan="7" class="text-right finished">
                    <b><?=$this->getTrans('totalPrice') ?>:</b>
                </td>
                <td colspan="1" class="text-right finished">
                    <b><?=number_format($total_price, 2, '.', '') ?> <?=$this->escape($this->get('currency')) ?></b>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
<?php else : ?>
<p><?=$this->getTrans('customerAreaOrderNotFound') ?></p>
<?php endif; ?>
