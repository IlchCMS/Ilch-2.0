<h1>
    <?=$this->getTrans('manage') ?>
    <a class="badge" data-toggle="modal" data-target="#infoModal">
        <i class="fa fa-info"></i>
    </a>
</h1>
<?php if ($this->get('teams') != ''): ?>
    <form class="form-horizontal" id="teamsForm" method="POST" action="">
        <?=$this->getTokenField() ?>
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <colgroup>
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
                        <th><?=$this->getTrans('menuTeam') ?></th>
                    </tr>
                </thead>
                <tbody id="sortable">
                    <?php foreach ($this->get('teams') as $team): ?>
                        <tr id="<?=$team->getId() ?>">
                            <td><?=$this->getDeleteCheckbox('check_teams', $team->getId()) ?></td>
                            <td><?=$this->getEditIcon(['action' => 'treat', 'id' => $team->getId()]) ?></td>
                            <td><?=$this->getDeleteIcon(['action' => 'del', 'id' => $team->getId()]) ?></td>
                            <td><?=$this->escape($team->getName()) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <input type="hidden" id="hiddenMenu" name="hiddenMenu" value="" />
        </div>
        <div class="content_savebox">
            <button type="submit" class="btn btn-default" name="save" value="save">
                <?=$this->getTrans('saveButton') ?>
            </button>
            <input type="hidden" class="content_savebox_hidden" name="action" value="" />
            <div class="btn-group dropup">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                    <?=$this->getTrans('selected') ?> <span class="caret"></span>
                </button>
                <ul class="dropdown-menu listChooser" role="menu">
                    <li><a href="#" data-hiddenkey="delete"><?=$this->getTrans('delete') ?></a></li>
                </ul>
            </div>
        </div>
    </form>
<?php else: ?>
    <?=$this->getTrans('noTeams') ?>
<?php endif; ?>

<?=$this->getDialog("infoModal", $this->getTrans('info'), $this->getTrans('teamInfoText')); ?>

<script>
$(function() {
$( "#sortable" ).sortable();
$( "#sortable" ).disableSelection();
});
$('#teamsForm').submit (
    function () {
        var items = $("#sortable tr");
        var teamIDs = [items.length];
        var index = 0;
        items.each(
            function(intIndex) {
                teamIDs[index] = $(this).attr("id");
                index++;
            });
        $('#hiddenMenu').val(teamIDs.join(","));
    }
);
</script>
