<link href="<?=$this->getModuleUrl('static/css/bbcodeconvert_styles.css') ?>" rel="stylesheet">

<h1><?=$this->getTrans('menuOverview') ?>
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

<?php if (!$this->get('getHtmlFromBBCodeExists')) : ?>
    <div class="alert alert-danger">
        <strong><?=$this->getTrans('moduleNoLongerSupported') ?></strong>
    </div>
<?php endif; ?>

<?php if (!$this->get('maintenanceModeEnabled')) : ?>
    <div class="alert alert-danger">
        <strong><?=$this->getTrans('warningMaintenanceMode') ?></strong>
    </div>
<?php endif; ?>

<div class="alert alert-danger">
    <strong><?=$this->getTrans('warningBackup', ($this->get('lastBackup')) ? $this->get('lastBackup')->getDate() : $this->getTrans('noBackup')) ?></strong>
</div>

<p><strong><?=$this->getTrans('infoMessage') ?></strong></p>

<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <?php if (!empty($this->get('installedSupportedModules'))) : ?>
    <div class="table-responsive">
        <table id="sortTable" class="table table-hover table-striped">
            <colgroup>
                <col class="icon_width">
                <col>
                <col>
                <col>
            </colgroup>
            <thead>
            <tr>
                <th><?=$this->getCheckAllCheckbox('check_modules') ?></th>
                <th class="sort"><?= $this->getTrans('module') ?></th>
                <th><?=$this->getTrans('version') ?></th>
                <th class="sort"><?= $this->getTrans('converted') ?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($this->get('installedSupportedModules') as $module) : ?>
                <tr class="filter">
                    <td><?=$this->getDeleteCheckbox('check_modules', $module->getKey()) ?></td>
                    <td><?=$this->getTrans($module->getKey()) ?></td>
                    <td><?=$module->getVersion() ?></td>
                    <td><?=(key_exists($module->getKey(), $this->get('converted'))) ? $this->getTrans('converted') : $this->getTrans('notConverted') ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else : ?>
    <p><?=$this->getTrans('noModulesToConvert') ?></p>
    <?php endif; ?>

    <?php if (!empty($this->get('installedSupportedLayouts'))) : ?>
    <div class="table-responsive">
        <table id="sortTable" class="table table-hover table-striped">
            <colgroup>
                <col class="icon_width">
                <col>
                <col>
                <col>
            </colgroup>
            <thead>
            <tr>
                <th><?=$this->getCheckAllCheckbox('check_layouts') ?></th>
                <th class="sort"><?= $this->getTrans('layout') ?></th>
                <th><?=$this->getTrans('version') ?></th>
                <th class="sort"><?= $this->getTrans('converted') ?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($this->get('installedSupportedLayouts') as $layout) : ?>
                <tr class="filter">
                    <td><?=$this->getDeleteCheckbox('check_layouts', $layout->getKey()) ?></td>
                    <td><?=$this->getTrans($layout->getKey()) ?></td>
                    <td><?=$layout->getVersion() ?></td>
                    <td><?=(key_exists($layout->getKey(), $this->get('converted'))) ? $this->getTrans('converted') : $this->getTrans('notConverted') ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else : ?>
    <p><?=$this->getTrans('noLayoutsToConvert') ?></p>
    <?php endif; ?>


    <div class="content_savebox">
        <input type="hidden" class="content_savebox_hidden" name="action" value="convert" />
        <div class="btn-group dropup">
            <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown"<?=($this->get('getHtmlFromBBCodeExists')) ? '' : ' disabled' ?>>
                <?=$this->getTrans('selected') ?> <span class="caret"></span>
            </button>
            <ul class="dropdown-menu listConvert" role="menu">
                <li clas="dropdown-item"><a href="#" data-hiddenkey="convert" id="convert"><?=$this->getTrans('convert') ?></a></li>
            </ul>
        </div>
    </div>
</form>

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

    $("#filterClear").click(function() {
        $("#sortTable tr.filter").show(function() {
            $("#filterInput").val('');
        });
    });

    $('.listConvert a').click(function() {
        if (confirm(<?=json_encode($this->getTrans('confirmConvert')) ?>)) {
            $(this).closest('form').submit();
        }
    });
</script>
