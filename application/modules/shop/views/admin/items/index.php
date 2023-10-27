<?php $categoryMapper = $this->get('categoryMapper'); ?>
<link href="<?=$this->getModuleUrl('static/css/shop_admin.css') ?>" rel="stylesheet">

<h1><?=$this->getTrans('manage') ?>
    <div class="input-group input-group-sm filter">
        <span class="input-group-text">
            <i class="fa-solid fa-filter"></i>
        </span>
        <input type="text" id="filterInput" class="form-control" placeholder="<?=$this->getTrans('filter') ?>">
        <span class="input-group-text">
            <span id="filterClear" class="fa-solid fa-xmark"></span>
        </span>
    </div>
</h1>

<?php if (!empty($this->get('shopItems'))) : ?>
    <form class="form-horizontal" method="POST" action="">
        <?=$this->getTokenField() ?>
        <div class="table-responsive">
            <table id="sortTable" class="table table-hover table-striped">
                <colgroup>
                    <col class="icon_width">
                    <col class="icon_width">
                    <col class="icon_width">
                    <col class="icon_width">
                    <col>
                    <col>
                    <col>
                    <col>
                    <col>
                </colgroup>
                <thead>
                    <tr>
                        <th><?=$this->getCheckAllCheckbox('check_shops') ?></th>
                        <th></th>
                        <th></th>
                        <th class="text-center"><?=$this->getTrans('status') ?></th>
                        <th class="text-center"><?=$this->getTrans('productImage') ?></th>
                        <th class="sort"><?=$this->getTrans('productName') ?></th>
                        <th class="sort"><?=$this->getTrans('itemNumber') ?></th>
                        <th class="sort"><?=$this->getTrans('cat') ?></th>
                        <th class="text-right"><?=$this->getTrans('stock') ?></th>
                        <th class="text-right"><?=$this->getTrans('price') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($this->get('shopItems') as $shopItem) : ?>
                        <?php
                        $shopCats = $categoryMapper->getCategoryById($shopItem->getCatId());
                        $shopImgPath = '/application/modules/shop/static/img/';
                        if ($shopItem->getImage() && file_exists(ROOT_PATH . '/' . $shopItem->getImage())) {
                            $img = BASE_URL . '/' . $shopItem->getImage();
                        } else {
                            $img = BASE_URL . $shopImgPath . 'noimg.jpg';
                        }
                        ?>
                        <tr class="filter">
                            <td><?=$this->getDeleteCheckbox('check_shops', $shopItem->getId()) ?></td>
                            <td><?=$this->getEditIcon(['action' => 'treat', 'id' => $shopItem->getId()]) ?></td>
                            <td><?=$this->getDeleteIcon(['action' => 'delshop', 'id' => $shopItem->getId()]) ?></td>
                            <td class="text-center">
                            <?php
                            if ($shopItem->getStatus() == 1) {
                                echo '<a href="' . $this->getUrl(['action' => 'treat', 'id' => $shopItem->getId()]) . '" class="btn btn-sm btn-success" title="' . $this->getTrans('active') . '"><i class="fa-solid fa-eye"></i></a>';
                            } else {
                                echo '<a href="' . $this->getUrl(['action' => 'treat', 'id' => $shopItem->getId()]) . '" class="btn btn-sm btn-danger" title="' . $this->getTrans('inactive') . '"><i class="fa-solid fa-eye-slash inactiv"></i></a>';
                            }
                            ?>
                            </td>
                            <td class="text-center"><a href="<?=$this->getUrl(['action' => 'treat', 'id' => $shopItem->getId()]) ?>"><img src="<?=$img ?>" class="item_image <?=($shopItem->getCordon() == 1) ? $shopItem->getCordonColor() : ''; ?>" alt="<?=$this->escape($shopItem->getName()) ?>"/></a></td>
                            <td><?=$this->escape($shopItem->getName()) ?></td>
                            <td><?=$this->escape($shopItem->getItemnumber()) ?></td>
                            <td><?=($shopCats) ? $this->escape($shopCats->getTitle()) : ''; ?></td>
                            <td class="text-right">
                            <?php if ($this->escape($shopItem->getStock()) < 1) { ?>
                                <button class="btn btn-sm btn-danger stock"><?=$this->escape($shopItem->getStock()) ?></button>
                            <?php } elseif ($this->escape($shopItem->getStock()) <= 5) { ?>
                                <button class="btn btn-sm btn-warning stock"><?=$this->escape($shopItem->getStock()) ?></button>
                            <?php } else { ?>
                                <button class="btn btn-sm btn-success stock"><?=$this->escape($shopItem->getStock()) ?></button>
                            <?php } ?>
                            </td>
                            <td class="text-right"><?=$this->escape($shopItem->getPrice()) ?> <?=$this->escape($this->get('currency')) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?=$this->getListBar(['delete' => 'delete']) ?>
    </form>
    <script>
    $("table").on("click", "th.sort", function () {
        const index = $(this).index(),
            rows = [],
            thClass = $(this).hasClass("asc") ? "desc" : "asc";
        $("#sortTable th.sort").removeClass("asc desc");
        $(this).addClass(thClass);
        $("#sortTable tbody tr").each(function (index, row) {
            rows.push($(row).detach());
        });
        rows.sort(function (a, b) {
            const aValue = $(a).find("td").eq(index).text(),
                bValue = $(b).find("td").eq(index).text();
            return aValue > bValue ? 1 : (aValue < bValue ? -1 : 0);
        });
        if ($(this).hasClass("desc")) {
            rows.reverse();
        }
        $.each(rows, function (index, row) {
            $("#sortTable tbody").append(row);
        });
    });
    $("#filterInput").on("keyup", function() {
        const value = $(this).val().toLowerCase();
        $("#sortTable tr.filter").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
    $("#filterClear").click(function(){
        $("#sortTable tr.filter").show(function() {
            $("#filterInput").val('');
        });
    });
    </script>
<?php else : ?>
    <?=$this->getTrans('noItems') ?>
<?php endif; ?>
