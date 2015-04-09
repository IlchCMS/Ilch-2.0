<legend><?=$this->getTrans('managePartner') ?></legend>
<?php if ($this->get('entries') != ''): ?>
    <form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
        <ul class="nav nav-tabs">
            <li <?php if(!$this->getRequest()->getParam('showsetfree')) { echo 'class="active"'; } ?>>
                <a href="<?=$this->getUrl(array('controller' => 'index', 'action' => 'index')) ?>">
                    <?=$this->getTrans('entrys') ?>
                </a>
            </li>
            <?php if ($this->get('badge') > 0): ?>
                <li <?php if($this->getRequest()->getParam('showsetfree')) { echo 'class="active"'; } ?>>
                    <a href="<?=$this->getUrl(array('controller' => 'index', 'action' => 'index', 'showsetfree' => 1)) ?>">
                        <?=$this->getTrans('setfree'); ?> <span class="badge"><?php echo $this->get('badge') ?></span>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
        <br />
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <colgroup>
                    <col class="icon_width">
                    <col class="icon_width">
                    <col class="icon_width">
                    <col class="col-lg-2">
                    <col />
                </colgroup>
                <thead>
                    <tr>
                        <th><?=$this->getCheckAllCheckbox('check_entries') ?></th>
                        <th></th>
                        <th></th>
                        <th><?=$this->getTrans('name') ?></th>
                        <th><?=$this->getTrans('banner') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($this->get('entries') as $entry): ?>
                        <tr>
                            <td><input value="<?=$entry->getId() ?>" type="checkbox" name="check_entries[]" /></td>
                            <td>
                            <?php if($this->getRequest()->getParam('showsetfree')) {
                                $freeArray = array('module' => 'partner', 'controller' => 'index', 'action' => 'setfree', 'id' => $entry->getId());

                                if($this->get('badge') > 1) {
                                    $freeArray = array('action' => 'setfree', 'id' => $entry->getId(), 'showsetfree' => 1);
                                }
                            ?>
                                <a href="<?php echo $this->getUrl($freeArray).'" title="'.$this->getTrans('setfree'); ?>"><i class="fa fa-check-square-o text-success"></i></a>
                            <?php }else{
                                echo $this->getEditIcon(array('action' => 'treat', 'id' => $entry->getId()));
                            } ?>
                            </td>
                            <td>
                                <?php
                                    $deleteArray = array('action' => 'del', 'id' => $entry->getId());

                                    if($this->getRequest()->getParam('showsetfree') && $this->get('badge') > 1) {
                                        $deleteArray = array('action' => 'del', 'id' => $entry->getId(), 'showsetfree' => 1);
                                    }
                                ?>
                                <?=$this->getDeleteIcon($deleteArray) ?>
                            </td>
                            <td>
                                <?=$this->escape($entry->getName()) ?>
                            </td>
                            <td>
                                <a href='<?=$this->escape($entry->getLink()) ?>' target="_blank"><img src='<?=$this->escape($entry->getBanner()) ?>'></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
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
<?php else: ?>
    <?=$this->getTrans('noPartners') ?>
<?php endif; ?>
