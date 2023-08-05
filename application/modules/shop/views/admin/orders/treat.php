<link href="<?=$this->getModuleUrl('static/css/shop_admin.css') ?>" rel="stylesheet">
<?php
$itemsMapper = $this->get('itemsMapper');
$ordersMapper = $this->get('ordersMapper');
$settingsMapper = $this->get('settingsMapper');
?>
<?php if ($this->get('order') != '') : ?>
    <h1><?=$this->getTrans('editOrder'); ?></h1>
    <?php
    /** @var \Modules\Shop\Models\Order $order */
    $order = $this->get('order');
    $ilchDate = new Ilch\Date($this->escape($order->getDatetime()));
    $orderTime = $ilchDate->format(' H:i ', true);
    $orderDate = $ilchDate->format('d.m.Y ', true);
    $orderDateInvoice = $ilchDate->format('d.m.Y ');
    $invoiceNr = $ilchDate->format('ymd') . '-' . $order->getId();
    ?>
    <?php if ($order->getStatus() == 0) { ?>
        <div class="alert alert-danger">
            <i class="fa-solid fa-plus-square" aria-hidden="true"></i>&nbsp;
            <b><?=$this->getTrans('newBIG') ?></b>
            &emsp;|&emsp;<?=$orderDate . $this->getTrans('dateTimeAt') . $orderTime . $this->getTrans('dateTimeoClock') ?>&emsp;|&emsp;<?=$this->getTrans('infoOrderOpen') ?>
        </div>
    <?php } elseif ($order->getStatus() == 1) { ?>
        <div class="alert alert-warning">
            <i class="fa-solid fa-pencil-square" aria-hidden="true"></i>&nbsp;
            <b><?=$this->getTrans('processingBIG') ?></b>
            &emsp;|&emsp;<?=$orderDate . $this->getTrans('dateTimeAt') . $orderTime . $this->getTrans('dateTimeoClock') ?>&emsp;|&emsp;<?=$this->getTrans('infoOrderProcessing') ?>
        </div>
    <?php } elseif ($order->getStatus() == 2) { ?>
        <div class="alert alert-info">
            <i class="fa-solid fa-exclamation-triangle" aria-hidden="true"></i>&nbsp;
            <b><?=$this->getTrans('canceledBIG') ?></b>
            &emsp;|&emsp;<?=$orderDate . $this->getTrans('dateTimeAt') . $orderTime . $this->getTrans('dateTimeoClock') ?>>&emsp;|&emsp;<?=$this->getTrans('infoOrderCanceled') ?>
        </div>
    <?php } else { ?>
        <div class="alert alert-success">
            <i class="fa-solid fa-check-square" aria-hidden="true"></i>&nbsp;
            <b><?=$this->getTrans('completedBIG') ?></b>
            &emsp;|&emsp;<?=$orderDate . $this->getTrans('dateTimeAt') . $orderTime . $this->getTrans('dateTimeoClock') ?>&emsp;|&emsp;<?=$this->getTrans('infoOrderFinished') ?>
        </div>
    <?php } ?>
    <?php if ($order->getWillCollect()) : ?>
        <div class="alert alert-info">
            <i class="fa-solid fa-exclamation-triangle" aria-hidden="true"></i>&nbsp;
            <b><?=$this->getTrans('infoCustomerChoseWillCollect') ?></b>
        </div>
    <?php endif; ?>
    <h4><?=$this->getTrans('infoBuyer') ?></h4>
    <div class="table-responsive">
        <table class="table">
            <colgroup>
                <col class="col-lg-1">
                <col>
            </colgroup>
            <tbody>
                <tr>
                    <th><?=$this->getTrans('name') ?></th>
                    <td><?=$this->escape($order->getInvoiceAddress()->getPrename()) ?> <?=$this->escape($order->getInvoiceAddress()->getLastname()) ?></td>
                </tr>
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
            </tbody>
        </table>
    </div>
    <h4><?=$this->getTrans('orderedItems') ?></h4>
    <div class="table-responsive order">
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
                foreach ($orderItems as $orderItem) :
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
                    if ($itemImg && file_exists(ROOT_PATH . '/' . $itemImg)) {
                        $img = BASE_URL . '/' . $itemImg;
                    } else {
                        $img = BASE_URL . $shopImgPath . 'noimg.jpg';
                    }
                    $currency = iconv('UTF-8', 'windows-1252', $this->escape($this->get('currency')));
                    $pdfOrderData[] = [
                        $pdfOrderNr++,
                        mb_convert_encoding($itemName, 'ISO-8859-1', 'UTF-8'),
                        number_format($itemPriceWithoutTax, 2, '.', '') . ' ' . $currency,
                        $itemTax . ' %',
                        number_format($itemPrice, 2, '.', '') . ' ' . $currency,
                        $orderItem->getQuantity(),
                        number_format($itemPrice * $orderItem->getQuantity(), 2, '.', '') . ' ' . $currency,
                        mb_convert_encoding($this->getTrans('itemNumberShort') . ' ' . $itemNumber, 'ISO-8859-1', 'UTF-8')
                    ];
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
                        <?php $shipping_costs = ($order->getWillCollect()) ? 0 : max($arrayShippingCosts); ?>
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
    <h4><?=$this->getTrans('administration') ?></h4>
    <form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <input type="hidden" name="id" value="<?=$order->getId() ?>" />
    <input type="hidden" name="confirmTransferBackToStock" value="false" />
    <input type="hidden" name="confirmRemoveFromStock" value="false" />
        <table class="table table-striped">
            <tr>
                <th><?=$this->getTrans('adjustStatus') ?></th>
            </tr>
            <tr>
                <td>
                    <div class="btn-group btn-group-sm">
                        <button type="submit" name="status" value="0" class="btn btn-sm alert-danger">
                            <i class="fa-solid fa-plus-square" aria-hidden="true"></i>&nbsp;<?=$this->getTrans('openBIG') ?>
                        </button>
                        <button type="submit" name="status" value="1" class="btn btn-sm alert-warning">
                            <i class="fa-solid fa-pencil-square" aria-hidden="true"></i>&nbsp;<?=$this->getTrans('processingBIG') ?>
                        </button>
                        <button type="submit" name="status" value="2" class="btn btn-sm alert-info">
                            <i class="fa-solid fa-exclamation-triangle" aria-hidden="true"></i>&nbsp;<?=$this->getTrans('canceledBIG') ?>
                        </button>
                        <button type="submit" name="status" value="3" class="btn btn-sm alert-success">
                            <i class="fa-solid fa-check-square" aria-hidden="true"></i>&nbsp;<?=$this->getTrans('completedBIG') ?>
                        </button>
                    </div>
                </td>
            </tr>
            <tr>
                <th><?=$this->getTrans('invoice') ?> - <?=$this->getTrans('numberShort') ?> <?=$invoiceNr ?></th>
            </tr>
            <tr>
                <td>
                    <?php if ($order->getDateTimeInvoiceSent() != '0000-00-00 00:00:00') : ?>
                        <?php $ilchDate = new Ilch\Date($order->getDateTimeInvoiceSent()); ?>
                        <p><?=$this->getTrans('invoiceLastSent') . $ilchDate->format('d.m.Y H:i ', true) . $this->getTrans('dateTimeoClock') ?></p>
                    <?php endif ?>
                    <form class="form-horizontal" method="POST" action="">
                        <?php
                        $invoiceFilename = '';
                        $nameInvoice = mb_convert_encoding($this->getTrans('invoice'), 'ISO-8859-1', 'UTF-8');
                        $shopInvoicePath = '/application/modules/shop/static/invoice/';

                        if (empty($order->getInvoiceFIlename())) {
                            $hash = bin2hex(random_bytes(32));
                            $invoiceFilename = $nameInvoice . '_' . $invoiceNr . '_' . $hash;
                        } else {
                            $invoiceFilename = $order->getInvoiceFIlename();
                        }

                        $file_location = ROOT_PATH . $shopInvoicePath . $invoiceFilename . '.pdf';
                        $file_show = BASE_URL . $shopInvoicePath . $invoiceFilename . '.pdf';
                        if (file_exists($file_location)) { ?>
                            <a href="<?=$this->getUrl(['action' => 'download', 'id' => $order->getId()], null, true) ?>" target="_blank" class="btn btn-sm alert-success">
                                <i class="fa-solid fa-file-pdf" aria-hidden="true"></i>&nbsp;<?=$this->getTrans('showPDF') ?>
                            </a>
                            <a href="<?=$this->getUrl(['action' => 'sendInvoice', 'id' => $order->getId()], null, true) ?>" class="btn btn-sm alert-success">
                                <i class="fa-solid fa-file-pdf" aria-hidden="true"></i>&nbsp;<?=$this->getTrans('sendInvoice') ?>
                            </a>
                            <button type="submit" name="PDF" value="delete" class="btn btn-sm alert-danger">
                                <i class="fa-solid fa-minus-square" aria-hidden="true"></i>&nbsp;<?=$this->getTrans('deletePDF') ?>
                            </button>
                        <?php } else { ?>
                            <button type="submit" name="PDF" value="createInvoice" class="btn btn-sm alert-default">
                                <i class="fa-solid fa-file-pdf" aria-hidden="true"></i>&nbsp;<?=$this->getTrans('createPDF') ?>
                            </button>
                        <?php } ?>
                    </form>
                </td>
            </tr>
            <tr>            
                <th><?=$this->getTrans('deliveryNote') ?></th>
            </tr>
            <tr>            
                <td>
                    <div class="btn-group btn-group-sm">
                        <button type="submit" formtarget="_blank" name="PDF" value="showDeliveryNote" class="btn btn-sm alert-default">
                            <i class="fa-solid fa-file-pdf" aria-hidden="true"></i>&nbsp;<?=$this->getTrans('showPDF') ?>
                        </button>
                        <span class="btn btn-sm alert-default" style="pointer-events: none">
                            <i class="fa-solid fa-info"></i> <?=$this->getTrans('infoDeliveryNote') ?>
                        </span>
                    </div>
                </td>
            </tr>
            <tr>
                <th><?=$this->getTrans('delOrder') ?></th>
            </tr>
            <tr>
                <td>
                    <div class="btn-group btn-group-sm">
                        <button type="submit" name="delete" value="1" class="btn btn-sm alert-default delete_button">
                            <i class="fa-regular fa-trash-can" aria-hidden="true"></i>&nbsp;<?=$this->getTrans('deleteBIG') ?>
                        </button>
                        <span class="btn btn-sm alert-default" style="pointer-events: none">
                            <i class="fa-solid fa-info"></i> <?=$this->getTrans('infoDeleteOrder') ?>
                        </span>
                    </div>
                </td>
            </tr>
        </table>
        <a class="btn btn-default" href="<?=$this->getUrl(['action' => 'index']) ?>">
            <i class="fa-solid fa-backward"></i> <?=$this->getTrans('back') ?>
        </a>
    </form>
    <?php

    /* A4: 210mm x 297mm with border 20mm */
    if (isset($_POST['PDF']) && $_POST['PDF'] == 'createInvoice') {
        ob_end_clean();
        require(ROOT_PATH . '/application/modules/shop/static/class/fpdf/fpdf.php');

        class PDF extends FPDF
        {
            public $bankBIC;
            public $bankIBAN;
            public $bankName;
            public $bankOwner;
            public $DateInvoice;
            public $DeliveryCity;
            public $DeliveryCosts;
            public $DeliveryCountry;
            public $DeliveryDate;
            public $DeliveryLastname;
            public $DeliveryPostcode;
            public $DeliveryPrename;
            public $DeliveryStreet;
            public $invoiceNr;
            public $invoiceTextBottom;
            public $invoiceTextTop;
            public $nameByEmail;
            public $nameDateInvoice;
            public $nameDeliveryCosts;
            public $nameDeliveryDate;
            public $nameDeliveryPlace;
            public $nameFrom;
            public $nameInvoice;
            public $nameNumber;
            public $nameOrder;
            public $nameSite;
            public $nameSubTotalTax;
            public $nameSubTotalWithoutTax;
            public $nameTax;
            public $nameTotalPrice;
            public $OrderCurrency;
            public $OrderData;
            public $orderDate;
            public $OrderDifferenzTax;
            public $OrderHeader;
            public $OrderPriceWithoutTax;
            public $OrderTotalPrice;
            public $payInfoGreetings;
            public $ReceiverCity;
            public $ReceiverCountry;
            public $ReceiverEmail;
            public $ReceiverLastname;
            public $ReceiverPostcode;
            public $ReceiverPrename;
            public $ReceiverStreet;
            public $shopCity;
            public $shopFax;
            public $shopLogo;
            public $shopMail;
            public $shopName;
            public $shopPLZ;
            public $shopStNr;
            public $shopStreet;
            public $shopTel;
            public $shopWeb;
            public $willCollectNote;

            public function Header()
            {
                $this->SetMargins(20, 20, 20);
                $this->Image($this->shopLogo, 140, 15, 50);
            }

            public function InvoiceHead()
            {
                $this->SetFont('Arial', 'U', 8);
                $this->SetTextColor(100, 100, 100);
                $this->Cell(130, 5, $this->shopName . ' | ' . $this->shopStreet . ' | ' . $this->shopPLZ . ' ' . $this->shopCity, 0, 0, 'L');
                $this->SetTextColor(0, 0, 0);
                $this->SetFont('Arial', 'B', 11);
                $this->Cell(40, 5, $this->nameDateInvoice, 0, 1, 'R');
                $this->SetFont('Arial', '', 11);
                $this->Cell(130, 5, $this->ReceiverPrename . ' ' . $this->ReceiverLastname, 0, 0, 'L');
                $this->SetFont('Arial', '', 10);
                $this->Cell(40, 5, $this->DateInvoice, 0, 1, 'R');
                $this->SetFont('Arial', '', 11);
                $this->Cell(170, 5, $this->ReceiverStreet, 0, 1, 'L');
                $this->Cell(130, 5, $this->ReceiverPostcode . ' ' . $this->ReceiverCity, 0, 0, 'L');
                $this->SetFont('Arial', 'B', 11);
                $this->Cell(40, 5, $this->nameDeliveryDate, 0, 1, 'R');
                $this->SetFont('Arial', '', 11);
                $this->Cell(130, 5, $this->ReceiverCountry, 0, 0, 'L');
                $this->SetFont('Arial', '', 10);
                $this->Cell(40, 5, $this->DeliveryDate, 0, 1, 'R');
                $this->Cell(130, 5);
                $this->Cell(40, 5, $this->willCollectNote, 0, 1, 'R');
                $this->SetFont('Arial', 'I', 9);
                $this->Cell(130, 5, $this->nameByEmail . ': ' . $this->ReceiverEmail, 0, 1, 'L');
                $this->Cell(130, 5);
                $this->SetFont('Arial', 'B', 11);
                $this->Cell(40, 5, $this->nameDeliveryPlace, 0, 1, 'R');
                $this->SetFont('Arial', '', 10);
                $this->Cell(170, 5, $this->DeliveryPrename . ' ' . $this->DeliveryLastname, 0, 1, 'R');
                $this->Cell(170, 5, $this->DeliveryStreet, 0, 1, 'R');
                $this->Cell(170, 5, $this->DeliveryPostcode . ' ' . $this->DeliveryCity, 0, 1, 'R');
                $this->Cell(170, 5, $this->DeliveryCountry, 0, 1, 'R');
            }

            public function TitleLine()
            {
                $this->SetFont('Arial', 'B', 14);
                $this->SetTextColor(0, 0, 0);
                $this->Cell(0, 8, $this->nameInvoice . ' - ' . $this->nameNumber . ' ' . $this->invoiceNr, 0, 1, 'L');
                $this->SetFont('Arial', 'I', 10);
                $this->Cell(0, 5, $this->nameOrder . ' ' . $this->nameFrom . ' ' . $this->orderDate, 0, 1, 'L');
                $this->SetFont('Arial', '', 11);
                $this->Ln(15);
                $this->MultiCell(170, 5, $this->invoiceTextTop);
            }

            public function OrderTable()
            {
                $this->SetFillColor(220, 220, 220);
                $this->SetTextColor(0);
                $this->SetDrawColor(100, 100, 100);
                $this->SetLineWidth(.1);
                $this->SetFont('Arial', 'B', 9);
                $w = [5, 65, 26, 10, 26, 12, 26];
                $this->Cell($w[0], 6, $this->OrderHeader[0], 1, 0, 'R', true);
                $this->Cell($w[1], 6, $this->OrderHeader[1], 1, 0, 'L', true);
                $this->Cell($w[2], 6, $this->OrderHeader[2], 1, 0, 'R', true);
                $this->Cell($w[3], 6, $this->OrderHeader[3], 1, 0, 'R', true);
                $this->Cell($w[4], 6, $this->OrderHeader[4], 1, 0, 'R', true);
                $this->Cell($w[5], 6, $this->OrderHeader[5], 1, 0, 'R', true);
                $this->Cell($w[6], 6, $this->OrderHeader[6], 1, 0, 'R', true);
                $this->Ln();
                $this->SetFillColor(240, 240, 240);
                $this->SetTextColor(0);
                $fill = false;
                foreach ($this->OrderData as $row) {
                    $this->SetFont('Arial', '', 9);
                    $this->Cell($w[0], 6, $row[0], 'LR', 0, 'R', $fill);
                    $this->Cell($w[1], 6, $row[1], 'LR', 0, 'L', $fill);
                    $this->Cell($w[2], 6, $row[2], 'LR', 0, 'R', $fill);
                    $this->Cell($w[3], 6, $row[3], 'LR', 0, 'R', $fill);
                    $this->Cell($w[4], 6, $row[4], 'LR', 0, 'R', $fill);
                    $this->Cell($w[5], 6, $row[5], 'LR', 0, 'R', $fill);
                    $this->SetFont('Arial', 'B', 9);
                    $this->Cell($w[6], 6, $row[6], 'LR', 0, 'R', $fill);
                    $this->Ln();
                    $this->SetFont('Arial', 'I', 7);
                    $this->Cell($w[0], 4, '', 'LR', 0, 'R', $fill);
                    $this->Cell($w[1], 4, $row[7], 'LR', 0, 'L', $fill);
                    $this->Cell($w[2], 4, '', 'LR', 0, 'R', $fill);
                    $this->Cell($w[3], 4, '', 'LR', 0, 'R', $fill);
                    $this->Cell($w[4], 4, '', 'LR', 0, 'R', $fill);
                    $this->Cell($w[5], 4, '', 'LR', 0, 'R', $fill);
                    $this->Cell($w[6], 4, '', 'LR', 0, 'R', $fill);
                    $this->Ln();
                    $fill = !$fill;
                }
                $this->Cell(170, 0, '', 'T');
                $this->Ln(1);
                $this->SetFont('Arial', 'B', 9);
                $this->SetDrawColor(100, 100, 100);
                $this->SetLineWidth(.1);
                $this->Cell(144, 5, $this->nameDeliveryCosts, 'TL', 0, 'R');
                $this->Cell(26, 5, $this->DeliveryCosts . ' ' . $this->OrderCurrency, 'TR', 1, 'R');
                $this->SetFont('Arial', '', 9);
                $this->Cell(144, 5, $this->nameSubTotalTax, 'L', 0, 'R');
                $this->Cell(26, 5, $this->OrderTotalPrice . ' ' . $this->OrderCurrency, 'R', 1, 'R');
                $this->Cell(144, 5, $this->nameSubTotalWithoutTax, 'L', 0, 'R');
                $this->Cell(26, 5, $this->OrderPriceWithoutTax . ' ' . $this->OrderCurrency, 'R', 1, 'R');
                $this->Cell(144, 5, $this->nameTax, 'L', 0, 'R');
                $this->Cell(26, 5, $this->OrderDifferenzTax . ' ' . $this->OrderCurrency, 'R', 1, 'R');
                $this->Cell(170, 0, '', 'T', true);
                $this->Ln(1);
                $this->Cell(170, 0, '', 'T', true);
                $this->SetFont('Arial', 'B', 9);
                $this->SetFillColor(240, 240, 240);
                $this->SetLineWidth(.1);
                $this->Cell(144, 6, $this->nameTotalPrice, 'TL', 0, 'R', true);
                $this->Cell(26, 6, $this->OrderTotalPrice . ' ' . $this->OrderCurrency, 'TR', 1, 'R', true);
                $this->Cell(170, 0, '', 'T', true);
            }

            public function payInfo()
            {
                $this->SetFont('Arial', '', 11);
                $this->MultiCell(170, 5, $this->invoiceTextBottom);
                $this->Ln(5);
                $this->Cell(170, 5, $this->payInfoGreetings, 0, 1);
                $this->SetFont('Times', 'I', 14);
                $this->SetTextColor(100, 100, 100);
                $this->Cell(170, 10, $this->shopName, 0, 1);
            }

            public function Footer()
            {
                $this->SetY(-30);
                $this->SetTextColor(75, 75, 75);
                $this->SetFont('Courier', 'I', 8);
                $this->Cell(170, 6, $this->nameSite . ' ' . $this->PageNo() . ' / {nb}', 0, 1, 'R');
                $this->SetFont('Courier', '', 8);
                $this->SetDrawColor(150, 150, 150);
                $this->SetLineWidth(.1);
                $this->Cell(170, 1, '', 'T', 1);
                $this->Cell(55, 4, $this->shopName, 0, 0, 'L');
                $this->Cell(10, 4, 'TEL:', 0, 0, 'L');
                $this->Cell(50, 4, $this->shopTel, 0, 0, 'L');
                $this->Cell(10, 4, 'BANK:', 0, 0, 'L');
                $this->Cell(45, 4, $this->bankName, 0, 1, 'L');
                $this->Cell(55, 4, $this->shopStreet, 0, 0, 'L');
                $this->Cell(10, 4, 'FAX:', 0, 0, 'L');
                $this->Cell(50, 4, $this->shopFax, 0, 0, 'L');
                $this->Cell(10, 4, 'IBAN:', 0, 0, 'L');
                $this->Cell(45, 4, $this->bankIBAN, 0, 1, 'L');
                $this->Cell(55, 4, $this->shopPLZ . ' ' . $this->shopCity, 0, 0, 'L');
                $this->Cell(10, 4, 'MAIL:', 0, 0, 'L');
                $this->Cell(50, 4, $this->shopMail, 0, 0, 'L');
                $this->Cell(10, 4, 'BIC:', 0, 0, 'L');
                $this->Cell(45, 4, $this->bankBIC, 0, 1, 'L');
                $this->Cell(55, 4, 'Ust-IdNr.: ' . $this->shopStNr, 0, 0, 'L');
                $this->Cell(10, 4, 'WEB:', 0, 0, 'L');
                $this->Cell(50, 4, $this->shopWeb, 0, 0, 'L');
                $this->Cell(10, 4, 'INH:', 0, 0, 'L');
                $this->Cell(45, 4, $this->bankOwner, 0, 1, 'L');
                $this->Cell(170, 1, '', 'B', 1);
            }
        }

        // render
        $pdf = new PDF('P', 'mm', 'A4');
        // data
        if ($settingsMapper->getSettings()->getShopLogo() && file_exists(ROOT_PATH . '/' . $settingsMapper->getSettings()->getShopLogo())) {
            $pdf->shopLogo = ROOT_PATH . '/' . $settingsMapper->getSettings()->getShopLogo();
        } else {
            $pdf->shopLogo = ROOT_PATH . '/application/modules/shop/static/img/empty.jpg';
        }
        $pdf->nameDateInvoice = mb_convert_encoding($this->getTrans('dateOfInvoice'), 'ISO-8859-1', 'UTF-8');
        $pdf->DateInvoice = $dateInvoice = date('d.m.Y', time());
        $pdf->nameDeliveryDate = mb_convert_encoding($this->getTrans('expectedDelivery'), 'ISO-8859-1', 'UTF-8');
        if ($order->getWillCollect()) {
            $sumDeliveryTime = time();
            $pdf->DeliveryDate = date('d.m.Y', $sumDeliveryTime);
            $pdf->willCollectNote = mb_convert_encoding($this->getTrans('willCollectShort'), 'ISO-8859-1', 'UTF-8');
        } else {
            $maxDeliveryTime = max($arrayShippingTime);
            $sumDeliveryTime = time() + ($maxDeliveryTime * 24 * 60 * 60);
            $pdf->DeliveryDate = mb_convert_encoding($this->getTrans('approx'), 'ISO-8859-1', 'UTF-8') . ' ' . date('d.m.Y', $sumDeliveryTime);
        }
        $pdf->ReceiverPrename = mb_convert_encoding($this->escape($order->getInvoiceAddress()->getPrename()), 'ISO-8859-1', 'UTF-8');
        $pdf->ReceiverLastname = mb_convert_encoding($this->escape($order->getInvoiceAddress()->getLastname()), 'ISO-8859-1', 'UTF-8');
        $pdf->ReceiverStreet = mb_convert_encoding($this->escape($order->getInvoiceAddress()->getStreet()), 'ISO-8859-1', 'UTF-8');
        $pdf->ReceiverPostcode = $this->escape($order->getInvoiceAddress()->getPostcode());
        $pdf->ReceiverCity = mb_convert_encoding($this->escape($order->getInvoiceAddress()->getCity()), 'ISO-8859-1', 'UTF-8');
        $pdf->ReceiverCountry = mb_convert_encoding($this->escape($order->getInvoiceAddress()->getCountry()), 'ISO-8859-1', 'UTF-8');
        $pdf->nameByEmail = mb_convert_encoding($this->getTrans('byEmail'), 'ISO-8859-1', 'UTF-8');
        $pdf->ReceiverEmail = $this->escape($order->getEmail());
        $pdf->nameDeliveryPlace = mb_convert_encoding($this->getTrans('placeOfDelivery'), 'ISO-8859-1', 'UTF-8');
        $pdf->DeliveryPrename = mb_convert_encoding($this->escape($order->getDeliveryAddress()->getPrename()), 'ISO-8859-1', 'UTF-8');
        $pdf->DeliveryLastname = mb_convert_encoding($this->escape($order->getDeliveryAddress()->getLastname()), 'ISO-8859-1', 'UTF-8');
        $pdf->DeliveryStreet = mb_convert_encoding($this->escape($order->getDeliveryAddress()->getStreet()), 'ISO-8859-1', 'UTF-8');
        $pdf->DeliveryPostcode = $this->escape($order->getDeliveryAddress()->getPostcode());
        $pdf->DeliveryCity = mb_convert_encoding($this->escape($order->getDeliveryAddress()->getCity()), 'ISO-8859-1', 'UTF-8');
        $pdf->DeliveryCountry = mb_convert_encoding($this->escape($order->getDeliveryAddress()->getCountry()), 'ISO-8859-1', 'UTF-8');
        $pdf->nameInvoice = strtoupper($nameInvoice);
        $pdf->nameOrder = $nameOrder = mb_convert_encoding($this->getTrans('order'), 'ISO-8859-1', 'UTF-8');
        $pdf->nameFrom = $nameFrom = mb_convert_encoding($this->getTrans('from'), 'ISO-8859-1', 'UTF-8');
        $pdf->orderDate = $orderDateInvoice;
        $pdf->nameNumber = $nameNumber = mb_convert_encoding($this->getTrans('numberShort'), 'ISO-8859-1', 'UTF-8');
        $pdf->invoiceNr = $invoiceNr;
        $pdf->invoiceTextTop = mb_convert_encoding($settingsMapper->getSettings()->getInvoiceTextTop(), 'ISO-8859-1', 'UTF-8');
        $pdf->OrderHeader = ['#',
              mb_convert_encoding($this->getTrans('productName'), 'ISO-8859-1', 'UTF-8'),
              mb_convert_encoding($this->getTrans('singlePrice'), 'ISO-8859-1', 'UTF-8'),
              mb_convert_encoding($this->getTrans('taxShort'), 'ISO-8859-1', 'UTF-8'),
              mb_convert_encoding($this->getTrans('singlePrice'), 'ISO-8859-1', 'UTF-8'),
              mb_convert_encoding($this->getTrans('entries'), 'ISO-8859-1', 'UTF-8'),
              mb_convert_encoding($this->getTrans('total'), 'ISO-8859-1', 'UTF-8')
        ];
        $pdf->OrderData = $pdfOrderData;
        $pdf->OrderCurrency = iconv('UTF-8', 'windows-1252', $this->escape($this->get('currency')));
        $pdf->nameDeliveryCosts = mb_convert_encoding($this->getTrans('deliveryCosts'), 'ISO-8859-1', 'UTF-8') . ':';
        $pdf->DeliveryCosts = number_format($shipping_costs, 2, '.', '');
        $pdf->nameSubTotalTax = mb_convert_encoding($this->getTrans('subtotal') . ' ' . $this->getTrans('withTax'), 'ISO-8859-1', 'UTF-8') . ':';
        $pdf->OrderTotalPrice = number_format($total_price, 2, '.', '');
        $pdf->nameSubTotalWithoutTax = mb_convert_encoding($this->getTrans('subtotal') . ' ' . $this->getTrans('withoutTax'), 'ISO-8859-1', 'UTF-8') . ':';
        $pdf->OrderPriceWithoutTax = number_format($sumPricewithoutTax, 2, '.', '');
        $pdf->nameTax = mb_convert_encoding($this->getTrans('tax'), 'ISO-8859-1', 'UTF-8') . ':';
        $pdf->OrderDifferenzTax = number_format($differenzTax, 2, '.', '');
        $pdf->nameTotalPrice = mb_convert_encoding($this->getTrans('totalPrice'), 'ISO-8859-1', 'UTF-8') . ':';
        $pdf->invoiceTextBottom = mb_convert_encoding($settingsMapper->getSettings()->getInvoiceTextBottom(), 'ISO-8859-1', 'UTF-8');
        $pdf->payInfoGreetings = mb_convert_encoding($this->getTrans('greetings'), 'ISO-8859-1', 'UTF-8');
        $pdf->nameSite = mb_convert_encoding($this->getTrans('site'), 'ISO-8859-1', 'UTF-8');
        $pdf->shopName = $shopName = mb_convert_encoding($settingsMapper->getSettings()->getShopName(), 'ISO-8859-1', 'UTF-8');
        $pdf->shopStreet = mb_convert_encoding($settingsMapper->getSettings()->getShopStreet(), 'ISO-8859-1', 'UTF-8');
        $pdf->shopPLZ = mb_convert_encoding($settingsMapper->getSettings()->getShopPLZ(), 'ISO-8859-1', 'UTF-8');
        $pdf->shopCity = mb_convert_encoding($settingsMapper->getSettings()->getShopCity(), 'ISO-8859-1', 'UTF-8');
        $pdf->shopStNr = mb_convert_encoding($settingsMapper->getSettings()->getShopStNr(), 'ISO-8859-1', 'UTF-8');
        $pdf->shopTel = mb_convert_encoding($settingsMapper->getSettings()->getShopTel(), 'ISO-8859-1', 'UTF-8');
        $pdf->shopFax = mb_convert_encoding($settingsMapper->getSettings()->getShopFax(), 'ISO-8859-1', 'UTF-8');
        $pdf->shopMail = mb_convert_encoding($settingsMapper->getSettings()->getShopMail(), 'ISO-8859-1', 'UTF-8');
        $pdf->shopWeb = $shopWeb = mb_convert_encoding($settingsMapper->getSettings()->getShopWeb(), 'ISO-8859-1', 'UTF-8');
        $pdf->bankName = mb_convert_encoding($settingsMapper->getSettings()->getBankName(), 'ISO-8859-1', 'UTF-8');
        $pdf->bankIBAN = mb_convert_encoding($settingsMapper->getSettings()->getBankIBAN(), 'ISO-8859-1', 'UTF-8');
        $pdf->bankBIC = mb_convert_encoding($settingsMapper->getSettings()->getBankBIC(), 'ISO-8859-1', 'UTF-8');
        $pdf->bankOwner = mb_convert_encoding($settingsMapper->getSettings()->getBankOwner(), 'ISO-8859-1', 'UTF-8');
        //
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->Ln(40);
        $pdf->InvoiceHead();
        $pdf->Ln(5);
        $pdf->TitleLine();
        $pdf->Ln(5);
        $pdf->OrderTable();
        $pdf->Ln(5);
        $pdf->payInfo();
        // document
        $pdf->SetTitle($nameInvoice . ' ' . $nameNumber . ' ' . $invoiceNr);
        $pdf->SetSubject($nameOrder . ' ' . $nameFrom . ' ' . $orderDateInvoice);
        $pdf->SetAuthor($shopName . ' (' . $shopWeb . ')');
        $pdf->SetCreator('Shop-Modul by ilch.de');
        $pdf->SetKeywords($nameInvoice . ', ' . $nameOrder);
        $pdf->Output($file_location, 'F');

        $order->setInvoiceFilename($invoiceFilename);
        $ordersMapper->save($order);
        header('Location: ' . $order->getId());
        exit();
    } elseif (isset($_POST['PDF']) && $_POST['PDF'] == 'delete') {
        $order->setInvoiceFilename('');
        $ordersMapper->save($order);
        unlink($file_location);
        header('Location: ' . $order->getId());
        exit();
    } elseif (isset($_POST['PDF']) && $_POST['PDF'] == 'showDeliveryNote') {
        ob_end_clean();
        require(ROOT_PATH . '/application/modules/shop/static/class/fpdf/fpdf.php');

        class PDF extends FPDF
        {
            public $bankBIC;
            public $bankIBAN;
            public $bankName;
            public $bankOwner;
            public $DateInvoice;
            public $DeliveryCity;
            public $DeliveryCosts;
            public $DeliveryCountry;
            public $DeliveryDate;
            public $deliveryInfoGreetings;
            public $DeliveryLastname;
            public $DeliveryPostcode;
            public $DeliveryPrename;
            public $DeliveryStreet;
            public $deliveryTextTop;
            public $invoiceNr;
            public $invoiceTextBottom;
            public $invoiceTextTop;
            public $nameByEmail;
            public $nameDateInvoice;
            public $nameDeliveryCosts;
            public $nameDeliveryDate;
            public $nameDeliveryNote;
            public $nameDeliveryPlace;
            public $nameFrom;
            public $nameInvoice;
            public $nameNumber;
            public $nameOrder;
            public $nameShippingDate;
            public $nameSite;
            public $nameSubTotalTax;
            public $nameSubTotalWithoutTax;
            public $nameTax;
            public $nameTotalPrice;
            public $OrderCurrency;
            public $OrderData;
            public $orderDate;
            public $OrderDifferenzTax;
            public $OrderHeader;
            public $OrderPriceWithoutTax;
            public $OrderTotalPrice;
            public $payInfoGreetings;
            public $ReceiverCity;
            public $ReceiverCountry;
            public $ReceiverEmail;
            public $ReceiverLastname;
            public $ReceiverPostcode;
            public $ReceiverPrename;
            public $ReceiverStreet;
            public $shippingDate;
            public $shopCity;
            public $shopFax;
            public $shopLogo;
            public $shopMail;
            public $shopName;
            public $shopPLZ;
            public $shopStNr;
            public $shopStreet;
            public $shopTel;
            public $shopWeb;
            public $willCollectNote;

            public function Header()
            {
                $this->SetMargins(20, 20, 20);
                $this->Image($this->shopLogo, 140, 15, 50);
            }

            public function DeliveryHead()
            {
                $this->SetFont('Arial', 'U', 8);
                $this->SetTextColor(100, 100, 100);
                $this->Cell(130, 5, $this->shopName . ' | ' . $this->shopStreet . ' | ' . $this->shopPLZ . ' ' . $this->shopCity, 0, 0, 'L');
                $this->SetTextColor(0, 0, 0);
                $this->SetFont('Arial', 'B', 11);
                $this->Cell(40, 5, $this->nameShippingDate, 0, 1, 'R');
                $this->SetFont('Arial', '', 11);
                $this->Cell(130, 5, $this->DeliveryPrename . ' ' . $this->DeliveryLastname, 0, 0, 'L');
                $this->SetFont('Arial', '', 10);
                $this->Cell(40, 5, $this->shippingDate, 0, 1, 'R');
                $this->SetFont('Arial', '', 11);
                $this->Cell(170, 5, $this->DeliveryStreet, 0, 1, 'L');
                $this->Cell(170, 5, $this->DeliveryPostcode . ' ' . $this->DeliveryCity, 0, 1, 'L');
                $this->Cell(170, 5, $this->DeliveryCountry, 0, 1, 'L');
            }

            public function TitleLine()
            {
                $this->SetFont('Arial', 'B', 14);
                $this->SetTextColor(0, 0, 0);
                $this->Cell(0, 8, $this->nameDeliveryNote, 0, 1, 'L');
                $this->SetFont('Arial', 'I', 10);
                $this->Cell(0, 5, $this->nameOrder . ' ' . $this->nameNumber . ' ' . $this->invoiceNr . ' ' . $this->nameFrom . ' ' . $this->orderDate, 0, 1, 'L');
                $this->SetFont('Arial', '', 11);
                $this->Ln(15);
                $this->MultiCell(170, 5, $this->deliveryTextTop);
            }

            public function DeliveryTable()
            {
                $this->SetFillColor(220, 220, 220);
                $this->SetTextColor(0);
                $this->SetDrawColor(100, 100, 100);
                $this->SetLineWidth(.1);
                $this->SetFont('Arial', 'B', 9);
                $w = [10, 100, 40, 20];
                $this->Cell($w[0], 6, $this->OrderHeader[0], 1, 0, 'R', true);
                $this->Cell($w[1], 6, $this->OrderHeader[1], 1, 0, 'L', true);
                $this->Cell($w[2], 6, $this->OrderHeader[2], 1, 0, 'L', true);
                $this->Cell($w[3], 6, $this->OrderHeader[3], 1, 0, 'R', true);
                $this->Ln();
                $this->SetFillColor(240, 240, 240);
                $this->SetTextColor(0);
                $fill = false;
                foreach ($this->OrderData as $row) {
                    $this->SetFont('Arial', '', 9);
                    $this->Cell($w[0], 6, $row[0], 'LR', 0, 'R', $fill);
                    $this->Cell($w[1], 6, $row[1], 'LR', 0, 'L', $fill);
                    $this->Cell($w[2], 6, $row[7], 'LR', 0, 'L', $fill);
                    $this->Cell($w[3], 6, $row[5], 'LR', 0, 'R', $fill);
                    $this->Ln();
                    $fill = !$fill;
                }
                $this->Cell(170, 0, '', 'T');
                $this->Ln(1);
            }

            public function DeliveryInfo()
            {
                $this->SetFont('Arial', '', 11);
                $this->Cell(170, 5, $this->deliveryInfoGreetings, 0, 1);
                $this->SetFont('Times', 'I', 14);
                $this->SetTextColor(100, 100, 100);
                $this->Cell(170, 10, $this->shopName, 0, 1);
            }

            public function Footer()
            {
                $this->SetY(-30);
                $this->SetTextColor(75, 75, 75);
                $this->SetFont('Courier', 'I', 8);
                $this->Cell(170, 6, $this->nameSite . ' ' . $this->PageNo() . ' / {nb}', 0, 1, 'R');
                $this->SetFont('Courier', '', 8);
                $this->SetDrawColor(150, 150, 150);
                $this->SetLineWidth(.1);
                $this->Cell(170, 1, '', 'T', 1);
                $this->Cell(55, 4, $this->shopName, 0, 0, 'L');
                $this->Cell(10, 4, 'TEL:', 0, 0, 'L');
                $this->Cell(50, 4, $this->shopTel, 0, 0, 'L');
                $this->Cell(10, 4, 'BANK:', 0, 0, 'L');
                $this->Cell(45, 4, $this->bankName, 0, 1, 'L');
                $this->Cell(55, 4, $this->shopStreet, 0, 0, 'L');
                $this->Cell(10, 4, 'FAX:', 0, 0, 'L');
                $this->Cell(50, 4, $this->shopFax, 0, 0, 'L');
                $this->Cell(10, 4, 'IBAN:', 0, 0, 'L');
                $this->Cell(45, 4, $this->bankIBAN, 0, 1, 'L');
                $this->Cell(55, 4, $this->shopPLZ . ' ' . $this->shopCity, 0, 0, 'L');
                $this->Cell(10, 4, 'MAIL:', 0, 0, 'L');
                $this->Cell(50, 4, $this->shopMail, 0, 0, 'L');
                $this->Cell(10, 4, 'BIC:', 0, 0, 'L');
                $this->Cell(45, 4, $this->bankBIC, 0, 1, 'L');
                $this->Cell(55, 4, 'Ust-IdNr.: ' . $this->shopStNr, 0, 0, 'L');
                $this->Cell(10, 4, 'WEB:', 0, 0, 'L');
                $this->Cell(50, 4, $this->shopWeb, 0, 0, 'L');
                $this->Cell(10, 4, 'INH:', 0, 0, 'L');
                $this->Cell(45, 4, $this->bankOwner, 0, 1, 'L');
                $this->Cell(170, 1, '', 'B', 1);
            }
        }

        // render
        $pdf = new PDF('P', 'mm', 'A4');
        // data
        if ($settingsMapper->getSettings()->getShopLogo() && file_exists(ROOT_PATH . '/' . $settingsMapper->getSettings()->getShopLogo())) {
            $pdf->shopLogo = ROOT_PATH . '/' . $settingsMapper->getSettings()->getShopLogo();
        } else {
            $pdf->shopLogo = ROOT_PATH . '/application/modules/shop/static/img/empty.jpg';
        }
        $pdf->nameShippingDate = mb_convert_encoding($this->getTrans('shippingDate'), 'ISO-8859-1', 'UTF-8');
        $pdf->shippingDate = date('d.m.Y', time());
        $pdf->nameDeliveryDate = mb_convert_encoding($this->getTrans('expectedDelivery'), 'ISO-8859-1', 'UTF-8');
        if ($order->getWillCollect()) {
            $sumDeliveryTime = time();
            $pdf->DeliveryDate = date('d.m.Y', $sumDeliveryTime);
        } else {
            $maxDeliveryTime = max($arrayShippingTime);
            $sumDeliveryTime = time() + ($maxDeliveryTime * 24 * 60 * 60);
            $pdf->DeliveryDate = mb_convert_encoding($this->getTrans('approx'), 'ISO-8859-1', 'UTF-8') . ' ' . date('d.m.Y', $sumDeliveryTime);
        }
        $nameDeliveryNote = mb_convert_encoding($this->getTrans('deliveryNote'), 'ISO-8859-1', 'UTF-8');
        $pdf->nameDeliveryNote = strtoupper($nameDeliveryNote);
        $pdf->DeliveryPrename = mb_convert_encoding($this->escape($order->getDeliveryAddress()->getPrename()), 'ISO-8859-1', 'UTF-8');
        $pdf->DeliveryLastname = mb_convert_encoding($this->escape($order->getDeliveryAddress()->getLastname()), 'ISO-8859-1', 'UTF-8');
        $pdf->DeliveryStreet = mb_convert_encoding($this->escape($order->getDeliveryAddress()->getStreet()), 'ISO-8859-1', 'UTF-8');
        $pdf->DeliveryPostcode = $this->escape($order->getDeliveryAddress()->getPostcode());
        $pdf->DeliveryCity = mb_convert_encoding($this->escape($order->getDeliveryAddress()->getCity()), 'ISO-8859-1', 'UTF-8');
        $pdf->DeliveryCountry = mb_convert_encoding($this->escape($order->getDeliveryAddress()->getCountry()), 'ISO-8859-1', 'UTF-8');
        $pdf->nameOrder = $nameOrder = mb_convert_encoding($this->getTrans('order'), 'ISO-8859-1', 'UTF-8');
        $pdf->nameFrom = $nameFrom = mb_convert_encoding($this->getTrans('from'), 'ISO-8859-1', 'UTF-8');
        $pdf->orderDate = $orderDateInvoice;
        $pdf->nameNumber = $nameNumber = mb_convert_encoding($this->getTrans('numberShort'), 'ISO-8859-1', 'UTF-8');
        $pdf->invoiceNr = $invoiceNr;
        $pdf->deliveryTextTop = mb_convert_encoding($settingsMapper->getSettings()->getDeliveryTextTop(), 'ISO-8859-1', 'UTF-8');
        $pdf->OrderHeader = ['#',
              mb_convert_encoding($this->getTrans('productName'), 'ISO-8859-1', 'UTF-8'),
              mb_convert_encoding($this->getTrans('itemNumber'), 'ISO-8859-1', 'UTF-8'),
              mb_convert_encoding($this->getTrans('amount'), 'ISO-8859-1', 'UTF-8')
        ];
        $pdf->OrderData = $pdfOrderData;
        $pdf->deliveryInfoGreetings = mb_convert_encoding($this->getTrans('greetings'), 'ISO-8859-1', 'UTF-8');
        $pdf->nameSite = mb_convert_encoding($this->getTrans('site'), 'ISO-8859-1', 'UTF-8');
        $pdf->shopName = $shopName = mb_convert_encoding($settingsMapper->getSettings()->getShopName(), 'ISO-8859-1', 'UTF-8');
        $pdf->shopStreet = mb_convert_encoding($settingsMapper->getSettings()->getShopStreet(), 'ISO-8859-1', 'UTF-8');
        $pdf->shopPLZ = mb_convert_encoding($settingsMapper->getSettings()->getShopPLZ(), 'ISO-8859-1', 'UTF-8');
        $pdf->shopCity = mb_convert_encoding($settingsMapper->getSettings()->getShopCity(), 'ISO-8859-1', 'UTF-8');
        $pdf->shopStNr = mb_convert_encoding($settingsMapper->getSettings()->getShopStNr(), 'ISO-8859-1', 'UTF-8');
        $pdf->shopTel = mb_convert_encoding($settingsMapper->getSettings()->getShopTel(), 'ISO-8859-1', 'UTF-8');
        $pdf->shopFax = mb_convert_encoding($settingsMapper->getSettings()->getShopFax(), 'ISO-8859-1', 'UTF-8');
        $pdf->shopMail = mb_convert_encoding($settingsMapper->getSettings()->getShopMail(), 'ISO-8859-1', 'UTF-8');
        $pdf->shopWeb = $shopWeb = mb_convert_encoding($settingsMapper->getSettings()->getShopWeb(), 'ISO-8859-1', 'UTF-8');
        $pdf->bankName = mb_convert_encoding($settingsMapper->getSettings()->getBankName(), 'ISO-8859-1', 'UTF-8');
        $pdf->bankIBAN = mb_convert_encoding($settingsMapper->getSettings()->getBankIBAN(), 'ISO-8859-1', 'UTF-8');
        $pdf->bankBIC = mb_convert_encoding($settingsMapper->getSettings()->getBankBIC(), 'ISO-8859-1', 'UTF-8');
        $pdf->bankOwner = mb_convert_encoding($settingsMapper->getSettings()->getBankOwner(), 'ISO-8859-1', 'UTF-8');
        //
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->Ln(40);
        $pdf->DeliveryHead();
        $pdf->Ln(30);
        $pdf->TitleLine();
        $pdf->Ln(5);
        $pdf->DeliveryTable();
        $pdf->Ln(20);
        $pdf->DeliveryInfo();
        // document
        $pdf->SetTitle($nameDeliveryNote . ' ' . $nameNumber . ' ' . $invoiceNr);
        $pdf->SetSubject($nameOrder . ' ' . $nameFrom . ' ' . $orderDateInvoice);
        $pdf->SetAuthor($shopName . ' (' . $shopWeb . ')');
        $pdf->SetCreator('Shop-Modul by ilch.de');
        $pdf->SetKeywords($nameDeliveryNote);
        $pdf->Output($nameDeliveryNote, 'I');
        exit();
    } ?>

<?php else : ?>
    <h1><?=$this->getTrans('menuOrder'); ?></h1>
    <div class="alert alert-warning"><?=$this->getTrans('noOrderSelected') ?></div>
    <a class="btn btn-default" href="<?=$this->getUrl(['action' => 'index']) ?>">
        <i class="fa-solid fa-backward"></i> <?=$this->getTrans('back') ?>
    </a>
<?php endif; ?>

<script>
    let currentStatus = <?=($this->get('order') != '') ? $this->get('order')->getStatus() : null ?>;

    $("button[name='status'][value=2]").click(function() {
        $("input[name='confirmTransferBackToStock']").val(confirm('<?=$this->getTrans('confirmTransferBackToStock') ?>'));
    });

    $("button[name='status'][value!=2]").click(function() {
        if (currentStatus === 2) {
            $("input[name='confirmRemoveFromStock']").val(confirm('<?=$this->getTrans('confirmRemoveFromStock') ?>'));
        }
    });
</script>
