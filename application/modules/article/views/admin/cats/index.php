<h1><?=$this->getTrans('menuCats') ?></h1>
<form class="form-horizontal" method="POST">
    <?=$this->getTokenField() ?>
    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <colgroup>
                <col class="icon_width" />
                <col class="icon_width" />
                <col class="icon_width" />
                <col class="icon_width" />
                <col />
            </colgroup>
            <thead>
                <tr>
                    <th><?=$this->getCheckAllCheckbox('check_cats') ?></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th><?=$this->getTrans('title') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($this->get('cats'))): ?>
                    <?php foreach ($this->get('cats') as $cat): ?>
                        <tr>
                            <input type="hidden" name="items[]" value="<?=$cat->getId() ?>" />
                            <td><?=$this->getDeleteCheckbox('check_cats', $cat->getId()) ?></td>
                            <td><?=$this->getEditIcon(['action' => 'treat', 'id' => $cat->getId()]) ?></td>
                            <td><?=$this->getDeleteIcon(['action' => 'delcat', 'id' => $cat->getId()]) ?></td>
                            <td><i class="fa fa-sort"></i></td>
                            <td><?=$this->escape($cat->getName()) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5"><?=$this->getTrans('noCats') ?></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="content_savebox">
        <input type="hidden" class="content_savebox_hidden" name="action" value="" />
        <div class="btn-group dropup">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                <?=$this->getTrans('selected') ?> <span class="caret"></span>
            </button>
            <ul class="dropdown-menu listChooser" role="menu">
                <li><a href="#" data-hiddenkey="delete"><?=$this->getTrans('delete') ?></a></li>
            </ul>
        </div>
        <button type="submit" class="save_button btn btn-default" name="saveCats" value="save">
            <?=$this->getTrans('saveButton') ?>
        </button>
    </div>
</form>

<script>
$('table tbody').sortable({
    handle: 'td',
    cursorAt: { left: 15 },
    placeholder: "table-sort-drop",
    forcePlaceholderSize: true,
    'start': function (event, ui) {
        ui.placeholder.html("<td colspan='5'></td>");
        ui.placeholder.height(ui.item.height());
    }
}).disableSelection();
</script>
