<link href="<?=$this->getModuleUrl('static/css/bbcodeconvert_styles.css') ?>" rel="stylesheet">

<h1><?=$this->getTrans('menuNote') ?>
    <div class="input-group input-group-sm filter">
        <span class="input-group-addon">
            <i class="fa-solid fa-filter"></i>
        </span>
        <input type="text" id="filterInput" class="form-control" placeholder="<?=$this->getTrans('filterModules') ?>">
        <span class="input-group-addon">
            <span id="filterClear" class="fa-solid fa-xmark"></span>
        </span>
    </div>
</h1>

<p><?=$this->getTrans('noteDescription') ?></p>

<div class="table-responsive">
    <table id="sortTable" class="table table-hover table-striped">
        <colgroup>
            <col>
            <col>
        </colgroup>
        <thead>
        <tr>
            <th class="sort"><?=$this->getTrans('module') ?></th>
            <th><?=$this->getTrans('supportedVersions') ?></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($this->get('supportedModules') as $supportedModuleKey => $supportedModuleVersions) : ?>
            <tr class="filter">
                <td><?=$this->getTrans($supportedModuleKey) ?></td>
                <td><?=implode(', ', $supportedModuleVersions) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div class="table-responsive">
    <table id="sortTable" class="table table-hover table-striped">
        <colgroup>
            <col>
            <col>
        </colgroup>
        <thead>
        <tr>
            <th class="sort"><?=$this->getTrans('layout') ?></th>
            <th><?=$this->getTrans('supportedVersions') ?></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($this->get('supportedLayouts') as $supportedLayoutKey => $supportedLayoutVersions) : ?>
            <tr class="filter">
                <td><?=$this->getTrans($supportedLayoutKey) ?></td>
                <td><?=implode(', ', $supportedLayoutVersions) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
    $("table").on("click", "th.sort", function () {
        const index = $(this).index(),
            rows = [],
            thClass = $(this).hasClass("asc") ? "desc" : "asc";
        $(this).removeClass("asc desc");
        $(this).addClass(thClass);
        $(this).closest('.table').find('tbody tr').each(function (index, row) {
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
        let tableBody = $(this).closest('.table').find('tbody');
        $.each(rows, function (index, row) {
            tableBody.append(row);
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
