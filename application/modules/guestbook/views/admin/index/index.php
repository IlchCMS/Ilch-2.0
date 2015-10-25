<legend><?=$this->getTrans('manage') ?></legend>
<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <ul class="nav nav-tabs">
        <li <?php if(!$this->getRequest()->getParam('showsetfree')) { echo 'class="active"'; } ?>>
            <a href="<?=$this->getUrl(array('controller' => 'index', 'action' => 'index')) ?>">
                <?=$this->getTrans('entrys') ?>
            </a>
        </li>
        <?php if ($this->get('badge') > 0) : ?>
            <li <?php if($this->getRequest()->getParam('showsetfree')) { echo 'class="active"'; } ?>>
                <a href="<?=$this->getUrl(array('controller' => 'index', 'action' => 'index', 'showsetfree' => 1)) ?>">
                    <?=$this->getTrans('setfree') ?><span class="badge"><?=$this->get('badge') ?></span>
                </a>
            </li>
        <?php endif; ?>
    </ul>
    <br />
    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <colgroup>
                <col class="icon_width" />
                <?php
                    if($this->getRequest()->getParam('showsetfree')) {
                        echo '<col class="icon_width" />';
                    }
                ?>
                <col class="icon_width" />
                <col class="col-lg-2" />
                <col class="col-lg-2" />
                <col />
            </colgroup>
            <thead>
                <tr>
                    <th><?=$this->getCheckAllCheckbox('check_entries')?></th>
                    <?php
                        if($this->getRequest()->getParam('showsetfree')) {
                            echo '<th></th>';
                        }
                    ?>
                    <th></th>
                    <th><?=$this->getTrans('from') ?></th>
                    <th><?=$this->getTrans('date') ?></th>
                    <th><?=$this->getTrans('message') ?></th>
                </tr>
            </thead>
            <?php foreach ($this->get('entries') as $entry): ?>
                <tbody>
                    <tr>
                        <td><input value="<?=$entry->getId()?>" type="checkbox" name="check_entries[]" /></td>
                        <?php
                            if($this->getRequest()->getParam('showsetfree')) {
                                echo '<td>';
                                $freeArray = array('action' => 'setfree', 'id' => $entry->getId());

                                if($this->get('badge') > 1) {
                                    $freeArray = array('action' => 'setfree', 'id' => $entry->getId(), 'showsetfree' => 1);
                                }

                               echo '<a href="'.$this->getUrl($freeArray).'"><span class="fa fa-check-square-o text-success"></span></a>';
                               echo '</td>';
                            }

                            $deleteArray = array('action' => 'del', 'id' => $entry->getId());

                            if($this->getRequest()->getParam('showsetfree') && $this->get('badge') > 1) {
                                $deleteArray = array('action' => 'del', 'id' => $entry->getId(), 'showsetfree' => 1);
                            }
                        ?>
                        </td>
                        <td><?=$this->getDeleteIcon($deleteArray)?></td>
                        <td>
                            <?=$this->escape($entry->getName()) ?>
                        </td>
                        <td>
                            <?=$this->escape($entry->getDateTime()) ?>
                        </td>
                        <td>
                            <?=$entry->getText() ?>
                        </td>
                    </tr>
                </tbody>
            <?php endforeach; ?>
        </table>
    </div>

    <?php
    $actions = array('delete' => 'delete');

    if($this->getRequest()->getParam('showsetfree')) {
        $actions = array('delete' => 'delete', 'setfree' => 'setfree');
    }

    echo $this->getListBar($actions);
    ?>
</form>
