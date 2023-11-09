<h1><?=$this->getTrans('manage') ?></h1>
<?php if (!empty($this->get('layouts'))): ?>
    <form class="form-horizontal" method="POST">
        <?=$this->getTokenField() ?>
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <colgroup>
                    <col class="icon_width" />
                    <col class="icon_width" />
                    <col class="icon_width" />
                    <col />
                    <col />
                </colgroup>
                <thead>
                <tr>
                    <th><?=$this->getCheckAllCheckbox('check_layouts') ?></th>
                    <th></th>
                    <th></th>
                    <th><?=$this->getTrans('name') ?></th>
                    <th><?=$this->getTrans('author') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($this->get('layouts') as $layout): ?>
                    <tr>
                        <td><?=$this->getDeleteCheckbox('check_layouts', $layout->getKey()) ?></td>
                        <td><?=$this->getDeleteIcon(['action' => 'deleteAdvSettings', 'layoutKey' => $layout->getKey()]) ?></td>
                        <td><?=$this->getEditIcon(['action' => 'advSettingsShow', 'layoutKey' => $layout->getKey()]) ?></td>
                        <td><a href="<?=$this->getUrl(['action' => 'advSettingsShow', 'layoutKey' => $layout->getKey()]) ?>"><?=$this->escape($layout->getName()) ?></a></td>
                        <td><?=$this->escape($layout->getAuthor()) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="content_savebox">
            <input type="hidden" class="content_savebox_hidden" name="action" value="delete" />
            <div class="btn-group dropup">
                <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                    <?=$this->getTrans('selected') ?> <span class="caret"></span>
                </button>
                <ul class="dropdown-menu listChooser" role="menu">
                    <li><a class="dropdown-item" href="#" data-hiddenkey="delete"><?=$this->getTrans('delete') ?></a></li>
                </ul>
            </div>
            <?php if ($this->get('orphanedSettingsExist')) : ?>
                <button type="submit" class="save_button btn-outline-secondary" name="deleteOrphanedSettings" value="deleteOrphanedSettings">
                    <?=$this->getTrans('deleteOrphanedSettings') ?>
                </button>
            <?php endif; ?>
        </div>
    </form>
<?php else: ?>
    <?=$this->getTrans('noLayouts') ?>
<?php endif; ?>
