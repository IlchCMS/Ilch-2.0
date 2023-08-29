<?php

/** @var \Ilch\View $this */

/** @var Modules\Privacy\Models\Privacy[]|null $privacys */
$privacys = $this->get('privacys');
?>
<h1><?=$this->getTrans('manage') ?></h1>
<?php if ($privacys) : ?>
    <form class="form-horizontal" method="POST">
        <?=$this->getTokenField() ?>
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <colgroup>
                    <col class="icon_width" />
                    <col class="icon_width" />
                    <col class="icon_width" />
                    <col class="icon_width" />
                    <col class="icon_width" />
                    <col />
                </colgroup>
                <thead>
                    <tr>
                        <th><?=$this->getCheckAllCheckbox('check_privacys') ?></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th><?=$this->getTrans('title') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($privacys as $privacy) : ?>
                        <tr>
                            <input type="hidden" name="items[]" value="<?=$privacy->getId() ?>" />
                            <td><?=$this->getDeleteCheckbox('check_privacys', $privacy->getId()) ?></td>
                            <td><?=$this->getEditIcon(['action' => 'treat', 'id' => $privacy->getId()]) ?></td>
                            <td><?=$this->getDeleteIcon(['action' => 'del', 'id' => $privacy->getId()]) ?></td>
                            <td><i class="fa-solid fa-sort"></i></td>
                            <td>
                                <a href="<?=$this->getUrl(['action' => 'update', 'id' => $privacy->getId()], null, true) ?>">
                                    <span class="fa-regular fa-square<?=($privacy->getShow() ? '-check' : '') ?> text-info"></span>
                                </a>
                            </td>
                            <td><?=$this->escape($privacy->getTitle()) ?></td>
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
            <button type="submit" class="save_button btn btn-default" name="saveRules" value="save">
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
                ui.placeholder.html("<td colspan='6'></td>");
                ui.placeholder.height(ui.item.height());
            }
        }).disableSelection();
    </script>
<?php else : ?>
    <?=$this->getTrans('noPrivacy') ?>
<?php endif; ?>
