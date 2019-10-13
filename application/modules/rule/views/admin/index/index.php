<h1><?=$this->getTrans('manage') ?></h1>
<?php if ($this->get('rules') != ''): ?>
    <form class="form-horizontal" method="POST" action="">
        <?=$this->getTokenField() ?>
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <colgroup>
                    <col class="icon_width" />
                    <col class="icon_width" />
                    <col class="icon_width" />
                    <col class="icon_width" />
                    <col class="icon_width" />
                    <col class="col-lg-2" />
                    <col class="col-lg-2" />
                    <col />
                </colgroup>
                <thead>
                    <tr>
                        <th><?=$this->getCheckAllCheckbox('check_entries') ?></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th><?=$this->getTrans('art') ?>  /  <?=$this->getTrans('paragraphsign') ?></th>
                        <th><?=$this->getTrans('title') ?></th>
                        <th><?=$this->getTrans('cat') ?></th>
                        <th><?=$this->getTrans('text');?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($this->get('rules') as $rule): ?>
                    <?php
                    $rulesparent = $this->get('ruleMapper')->getRuleById($rule->getParent_Id())
                    ?>
                        <tr>
                            <input type="hidden" name="items[]" value="<?=$rule->getId() ?>" />
                            <td><?=$this->getDeleteCheckbox('check_entries', $rule->getId()) ?></td>
                            <td><?=$this->getEditIcon(array_merge(($rule->getParent_Id()==0?['controller' => 'cats']:[]),['action' => 'treat', 'id' => $rule->getId()])) ?></td>
                            <td><?=$this->getDeleteIcon(['action' => 'del', 'id' => $rule->getId()]) ?></td>
                            <td><i class="fa fa-sort"></i></td>
                            <td><?=($rulesparent?$this->escape($rulesparent->getParagraph()).' / ':'') ?><?=$this->escape($rule->getParagraph()) ?></td>
                            <td><?=$this->escape($rule->getTitle()) ?></td>
                            <td><?=$this->escape($rule->getParentTitle()) ?></td>
                            <td><?=$this->purify($rule->getText()) ?></td>
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
            ui.placeholder.html("<td colspan='7'></td>");
            ui.placeholder.height(ui.item.height());
        }
    }).disableSelection();
    </script>
<?php else: ?>
    <?=$this->getTrans('noRules') ?>
<?php endif; ?>
