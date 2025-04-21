<link href="<?=$this->getModuleUrl('static/css/shop_admin.css') ?>" rel="stylesheet">

<?php
/** @var \Modules\Shop\Models\Property[] $properties */
$properties = $this->get('properties');
?>

<div class="d-flex align-items-start heading-filter-wrapper">
    <h1><?=$this->getTrans('menuProperties') ?></h1>
    <div class="input-group input-group-sm filter d-flex justify-content-end">
        <span class="input-group-text">
            <i class="fa-solid fa-filter"></i>
        </span>
        <input type="text" id="filterInput" class="form-control" placeholder="<?=$this->getTrans('filter') ?>">
        <span class="input-group-text">
            <span id="filterClear" class="fa-solid fa-xmark"></span>
        </span>
    </div>
</div>
<p><?=$this->getTrans('propertiesDesc') ?></p>

<?php if (!empty($this->get('properties'))) : ?>
    <form method="POST" action="">
        <?=$this->getTokenField() ?>

        <div class="table-responsive">
            <table id="sortTable" class="table table-hover table-striped">
                <colgroup>
                    <col class="icon_width">
                    <col class="icon_width">
                    <col class="icon_width">
                    <col class="col-xl-1">
                    <col>
                </colgroup>
                <thead>
                <tr>
                    <th><?=$this->getCheckAllCheckbox('check_properties') ?></th>
                    <th></th>
                    <th></th>
                    <th class="sort"><?=$this->getTrans('propertyEnabled') ?></th>
                    <th class="sort"><?=$this->getTrans('propertyName') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($properties as $property) : ?>
                    <tr class="filter">
                        <td><?=$this->getDeleteCheckbox('check_properties', $property->getId()) ?></td>
                        <td><?=$this->getEditIcon(['action' => 'treat', 'id' => $property->getId()]) ?></td>
                        <td><?=$this->getDeleteIcon(['action' => 'delete', 'id' => $property->getId()]) ?></td>
                        <td>
                            <?php
                            if ($property->isEnabled()) {
                                echo '<a href="' . $this->getUrl(['action' => 'updateenabled', 'id' => $property->getId()], null, true) . '" title="' . $this->getTrans('active') . '"><i class="fa-solid fa-toggle-on text-success"></i></a>';
                            } else {
                                echo '<a href="' . $this->getUrl(['action' => 'updateenabled', 'id' => $property->getId(), 'enabled' => 'true'], null, true) . '" title="' . $this->getTrans('inactive') . '"><i class="fa-solid fa-toggle-off inactive text-danger"></i></a>';
                            }
                            ?>
                        </td>
                        <td><?=$this->escape($property->getName()) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?=$this->getListBar(['delete' => 'delete']) ?>
    </form>
<?php else : ?>
    <p><?=$this->getTrans('noProperties') ?></p>
<?php endif; ?>

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
