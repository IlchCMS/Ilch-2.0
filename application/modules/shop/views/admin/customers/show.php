<link href="<?=$this->getModuleUrl('static/css/shop_admin.css') ?>" rel="stylesheet">

<h1><?=$this->getTrans('menuCustomer') ?></h1>

<h2><?=$this->getTrans('infoCustomer') ?></h2>
<div class="table-responsive">
    <table class="table">
        <colgroup>
            <col class="col-lg-1">
            <col>
        </colgroup>
        <tbody>
        <tr>
            <th><?=$this->getTrans('customerId') ?></th>
            <td><?=$this->escape($this->get('customer')->getId()) ?></td>
        </tr>
        <tr>
            <th><?=$this->getTrans('username') ?></th>
            <td><?=$this->escape($this->get('customerUsername')) ?></td>
        </tr>
        <tr>
            <th><?=$this->getTrans('emailAddress') ?></th>
            <td><a href="mailto:<?=$this->escape($this->get('customer')->getEmail()) ?>"><?=$this->escape($this->get('customer')->getEmail()) ?></a></td>
        </tr>
        </tbody>
    </table>
</div>

<h2><?=$this->getTrans('customerAddresses') ?></h2>
<div class="table-responsive">
    <table class="table table-hover table-striped">
        <colgroup>
            <col>
            <col>
            <col>
            <col>
            <col>
            <col>
        </colgroup>
        <thead>
        <tr>
            <th><?=$this->getTrans('prename') ?></th>
            <th><?=$this->getTrans('lastname') ?></th>
            <th><?=$this->getTrans('street') ?></th>
            <th><?=$this->getTrans('postcode') ?></th>
            <th><?=$this->getTrans('city') ?></th>
            <th><?=$this->getTrans('country') ?></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($this->get('addresses') as $address) : ?>
            <tr>
                <td><?=$this->escape($address->getPrename()) ?></td>
                <td><?=$this->escape($address->getLastname()) ?></td>
                <td><?=$this->escape($address->getStreet()) ?></td>
                <td><?=$this->escape($address->getPostcode()) ?></td>
                <td><?=$this->escape($address->getCity()) ?></td>
                <td><?=$this->escape($address->getCountry()) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<h2><?=$this->getTrans('customerOrders') ?></h2>
<div class="table-responsive">
    <table class="table table-hover table-striped">
        <colgroup>
            <col class="icon_width">
            <col class="icon_width">
            <col class="icon_width">
            <col>
            <col>
            <col>
            <col>
            <col>
            <col>
        </colgroup>
        <thead>
        <tr>
            <th></th>
            <th></th>
            <th></th>
            <th class="sort"><?=$this->getTrans('status') ?></th>
            <th class="sort"><?=$this->getTrans('orderDate') ?></th>
            <th class="sort"><?=$this->getTrans('invoice') . ' ' . $this->getTrans('numberShort') ?></th>
            <th class="sort"><?=$this->getTrans('name') ?></th>
            <th class="sort"><?=$this->getTrans('deliveryAddress') ?></th>
            <th class="sort"><?=$this->getTrans('invoiceAddress') ?></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($this->get('orders') as $order) : ?>
            <tr class="filter">
                <td><?=$this->getEditIcon(['controller' => 'orders', 'action' => 'treat', 'id' => $order->getId()]) ?></td>
                <td><?=$this->getDeleteIcon(['controller' => 'orders', 'action' => 'delorder', 'id' => $order->getId()]) ?></td>
                <td></td>
                <td>
                    <?php if ($order->getStatus() == 0) { ?>
                        <a href="<?=$this->getUrl(['controller' => 'orders', 'action' => 'treat', 'id' => $order->getId()]) ?>" class="btn btn-sm alert-danger">
                            <i class="fa-solid fa-plus-square" aria-hidden="true"></i>&nbsp;<b><?=$this->getTrans('newBIG') ?></b>
                        </a>
                    <?php } elseif ($order->getStatus() == 1) { ?>
                        <a href="<?=$this->getUrl(['controller' => 'orders', 'action' => 'treat', 'id' => $order->getId()]) ?>" class="btn btn-sm btn-warning">
                            <i class="fa-solid fa-pencil-square" aria-hidden="true"></i>&nbsp;<b><?= $this->getTrans('processingBIG') ?></b>
                        </a>
                    <?php } elseif ($order->getStatus() == 2) { ?>
                        <a href="<?=$this->getUrl(['controller' => 'orders', 'action' => 'treat', 'id' => $order->getId()]) ?>" class="btn btn-sm alert-info">
                            <i class="fa-solid fa-exclamation-triangle" aria-hidden="true"></i>&nbsp;<b><?= $this->getTrans('canceledBIG') ?></b>
                        </a>
                    <?php } else { ?>
                        <a href="<?=$this->getUrl(['controller' => 'orders', 'action' => 'treat', 'id' => $order->getId()]) ?>" class="btn btn-sm btn-success">
                            <i class="fa-solid fa-check-square" aria-hidden="true"></i>&nbsp;<b><?= $this->getTrans('completedBIG') ?></b>
                        </a>
                    <?php } ?>
                </td>
                <?php
                $myDateTime = DateTime::createFromFormat('Y-m-d H:i:s', $this->escape($order->getDatetime()));
                $orderTime = date_format($myDateTime, 'd.m.Y | H:i \U\h\r');
                ?>
                <td>
                    <?=$orderTime ?>
                </td>
                <?php
                $orderDate = date_format($myDateTime, 'd.m.Y');
                $invoiceNr = date_format($myDateTime, 'ymd') . '-' . $order->getId();
                ?>
                <td>
                    <?=$invoiceNr ?>
                </td>
                <td>
                    <?=$this->escape($order->getInvoiceAddress()->getPrename()) ?> <?=$this->escape($order->getInvoiceAddress()->getLastname()) ?>
                </td>
                <td>
                    <?=$this->escape($order->getInvoiceAddress()->getStreet()) ?>,
                    <?=$this->escape($order->getInvoiceAddress()->getPostcode()) ?> <?=$this->escape($order->getInvoiceAddress()->getCity()) ?>,
                    <?=$this->escape($order->getInvoiceAddress()->getCountry()) ?>
                </td>
                <td>
                    <?=$this->escape($order->getDeliveryAddress()->getStreet()) ?>,
                    <?=$this->escape($order->getDeliveryAddress()->getPostcode()) ?> <?=$this->escape($order->getDeliveryAddress()->getCity()) ?>,
                    <?=$this->escape($order->getDeliveryAddress()->getCountry()) ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
