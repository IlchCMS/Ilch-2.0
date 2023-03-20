<h1><?=$this->getTrans('manage') ?></h1>
<?php if (!empty($this->get('teams'))): ?>
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
                    <th><?=$this->getCheckAllCheckbox('check_teams') ?></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th><?=$this->getTrans('menuTeam') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($this->get('teams') as $team): ?>
                    <tr>
                        <input type="hidden" name="items[]" value="<?=$team->getId() ?>" />
                        <td><?=$this->getDeleteCheckbox('check_teams', $team->getId()) ?></td>
                        <td><?=$this->getEditIcon(['action' => 'treat', 'id' => $team->getId()]) ?></td>
                        <td><?=$this->getDeleteIcon(['action' => 'del', 'id' => $team->getId()]) ?></td>
                        <td><i class="fa-solid fa-sort"></i></td>
                        <td><?=$this->escape($team->getName()) ?></td>
                    </tr>
                <?php endforeach; ?>
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
            <button type="submit" class="save_button btn btn-default" name="saveTeams" value="save">
                <?=$this->getTrans('saveButton') ?>
            </button>
        </div>
    </form>
<?php else: ?>
    <?=$this->getTrans('noTeams') ?>
<?php endif; ?>

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
