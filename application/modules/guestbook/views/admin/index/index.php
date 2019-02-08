<h1><?=$this->getTrans('manage') ?></h1>
<form class="form-horizontal" method="POST">
    <?=$this->getTokenField() ?>
    <ul class="nav nav-tabs">
        <li <?php if (!$this->getRequest()->getParam('showsetfree')) { echo 'class="active"'; } ?>>
            <a href="<?=$this->getUrl(['controller' => 'index', 'action' => 'index']) ?>">
                <?=$this->getTrans('entrys') ?>
            </a>
        </li>
        <?php if ($this->get('badge') > 0): ?>
            <li <?php if ($this->getRequest()->getParam('showsetfree')) { echo 'class="active"'; } ?>>
                <a href="<?=$this->getUrl(['controller' => 'index', 'action' => 'index', 'showsetfree' => 1]) ?>">
                    <?=$this->getTrans('setfree') ?><span class="badge"><?=$this->get('badge') ?></span>
                </a>
            </li>
        <?php endif; ?>
    </ul>
    <br />
    <?php if (!empty($this->get('entries'))) : ?>
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <colgroup>
                    <col class="icon_width">
                    <?php
                    if ($this->getRequest()->getParam('showsetfree')) {
                        echo '<col class="icon_width">';
                    }
                    ?>
                    <col class="icon_width">
                    <col class="col-lg-2">
                    <col class="col-lg-2">
                    <col class="col-lg-2">
                    <col>
                </colgroup>
                <thead>
                <tr>
                    <th><?=$this->getCheckAllCheckbox('check_entries') ?></th>
                    <?php
                    if ($this->getRequest()->getParam('showsetfree')) {
                        echo '<th></th>';
                    }
                    ?>
                    <th></th>
                    <th><?=$this->getTrans('from') ?></th>
                    <th><?=$this->getTrans('email') ?></th>
                    <th><?=$this->getTrans('date') ?></th>
                    <th><?=$this->getTrans('message') ?></th>
                </tr>
                </thead>
                <?php foreach ($this->get('entries') as $entry): ?>
                    <tbody>
                    <tr>
                        <td><?=$this->getDeleteCheckbox('check_entries', $entry->getId()) ?></td>
                        <?php
                        if ($this->getRequest()->getParam('showsetfree')) {
                            echo '<td>';
                            $freeArray = ['action' => 'setfree', 'id' => $entry->getId()];

                            if ($this->get('badge') > 1) {
                                $freeArray = ['action' => 'setfree', 'id' => $entry->getId(), 'showsetfree' => 1];
                            }

                            echo '<a href="'.$this->getUrl($freeArray, null, true).'"><span class="fa fa-check-square-o text-success"></span></a>';
                            echo '</td>';
                        }

                        $deleteArray = ['action' => 'del', 'id' => $entry->getId()];

                        if ($this->getRequest()->getParam('showsetfree') && $this->get('badge') > 1) {
                            $deleteArray = ['action' => 'del', 'id' => $entry->getId(), 'showsetfree' => 1];
                        }
                        ?>
                        <td><?=$this->getDeleteIcon($deleteArray) ?></td>
                        <td>
                            <?=$this->escape($entry->getName()) ?>
                        </td>
                        <td>
                            <a target="_blank" href="mailto:<?=$this->escape($entry->getEmail()) ?>">
                                <?=$this->escape($entry->getEmail()) ?>
                            </a>
                        </td>
                        <td>
                            <?=$this->escape($entry->getDateTime()) ?>
                        </td>
                        <td>
                            <?=nl2br($this->getHtmlFromBBCode($this->escape($entry->getText()))) ?>
                        </td>
                    </tr>
                    </tbody>
                <?php endforeach; ?>
            </table>
        </div>
    <?php else : ?>
        <?=$this->getTrans('noEntries') ?>
    <?php endif; ?>

    <?php
    $actions = ['delete' => 'delete'];

    if ($this->getRequest()->getParam('showsetfree')) {
        $actions = ['delete' => 'delete', 'setfree' => 'setfree'];
    }

    echo $this->getListBar($actions);
    ?>
</form>
