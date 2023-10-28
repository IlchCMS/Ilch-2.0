<link href="<?=$this->getModuleUrl('static/css/shop_admin.css') ?>" rel="stylesheet">

<h1><?=$this->getTrans('menuShopOverview') ?></h1>

<?php
$countAllOrders = count($this->get('orders')->getOrders());
$countNewOrders = count($this->get('orders')->getOrders(['status' => '0']));
$countWorkOrders = count($this->get('orders')->getOrders(['status' => '1']));
$countCancelOrders = count($this->get('orders')->getOrders(['status' => '2']));
$countDoneOrders = count($this->get('orders')->getOrders(['status' => '3']));
?>

<?php if (!(strncmp($this->getURL(), 'https', 5) === 0)) : ?>
    <div class="alert alert-danger">
        <b><?=$this->getTrans('warningUnencryptedConnection') ?></b>
    </div>
<?php endif; ?>

<?php if ($countNewOrders > 0) : ?>
    <div class="alert alert-danger">
        <i class="fa-solid fa-plus-square" aria-hidden="true"></i>
        <b> &nbsp; <?=$this->getTrans('infoNewOrders') ?></b>
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-md-12 col-lg-6 mb-3">
        <div class="img-thumbnail media">
            <div class="media-body">
                <h4 class="media-heading">
                    <i class="fa-solid fa-cart-arrow-down"></i>&nbsp;&nbsp;<?=$this->getTrans('menuOrders') ?></h4>
                <hr>
                <?php if ($countAllOrders > 0) { ?>
                    <p>
                        &nbsp;<a href="<?=$this->getUrl(['controller' => 'orders', 'action' => 'index']) ?>" class="btn btn-outline-secondary">
                            <b><?=$countAllOrders ?>&ensp;<?=($countAllOrders == 1) ? $this->getTrans('order') : $this->getTrans('orders') ?></b>
                        </a> &nbsp;<?=$this->getTrans('available') ?>
                    </p>
                    <?=($countNewOrders > 0) ? '&nbsp;<div class="alert alert-danger btn-sm d-inblock"><b>' . $countNewOrders . '&ensp;' . $this->getTrans('new') . '</b></div>' : ''; ?>
                    <?=($countWorkOrders > 0) ? '&nbsp;<div class="btn-sm alert  alert-warning d-inblock"><b>' . $countWorkOrders . '&ensp;' . $this->getTrans('processing') . '</b></div>' : ''; ?>
                    <?=($countCancelOrders > 0) ? '&nbsp;<div class="btn-sm alert  alert-info d-inblock"><b>' . $countCancelOrders . '&ensp;' . $this->getTrans('canceled') . '</b></div>' : ''; ?>
                    <?=($countDoneOrders > 0) ? '&nbsp;<div class="btn-sm alert  alert-success d-inblock"><b>' . $countDoneOrders . '&ensp;' . $this->getTrans('completed') . '</b></div>' : ''; ?>
                <?php } else { ?>
                    <p>
                        <?=$this->getTrans('infoNoOrder') ?>
                    </p>
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-lg-6 mb-3">
        <div class="img-thumbnail media">
            <div class="media-body">
                <h4 class="media-heading"><i class="fa-solid fa-tshirt"></i>&nbsp;&nbsp;<?=$this->getTrans('menuItems') ?></h4>
                <hr>
                <?php $countCats = count($this->get('cats')); ?>
                <?php $countItems = count($this->get('itemsMapper')); ?>
                <p>
                    &nbsp;<a href="<?=$this->getUrl(['controller' => 'items', 'action' => 'index']) ?>" class="btn btn-outline-secondary">
                        <b><?=$countItems ?>&ensp;<?=$this->getTrans('products') ?></b>
                    </a> &nbsp; in &nbsp;
                    <a href="<?=$this->getUrl(['controller' => 'cats', 'action' => 'index']) ?>" class="btn btn-outline-secondary">
                        <b><?=$countCats ?>&ensp;<?=$this->getTrans('cats') ?></b>
                    </a> &nbsp;<?=$this->getTrans('available') ?>
                </p>
            </div>
        </div>
    </div>
    <?php if ($this->get('settings')->getIfSampleData() == 1) : ?>
    <div class="col-lg-12" style="text-align:right">
        <form class="form-horizontal" id="delSamplaDataForm" method="POST" action="">
            <?=$this->getTokenField() ?>
            <a class="badge rounded-pill bg-secondary" data-bs-toggle="modal" data-bs-target="#infoModal">
                <i class="fa-solid fa-info"></i>
            </a>
            <input type="button" id="delete_button" class="btn btn-outline-secondary del" value="<?=$this->getTrans('delSampleData') ?>" />
            <input type="hidden" name="delSampleData" value="delete" />
            <input type="submit" name="keepSampleData" id="keep_button" class="btn btn-outline-secondary keep" value="<?=$this->getTrans('keepSampleData') ?>" />
        </form>
    </div>
    <?php endif; ?>
</div>

<?=$this->getDialog('infoModal', $this->getTrans('info'), $this->getTrans('delSampleDataInfo')) ?>
<script>
$(document).ready(function() {
    $(function() {
        $('#delete_button').click(function() {
            if (confirm('<?=$this->getTrans('confirmDeleteSampleData') ?>')) {
                $('form#delSamplaDataForm').submit();
            }
        });
    });
});
</script>
