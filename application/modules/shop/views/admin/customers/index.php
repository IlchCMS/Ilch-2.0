<link href="<?=$this->getModuleUrl('static/css/shop_admin.css') ?>" rel="stylesheet">

<h1><?=$this->getTrans('menuCustomers') ?></h1>
<div class="alert alert-danger"><?=$this->getTrans('warningDeletionOfCustomer') ?></div>

<div class="table-responsive">
    <table class="table table-hover table-striped">
        <colgroup>
            <col class="icon_width">
            <col class="icon_width">
            <col class="icon_width">
            <col>
            <col>
        </colgroup>
        <thead>
        <tr>
            <th><?=$this->getCheckAllCheckbox('check_customers') ?></th>
            <th></th>
            <th></th>
            <th><?=$this->getTrans('customerId') ?></th>
            <th><?=$this->getTrans('emailAddress') ?></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($this->get('customers') as $customer): ?>
            <tr id="<?=$this->escape($customer->getId()) ?>">
                <td><?=$this->getDeleteCheckbox('check_customers', $customer->getId()) ?></td>
                <td><?=$this->getDeleteIcon(['action' => 'delete', 'id' => $customer->getId()]) ?></td>
                <td><a href="<?=$this->getUrl(['action' => 'show', 'id' => $customer->getId()]) ?>" title="<?=$this->getTrans('showCustomerDetails') ?>"><i class="fa-regular fa-folder-open"></i></a></td>
                <td><?=$this->escape($customer->getId()) ?></td>
                <td><?=$this->escape($customer->getEmail()) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
