<?php
$appearances = $this->get('appearances');
?>
<h1><?=$this->getTrans('groupAppearanceSettings') ?></h1>
<form class="form-horizontal" method="POST">
    <?=$this->getTokenField() ?>
    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <colgroup>
                <col class="icon_width">
                <col class="icon_width" />
                <col class="col-lg-2">
                <col class="col-lg-10">
            </colgroup>
            <thead>
            <tr>
                <th></th>
                <th></th>
                <th><?=$this->getTrans('name') ?></th>
                <th><?=$this->getTrans('appearance') ?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($this->get('groupList') as $group) : ?>
                <?php if ($group->getId() == 3)  {
                    continue;
                } ?>
                <tr>
                    <td>
                        <input type="checkbox" id="active<?=$group->getId() ?>" name="appearances[<?=$group->getId() ?>][active]" <?=(isset($appearances[$group->getId()]['active'])) ? 'checked' : '' ?>>
                        <label for="active<?=$group->getId() ?>" style="display:none"><?=$this->getTrans('active') ?></label>
                    </td>
                    <td><i class="fas fa-sort"></i></td>
                    <td><?=$group->getName() ?></td>
                    <td>
                        <input type="color" id="textcolor<?=$group->getId() ?>" name="appearances[<?=$group->getId() ?>][textcolor]"
                               value="<?=(isset($appearances[$group->getId()]['textcolor'])) ? $appearances[$group->getId()]['textcolor'] : '#000000' ?>">
                        <label for="textcolor<?=$group->getId() ?>"><?=$this->getTrans('textcolor') ?></label>
                        <input type="checkbox" id="bold<?=$group->getId() ?>"  name="appearances[<?=$group->getId() ?>][bold]" <?=(isset($appearances[$group->getId()]['bold'])) ? 'checked' : '' ?>>
                        <label for="bold<?=$group->getId() ?>"><b><?=$this->getTrans('bold') ?></b></label>
                        <input type="checkbox" id="italic<?=$group->getId() ?>"  name="appearances[<?=$group->getId() ?>][italic]" <?=(isset($appearances[$group->getId()]['italic'])) ? 'checked' : '' ?>>
                        <label for="italic<?=$group->getId() ?>"><i><?=$this->getTrans('italic') ?></i></label>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?=$this->getSaveBar() ?>
</form>

<script>
    $('table tbody').sortable({
        handle: 'td',
        cursorAt: { left: 15 },
        placeholder: "table-sort-drop",
        forcePlaceholderSize: true,
        'start': function (event, ui) {
            ui.placeholder.html("<td colspan='4'></td>");
            ui.placeholder.height(ui.item.height());
        }
    }).disableSelection();
</script>
